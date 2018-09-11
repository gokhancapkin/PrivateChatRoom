<?php
$host = "localhost:3306";
$vt_k_adi = "root";
$vt_sifre = "";
$mysqli = new mysqli("127.0.0.1", "$vt_k_adi", "$vt_sifre", "chat", 3306);
$ip = $_SERVER['REMOTE_ADDR'];
$username = $mysqli->query("Select kullanici_adi From DURUMLAR Where ip = '$ip'");
$username = $username->fetch_assoc();
$username = $username['kullanici_adi'];

if($_GET['islem'] == 'isteksorgu')
{
  $sorgu = $mysqli->query("Select kullanici2 From izinler Where izin = 'istek' and kullanici1 = '$username'");
  $sorgu = $sorgu->fetch_assoc();
  $sorgu = $sorgu['kullanici2'];
  echo $sorgu;
}

else if($_GET['islem'] == 'izinver' && $_GET['cevap'] == 'true' && $_GET['alici'] !=null)
{
  $izin1 = $mysqli->query("Select izin From izinler  Where kullanici1='$username' and kullanici2='" .$_GET['alici']. "'");
  $izin2 = $mysqli->query("Select izin From izinler  Where kullanici1='" .$_GET['alici']. "' and kullanici2='$username'");
  $izin1cevap = $izin1->fetch_assoc();
  $izin1cevap = $izin1cevap['izin'];
  $izin2cevap = $izin2->fetch_assoc();
  $izin2cevap = $izin2cevap['izin'];
  if($izin1cevap == 'istek' )
  {
    $mysqli->query("Update izinler Set izin = 'evet' Where kullanici1='$username' and kullanici2='".$_GET['alici']."'");
  }
  else
  {
    $mysqli->query("insert into izinler (kullanici1,kullanici2,izin) values('$username','" . $_GET['alici'] ."','evet')");
    if(mysqli_num_rows($izin2) == 0)
    {
      $mysqli->query("insert into izinler (kullanici1,kullanici2,izin) values('" .$_GET['alici']. "','$username','istek')");
    }
  }

}
else if($_GET['islem'] == 'izinver' && $_GET['cevap'] == 'false' && $_GET['alici'] !=null)
{
  $izin1 = $mysqli->query("Select izin From izinler  Where kullanici1='$username' and kullanici2='" .$_GET['alici']. "'");
  if(mysqli_num_rows($izin1)>0 )
  {
    $mysqli->query("Update izinler Set izin = 'hayir' Where kullanici1='$username' and kullanici2='" .$_GET['alici']. "'");
  }
  else {
    $mysqli->query("insert into izinler (kullanici1,kullanici2,izin) values('$username','" . $_GET['alici'] ."','hayir')");
  }
}

else if($_GET['islem'] == 'sorgu' && $_GET['alici'] !=null)
{
  $izin1 = $mysqli->query("Select izin From izinler Where kullanici1='$username' and kullanici2='" .$_GET['alici']. "'");
  $izin2 = $mysqli->query("Select izin From izinler Where kullanici1='" .$_GET['alici']. "' and kullanici2='$username'");
  $izin1 = $izin1->fetch_assoc();
  $izin1 = $izin1['izin'];
  $izin2 = $izin2->fetch_assoc();
  $izin2 = $izin2['izin'];
  if($izin1 == 'evet' && $izin2 == 'evet')
  {
    echo 'izinli';
  }
  else if($izin1 == 'istek' && $izin2 == 'evet')
  {
    echo 'sen';
  }
  else if($izin1 == 'evet' && $izin2 == 'istek')
  {
    echo 'karsi';
  }
  else if($izin1 == 'hayir' || $izin2 == 'hayir')
  {
    echo 'yasakli';
  }
  else if($izin1 == '' || $izin2 == '')
  {
    echo 'ilk';
  }
}

?>
