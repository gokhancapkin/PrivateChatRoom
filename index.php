<!DOCTYPE html>
<html>
  <head>
    <style>
    .mesbox{resize:none;}
    </style>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <meta charset="utf-8">
    <title> RAINBOW Chat </title>
    <link rel="stylesheet" href="main.css"/>
  </head>
  <body>
    <?php
    $host = "localhost:3306";
    $vt_k_adi = "root";
    $vt_sifre = "";
    $mysqli = new mysqli("127.0.0.1", "$vt_k_adi", "$vt_sifre", "chat", 3306);
    if(mysqli_num_rows($mysqli->query("SELECT kullanici_adi FROM durumlar WHERE ip = '" . $_SERVER['REMOTE_ADDR'] . "' and durum = 'Online'")) == 0){ ?>
      <div id="login">
        Kullanıcı Adınızı Giriniz : <br />
        <input type="text" id="usernameinput"/>
        <input type="button" value="Login" id="loginbutton"/>
      </div>
    <?php }
    else {
      ?>
      <div id="chat">
        <div id = "profil"></div>
      <input type = "button" value="ÇIKIŞ" id = "exit"/>
      <div id = "ortaalan">
      <div id="onlineusers"></div>
      <div id="send">
        <div id="mesajlar"></div>
          <textarea class = "mesbox" id="mesaj" cols="50" rows="5"></textarea><br/><input type="button" value="Send" id="sendbutton" disabled="disabled"/>
          <input type="text" id="aliciinput"/>
        </div>
      </div>
      <div id = "sari"></div>
    <?php } ?>
    <script>
    $('#loginbutton').click(function (){
      if($(this).attr("disabled") != "disabled"){
        var error = $.ajax({
          url: "giris.php",
          data: "username=" + $('#usernameinput').val(),
          async:false
        }).responseText;
        if(error != ''){
          alert(error);
        }
        else {
          location.reload();
        }

      }
    });

    var from = '';
    var onceki = '';
    showusers();
    function showusers(){
      $('#onlineusers').html($.ajax({
        url: "online.php",
        async:false
      }).responseText);
        $('#mesajlar').html($.ajax({
          url: "mesajlar.php",
          data: 'alici=' + from,
          async:false
        }).responseText);
      istekCheck();
      setTimeout('showusers()',5000);
    }
    $(document).on('click','.onlineuser',function (){
      onceki = from;
      from = $(this).html();
      if(izinCheck()){
        $('#mesajlar').html($.ajax({
          url: "mesajlar.php",
          data: 'alici=' + $(this).html(),
          async:false
        }).responseText);
      $('#aliciinput').val(from);
      $('#sendbutton').removeAttr("disabled");
      }
      else {
        from = onceki;
      }
      showusers();
    });
    $(document).on('click','.offlineuser',function (){
      var error = "Kişi Çevrimdışı olduğundan mesaj yazamazsınız";
      alert(error);
      showusers();
    });
    function istekCheck(){
      var text;
      var error = '';
      var error = $.ajax({
        url: "izin.php",
        data : 'islem='+"isteksorgu"+'&alici='+from,
        async:false
        }).responseText;
        if(error != '')
        {
          text = error +" adlı kullanıcıya sohbet izni verilsin mi?";
          if (confirm(text))
          {
            onceki = from;
            from = error;
            var sonuc = $.ajax({
            url: "izin.php",
            data : 'alici='+from+'&islem='+'izinver'+'&cevap='+'true',
            async:false
            }).responseText;
            from = onceki;
          }
          else
          {
            onceki = from;
            from = error;
            var sonuc = $.ajax({
            url: "izin.php",
            data : 'alici='+from+'&islem='+'izinver'+'&cevap='+'false',
            async:false
            }).responseText;
            from = onceki;
          }
        }
    }
    function izinCheck(){
      var text;
      if(from == '')
      {
        return false;
      }
        var error = $.ajax({
        url: "izin.php",
        data : 'alici='+from+'&islem='+'sorgu',
        async:false
        }).responseText;
      if(error == 'sen' || error == 'ilk')
      {
        text = from+" adlı kullanıcıya izin vermelisiniz";
        if (confirm(text))
        {
            var error = $.ajax({
            url: "izin.php",
            data : 'alici='+from+'&islem='+'izinver'+'&cevap='+'true',
            async:false
            }).responseText;
        }
        else
        {
            var error = $.ajax({
            url: "izin.php",
            data : 'alici='+from+'&islem='+'izinver'+'&cevap='+'false',
            async:false
            }).responseText;
            return false;
        }
        return false;
      }
      else if( error == 'karsi')
      {
        text = from+" adlı kullanıcının izin vermesini beklemelisiniz";
        alert(text);
        return false;
      }
      else if( error == 'yasakli')
      {
        text = "Bu sohbete kişilerden biri istemediğinden izin verilmedi";
        alert(text);
        return false;
      }
      else if( error == 'izinli')
      {
        return true;
      }
      else{
          return false;
      }
    }
    $('#sendbutton').click(function (){
      if($(this).attr("disabled") != "disabled"){
        var mesaj = $('#mesaj').val();
        var error = $.ajax({
          url: "mesajyolla.php",
          data : 'alici='+from+'&mesaj=' + mesaj,
          async:false
        }).responseText;
        $('#mesaj').val('');
        showusers();
      }
    });
    $('#mesaj').on("keydown", function(e){
      if(e.which == 13){
          $('#sendbutton').focus().click();
        return false;
      }
    });
    $('#usernameinput').on("keydown", function(e){
      if(e.which == 13){
          $('#loginbutton').focus().click();
        return false;
      }
    });
    $('#exit').click(function (){
        var error = $.ajax({
          url: "cikis.php",
          data: "username=" + $('#usernameinput').val(),
          async:false
        }).responseText;
        if(error != ''){
          alert(error);
        }
        else {
          window.location.reload();
        }
    });
    </script>
</body>
</html>
