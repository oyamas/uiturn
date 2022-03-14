<?php
/****************************************/
/* ユーザ情報更新実行ページ           */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  login_check();
  print htmlheader("ユーザ情報更新実行");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");
  //リロード時は処理を無効に
  if($_SESSION['regcom']==1){
    unset($_SESSION['regcom']);
  } else {
    print htmlheader("ユーザ情報更新実行済み");
    print "この情報はすでに更新処理が終わっています。<br><br>
       <INPUT type='button' value='入力者用ホームへ戻る'
       onclick=\"location.href='../index.php'\">";
    exit();
  }
  //user_tblのレコードを更新する
  $sql = "UPDATE user_tbl SET "
  		 ."name=\"". $_POST['name']. "\","
         ."kana=\"". $_POST['kana']. "\","
 /*
         ."postal =\"". $_POST['postal']. "\","
         ."address=\"". $_POST['address']. "\","
         ."tel=\"". $_POST['tel']. "\","
 */
         ."email=\"". $_POST['email']."\","
         ."regdate=now() WHERE user_id=".$_SESSION['user_id'];
  //SQL文を発行します
  $rst = mysqli_query($con,$sql);
  if ($rst) {
    print $_POST['name']."様の情報を更新しました。<br><br>確認メールをお送りしていますので、到着しているか確認してください。";
    //ユーザへの確認メール
    //差出人
    $header = "From: "."hokkaido-nct@hokkaido-nct.sakura.ne.jp";
    //件名
    $subject = "ユーザ情報を更新しました";
    //本文
    $body = $_POST['name']."様\r\n　道内高専卒者向け求人検索システムをご利用いただきありがとうございます。\r\n"
      ."　下記日時に、ご登録いただいている情報を更新しましたので、お知らせいたします。\r\n"
      ."　この度のお取扱いに関し、ご不明な点がございましたら、info@hokkaido-nct.sakura.ne.jp までご連絡をお願いいたします。\r\n\r\n"
      ."　道内高専卒者向け求人検索システム　管理者";
    mb_send_mail($TO,$subject,$body,$header);

    //管理人への変更通知メール
    $header = "From: "."hokkaido-nct@hokkaido-nct.sakura.ne.jp";
    $subject = "ユーザ情報更新".date("Y-m-d H:i");
    //本文
    $body = "下記のユーザの情報が更新されました。\r\n　".$_POST['name']."様 \r\n ユーザID：".$_SESSION['user_id'];
    mb_send_mail($TO,$subject,$body,$header);
  } else {
    print $_POST['name']."様の情報の更新に失敗しました。<br><br>管理者に連絡しましたので、しばらくお待ち下さい。";
    //管理人への変更通知メール
    $header = "From: "."hokkaido-nct@hokkaido-nct.sakura.ne.jp";
    $subject = "ユーザ情報更新失敗".date("Y-m-d H:i");
    //本文
    $body = "下記のユーザ情報の更新に失敗しました。\r\n　".$_POST['name']."様 \r\n ユーザID：".$_SESSION['user_id'];
    mb_send_mail($TO,$subject,$body,$header);
  }
  //MySQLとの接続を解除します
  $con = mysql_close($con);


  print htmlfooter();
?>
