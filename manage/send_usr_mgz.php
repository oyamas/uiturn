<?php
/****************************************/
/* ユーザ向けメールマガジン送信ページ           */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  $con = mysqli_connect($DBSERVER, $DBUSER, $DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");

  if(strlen($_POST['magazine'])==0){
    //直リン禁止
    print htmlheader("アクセスエラー");
    print "<br><br><hr><DIV class='largefont' align='center'>
     エラー<br>入力値が不正です。</DIV><hr><br>
    <FORM name='error' action='index.php' method='GET'>
    <div align='center'>下のボタンを押してトップ画面に戻ってください。<br><br>
      <INPUT type='submit' value='トップ画面へ'>
      </FORM></div>";
    exit();
  }
  print htmlheader("メールマガジン送信完了");
  $body=$_POST["magazine"];

  //差出人
  $header = "From: "."info@hokkaido-nct.sakura.ne.jp\n";
  //あて先(BCC)
  $bcc="Bcc:";
  //$header .= "Bcc:oyamashinya@gmail.com,oyama@hakodate-ct.ac.jp";
  $sql = "SELECT email FROM user_tbl WHERE auth = 1";
  $rst = mysqli_query($con,$sql);
  while($col=mysqli_fetch_array($rst)){
    if(strlen($col['email'])>0) $bcc.=$col['email'].",";
  }
  $bcc = substr($bcc,0,-1);
   print $bcc;
  
  //件名
  $subject = "道内高専卒者求人 メールマガジン".date("Y-n-j");
  //あて先 
  $to = "oyamas@rg8.so-net.ne.jp"; //test
  //ユーザへのメール送付
  if(mb_send_mail($to,$subject,$body,$header)){
    echo "下記の内容をユーザにメール送信しました。<BR><BR>".nl2br($body);
  }else{
    echo "メール送信失敗しました。<br><br>";
  }
  print htmlfooter();
?>
