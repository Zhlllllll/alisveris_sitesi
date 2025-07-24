<?php
require "connection.php";

if (isset($_GET['ust_kategori_id'])) {
    $ustKategoriId = intval($_GET['ust_kategori_id']);

    $query = "SELECT id, kategori_ad FROM kategoriler WHERE parent_id = ?";
    $stmt = $baglanti->prepare($query);
    $stmt->bind_param("i", $ustKategoriId);
    $stmt->execute();
    $result = $stmt->get_result();

    $altKategoriler = [];
    while ($row = $result->fetch_assoc()) {
        $altKategoriler[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($altKategoriler);
}
