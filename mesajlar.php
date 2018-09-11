<?php
$host = "localhost:3306";
$vt_k_adi = "root";
$vt_sifre = "";
$mysqli = new mysqli("127.0.0.1", "$vt_k_adi", "$vt_sifre", "chat", 3306);
$ip = $_SERVER['REMOTE_ADDR'];
$username = $mysqli->query("Select kullanici_adi From DURUMLAR Where ip = '$ip'");
$username = $username->fetch_assoc();
$username = $username['kullanici_adi'];
$mesajlar = $mysqli->query("Select * From mesajlar Where (gonderici = '$username' or gonderici = '" . $_GET['alici'] . "') and (alici = '$username' or alici = '" . $_GET['alici'] . "')");
$sayacMesaj = 0;
if($mesajlar->num_rows == 0)
{
  echo "Mesaj yok";
}
else
{
  while($mesaj = $mesajlar->fetch_array(MYSQLI_ASSOC))
  {
    if($mesaj['gonderici'] == $username)
    {
      $kisi = 'Sen';
    }
    else
    {
      $kisi = $mesaj['gonderici'];
    }
    echo '<div class="mesaj">' . $kisi . ':</b> ' . $mesaj['mesaj'] .'</div>';
    $sayacMesaj = $sayacMesaj+1;
  }
}
?>
