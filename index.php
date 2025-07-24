<?php

    require "libs/functions.php";?>

<?php include "partials/_header.php" ?>
<?php include "partials/_navbar.php" ?>


<div class="ust-bosluk ">
<!-- Ortalamak için dış div -->
<?php if(isset($_SESSION["siparis_basarili"])){
  echo '<div class="alert alert-success">Siparişiniz başarıyla tamamlandı.</div>';
  unset($_SESSION["siparis_basarili"]);
}
?>
 <?php if(isset($_SESSION["message"])){
  echo "<div class='alert alert-danger text-center'>".$_SESSION["message"]."</div>";
  unset($_SESSION["message"]);} ?>
<div class="container d-flex flex-column justify-content-center ">
  <!-- Asıl carousel -->
  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    
    <!-- Göstergeler -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
    </div>

    <!-- Slider içerikleri buraya -->
    <div class="carousel-inner mt-3">
      <div class="carousel-item active">
        <img src="resimler/resim1.png" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="resimler/resim2.png" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="resimler/resim3.png" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="resimler/resim4.png" class="d-block w-100" alt="...">
      </div>
    </div>

    <!-- Önceki/Sonraki butonları -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Geri</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">İleri</span>
    </button>
  </div>
</div>

<!-- fırsatlar kutular -->
<div class="container d-flex flex-column mt-5">
  <h2>Fırsatlar</h2>
<div class="container hiza">
  <div class="kampanya-sol">
    <img src="resimler/Rectangle 1 (3).png" alt="Gömlek Kampanya" />
    <div class="kampanya-icerik">
      <h2><strong>%80’e varan indirimler</strong> <br>  sizleri bekliyor</h2>
      <button>Detaylı Bilgi</button>
    </div>
  </div>

  <div class="kampanya-sag">
    <div class="sag-ust">
      <div class="kutu">
        <div>
          <h3> %10 indirim</h3>
          <p>1000 TL VE ÜZERİ Tüm Ürünlerde</p>
        </div>
        <button>Detaylı Bilgi</button>
      </div>
      <div class="kutu">
        <div>
          <h3>Çantalar</h3>
          <p>İhtiyacınız olan her şey</p>
        </div>
        <button>Detaylı Bilgi</button>
      </div>
    </div>

    <div class="pembe-kutu">
      <h3>3 al 2 öde!</h3>
      <p>Seçili tüm ürünlerde 3 tane satın alın 2 ürün fiyatı ödeyin.</p>
      <button>Detaylı Bilgi</button>
    </div>
  </div>
</div>
</div>



<!-- ÜRÜNLER -->
<div class="container d-flex flex-column mt-5">
  <div class="row g-4">
  <h5>YENİ ÜRÜNLER</h5>
    
     <?php $dizi=[];
     $sonuc=getUrunler(); while($urun=mysqli_fetch_assoc($sonuc)){
      $dizi[]=$urun;}

      if (empty($dizi)) {
    echo "Hiç ürün bulunamadı.";
    exit;}?>
     <?php for($i=0;$i<8;$i++):?> 
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
    <button type="button" class="btn btn-outline-dark" onclick="window.location.href='tumurunler.php'" role="button">Daha fazlasını gör</button>


  </div> <!-- row -->
</div> <!-- container -->

<?php include "partials/_footer.php" ?></div>