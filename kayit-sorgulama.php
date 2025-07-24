<?php
include "connection.php";

$query= "SELECT * from uyeler";

$sonuc= mysqli_query($baglanti,$query);

while($row= mysqli_fetch_row($sonuc)){
 echo $row[0]." ".$row[1]." ".$row[2];
 echo "<br>";
}

while($row=mysqli_fetch_assoc($sonuc)){
    echo $row["ad"]." ".$row["soyad"]." ".$row["telefon_no"];
    echo "<br>";
}



mysqli_close($baglanti);
?>