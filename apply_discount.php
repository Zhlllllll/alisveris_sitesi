<?php
session_start();
include "connection.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $discount_code = $_POST["discount_code"] ?? '';
    $total_amount = floatval($_POST["total_amount"] ?? 0);
    $uye_id = $_POST["uye_id"] ?? '';

    // Gelen verileri logla
    error_log("Received: discount_code=$discount_code, total_amount=$total_amount, uye_id=$uye_id");

    // İndirim kodunu kontrol et (basitleştirilmiş sorgu)
    $sql = "SELECT indirim_orani, nakit_indirim, baslangic, bitis FROM indirim WHERE indirim_kod = ?";
    $stmt = $baglanti->prepare($sql);
    if ($stmt === false) {
        error_log("Prepare failed: " . $baglanti->error);
        echo json_encode(["success" => false, "error" => "Sorgu hazırlama hatası: " . $baglanti->error]);
        exit;
    }
    $stmt->bind_param("s", $discount_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        error_log("Execute failed: " . $stmt->error);
        echo json_encode(["success" => false, "error" => "Sorgu çalıştırma hatası: " . $stmt->error]);
        exit;
    }

    $indirim = $result->fetch_assoc();

    if ($indirim) {
        $baslangic = strtotime($indirim['baslangic']);
        $bitis = strtotime($indirim['bitis']);
        $current_time = time();

        if ($baslangic <= $current_time && $bitis >= $current_time) {
            $discount_amount = 0;
            if ($indirim['indirim_orani'] > 0) {
                $discount_amount = $total_amount * ($indirim['indirim_orani'] / 100);
            } elseif ($indirim['nakit_indirim'] > 0) {
                $discount_amount = min($indirim['nakit_indirim'], $total_amount);
            }

            $new_total = max(0, $total_amount - $discount_amount);
            echo json_encode([
                "success" => true,
                "discount_amount" => $discount_amount,
                "new_total" => $new_total
            ]);
        } else {
            echo json_encode(["success" => false, "error" => "İndirim kodu süresi dolmuş!"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Geçersiz indirim kodu!"]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Geçersiz istek!"]);
}
$baglanti->close();
?>