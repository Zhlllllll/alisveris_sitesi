<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $urun_id = $_POST["urun_id"];
    $kullanici_id = $_POST["kullanici_id"];

    // Mevcut favoriyi kontrol et, tekrar eklenmesin
    $sql_check = "SELECT id FROM favoriler WHERE kullanici_id = ? AND urun_id = ?";
    $stmt_check = $baglanti->prepare($sql_check);
    $stmt_check->bind_param("ii", $kullanici_id, $urun_id);
    $stmt_check->execute();
    $existing = $stmt_check->get_result()->fetch_assoc();

    if (!$existing) {
        $sql_insert = "INSERT INTO favoriler (kullanici_id, urun_id) VALUES (?, ?)";
        $stmt_insert = $baglanti->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $kullanici_id, $urun_id);

        if ($stmt_insert->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt_insert->error]);
        }
        $stmt_insert->close();
    } else {
        echo json_encode(["success" => true, "message" => "Zaten favorilerde!"]);
    }
    $stmt_check->close();
} else {
    echo json_encode(["success" => false, "error" => "Geçersiz istek!"]);
}
$baglanti->close();
?>