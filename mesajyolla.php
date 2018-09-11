<?php
$host = "localhost:3306";
$vt_k_adi = "root";
$vt_sifre = "";
$mysqli = new mysqli("127.0.0.1", "$vt_k_adi", "$vt_sifre", "chat", 3306);
$ip = $_SERVER['REMOTE_ADDR'];
$username = $mysqli->query("Select kullanici_adi From DURUMLAR Where ip = '$ip'");
$username = $username->fetch_assoc();
$username = $username['kullanici_adi'];
$sql = $mysqli->query("Select MAX(sira) From mesajlar Where (gonderici = '$username' or gonderici = '" . $_GET['alici'] . "') and (alici = '$username' or alici = '" . $_GET['alici'] . "')");
$sql = $sql->fetch_assoc();
$sira = intval($sql['MAX(sira)']);
if($sira == null)
{
  $sira = 0;
}
$sira = $sira+1;
$mysqli->query("insert into mesajlar (gonderici,alici,mesaj,sira) values ('$username','" . $_GET['alici'] . "','" . $_GET['mesaj'] . "','$sira')");
$mysqli->close();
?>
