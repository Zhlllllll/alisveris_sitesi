<?php
require "libs/functions.php";
?>
<?php include "partials/_header.php" ?>
<?php include "partials/_navbar.php" ?>
<?php include "connection.php";?>

<div class="container ust-bosluk">
    <h2>Ürün Arama</h2>
    <form method="post" id="kategoriForm" class="mb-4">
        <label for="ust_kategori_id">Üst Kategori Seç:</label>
        <select name="ust_kategori_id" id="ust_kategori_id" class="border p-2 rounded mr-2" onchange="loadAltKategoriler()">
            <option value="">Tüm Üst Kategoriler</option>
            <?php
            $ustKategoriler = getUstKategoriler(1);
            foreach ($ustKategoriler as $kategori): ?>
                <option value="<?php echo $kategori['id']; ?>">
                    <?php echo htmlspecialchars($kategori['kategori_ad']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="kategori_id">Alt Kategori Seç:</label>
        <select name="kategori_id" id="kategori_id" class="border p-2 rounded mr-2">
            <option value="">Tüm Alt Kategoriler</option>
        </select>

        <button type="submit" class="koyumavibuton">Ara</button>
    </form>

    <?php
    $urunler = [];
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kategori_id'])) {
        $kategori_id = intval($_POST['kategori_id']);
        $urunler = getFilteredUrunlerByKategori($kategori_id);
    }
    ?>

    <?php if (!empty($urunler)): ?>
        <div class="row g-4">
            <?php foreach ($urunler as $urun): ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100">
                        <img src="urunler/<?php echo $urun["urun_resim"]; ?>.png" class="card-img-top" alt="...">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $urun["urun_ad"] ?></h5>
                            <p class="card-text"><?php echo $urun["aciklama"] ?></p>
                            <a href="urundetay.php?id=<?php echo $urun["urun_id"] ?>" class="btn btn-primary mt-auto koyumavibuton">İncele</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>Bu kategoride ürün bulunamadı.</p>

    <?php else: ?>
        <?php
        $dizi = [];
        $sonuc = getUrunlerbyCinsiyet(1);
        while ($urun = mysqli_fetch_assoc($sonuc)) {
            $dizi[] = $urun;
        }

        if (empty($dizi)) {
            echo "Hiç ürün bulunamadı.";
            exit;
        }
        ?>
        <div class="row g-4">
            <?php for ($i = 0; $i < count($dizi); $i++): ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100">
                        <img src="urunler/<?php echo $dizi[$i]["urun_resim"]; ?>.png" class="card-img-top" alt="...">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $dizi[$i]["urun_ad"] ?></h5>
                            <p class="card-text"><?php echo $dizi[$i]["aciklama"] ?></p>
                            <a href="urundetay.php?id=<?php echo $dizi[$i]["urun_id"] ?>" class="btn btn-primary mt-auto koyumavibuton">İncele</a>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function loadAltKategoriler() {
    const ustKategoriId = document.getElementById('ust_kategori_id').value;
    const altKategoriSelect = document.getElementById('kategori_id');

    altKategoriSelect.innerHTML = '<option value="">Tüm Alt Kategoriler</option>';

    if (ustKategoriId) {
        fetch(`get_alt_kategoriler.php?ust_kategori_id=${ustKategoriId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(kategori => {
                    const option = document.createElement('option');
                    option.value = kategori.id;
                    option.textContent = kategori.kategori_ad;
                    altKategoriSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Hata:', error));
    }
}
</script>
