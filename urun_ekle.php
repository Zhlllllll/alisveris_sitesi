<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $urun_ad = $_POST["urun_ad"] ?? "";
    $kategori_id = (int) ($_POST["kategori_id"] ?? 0);
    $aciklama = $_POST["aciklama"] ?? "";
    $beden = $_POST["beden"] ?? "";
    $renk = $_POST["renk"] ?? "";
    $adet = (int) ($_POST["adet"] ?? 0);
    $fiyat = (float) ($_POST["fiyat"] ?? 0);


$upload_dir = "urunler/";
    $uploadOk = 1;

    $resim_adi = basename($_FILES["urun_resim"]["name"]);
    $resim_adi = str_replace(" ", "", $resim_adi);

    $imageFileType = strtolower(pathinfo($resim_adi, PATHINFO_EXTENSION));
    $resim_adi = pathinfo($resim_adi, PATHINFO_FILENAME);

    $upload_file = $upload_dir . $resim_adi . "." . $imageFileType;

    // Resmin gerçekten resim olup olmadığını kontrol et
    $check = getimagesize($_FILES["urun_resim"]["tmp_name"]);
    if ($check === false) {
        echo "Dosya bir resim değil.";
        $uploadOk = 0;
    }

    // Uzantı kontrolü
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Sadece JPG, JPEG, PNG ve GIF dosyalarına izin verilir.";
        $uploadOk = 0;
    }


    if ($uploadOk && move_uploaded_file($_FILES["urun_resim"]["tmp_name"], $upload_file)) {
        // Ürün ekle
        $stmt = mysqli_prepare($baglanti, "INSERT INTO urunler (urun_ad, kategori_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "si", $urun_ad, $kategori_id);
        $result = mysqli_stmt_execute($stmt);


if ($result) {
    // 🔧 BURASI EKLENDİ
    $urun_id = mysqli_insert_id($baglanti);

    if (!$urun_id) {
        echo "Ürün ID alınamadı.";
        exit;
    }

    // 1. urun_detay tablosuna kayıt
    $stmt_detay = mysqli_prepare($baglanti, "INSERT INTO urun_detay (aciklama, urun_resim, fiyat, urun_id, renk) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_detay, "ssdis", $aciklama, $resim_adi, $fiyat, $urun_id, $renk);
    mysqli_stmt_execute($stmt_detay);



// 2. beden_ad ile beden_id'yi çek
$stmt_beden = mysqli_prepare($baglanti, "SELECT beden_id FROM beden WHERE beden_ad = ?");
mysqli_stmt_bind_param($stmt_beden, "s", $beden);
mysqli_stmt_execute($stmt_beden);
mysqli_stmt_bind_result($stmt_beden, $beden_id);
mysqli_stmt_fetch($stmt_beden);
mysqli_stmt_close($stmt_beden);

// 3. urun_beden tablosuna kayıt
if ($beden_id) {
    $stmt_ub = mysqli_prepare($baglanti, "INSERT INTO urun_beden (urun_id, beden_id, stok) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt_ub, "iii", $urun_id, $beden_id, $adet);
    mysqli_stmt_execute($stmt_ub);
    mysqli_stmt_close($stmt_ub);
} else {
    echo "Beden bulunamadı: " . htmlspecialchars($beden);
}


            echo "Ürün başarıyla eklendi.";

        } else {
            echo "Veritabanına eklenirken hata oluştu: " . mysqli_error($baglanti);
        }
    } else {
        echo "Resim yüklenirken hata oluştu.";
    }
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Ürün Ekleme</title>
</head>
<body>

<h2>Yeni Ürün Ekle</h2>
<form action="" method="post" enctype="multipart/form-data">
    <label>Ürün Adı:</label><br>
    <input type="text" name="urun_ad" required><br><br>

<label>Kategori:</label><br>
<select name="kategori_id" required>
    <option value="">Kategori Seç</option>
    <?php
    $kategori_sorgu = mysqli_query($baglanti, "
        SELECT k1.id AS alt_id, k1.kategori_ad AS alt_ad,
               k2.kategori_ad AS orta_ad,
               k3.kategori_ad AS ust_ad
        FROM kategoriler k1
        LEFT JOIN kategoriler k2 ON k1.parent_id = k2.id
        LEFT JOIN kategoriler k3 ON k2.parent_id = k3.id
        ORDER BY k3.kategori_ad, k2.kategori_ad, k1.kategori_ad
    ");

    while ($row = mysqli_fetch_assoc($kategori_sorgu)) {
        $ust = $row['ust_ad'] ? $row['ust_ad'] . " > " : "";
        $orta = $row['orta_ad'] ? $row['orta_ad'] . " > " : "";
        $ad = $row['alt_ad'];
        echo '<option value="' . $row['alt_id'] . '">' . htmlspecialchars($ust . $orta . $ad) . '</option>';
        
    }
    ?>
</select><br><br>


    <label>Ürün Resmi:</label><br>
    <input type="file" name="urun_resim" accept="image/*" required><br><br>

    <label>Açıklama:</label><br>
    <textarea name="aciklama" rows="4" cols="200" required></textarea><br><br>
    <label>Beden:</label><br>
    <input type="text" name="beden" required></input><br><br>

    <label>Renk:</label><br>
    <input type="text" name="renk" required></input><br><br>

    <label>Adet:</label><br>
    <input type="number" name="adet" required></input><br><br>

    <label>Fiyat (TL):</label><br>
    <input type="number" name="fiyat" step="100" required><br><br>

    <button type="submit">Ürün Ekle</button>
</form>

</body>
</html>
