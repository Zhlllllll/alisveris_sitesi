<?php
if (!defined("host")) define("host", "localhost");
if (!defined("username")) define("username", "root");
if (!defined("password")) define("password", "");
if (!defined("database")) define("database", "projedb");

$baglanti = mysqli_connect('localhost', 'root', '', 'projedb', 3307);


if(mysqli_connect_errno()>0){
    die("hata: ".mysqli_connect_errno());
}
?>