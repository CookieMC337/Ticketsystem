<?php
if(file_exists("../mysql.php")){
  header("Location: ../index.php");
  exit;
}
require("../datamanager.php");
session_start();
if(isset($_SESSION["host"]) && isset($_SESSION["database"]) &&
isset($_SESSION["user"]) && isset($_SESSION["password"])){
  if(isset($_POST["submit"])){
    $mysqlfile = fopen("../mysql.php", "w");
    if(!$mysqlfile){
      ?>
      <head>
        <meta charset="utf-8">
        <title>Setup</title>
        <link rel="stylesheet" href="../assets/css/main.css">
        <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
      </head>
      <body>
        <div class="flex">
          <div class="flex-item login">
            <h1>Fehler</h1>
            <p>Installtion Fehlgeschlagen. Die MYSQL.php konnte nicht beschrieben werden.<br>
              Bitte Gebe den Ordner "Ticketsysem" 777 Rechte.</p>
            <br>
            <a href="step3.php" class="btn">Erneut Versuchen</a>
          </div>
        </div>
      </body>
      <?php
      exit;
    }
    echo fwrite($mysqlfile, '
    <?php
    $host = "'.$_SESSION["host"].'";
    $name = "'.$_SESSION["database"].'";
    $user = "'.$_SESSION["user"].'";
    $passwort = "'.$_SESSION["password"].'";
    try{
        $mysql = new PDO("mysql:host=$host;dbname=$name", $user, $passwort);
    } catch (PDOException $e){

    }
    ?>
    ');
    fclose($mysqlfile);
    session_destroy();
    setSetting("lang", $_POST["lang"]);
    setSetting("captcha", $_POST["captcha"]);
    setSetting("captcha_public", $_POST["captcha-public"]);
    setSetting("captcha_private", $_POST["captcha-secret"]);
    ?>
    <meta http-equiv="refresh" content="0; URL=../index.php">
    <?php
    exit;
  }
} else {
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Ticket-Setup</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="flex">
      <div class="flex-item login">
        <h1>Setup</h1>
        <p>Hier kannst du die grund Einstellungen ändern</p>
        <form action="step3.php" method="post">
          <select name="lang">
            <option value="en">English</option>
            <option value="de">Deutsch</option>
		    <option value="#">#</option>
			<option value="#">#</option>
          </select>
          <h3>ReCaptcha</h3>
          <select name="captcha">
            <option value="0">Disabled</option>
            <option value="1">Enabled</option>
          </select>
          <p>Create this keys for free on: <a href="https://www.google.com/recaptcha/admin">Google ReCaptcha</a>Bitte nur einstellen wenn ReCaptcha aktiviert ist</p>
          <input type="text" name="captcha-public" placeholder="Dein Websiten Key"><br>
          <input type="text" name="captcha-secret" placeholder="Dein Secret Key"><br>
		  		   <p>Wichtig! Bitte löschen sie Nach der Installation den Setup Ordner.</p>
          <button type="submit" name="submit">Fertig</button>

        </form>
      </div>
    </div>
  </body>
</html>
