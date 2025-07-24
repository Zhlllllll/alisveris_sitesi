<?php
require "libs/functions.php";
include "partials/_header.php";
include "partials/_navbar.php";

include "connection.php";
$kullanici_id = $_SESSION["id"];

$sql = "SELECT f.urun_id, u.urun_ad, ud.urun_resim, ud.fiyat 
        FROM favoriler f 
        JOIN urunler u ON f.urun_id = u.id
        JOIN urun_detay ud ON u.id = ud.urun_id
        WHERE f.kullanici_id = ?";
$stmt = $baglanti->prepare($sql);
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();

// Sonuçları bind_result ile al
$stmt->bind_result($urun_id, $urun_ad, $urun_resim, $fiyat);
$favoriler = [];
while ($stmt->fetch()) {
    $favoriler[] = [
        'urun_id' => $urun_id,
        'urun_ad' => $urun_ad,
        'urun_resim' => $urun_resim,
        'fiyat' => $fiyat
    ];
}
$stmt->close();
?>

<div class="container d-flex flex-column ust-bosluk ">
    <h2>Favori Ürünlerim</h2>
    <?php if (count($favoriler) > 0): ?>
        <div class="row g-4">
            <?php foreach ($favoriler as $row): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="urunler/<?php echo htmlspecialchars($row["urun_resim"]); ?>.png" class="card-img-top" alt="<?php echo htmlspecialchars($row["urun_ad"]); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row["urun_ad"]); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row["fiyat"]); ?> TL</p>
                            <a href="urundetay.php?id=<?php echo $row["urun_id"]; ?>" class="btn koyumavibuton">Detayları Gör</a>
                            <a href="favori_sil.php" class="btn btn-danger remove-favorite" data-urun-id="<?php echo $row["urun_id"]; ?>">Favorilerden Kaldır</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Favori ürününüz bulunmamaktadır.</p>
    <?php endif; ?>
</div>

<?php include "partials/_footer.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-favorite').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const urun_id = this.getAttribute('data-urun-id');
            const kullanici_id = <?php echo $kullanici_id; ?>;

            fetch('favori_sil.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `urun_id=${urun_id}&kullanici_id=${kullanici_id}`
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    this.closest('.col-md-4').remove(); // Kartı sil
                    if (document.querySelectorAll('.col-md-4').length === 0) {
                        document.querySelector('.container ust-bosluk mt-5').innerHTML = '<p>Favori ürününüz bulunmamaktadır.</p>';
                    }
                } else {
                    console.error("Favori kaldırma başarısız:", data.error);
                }
            });
        });
    });
});
</script>