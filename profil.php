<?php
include "partials/_header.php";
include "partials/_navbar.php";


require "libs/functions.php";
include "connection.php";
if(isset($_SESSION["id"])){
    $id = $_SESSION["id"];
    $siparisler=siparisGetir($id);
if (isset($_POST["degistir"])) {
    
    $yeni_sifre = trim($_POST["new-password"]);
    $yeni_sifre_tekrar = trim($_POST["new-password-repeat"]);

    

    if (empty($yeni_sifre) || empty($yeni_sifre_tekrar)) {
        echo '<div class="alert alert-danger">Lütfen tüm alanları doldurun.</div>';
    } elseif ($yeni_sifre !== $yeni_sifre_tekrar) {
        echo '<div class="alert alert-danger">Şifreler uyuşmuyor.</div>';
    } else {
        $sifre_hash = password_hash($yeni_sifre, PASSWORD_DEFAULT);

        $query = "UPDATE uyeler SET sifre = ? WHERE id = ?";
        $stmt = mysqli_prepare($baglanti, $query);
        mysqli_stmt_bind_param($stmt, "si", $sifre_hash, $id);
        $sonuc = mysqli_stmt_execute($stmt);

        if ($sonuc) {
            echo '<div class="alert alert-success ">Şifre başarıyla güncellendi.</div>';
        } else {
            echo '<div class="alert alert-danger">Şifre güncellenirken hata oluştu.</div>';
        }

        mysqli_stmt_close($stmt);
    }
}}
if (isset($_POST["degistir"]) && isset($_SESSION["id"])) {
$query="SELECT il_id from iller where il_adi=?";
$query="SELECT ilce_id from ilceler where il_id=?";
$query="SELECT semt_id from semtler where ilce_adi=?";
$query="SELECT mahalle_id from mahalleler where semt_adi=?";
}
if (isset($_POST["adresKaydet"])) {
    $il_id = $_POST["city"];
    $ilce_id = $_POST["district"];
    $semt_id = $_POST["neighborhood"];
    $mahalle_id = $_POST["mahalle"];
    $tam_adres = trim($_POST["full_address"]);

    // Tablona uygun şekilde adresi kaydet
    $stmt = $baglanti->prepare("INSERT INTO adresler (uye_id, il_id, ilce_id, semt_id, mahalle_id, tam_adres) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiis", $_SESSION["id"], $il_id, $ilce_id, $semt_id, $mahalle_id, $tam_adres);
    $stmt->execute();
    echo '<div class="alert alert-success">Adres başarıyla kaydedildi.</div>';
}

?>




    <div class="container ust-bosluk">
        <div class="card mb-3">
            <div class="card-body">
                <h2 class="card-title">Şifre Değiştir</h2>
                <form method="post" action="">
    <div class="mb-3">
        <label for="new-password" class="form-label">Yeni Şifre:</label>
        <input type="password" class="form-control" id="new-password" name="new-password">
    </div>
    <div class="mb-3">
        <label for="new-password-repeat" class="form-label">Yeni Şifre Tekrar:</label>
        <input type="password" class="form-control" id="new-password-repeat" name="new-password-repeat">
    </div>
    <button type="submit" name="degistir" class="btn btn-primary">Değiştir</button>
</form>
            </div>
        </div>
        
<?php if (!empty($siparisler)): ?>
    <div class="row">
        <?php foreach ($siparisler as $siparis): ?>
            <div class="col-6 mb-3">
                <div class="card">
                    <img src="urunler/<?php echo htmlspecialchars($siparis["urun_resim"]); ?>.png" class="card-img-top" alt="<?php echo htmlspecialchars($siparis["urun_ad"]); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($siparis["urun_ad"]); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($siparis["fiyat"]); ?> TL</p>
                        <p class="card-text">Adet: <?php echo htmlspecialchars($siparis["adet"]); ?></p>
                        <p class="card-text">Beden: <?php echo htmlspecialchars($siparis["beden"]); ?></p>
                        <p class="card-text">Toplam Tutar: <?php echo htmlspecialchars($siparis["toplam_tutar"]); ?> TL</p>                
                        <p class="card-text">Durum: <?php echo htmlspecialchars($siparis["durum"]); ?></p>
                        <p class="card-text">Tarih: <?php echo htmlspecialchars($siparis["siparis_tarihi"]); ?></p>
                        <a href="urundetay.php?id=<?php echo $siparis["urun_id"]; ?>" class="btn btn-primary">Detayları Gör</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Henüz siparişiniz bulunmamaktadır.</p>
<?php endif; ?>



        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Adres</h2>
                <form method="post" action="">
    <!-- İl -->
    <div class="mb-3">
        <label for="city" class="form-label">İl:</label>
        <select class="form-select" id="city" name="city" onchange="this.form.submit()">
            <option value="">İl seçin</option>
            <?php
            $iller = mysqli_query($baglanti, "SELECT id, il_adi FROM iller");
            while ($il = mysqli_fetch_assoc($iller)) {
                $selected = (isset($_POST["city"]) && $_POST["city"] == $il["id"]) ? "selected" : "";
                echo '<option value="' . $il["id"] . '" ' . $selected . '>' . $il["il_adi"] . '</option>';
            }
            ?>
        </select>
    </div>

    <!-- İlçe -->
    <div class="mb-3">
        <label for="district" class="form-label">İlçe:</label>
        <select class="form-select" id="district" name="district" onchange="this.form.submit()">
            <option value="">İlçe seçin</option>
            <?php
            if (!empty($_POST["city"])) {
                $il_id = intval($_POST["city"]);
                $ilceler = mysqli_query($baglanti, "SELECT id, ilce_adi FROM ilceler WHERE il_id = $il_id");
                while ($ilce = mysqli_fetch_assoc($ilceler)) {
                    $selected = (isset($_POST["district"]) && $_POST["district"] == $ilce["id"]) ? "selected" : "";
                    echo '<option value="' . $ilce["id"] . '" ' . $selected . '>' . $ilce["ilce_adi"] . '</option>';
                }
            }
            ?>
        </select>
    </div>

    <!-- Semt -->
    <div class="mb-3">
        <label for="neighborhood" class="form-label">Semt:</label>
        <select class="form-select" id="neighborhood" name="neighborhood" onchange="this.form.submit()">
            <option value="">Semt seçin</option>
            <?php
            if (!empty($_POST["district"])) {
                $ilce_id = intval($_POST["district"]);
                $semtler = mysqli_query($baglanti, "SELECT id, semt_adi FROM semtler WHERE ilce_id = $ilce_id");
                while ($semt = mysqli_fetch_assoc($semtler)) {
                    $selected = (isset($_POST["neighborhood"]) && $_POST["neighborhood"] == $semt["id"]) ? "selected" : "";
                    echo '<option value="' . $semt["id"] . '" ' . $selected . '>' . $semt["semt_adi"] . '</option>';
                }
            }
            ?>
        </select>
    </div>

    <!-- Mahalle -->
    <div class="mb-3">
        <label for="mahalle" class="form-label">Mahalle:</label>
        <select class="form-select" id="mahalle" name="mahalle">
            <option value="">Mahalle seçin</option>
            <?php
            if (!empty($_POST["neighborhood"])) {
                $semt_id = intval($_POST["neighborhood"]);
                $mahalleler = mysqli_query($baglanti, "SELECT id, mahalle_adi FROM mahalleler WHERE semt_id = $semt_id");
                while ($mahalle = mysqli_fetch_assoc($mahalleler)) {
                    $selected = (isset($_POST["mahalle"]) && $_POST["mahalle"] == $mahalle["id"]) ? "selected" : "";
                    echo '<option value="' . $mahalle["id"] . '" ' . $selected . '>' . $mahalle["mahalle_adi"] . '</option>';
                }
            }
            ?>
        </select>
    </div>

    <!-- Tam adres -->
    <div class="mb-3">
        <label for="full-address" class="form-label">Tam Adres:</label>
        <textarea class="form-control" name="full_address" id="full-address" placeholder="Tam adres girin"></textarea>
    </div>

    <button type="submit" class="btn btn-primary" name="adresKaydet">Kaydet</button>
</form>
                
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>