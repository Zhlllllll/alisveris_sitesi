<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $urun_id = $_POST["urun_id"];
    $kullanici_id = $_POST["kullanici_id"];

    $sql_delete = "DELETE FROM favoriler WHERE kullanici_id = ? AND urun_id = ?";
    $stmt_delete = $baglanti->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $kullanici_id, $urun_id);

    if ($stmt_delete->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt_delete->error]);
    }
    $stmt_delete->close();
} else {
    echo json_encode(["success" => false, "error" => "Geçersiz istek!"]);
}
$baglanti->close();
?>