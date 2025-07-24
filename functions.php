<?php
  // function urlDuzenle($baslik){
  //   return str_replace([" ","ç","@","."],["-","c","","-"],strtolower($baslik));
  // }
  // function kisaAçiklama($altBaslik){
  //   if(strlen($altBaslik)>50){
  //    return substr($altBaslik,0,50)."...";}
  // else{
  //   return $altBaslik; 
  // }
  // }


function uyeekle($ad, $soyad,$telefon, $e_posta, $sifre, $cinsiyet,$dogum_tarihi) {
  include "connection.php";
  $query= "INSERT INTO uyeler (ad, soyad, telefon_no, e_posta, sifre, durum) VALUES (?,?,?,?,?,1)";
  $stmt= mysqli_prepare($baglanti,$query);
  mysqli_stmt_bind_param($stmt, 'sssss' , $ad, $soyad, $telefon, $e_posta, $sifre);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  $uyeler_id = mysqli_insert_id($baglanti);

  $query = "INSERT INTO uye_detay (cinsiyet, dogum_tarihi, uyeler_id) VALUES (?,?,?)";
  $stmt= mysqli_prepare($baglanti,$query);
  mysqli_stmt_bind_param($stmt, 'ssi' , $cinsiyet, $dogum_tarihi, $uyeler_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}


  function safe_html($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
    function getUrunler(){
  include "connection.php";
  $query = "SELECT
  urunler.id AS urun_id,
  urunler.urun_ad,
  urun_detay.id AS urundetay_id,
  urun_detay.*,
  kategoriler.kategori_ad AS alt_kategori_ad,
  ust_kategori.kategori_ad AS ust_kategori_ad,
  en_ust_kategori.kategori_ad AS en_ust_kategori_ad
FROM urunler
JOIN urun_detay ON urunler.id = urun_detay.urun_id
JOIN kategoriler ON urunler.kategori_id = kategoriler.id
LEFT JOIN kategoriler AS ust_kategori ON kategoriler.parent_id = ust_kategori.id #aynı kategoriler tablosu ama alias olarak ust_kategori kullandık
LEFT JOIN kategoriler AS en_ust_kategori ON ust_kategori.parent_id = en_ust_kategori.id
ORDER BY urunler.id desc;" ;

    $sonuc=mysqli_query($baglanti,$query);
    if (!$sonuc) {
        die("Sorgu hatası: " . mysqli_error($baglanti));
    }

    mysqli_close($baglanti);
    return $sonuc;

  }




  function getUrunlerbyCinsiyet($parent_id){ // 1 2 3
  include "connection.php";
  $query = "SELECT
  urunler.id AS urun_id,
  urunler.urun_ad,
  urun_detay.id AS urundetay_id,
  urun_detay.*,
  kategoriler.kategori_ad AS alt_kategori_ad,
  ust_kategori.kategori_ad AS ust_kategori_ad,
  en_ust_kategori.kategori_ad AS en_ust_kategori_ad
FROM urunler
JOIN urun_detay ON urunler.id = urun_detay.urun_id
JOIN kategoriler ON urunler.kategori_id = kategoriler.id
LEFT JOIN kategoriler AS ust_kategori ON kategoriler.parent_id = ust_kategori.id #aynı kategoriler tablosu ama alias olarak ust_kategori kullandık
LEFT JOIN kategoriler AS en_ust_kategori ON ust_kategori.parent_id = en_ust_kategori.id
WHERE urunler.kategori_id IN (
    SELECT id FROM kategoriler 
    WHERE parent_id = $parent_id
    OR parent_id IN (
        SELECT id FROM kategoriler WHERE parent_id = $parent_id
    )
)
ORDER BY RAND();" ;

    $sonuc=mysqli_query($baglanti,$query);
    if (!$sonuc) {
        die("Sorgu hatası: " . mysqli_error($baglanti));
    }

    mysqli_close($baglanti);
    return $sonuc;

  }






  function getUrunById($id){
    include "connection.php";
    $query = "SELECT urunler.*,urun_detay.id as urun_d_id, urun_detay.*,urun_beden.*,GROUP_CONCAT(beden.beden_ad  SEPARATOR ', ') AS bedenler,
    GROUP_CONCAT(DISTINCT urun_detay.renk SEPARATOR ', ') AS renkler,
    kategoriler.kategori_ad AS alt_kategori_ad,
    ust_kategori.kategori_ad AS ust_kategori_ad,
    en_ust_kategori.kategori_ad AS en_ust_kategori_ad
FROM urunler
LEFT JOIN urun_detay ON urunler.id = urun_detay.urun_id
LEFT JOIN urun_beden ON urun_beden.urun_id=urunler.id
LEFT JOIN beden ON urun_beden.beden_id=beden.beden_id
LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.id
LEFT JOIN kategoriler AS ust_kategori ON kategoriler.parent_id = ust_kategori.id
LEFT JOIN kategoriler AS en_ust_kategori ON ust_kategori.parent_id = en_ust_kategori.id
WHERE urunler.id = ?
GROUP BY urunler.id";
     $stmt = mysqli_prepare($baglanti, $query);
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($baglanti));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        die("Execute failed: " . mysqli_stmt_error($stmt));
    }

    $sonuc = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($sonuc)) {
        return $row;
    } else {
        echo "<pre>Sorgudan sonuç dönmedi. Gelen ID: $id</pre>";
        return null;
    }
}
function getUrunByAd($arama){
  

    
    include "connection.php";
    $query = "SELECT urunler.*, urun_detay.*, kategoriler.kategori_ad AS alt_kategori_ad,
       ust_kategori.kategori_ad AS ust_kategori_ad,
       en_ust_kategori.kategori_ad AS en_ust_kategori_ad
FROM urunler
LEFT JOIN urun_detay ON urunler.id = urun_detay.urun_id
LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.id
LEFT JOIN kategoriler AS ust_kategori ON kategoriler.parent_id = ust_kategori.id
LEFT JOIN kategoriler AS en_ust_kategori ON ust_kategori.parent_id = en_ust_kategori.id
WHERE urunler.urun_ad like ?";
$kelime="%".$arama."%";
     $stmt = mysqli_prepare($baglanti, $query);
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($baglanti));
    }

    mysqli_stmt_bind_param($stmt, "s", $kelime);
    if (!mysqli_stmt_execute($stmt)) {
        die("Execute failed: " . mysqli_stmt_error($stmt));
    }

    $sonuc = mysqli_stmt_get_result($stmt);
    $urunler=[];
    while ($row = mysqli_fetch_assoc($sonuc)) {
        $urunler[]=$row;
    } 
    return $urunler;
  }



function getUrunFiyat($urun_id, $urun_detay_id = null) {
    global $baglanti;

    if ($urun_detay_id) {
        $sql = "SELECT son_fiyat, fiyat FROM urun_detay WHERE id = ? AND urun_id = ?";
        $stmt = $baglanti->prepare($sql);
        $stmt->bind_param("ii", $urun_detay_id, $urun_id);
    } else {
        $sql = "SELECT fiyat FROM urun_detay WHERE urun_id = ? LIMIT 1";
        $stmt = $baglanti->prepare($sql);
        $stmt->bind_param("i", $urun_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $urun = $result->fetch_assoc();
    $stmt->close();

    if (!$urun) return 0;

    // son_fiyat boş veya 0 ise fiyatı kullan
    if (!empty($urun["son_fiyat"]) && $urun["son_fiyat"] > 0) {
        return $urun["son_fiyat"];
    }

    return $urun["fiyat"] ?? 0;
}
function siparisGetir($id) {
    include "connection.php";

    $query = "SELECT s.*, ud.*, u.* FROM siparisler s
              LEFT JOIN urunler u ON s.urun_id = u.id
              LEFT JOIN urun_detay ud ON ud.urun_id = u.id
              WHERE s.kullanici_id = ?";

    $stmt = mysqli_prepare($baglanti, $query);
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($baglanti));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        die("Execute failed: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);

    $siparisler = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $siparisler[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $siparisler;
}

function getUstKategoriler($id) {
    global $baglanti;
    $sql = "SELECT id, kategori_ad FROM kategoriler WHERE parent_id=$id ORDER BY kategori_ad ASC";
    $stmt = $baglanti->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $ustKategoriler = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $ustKategoriler;
}

function getAltKategorilerByUstKategori($ust_kategori_id) {
    global $baglanti;
    $sql = "SELECT id, kategori_ad FROM kategoriler WHERE parent_id = ? ORDER BY kategori_ad ASC";
    $stmt = $baglanti->prepare($sql);
    $stmt->bind_param("i", $ust_kategori_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $altKategoriler = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $altKategoriler;
}

function getFilteredUrunlerByKategori($kategori_id = null) {
    global $baglanti;

    $query = "SELECT urunler.*, urun_detay.*, kategoriler.kategori_ad AS alt_kategori_ad,
              ust_kategori.kategori_ad AS ust_kategori_ad,
              en_ust_kategori.kategori_ad AS en_ust_kategori_ad
              FROM urunler
              LEFT JOIN urun_detay ON urunler.id = urun_detay.urun_id
              LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.id
              LEFT JOIN kategoriler AS ust_kategori ON kategoriler.parent_id = ust_kategori.id
              LEFT JOIN kategoriler AS en_ust_kategori ON ust_kategori.parent_id = en_ust_kategori.id";

    $where = [];
    $params = [];
    $types = "";

    // Kategori filtresi (alt kategori ID'si)
    if ($kategori_id && is_numeric($kategori_id) && $kategori_id > 0) {
        $where[] = "urunler.kategori_id = ?";
        $params[] = $kategori_id;
        $types .= "i";
    }

    // WHERE koşullarını ekle
    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }

    $stmt = $baglanti->prepare($query);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $urunler = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    return $urunler;
}

?>