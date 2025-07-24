<?php

    require "libs/functions.php";
?>
<?php include "partials/_header.php" ?>
<?php include "partials/_navbar.php" ?>
<div class="container ust-bosluk">
  <div class="row g-4">
  <h5>YENİ ÜRÜNLER</h5>
    <!-- Kart 1 -->
     
     <?php $dizi=[];
     $sonuc=getUrunler(); while($urun=mysqli_fetch_assoc($sonuc)){
      $dizi[]=$urun;}
      ?>
     <?php for($i=0;$i<8;$i++):?> 
    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <img src="urunler/<?php echo $dizi[$i]["urun_resim"];?>.png" class="card-img-top" alt="...">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?php echo $dizi[$i]["urun_ad"]?></h5>
          <p class="card-text"><?php echo $dizi[$i]["acıklama"]?></p>
          <a href="urundetay.php?id=<?php echo $dizi[$i]["id"]?>" class="btn btn-primary mt-auto koyumavibuton">İncele</a>
        </div>
      </div>
    </div>
    
    <?php endfor;?>
    


  </div> <!-- row -->
</div> <!-- container -->



<nav aria-label="Page navigation">
  <ul class="pagination">
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
  </ul>
</nav>

<?php include "partials/_footer.php"?>