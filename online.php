<?php
$host = "localhost:3306";
$vt_k_adi = "root";
$vt_sifre = "";
$mysqli = new mysqli("127.0.0.1", "$vt_k_adi", "$vt_sifre", "chat", 3306);
$ip = $_SERVER['REMOTE_ADDR'];
$username = $mysqli->query("Select kullanici_adi From DURUMLAR Where ip = '$ip'");
$username = $username->fetch_assoc();
$username = $username['kullanici_adi'];
$online = $mysqli->query("Select kullanici_adi From DURUMLAR Where durum = 'Online'");
$offline = $mysqli->query("Select kullanici_adi From DURUMLAR Where durum = 'Offline'");
$sayacOnline = 0;
$sayacOffline = 0;
echo "DURUMLAR<br>";
echo "ÇEVRİMİÇİ<br>";
if($online->num_rows == 0)
{
  echo "";
}
else
{
  while($userOnline = $online->fetch_array(MYSQLI_ASSOC)){
    if($userOnline['kullanici_adi'] == $username)
    {
      continue;
    }
    else
    {
      echo '<span class="onlineuser ' . $sayacOnline . '">' . $userOnline['kullanici_adi'] . '</span>';
      echo "<br>";
      $sayacOnline = $sayacOnline + 1;
    }
  };
}
echo "ÇEVRİMDIŞI<br>";
if($offline->num_rows == 0)
{
  echo "";
}
else
{
  while($userOffline = $offline->fetch_array(MYSQLI_ASSOC)){
    if($userOffline['kullanici_adi'] == $username)
    {
      continue;
    }
    else
    {
      echo '<span class="offlineuser ' . $sayacOffline . '">' . $userOffline['kullanici_adi'] . '</span>';
      echo "<br>";
      $sayacOffline = $sayacOffline + 1;
    }
  };
}
?>
