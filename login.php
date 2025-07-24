<?php
require "libs/functions.php";
require_once "connection.php";
?>

<?php include "partials/_header.php"; ?>
<?php include "partials/_navbar.php"; ?>
<?php
$username=$password=$error = "";
$name=$surname=$email=$telefon=$cinsiyet=$dogumtarihi="";
$adErr = $soyadErr = $emailErr =$phoneErr= $passwordErr =$repasswordErr=$cinsiyetErr=$dogumtarihiErr="";
  if (isset($_POST["register"])) {

    if(empty($_POST["name"])){
      $adErr= "Ad zorunlu alan.";
    } else {
      $name=ucfirst(trim(safe_html($_POST["name"])));
    }
    if(empty($_POST["surname"])){
      $soyadErr= "Soyad zorunlu alan.";
    } else {
      $surname=ucfirst(trim(safe_html($_POST["surname"])));
    }
    if(empty($_POST["email"])){
      $emailErr= "Email zorunlu alan.";
    } else {
      $query="SELECT id from uyeler where e_posta=?";
      if($stmt=mysqli_prepare($baglanti,$query)){
        $param_email=$email=trim(safe_html($_POST["email"]));
        mysqli_stmt_bind_param($stmt,'s',$param_email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt)==1){
          $emailErr="Bu eposta adresi zaten kayıtlı. Devam etmek için oturum aç.";

        }else{
          $email=safe_html($_POST["email"]);
        }
        
      }else{
          echo mysqli_error($baglanti);
        }

    } 
    if (empty($_POST["phone"])) {
    $phoneErr = "Telefon zorunlu alan.";
    } else {
    $telefon = safe_html($_POST["phone"]);
    if (!preg_match('/^[0-9]{11}$/', $telefon)) {
        $phoneErr = "Geçerli bir telefon numarası giriniz.";
    }
    }
    if(empty($_POST["password"])){
       $passwordErr= "Şifre zorunlu alan.";
    } else {
      $password=$_POST["password"];
      if (strlen($password) < 6) {
        $passwordErr = "Şifre en az 6 karakter olmalıdır.";
    }
    }   
    if($_POST["password"]!=$_POST["repassword"]){
      $repasswordErr= "Şifre tekrar alanı eşleşmiyor";
    } else {
      $repassword=$_POST["repassword"];
    }
    if(!isset($_POST["cinsiyet"])){
      $cinsiyetErr= "Cinsiyet seçmelisiniz.";
    } else {
      $cinsiyet=$_POST["cinsiyet"];
    }
    if(empty($_POST["dogum_tarihi"])){
    $dogumtarihiErr = "Doğum tarihi zorunlu alan.";
    } else {
    $dogumtarihi = safe_html($_POST["dogum_tarihi"]);
}
  }
if (empty($adErr) && empty($soyadErr) && empty($emailErr) && empty($phoneErr) && empty($passwordErr) && empty($repasswordErr) && empty($cinsiyetErr) && empty($dogumtarihiErr)) {
if (isset($_POST["register"])){
  $hashed_password= password_hash($password, PASSWORD_DEFAULT);
  uyeekle($name,$surname,$telefon,$email,$hashed_password,$cinsiyet,$dogumtarihi);
}
}
if(empty($_POST["login_username"])){
    $lusernameErr = "Username zorunlu alan.";
    } else {
    $lusername = safe_html($_POST["login_username"]);
}
if(empty($_POST["login_password"])){
    $lpasswordErr = "Şifre zorunlu alan.";
    } else {
    $lpassword = $_POST["login_password"];
}

// Giriş işlemi
if(isset($_POST["login"]) && empty($lusernameErr)&& empty($lpasswordErr)){
session_start();
$query= "SELECT id,ad, e_posta, sifre, user_type from uyeler where e_posta=? or telefon_no = ? ";
$stmt= mysqli_prepare($baglanti,$query);

mysqli_stmt_bind_param($stmt, 'ss', $lusername, $lusername);
if(mysqli_stmt_execute($stmt)){
  mysqli_stmt_store_result($stmt);
  }else{
    $loginErr="hata";
  }
  if(mysqli_stmt_num_rows($stmt)==1){
    mysqli_stmt_bind_result($stmt, $id, $ad, $email, $hashed_password, $user_type);
    if(mysqli_stmt_fetch($stmt)){
      if(password_verify($lpassword,$hashed_password)){
        $_SESSION["loggedIn"]=true;
        $_SESSION["id"]=$id;
        $_SESSION["username"]= $ad;
        $_SESSION["message"]=$ad." "."kullanıcı adıyla giriş yapıldı.";
        $_SESSION["usertype"]=$user_type;
        header("Location: index.php");
        exit;
      }else{
        
        $loginErr= "Şifre yanlış";
      }
    }else{
      $loginErr= "Username yanlış";
    }
  }else{
    $loginErr= "Bir hata oluştu";
  }
}

?>


<div class="mx-auto ust-bosluk">
  <div class="row">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($loginErr)): ?>
        <div class="alert alert-danger text-center">
          <?php echo $loginErr; ?>
        </div>
      <?php endif; ?>
      
    <!-- Kayıt Ol Kısmı -->
    <div class="col-md-6 login-container ">
      <h2 class="form-title">Kayıt Ol</h2>
      <form class="form-box" method="post">
        <div class="mb-3">
          <input type="text" name="name" class="form-control custom-input" value= "<?php echo $name?>" placeholder="Ad">
          <div class="text-danger"><?php echo $adErr;?></div>          
        </div>

        <div class="mb-3">
          <input type="text" name="surname" class="form-control custom-input" value= "<?php echo $surname ?>" placeholder="Soyad">
          <div class="text-danger"><?php echo $soyadErr;?></div>  
        </div>

        <div class="mb-3">
          <input type="email" name="email" class="form-control custom-input" value= "<?php echo $email?>" placeholder="Eposta">
          <div class="text-danger"><?php echo $emailErr;?></div>  
        </div>

        <div class="mb-3">
          <input type="text" name="phone" class="form-control custom-input" value= "<?php echo $telefon?>" placeholder="(0___) ___ __ __">
          <div class="text-danger"><?php echo $phoneErr;?></div>  
        </div>

        <div class="mb-3">
          <input type="password" name="password" class="form-control custom-input" placeholder="Şifre">
          <div class="text-danger"><?php echo $passwordErr;?></div>  
        </div>

        <div class="mb-3">
          <input type="password" name="repassword" class="form-control custom-input" placeholder="Şifre Tekrar">
          <div class="text-danger"><?php echo $repasswordErr;?></div>  
        </div>

        <div class="mb-3">
          <input type="text" name="dogum_tarihi" id="dogum_tarihi" class="form-control custom-input" value= "<?php echo $dogumtarihi ?>" placeholder="Tarih seçiniz">
          <div class="text-danger"><?php echo $dogumtarihiErr;?></div>  
        </div>

        <div class="d-flex gap-4 align-items-center mb-3">
          <label class="d-flex align-items-center gap-2 mb-0">
            <input type="radio" name="cinsiyet" value="1"> Kadın
          </label>
          <label class="d-flex align-items-center gap-2 mb-0">
            <input type="radio" name="cinsiyet" value="0"> Erkek
          </label>
              <div class="text-danger"><?php echo $cinsiyetErr;?></div>
        </div>
        

        <button type="submit" class="btn koyumavibuton" name="register">Kayıt Ol</button>
      </form>
    </div>

    

    <!-- Giriş Yap Kısmı -->
    <div class="col-md-6 login-container">
      <h2 class="form-title">Giriş Yap</h2>

      

      <form method="post" class="form-box">
        <div class="mb-3">
          <input type="text" name="login_username" class="form-control custom-input"
                 value="<?php echo $username ?>"
                 placeholder="E-posta ya da telefon numarası">
        </div>

        <div class="mb-3">
          <input type="password" name="login_password" class="form-control custom-input"  placeholder="Şifre">
          <a href="sifremiunuttum.php" class="forget-password">Şifremi Unuttum</a>
        </div>

        <button type="submit" class="btn koyumavibuton" name="login">Giriş Yap</button>
      </form>
    </div>

  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
  flatpickr("#dogum_tarihi", {
    dateFormat: "Y-m-d",
    maxDate: "today",
    locale: "tr"
  });
});
</script>
<?php include "partials/_footer.php"; ?>
