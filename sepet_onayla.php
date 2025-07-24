<?php
include "partials/_header.php";
session_start();
require "libs/functions.php";
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["onayla"]) && isset($_SESSION["id"])) {

    $uye_id = $_SESSION["id"];
    // Aktif sepeti bul
    $sql_sepet = "SELECT id FROM alisveris_sepeti WHERE uye_id = ? AND is_completed = 0 LIMIT 1";
    $stmt_sepet = $baglanti->prepare($sql_sepet);
    $stmt_sepet->bind_param("i", $uye_id);
    $stmt_sepet->execute();
    $result_sepet = $stmt_sepet->get_result();
    $sepet = $result_sepet->fetch_assoc();
    $sepet_id = $sepet ? $sepet["id"] : null;
    error_log("Aktif sepet ID: $sepet_id");
    $stmt_sepet->close();

    if (!$sepet_id) {
        error_log("Aktif sepet bulunamadı, uye_id: $uye_id");
        die("Aktif sepet bulunamadı.");
    }

    // Sepet ürünlerini al
    $sql_urunler = "SELECT urun_id, urun_detay_id, beden, renk, adet FROM sepet_urunler WHERE sepet_id = ?";
    $stmt_urunler = $baglanti->prepare($sql_urunler);
    $stmt_urunler->bind_param("i", $sepet_id);
    $stmt_urunler->execute();
    $result_urunler = $stmt_urunler->get_result();
    $urunler = $result_urunler->fetch_all(MYSQLI_ASSOC);
    $stmt_urunler->close();

    if (empty($urunler)) {
        error_log("Sepette ürün bulunamadı, sepet_id: $sepet_id");
        die("Sepette ürün bulunamadı.");
    }


    foreach ($urunler as $urun) {
        
    }



        // Sepet ürünlerini sipariş detaylarına aktar
        foreach ($urunler as $urun) {
            $fiyat = getUrunFiyat($urun["urun_id"], $urun["urun_detay_id"]);
            if(isset($_SESSION["toplam_tutar"])){$toplam_tutar=$_SESSION["toplam_tutar"];}else{
            $toplam_tutar = $fiyat * $urun["adet"];}
            $sql_detay = "INSERT INTO siparisler (kullanici_id,urun_id, beden, renk, adet, toplam_tutar, siparis_tarihi, durum) VALUES (?, ?, ?,?,?,?, NOW(), 'Beklemede')";
            $stmt_detay = $baglanti->prepare($sql_detay);
            $stmt_detay->bind_param("iissii", $uye_id,  $urun["urun_id"], $urun["beden"], $urun["renk"], $urun["adet"], $toplam_tutar);
            $stmt_detay->execute();
            $stmt_detay->close();
        }
        // Sipariş onaylama kısmında, sipariş detayları girildikten sonra:

    // Urun_id ve beden bilgilerini al
    $urun_id = $urun["urun_id"];
    $beden = $urun["beden"];
    $adet = $urun["adet"];

    // Stok güncelleme sorgusu
    $sql_stok_guncelle = "
        UPDATE urun_beden ub
        JOIN beden b ON ub.beden_id = b.beden_id
        SET ub.stok = ub.stok - ?
        WHERE ub.urun_id = ? AND b.beden_ad = ? AND ub.stok >= ?
    ";

    $stmt_stok_guncelle = $baglanti->prepare($sql_stok_guncelle);
    $stmt_stok_guncelle->bind_param("iisi", $adet, $urun_id, $beden, $adet);
    $stmt_stok_guncelle->execute();

    if ($stmt_stok_guncelle->affected_rows === 0) {
        // Stok yetersizliği durumu (işlem başarısız)
        // Burada hata işlemi yapabilir ya da kullanıcıya mesaj dönebilirsin.
        error_log("Stok yetersiz: Urun ID $urun_id, Beden $beden, Adet $adet");
        // Örneğin işlemi iptal et ya da stok yetersiz mesajı ver.
    }
    $stmt_stok_guncelle->close();



        // Sepeti tamamla
        $sql_update = "UPDATE alisveris_sepeti SET is_completed = 1 WHERE id = ?";
        $stmt_update = $baglanti->prepare($sql_update);
        $stmt_update->bind_param("i", $sepet_id);
        $stmt_update->execute();
        $stmt_update->close();

        $_SESSION["siparis_basarili"] = true;
        
        echo '<script>window.top.location.href = "index.php";</script>';
exit;

} else {
    $_SESSION["siparis_basarili"] = false;
}

?>
</body>
</html>
