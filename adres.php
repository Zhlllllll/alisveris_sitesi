<?php

    require "libs/functions.php";
?>
<?php include "partials/_header.php" ?>
<?php
$adErr=$soyadErr=$emailErr=$phoneErr==$adresErr=$adresBaslikErr=0;
?>

<div class="container">
    <div class="row">
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
          <label for="iller">İl seçiniz: </label>
          <select name="iller" id="iller">
            <option value="volvo">Volvo</option>
          </select> 
        </div>

        <div class="mb-3">
          <label for="ilçeler">İl seçiniz: </label>
          <select name="ilçeler" id="ilçeler" >
            <option value="volvo">Volvo</option>
          </select> 
        </div>

        <div class="mb-3">
          <label for="iller">İl seçiniz: </label>
          <select name="iller" id="iller" form="carform">
            <option value="volvo">Volvo</option>
          </select> 
        </div>        

        <div class="mb-3">
          <input type="password" name="password" class="form-control custom-input" placeholder="Tam Adres">
          <div class="text-danger"><?php echo $adresErr;?></div>  
        </div>

        <div class="mb-3">
          <input type="password" name="repassword" class="form-control custom-input" placeholder="Adres Başlığı">
          <div class="text-danger"><?php echo $adresBaslikErr;?></div>  
        </div>

        
    </div>
</div>

</body>
</html>