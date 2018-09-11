<?php
$host = "localhost:3306";
$vt_k_adi = "root";
$vt_sifre = "";
$mysqli = new mysqli("127.0.0.1", "$vt_k_adi", "$vt_sifre", "chat", 3306);
$ip = $_SERVER['REMOTE_ADDR'];
$sql = $mysqli->query("Select * From durumlar Where kullanici_adi = '" . $_GET['username'] . "' ");
if($sql->num_rows ==0)
{
  $mysqli->query("insert into DURUMLAR (kullanici_adi,ip,durum) values ('" . $_GET['username'] . "','$ip','Online')");
}
else
{
  $sql = $sql->fetch_assoc();
  $sqlip = $sql['ip'];
  if($sqlip == $ip){
      $mysqli->query("Update durumlar Set durum = 'Online' Where kullanici_adi = '" . $_GET['username'] . "'");
  }
  else{
    echo "Bu isim kullanılmaktadır";
  }
}
?>
