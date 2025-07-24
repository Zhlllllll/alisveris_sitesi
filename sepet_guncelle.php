<?php
session_start();
require "libs/functions.php";
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["id"])) {
    $sepet_urun_id = $_POST["sepet_urun_id"] ?? null;
    $adet = $_POST["adet"] ?? 1;

    if ($sepet_urun_id && $adet >= 1) {
        $sql = "SELECT urun_id, beden FROM sepet_urunler WHERE id = ? AND sepet_id IN (SELECT id FROM alisveris_sepeti WHERE uye_id = ? AND is_completed = 0)";
        $stmt = $baglanti->prepare($sql);
        $stmt->bind_param("ii", $sepet_urun_id, $_SESSION["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $urun = $result->fetch_assoc();

        if ($urun) {
            $sql_stok = "SELECT stok FROM urun_beden WHERE urun_id = ? AND beden_id = (SELECT id FROM beden WHERE beden_ad = ?)";
            $stmt_stok = $baglanti->prepare($sql_stok);
            $stmt_stok->bind_param("is", $urun["urun_id"], $urun["beden"]);
            $stmt_stok->execute();
            $stok_result = $stmt_stok->get_result();
            $stok_kontrol = $stok_result->fetch_assoc();
            $stmt_stok->close();

            if ($stok_kontrol && $stok_kontrol["stok"] >= $adet) {
                $sql_update = "UPDATE sepet_urunler SET adet = ? WHERE id = ?";
                $stmt_update = $baglanti->prepare($sql_update);
                $stmt_update->bind_param("ii", $adet, $sepet_urun_id);
                $success = $stmt_update->execute();
                $stmt_update->close();

                if ($success) {
                    echo json_encode(["success" => true]);
                } else {
                    echo json_encode(["success" => false, "error" => "Güncelleme başarısız", "gecerli_adet" => $adet - 1]);
                }
            } else {
                echo json_encode(["success" => false, "error" => "Yeterli stok yok", "gecerli_adet" => $adet - 1]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Ürün bulunamadı"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Geçersiz veri"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Geçersiz istek"]);
}