<?php

    require "libs/functions.php";
?>
<?php include "partials/_header.php" ?>
<?php include "partials/_navbar.php" ?>
<div class="container ust-bosluk">
  <div class="row g-4">
  <h5></h5>
    <!-- Kart 1 -->


     <?php
     if(isset($_GET['q'])){
     $arama=$_GET['q'];}


     $dizi=getUrunByAd($arama);

      if (empty($dizi)) {
    echo "Hiç ürün bulunamadı.";
    exit;}?>
     <?php foreach($dizi as $urun):?> 
    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <img src="urunler/<?php echo $urun["urun_resim"];?>.png" class="card-img-top" alt="...">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?php echo $urun["urun_ad"]?></h5>
          <p class="card-text"><?php echo $urun["aciklama"]?></p>
          <a href="urundetay.php?id=<?php echo $urun["urun_id"]?>" class="btn btn-primary mt-auto koyumavibuton">İncele</a>
        </div>
      </div>
    </div>
    
    <?php endforeach;?>
    