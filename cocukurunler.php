<?php

    require "libs/functions.php";
?>
<?php include "partials/_header.php" ?>
<?php include "partials/_navbar.php" ?>
<div class="container ust-bosluk">
  <div class="row g-4">
  <h5></h5>
    <!-- Kart 1 -->


     <?php $dizi=[];
     $sonuc=getUrunlerbyCinsiyet(3); while($urun=mysqli_fetch_assoc($sonuc)){
      $dizi[]=$urun;}

      if (empty($dizi)) {
    echo "Hiç ürün bulunamadı.";
    exit;}?>
     <?php for($i=0;$i<count($dizi);$i++):?> 
    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <img src="urunler/<?php echo $dizi[$i]["urun_resim"];?>.png" class="card-img-top" alt="...">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?php echo $dizi[$i]["urun_ad"]?></h5>
          <p class="card-text"><?php echo $dizi[$i]["aciklama"]?></p>
          <a href="urundetay.php?id=<?php echo $dizi[$i]["urun_id"]?>" class="btn btn-primary mt-auto koyumavibuton">İncele</a>
        </div>
      </div>
    </div>
    
    <?php endfor;?>