<?php
include "connection.php";
$ad="ali";
$soyad="yılmaz";
$tel="05050454642";
$eposta="aliyılmaz@gmail";
$sifre=1234;
$durum=1;

$adi="ceket";
$k_id=27;


$query="INSERT INTO urunler(urun_ad,kategori_id) VALUES(?,?)";
// mysqli_query($baglanti,$query);
$stmt= mysqli_prepare($baglanti,$query);
mysqli_stmt_bind_param($stmt, 'si' ,$adi,$k_id );
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);


$query="INSERT INTO uyeler(ad,soyad,telefon_no,e_posta,sifre,durum) VALUES(?,?,?,?,?,?)";
// mysqli_query($baglanti,$query);
$stmt= mysqli_prepare($baglanti,$query);
mysqli_stmt_bind_param($stmt, 'ssssii' ,$ad,$soyad,$tel,$eposta,$sifre,$durum );
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_close($baglanti);
?>