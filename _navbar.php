<!-- Üst NavBar -->
<div class="main-navbar ">
  <div class="container d-flex align-items-center justify-content-between">
    
    <!-- Logo -->
    <nav class="navbar bg-body-tertiary">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img src="resimler/logo.svg" alt="SadeceSen" width="auto" height="auto" class="d-inline-block align-text-top">
        </a>
      </div>
    </nav>
<!-- Arama Çubuğu -->
<form class="d-none d-lg-flex me-3 w-50" method="GET" action="aramaya_uygun_urunler.php" role="search">
  <div class="search-wrapper">
    <input type="text" name="q" class="form-control search-input custom-placeholder" placeholder="Aramak istediğiniz ürünü yazınız" autocomplete="off">
    <i class="bi bi-search search-icon"></i>
  </div>
</form>
<?php
session_start();
if (isset($_SESSION["loggedIn"])): ?>
  <div class="nav-icons d-flex align-items-center">
    
    <a href="cikis.php" class="icon-border d-flex align-items-center" title="Çıkış Yap">
      <i class="bi bi-box-arrow-right me-2" style="font-size: 24px;"></i>
      <span class="d-none d-sm-inline">Çıkış Yap</span>
    </a>
    <a href="profil.php" class="icon-border d-flex align-items-center" title="Profilim">
        <i class="bi bi-person me-2" style="font-size: 24px;"></i>
        <span class="d-none d-sm-inline"><?php echo "Hoş Geldiniz " . $_SESSION["username"]; ?></span>
      </a>

    <?php if (isset($_SESSION["usertype"]) && $_SESSION["usertype"] == "admin"): ?>
      <a href="profil.php" class="icon-border d-flex align-items-center" title="Profilim">
        <i class="bi bi-person me-2" style="font-size: 24px;"></i>
        <span class="d-none d-sm-inline"><?php echo "Hoş Geldiniz " . $_SESSION["username"]; ?></span>
      </a>
      <!-- Admin için sadece profil görünür, favoriler ve sepet yok -->
    <?php else: ?>
      <a href="favoriler.php" class="icon-border d-flex align-items-center" title="Favoriler">
        <i class="bi bi-heart me-2" style="font-size: 24px;"></i>
        <span class="d-none d-sm-inline">Favoriler</span>
      </a>
      <a href="sepetim.php" class="icon-border d-flex align-items-center" title="Sepetim">
        <i class="bi bi-bag me-2" style="font-size: 24px;"></i>
        <span class="d-none d-sm-inline">Sepetim</span>
      </a>
    <?php endif; ?>
  </div>

<?php else: ?>
  <!-- Giriş yapmamış kullanıcılar -->
  <div class="nav-icons d-flex align-items-center">
    <a href="login.php" class="icon-border d-flex align-items-center" title="Giriş Yap">
      <i class="bi bi-person me-2" style="font-size: 24px;"></i>
      <span class="d-none d-sm-inline">Giriş Yap</span>
    </a>
    <a href="#" class="icon-border d-flex align-items-center" title="Favoriler">
      <i class="bi bi-heart me-2" style="font-size: 24px;"></i>
      <span class="d-none d-sm-inline">Favoriler</span>
    </a>
    <a href="sepetim.php" class="icon-border d-flex align-items-center" title="Sepetim">
      <i class="bi bi-bag me-2" style="font-size: 24px;"></i>
      <span class="d-none d-sm-inline">Sepetim</span>
    </a>
  </div>
<?php endif; ?>



<!-- Kategori Menüsü -->
<div class="category-menu py-3 d-flex flex-column " >
  <div class="container d-flex justify-content-start gap-3 " >
    <a href="kadinurunler.php" class="category-link">Kadın</a>
    <a href="erkekurunler.php" class="category-link">Erkek</a>
    <a href="cocukurunler.php" class="category-link">Çocuk</a>
    <a href="#" class="category-link">İndirimli Ürünler</a>
    <a href="#" class="category-link">Kampanyalar</a>
<?php if (isset($_SESSION["usertype"]) && $_SESSION["usertype"]=="admin"):?>
    <a href="urun_ekle.php" class="category-link">Ürün Ekle</a>

<?else: echo "bir hta oluştu"?><?php endif; ?>
  </div> 
</div>

</div> </div>