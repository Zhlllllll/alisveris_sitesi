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