<?php
session_start();
require "libs/functions.php";
include "connection.php";

if (!isset($_SESSION["id"])) {
    die("Lütfen giriş yapın.");
}
$uye_id = $_SESSION["id"];

$sql = "SELECT s.id AS sepet_id, u.urun_ad, ud.fiyat, ud.son_fiyat, ud.indirim_orani, ud.urun_resim, su.beden, su.renk, su.adet, su.id AS sepet_urun_id, u.id AS urun_id, u.kategori_id
        FROM alisveris_sepeti s 
        JOIN sepet_urunler su ON s.id = su.sepet_id 
        JOIN urun_detay ud ON su.urun_detay_id = ud.id 
        JOIN urunler u ON su.urun_id = u.id 
        WHERE s.uye_id = ? AND s.is_completed = 0";
$stmt = $baglanti->prepare($sql);
$stmt->bind_param("i", $uye_id);
$stmt->execute();
$sepet = $stmt->get_result();

$toplam_tutar = 0;
$sepet_urunler = [];
while ($row = $sepet->fetch_assoc()) {
    $gosterilecek_fiyat = $row["son_fiyat"] ?? $row["fiyat"];
    $toplam = $gosterilecek_fiyat * $row["adet"];
    $toplam_tutar += $toplam;
    $sepet_urunler[] = $row;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepetim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>
<body class="bg-gray-100 p-4">
    <div class="text-red-500 text-xl text-center mb-6">Tailwind ile Hazırlanan Sepet</div>
    <?php if ($sepet->num_rows > 0): ?>
    <div class="flex bg-white p-6 rounded-lg shadow-lg w-[1440px] max-w-full gap-6 mx-auto mt-[170px]">
        <div class="flex-1">
            <div class="bg-[#F8F2E9] grid grid-cols-5 text-sm font-medium text-center py-2 rounded-md mb-4">
                <div>Ürün</div>
                <div>Fiyat</div>
                <div>Beden</div>
                <div>Miktar</div>
                <div>Toplam Fiyat</div>
            </div>
            <?php foreach ($sepet_urunler as $row): 
                $gosterilecek_fiyat = $row["son_fiyat"] ?? $row["fiyat"];
                $toplam = $gosterilecek_fiyat * $row["adet"];
            ?>
            <div class="grid grid-cols-5 items-center text-center gap-4 sepet-satir" data-sepet-urun-id="<?php echo $row["sepet_urun_id"]; ?>">
                <div class="flex items-center gap-3">
                    <img src="urunler/<?php echo htmlspecialchars($row["urun_resim"] ?? 'default'); ?>.png" class="bg-[#F8F2E9] p-2 rounded-md w-16 h-16" />
                    <span class="text-sm"><?php echo htmlspecialchars($row["urun_ad"]); ?></span>
                </div>
                <div class="text-sm text-gray-500">
                    <?php if (isset($row["indirim_orani"]) && $row["indirim_orani"] > 0): ?>
                        <span class="line-through text-muted"><?php echo $row["fiyat"]; ?> TL</span>
                        <span class="text-green-600"><?php echo $row["son_fiyat"]; ?> TL</span>
                    <?php else: ?>
                        <?php echo $row["fiyat"]; ?> TL
                    <?php endif; ?>
                </div>
                <div>
                    <span><?php echo $row["beden"]; ?></span>
                </div>
                <div>
                    <input type="number" value="<?php echo $row["adet"]; ?>" class="w-12 border rounded text-center adet-input" min="1" data-sepet-urun-id="<?php echo $row["sepet_urun_id"]; ?>" data-fiyat="<?php echo $gosterilecek_fiyat; ?>">
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="toplam-fiyat"><?php echo $toplam; ?> TL</span>
                    <a href="#" class="text-gray-500 hover:text-red-500 remove-item" data-sepet-urun-id="<?php echo $row["sepet_urun_id"]; ?>"><i class="bi bi-trash3"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Sepet Özeti -->
        <div class="bg-[#F8F2E9] p-6 rounded-lg w-72">
            <h2 class="text-lg font-bold mb-4 text-center">Sepetim</h2>
            <div class="flex justify-between mb-2 text-sm">
                <span>Miktar</span>
                <span class="text-gray-500"><?php echo $sepet->num_rows; ?></span>
            </div>
            <div class="flex justify-between mb-4 text-sm">
                <span>Toplam</span>
                <span class="text-[#D65F5F] font-semibold" id="total-amount"><?php echo $toplam_tutar; ?> TL</span>
                <?php  $_SESSION["toplam_tutar"]=$toplam_tutar; ?>
            </div>
            <div class="flex justify-between mb-4 text-sm">
                <span>İndirim</span>
                <span class="text-[#D65F5F] font-semibold" id="discount-amount">0 TL</span>
            </div>
            <div class="flex justify-between mb-4 text-sm">
                <span>İndirim Kodu</span>
                <div>
                    <input type="text" id="discount-code" class="border rounded p-1 text-sm w-20" placeholder="Kodu gir">
                    <button id="apply-discount" class="bg-blue-500 text-white rounded p-1 ml-1 text-sm">Uygula</button>
                </div>
            </div>
            <form method="post" action="sepet_onayla.php">
                <button type="submit" name="onayla" class="bg-[#FF6600] text-white w-full py-2 rounded-md text-sm hover:bg-orange-600 transition-colors">
                    Sepeti Onayla →
                </button>
            </form>
        </div>
    </div>
    <?php else: ?>
        <p class="text-center mt-[170px]">Sepetiniz boş.</p>
    <?php endif; ?>
</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Silme butonları
    const removeButtons = document.querySelectorAll('.remove-item');
    if (removeButtons.length > 0) {
        removeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const sepet_urun_id = this.getAttribute('data-sepet-urun-id');
                console.log('Silme butonuna tıklandı, sepet_urun_id:', sepet_urun_id);

                fetch('sepet_sil.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `sepet_urun_id=${sepet_urun_id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const satir = this.closest('.sepet-satir');
                        satir.remove();
                        guncelleToplam();
                        if (document.querySelectorAll('.sepet-satir').length === 0) {
                            document.querySelector('body').innerHTML = '<p class="text-center mt-[170px]">Sepetiniz boş.</p>';
                        }
                    } else {
                        console.error('Ürün silme başarısız:', data.error);
                    }
                })
                .catch(error => console.error('Hata:', error));
            });
        });
    } else {
        console.log('Hiçbir .remove-item elemanı bulunamadı.');
    }

    // Adet değişikliği
    document.querySelectorAll('.adet-input').forEach(input => {
        input.addEventListener('change', function() {
            const sepet_urun_id = this.getAttribute('data-sepet-urun-id');
            const yeniAdet = parseInt(this.value);
            const fiyat = parseFloat(this.getAttribute('data-fiyat'));
            const toplamElement = this.closest('.sepet-satir').querySelector('.toplam-fiyat');

            if (yeniAdet >= 1) {
                fetch('sepet_guncelle.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `sepet_urun_id=${sepet_urun_id}&adet=${yeniAdet}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const yeniToplam = fiyat * yeniAdet;
                        toplamElement.textContent = `${yeniToplam.toFixed(2)} TL`;
                        guncelleToplam();
                    } else {
                        console.error('Adet güncellemesi başarısız:', data.error);
                        this.value = data.gecerli_adet; // Eski değeri geri yükle
                        const eskiToplam = fiyat * data.gecerli_adet;
                        toplamElement.textContent = `${eskiToplam.toFixed(2)} TL`;
                        guncelleToplam();
                    }
                })
                .catch(error => console.error('Hata:', error));
            } else {
                this.value = 1; // Minimum 1
                const yeniToplam = fiyat * 1;
                toplamElement.textContent = `${yeniToplam.toFixed(2)} TL`;
                guncelleToplam();
            }
        });
    });

    // İndirim kodu kontrolü
    const applyDiscountButton = document.getElementById('apply-discount');
    if (applyDiscountButton) {
        applyDiscountButton.addEventListener('click', function() {
            const discountCode = document.getElementById('discount-code').value;
            const totalAmountElement = document.getElementById('total-amount');
            const discountAmountElement = document.getElementById('discount-amount');
            let totalAmount = parseFloat(totalAmountElement.textContent.replace(' TL', '')) || 0;
            let discountAmount = 0;

            fetch('apply_discount.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `discount_code=${discountCode}&total_amount=${totalAmount}&uye_id=<?php echo $uye_id; ?>`
            })
            .then(response => response.json())
            .then(data => {
    if (data.success) {
        let discountAmount = parseFloat(data.discount_amount) || 0;
        let totalAmount = parseFloat(data.new_total) || 0;
        discountAmountElement.textContent = `${discountAmount.toFixed(2)} TL`;
        totalAmountElement.textContent = `${totalAmount.toFixed(2)} TL`;
    } else {
        alert('Geçersiz veya süresi dolmuş indirim kodu: ' + data.error);
        discountAmountElement.textContent = '0.00 TL';
        totalAmountElement.textContent = `${totalAmountElement.textContent}`; // Aynı kalabilir
    }
})
            .catch(error => {
                console.error('Hata:', error);
                alert('Bir hata oluştu, lütfen tekrar deneyin.');
            });
        });
    } else {
        console.log('apply-discount butonu bulunamadı.');
    }

    // Toplamı güncelleyen fonksiyon
    function guncelleToplam() {
        let yeniToplam = 0;
        document.querySelectorAll('.toplam-fiyat').forEach(element => {
            yeniToplam += parseFloat(element.textContent.replace(' TL', ''));
        });
        document.getElementById('total-amount').textContent = `${yeniToplam.toFixed(2)} TL`;
    }
   
});
</script>