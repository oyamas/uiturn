<?php
/****************************************/
/* 新規ユーザ登録実行(メール到達確認)ページ           */
/****************************************/
  session_start();
  require_once("ini.php");
  require_once("setting.php");
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");
?>
<?php
  //受信したGET値からパスワードを照合
  $sql="SELECT passwd FROM user_tbl WHERE user_id=".$_GET['user_id'];
  $rst=mysqli_query($con,$sql);
  $col=mysqli_fetch_array($rst);
  //var_dump($col);
  if($col[passwd]!=$_GET['key']){
    print htmlheader("アクセスエラー");
    print "<br><br><hr><DIV class='largefont' align='center'>
     エラー<br>入力値が不正です。</DIV><hr><br>
    <FORM name='error' action='index.php' method='GET'>
    <div align='center'>下のボタンを押してトップ画面に戻ってください。<br><br>
      <INPUT type='submit' value='トップ画面へ'>
      </FORM></div>";
    exit();
  } else {
    print htmlheader("ユーザ新規登録完了");
    //auth=1とする（有効化）&パスワード変更
    $pw=mt_rand(10000000,99999999);
    $pw_h=password_hash( $pw, PASSWORD_DEFAULT);
    $sql="UPDATE user_tbl SET auth=1, passwd=\"$pw_h\", regdate=now() WHERE user_id=".$_GET['user_id'];
    //print $sql;
    $rst=mysqli_query($con,$sql);
    //ユーザへのメッセージ
    echo "ユーザ新規登録が完了しました。確認メールをお送りしました。<BR><BR>下記のIDと仮パスワードでログインして下さい。<BR><BR>"
       ."ユーザID：".$_GET['user_id']."<br><br>仮パスワード：$pw <br><br>"
       ."仮パスワードはすぐ変更するようお願いします。<br>"
       ."不明な点があれば、管理者までご連絡ください。<br><br><br>"
       ."<a href='user_login.php'>ログイン画面へ</a><br><br><br>";
    //ユーザへの確認メール
    //差出人
    $header = "From: "."info@hokkaido-nct.sakura.ne.jp";
    //件名
    $subject = "登録完了しました【道内4高専卒者向け求人検索サイト】";
    //本文
    $name=dfirst($con,"name","user_tbl","user_id=".$_GET['user_id']);
    $email=dfirst($con,"email","user_tbl","user_id=".$_GET['user_id']);
    $mailbody = "$name 様\r\n"
      ."　ユーザ新規登録が完了しました。\r\n下記のIDと仮パスワードでログインして下さい。\r\n\r\n"
       ."ユーザID：".$_GET['user_id']."\r\n仮パスワード：$pw \r\n\r\n"
      ."　仮パスワードはすぐ変更するようお願いします。\r\n"
      ."　また、この度のお取扱いに関し、ご不明な点がございましたら、info@hokkaido-nct.sakura.ne.jp までご連絡をお願いいたします。\r\n\r\n"
      ."　道内高専卒者向け求人検索システム　管理者\r\nログイン画面 https://hokkaido-nct.sakura.ne.jp/uiturn/user_login.php \r\n\r\n";
    mb_send_mail($email,$subject,$mailbody,$header);
  }
  print htmlfooter();
?>
