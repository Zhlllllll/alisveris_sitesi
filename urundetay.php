<?php
require "libs/functions.php";
?>

<?php include "partials/_header.php" ?>
<?php include "partials/_navbar.php" ?>
<?php
if (isset($_GET["id"])) {
    $urun_id = $_GET["id"];
    $urun = getUrunById($urun_id); 
    if (!$urun) {
        echo "Ürün bulunamadı.";
        exit;
    }
} else {
    echo "ID bulunamadı.";
    exit;
}
?>


<!-- Ürün Detayları -->
<div class="container ust-bosluk">
    <div class="row">
        <!-- Ürün Resmi -->
        <div class="col-md-6">
            <img src="urunler/<?php echo htmlspecialchars($urun["urun_resim"]); ?>.png" class="resim-boyut" alt="Tişört">
        </div>
        <?php print_r($urun);?>
        <!-- Ürün Detayları -->
        <div class="col-md-6">
            <div class="product-header d-flex justify-content-between align-items-start">
                <div>
                    <h2><?php echo htmlspecialchars($urun["urun_ad"]); ?></h2>
                    <h3><?php echo htmlspecialchars($urun["fiyat"]); ?> TL</h3>
                </div>
                <i class="bi bi-heart-fill fs-4" style="cursor: pointer;" id="favoriteIcon"></i>
            </div>
            <div class="d-flex align-items-center mb-3">
                <div class="text-warning">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                </div>
                <span class="ms-2">5 Değerlendirme</span>
            </div>
            <p><?php echo htmlspecialchars($urun["aciklama"]); ?></p>
            <!-- Beden Seçimi -->
            <div class="mb-3">
                <h5>Bedeni</h5>
                <?php $bedenler= explode(',',$urun["bedenler"])?>
                <?php foreach ($bedenler as $beden):?>
                <?php $beden=trim($beden); ?>
                <div class='size-option d-inline-block me-2 p-2 border rounded' data-size="<?php echo $beden?>"> <?php echo $beden;?></div>
                <?php endforeach;?>   
            </div>
            <!-- Renk Seçimi -->
            <div class="mb-3">
                <h5>Renk</h5>
                <?php $renkler= explode(',',$urun["renkler"])?>
                <?php foreach ($renkler as $index => $renk): ?>
                <div class="color-option <?php echo $index === 0 ? "selected" : ""; ?>" data-color="<?php echo $renk; ?>" style="background-color:<?php echo $renk; ?>;"></div>
                <?php endforeach; ?>
            </div>
            <!-- Adet ve Sepete Ekle -->
            <div class="d-flex align-items-center mb-3">
                <button class="btn btn-outline-secondary" type="button" id="decreaseQuantity">-</button>
                <label for="quantity" class="visually-hidden">Adet</label>
                <input type="number" id="quantity" class="form-control mx-3" value="1" min="1" style="width: 80px;">
                <button class="btn btn-outline-secondary" type="button" id="increaseQuantity">+</button>
                
                <form id="sepeteEkleForm" method="post" action="sepete_ekle.php" class="ms-3" onsubmit="return validateForm()">
                    <input type="hidden" name="urun_id" value="<?php echo $urun['urun_id']; ?>">
                    <input type="hidden" name="urun_d_id" value="<?php echo $urun['urun_d_id']; ?>">        
                    <input type="hidden" name="beden" id="bedenInput" >
                    <input type="hidden" name="renk" id="renkInput">
                    <input type="hidden" name="adet" id="adetInput" value="1">
                    <button type="submit" class="btn btn-warning">Sepete Ekle</button>
                </form>
                <a href="sepetim.php" class="btn btn-warning ms-3">Sepeti Görüntüle</a>
            </div>
            <!-- Ürün Bilgileri -->
            <p><strong>Ürün Kodu:</strong> SS<?php echo $urun["id"]?> </p>
                <p><strong>Kategori:</strong> <?php echo $urun["alt_kategori_ad"]?></p>
                <p><strong>Etiketler:</strong> <?php echo $urun["alt_kategori_ad"].", ".$urun["ust_kategori_ad"].", ".$urun["en_ust_kategori_ad"] ?></p>
            <!-- Sosyal Medya İkonları -->
            <div>
                <strong>Paylaş:</strong>
                <i class="bi bi-facebook mx-2"></i>
                <i class="bi bi-instagram mx-2"></i>
                <i class="bi bi-twitter mx-2"></i>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="container d-block mt-5">
    <footer class="py-5">
        <div class="row g-4">
            <div class="col-md-3 mb-3">
                <h5>SadeceSen</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Kurumsal</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Bayii Başvurusu</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Ödeme</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Şubelerimiz</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Hakkımızda</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-3">
                <h5>Müşteri Hizmetleri</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">İletişim Formu</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Sık Sorulan Sorular</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-3">
                <h5>Bilgilendirme Menüsü</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Site Kullanım Şartları</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Veri Gizliliği ve Güvenliği Politikası</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Bilgi Toplumu Hizmetleri</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Kişisel Verilerin Korunması</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-3">
                <form>
                    <h5>Bülten Aboneliği</h5>
                    <p>Yenilikleri ve kampanyaları kaçırmamak için e-posta adresinizi bırakın.</p>
                    <div class="d-flex flex-column flex-sm-row w-100 gap-2">
                        <label for="newsletter1" class="visually-hidden">Email adresiniz</label>
                        <input id="newsletter1" type="email" class="form-control" placeholder="Email address">
                        <button class="btn koyumavibuton" type="button">Abone Ol</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
            <p>© 2025 Company, Inc. Tüm hakları saklıdır.</p>
            <ul class="list-unstyled d-flex">
                <li class="ms-3"><a class="link-body-emphasis" href="#" aria-label="Instagram"><svg class="bi" width="24" height="24"><use xlink:href="#instagram"></use></svg></a></li>
                <li class="ms-3"><a class="link-body-emphasis" href="#" aria-label="Facebook"><svg class="bi" width="24" height="24"><use xlink:href="#facebook"></use></svg></a></li>
            </ul>
        </div>
    </footer>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const favoriteIcon = document.getElementById("favoriteIcon");
        if (favoriteIcon) {
            favoriteIcon.addEventListener("click", function() {
                const urun_id = <?php echo $urun['urun_id']; ?>;
                const kullanici_id = <?php echo isset($_SESSION["id"]) ? $_SESSION["id"] : 'null'; ?>;
                if (kullanici_id === null) {
                alert("Favorilere eklemek için giriş yapmalısınız.");
                return;}

                if (this.classList.contains("favorited")) {
                    this.classList.remove("favorited");
                    this.style.color = "#888";
                    fetch('favori_sil.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `urun_id=${urun_id}&kullanici_id=${kullanici_id}`
                    }).then(response => response.json()).then(data => {
                        if (!data.success) console.error("Favori silme başarısız:", data.error);
                    });
                } else {
                    this.classList.add("favorited");
                    this.style.color = "red";
                    fetch('favori_ekle.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `urun_id=${urun_id}&kullanici_id=${kullanici_id}`
                    }).then(response => response.json()).then(data => {
                        if (!data.success) {
                            console.error("Favori ekleme başarısız:", data.error);
                            this.classList.remove("favorited");
                            this.style.color = "#888";
                        } else if (data.message) {
                            console.log(data.message);
                        }
                    });
                }
                console.log("Kalbe tıklandı, renk değişti:", this.style.color);
            });
        }

        // Diğer fonksiyonlar (changeQuantity, validateForm, vb.) aynı kalabilir
        function changeQuantity(change) {
            const input = document.getElementById('quantity');
            let currentValue = parseInt(input.value) || 1;
            currentValue += change;
            if (currentValue < 1) currentValue = 1;
            input.value = currentValue;
            document.getElementById('adetInput').value = currentValue;
            console.log("Adet değişti:", currentValue);
        }

        document.getElementById('decreaseQuantity').addEventListener('click', () => changeQuantity(-1));
        document.getElementById('increaseQuantity').addEventListener('click', () => changeQuantity(1));

        let selectedSize = null;
        document.querySelectorAll('.size-option').forEach(option => {
            option.addEventListener('click', () => {
                document.querySelectorAll('.size-option').forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                selectedSize = option.dataset.size;
                document.getElementById('bedenInput').value = selectedSize;
                console.log('Seçilen beden:', selectedSize);
            });
        });

        let selectedColor = '<?php echo htmlspecialchars($urun["renk"]); ?>';
        document.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', () => {
                document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                selectedColor = option.dataset.color || '<?php echo htmlspecialchars($urun["renk"]); ?>';
                document.getElementById('renkInput').value = selectedColor;
                console.log('Seçilen renk:', selectedColor);
            });
        });

        function validateForm() {
            const beden = document.getElementById('bedenInput').value;
            const renk = document.getElementById('renkInput').value;
            const adet = document.getElementById('adetInput').value;
            console.log('Form doğrulama:', { beden, renk, adet }); // Debug için log
            if (!beden) {
                alert("Lütfen bir beden seçin!");
                return false;
            }
            if (!renk) {
                alert("Lütfen bir renk seçin!");
                return false;
            }
            if (!adet || adet < 1) {
                alert("Lütfen geçerli bir adet girin!");
                return false;
            }
            return true;
        }

        // Varsayılan değerleri ayarla
        document.getElementById('bedenInput').value = selectedSize;
        document.getElementById('renkInput').value = selectedColor;
        document.getElementById('adetInput').value = 1;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>