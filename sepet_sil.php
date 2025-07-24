<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sepet_urun_id = $_POST["sepet_urun_id"];
    $uye_id = $_SESSION["id"];

    // Sepet ürününü kontrol et ve sil
    $sql_check = "SELECT sepet_id FROM sepet_urunler WHERE id = ? AND sepet_id IN (SELECT id FROM alisveris_sepeti WHERE uye_id = ? AND is_completed = 0)";
    $stmt_check = $baglanti->prepare($sql_check);
    $stmt_check->bind_param("ii", $sepet_urun_id, $uye_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $sql_delete = "DELETE FROM sepet_urunler WHERE id = ?";
        $stmt_delete = $baglanti->prepare($sql_delete);
        $stmt_delete->bind_param("i", $sepet_urun_id);

        if ($stmt_delete->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt_delete->error]);
        }
        $stmt_delete->close();
    } else {
        echo json_encode(["success" => false, "error" => "Ürün sepetinizde bulunamadı."]);
    }
    $stmt_check->close();
} else {
    echo json_encode(["success" => false, "error" => "Geçersiz istek!"]);
}
$baglanti->close();
?>