<?php
/****************************************/
/* 新規ユーザ登録実行(メール送信)ページ           */
/****************************************/
  session_start();
  require_once("ini.php");
  require_once("setting.php");
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  mysql_query("set names utf8");
  $selectdb = mysql_select_db($DBNAME, $con);
?>
<?php
if(strlen($_POST['name'])==0){
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
  print htmlheader("送信完了");
 $body="氏　　　　名：　". $_POST['name']
  ."\r\n氏名ふりがな：　". $_POST['kana']
  ."\r\n郵 便 番 号：　". $_POST['postal']
  ."\r\n住　　　　所：　". $_POST['address']
  ."\r\n電 話 番 号：　". $_POST['tel']
  ."\r\n電子メールアドレス：　".$_POST['email']
  ."\r\n出身校：　".dfirst($con,"name","college","college_id=".$_POST['college_id'])
  ." ".dfirst($con,"name","college_dept","college_id=".$_POST['college_id']." and dept_id=".$_POST['dept'])."　";
 if($_POST['gengo']=='s') $body.= "昭和"; else $body.= "平成";
 $body .= $_POST['year']."年卒業"
  ."\r\n備考：　".$_POST['bikou'];

//oyama@hakodate-ct.ac.jpあてにメール送信
//差出人
$header = "From: "."hokkaido-nct@hokkaido-nct.sakura.ne.jp";
//件名
$subject = "新規ユーザ登録申請".date("Y-m-d H:i");

if(mb_send_mail($TO,$subject,$body,$header)){
    echo "下記の登録内容を管理者にメール送信しました。確認メールをお送りしましたので、到着しているかご確認ください。<br>"
     ."追って管理者からご連絡いたします。<BR><BR>".nl2br($body);
    //ユーザへの確認メール
    //差出人
    $header = "From: "."info@hokkaido-nct.sakura.ne.jp";
    //件名
    $subject = "ユーザ新規登録申請を受け付けました";
    //本文
    $mailbody = $_POST['name']."様\r\n"
      ."　道内高専卒者向け求人検索システムへの登録申請いただきありがとうございます。\r\n"
      ."　下記内容を、管理者に連絡しましたのでお知らせいたします。\r\n"
      ."　この度のお取扱いに関し、ご不明な点がございましたら、info@hokkaido-nct.sakura.ne.jp までご連絡をお願いいたします。\r\n\r\n"
      ."　道内高専卒者向け求人検索システム　管理者\r\n\r\n\r\n"
      ."--------ご登録内容--------\r\n".$body;
    mb_send_mail($_POST['email'],$subject,$mailbody,$header);
}else{
   echo "メール送信失敗しました。<br><br>管理者に連絡しましたので、しばらくお待ち下さい。";
       //管理人への変更通知メール
    $header = "From: "."hokkaido-nct@hokkaido-nct.sakura.ne.jp";
    $subject = "メール送信失敗（ユーザ新規登録）".date("Y-m-d H:i");
    //本文
    $body = "下記の登録メール送信に失敗しました。\r\n　".$body;
    mb_send_mail($TO,$subject,$body,$header);
 

}

  print htmlfooter();
?>
