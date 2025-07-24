<?php
session_start();
require "libs/functions.php";
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $urun_id = $_POST["urun_id"] ?? null;
    $urun_d_id= $_POST["urun_d_id"] ?? null;
    $beden = $_POST["beden"] ?? null;
    $renk = $_POST["renk"] ?? null;
    $adet = $_POST["adet"] ?? null;
    error_log("Received: urun_id=$urun_id, beden=$beden, renk=$renk, adet=$adet");

    if (!isset($_SESSION["id"])) {
        echo "<div class='alert alert-warning'>Lütfen giriş yapın.</div>";
        exit;
    }
    $uye_id = $_SESSION["id"];

    $sql_sepet = "SELECT id FROM alisveris_sepeti WHERE uye_id = ? AND is_completed = 0";
    $stmt_sepet = $baglanti->prepare($sql_sepet);
    $stmt_sepet->bind_param("i", $uye_id);
    $stmt_sepet->execute();
    $result = $stmt_sepet->get_result();
    $sepet = $result->fetch_assoc();
    $sepet_id = $sepet ? $sepet["id"] : null;
    error_log("Sepet ID: $sepet_id");
    $stmt_sepet->close();

    if (!$sepet_id) {
        $sql_create = "INSERT INTO alisveris_sepeti (uye_id, is_completed, created_date) VALUES (?, 0, NOW())";
        $stmt_create = $baglanti->prepare($sql_create);
        $stmt_create->bind_param("i", $uye_id);
        $stmt_create->execute();
        $sepet_id = $baglanti->insert_id;
        error_log("Yeni sepet oluşturuldu, ID: $sepet_id");
        $stmt_create->close();
    }

    $sql_beden = "SELECT beden_id FROM beden WHERE beden_ad = ?";
    $stmt_beden = $baglanti->prepare($sql_beden);
    $stmt_beden->bind_param("s", $beden);
    $stmt_beden->execute();
    $result_beden = $stmt_beden->get_result();
    $beden_id = $result_beden->fetch_assoc()["beden_id"] ?? null;
    error_log("Beden: $beden, Beden ID: $beden_id");
    $stmt_beden->close();

    if (!$beden_id) {
        echo "<div class='alert alert-danger'>Geçersiz beden seçimi (beden: $beden).</div>";
        exit;
    }

    $sql_stok = "SELECT stok FROM urun_beden WHERE urun_id = ? AND beden_id = ?";
    $stmt_stok = $baglanti->prepare($sql_stok);
    $stmt_stok->bind_param("ii", $urun_id, $beden_id);
    $stmt_stok->execute();
    $result = $stmt_stok->get_result();
    $stok_kontrol = $result->fetch_assoc();
    error_log("Stok kontrolü: urun_id=$urun_id, beden_id=$beden_id, stok=" . ($stok_kontrol["stok"] ?? 'null'));
    if (!$stok_kontrol) {
        echo "<div class='alert alert-danger'>Stok bilgisi bulunamadı (urun_id: $urun_id, beden_id: $beden_id).</div>";
        exit;
    }
    if ($stok_kontrol["stok"] < $adet) {
        echo "<div class='alert alert-danger'>Yeterli stok bulunmamaktadır. Mevcut stok: " . ($stok_kontrol["stok"] ?? 'null') . ", İstenen: $adet.</div>";
        exit;
    }
    $stmt_stok->close();

    $sql_var_mi = "SELECT id, adet FROM sepet_urunler WHERE sepet_id = ? AND urun_id = ? AND beden = ? AND renk = ?";
    $stmt_var = $baglanti->prepare($sql_var_mi);
    $stmt_var->bind_param("iiss", $sepet_id, $urun_id, $beden, $renk);
    $stmt_var->execute();
    $result = $stmt_var->get_result();
    $var_urun = $result->fetch_assoc();
    error_log("Sepette ürün var mı? ID: " . ($var_urun["id"] ?? 'null') . ", mevcut adet: " . ($var_urun["adet"] ?? 'null'));
    $stmt_var->close();

    if ($var_urun) {
        $sql_guncelle = "UPDATE sepet_urunler SET adet = adet + ? WHERE id = ?";
        $stmt_guncelle = $baglanti->prepare($sql_guncelle);
        $stmt_guncelle->bind_param("ii", $adet, $var_urun["id"]);
        $success = $stmt_guncelle->execute();
        error_log("Ürün güncellendi, ID: " . $var_urun["id"] . ", Başarılı mı? " . ($success ? "Evet" : "Hayır"));
        $stmt_guncelle->close();
    } else {
        $sql_ekle = "INSERT INTO sepet_urunler (sepet_id, urun_id, urun_detay_id, beden, renk, adet) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_ekle = $baglanti->prepare($sql_ekle);
        $stmt_ekle->bind_param("iiissi", $sepet_id, $urun_id,$urun_d_id, $beden, $renk, $adet);
        $success = $stmt_ekle->execute();
        error_log("Yeni ürün eklendi, sepet_id: $sepet_id, urun_id: $urun_id, Başarılı mı? " . ($success ? "Evet" : "Hayır"));
        $stmt_ekle->close();
    }

    header("Location: sepetim.php?success=1");
    exit;
}
?>