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
    //各学校管理者メールアドレスの取得
    $sql="SELECT * FROM college WHERE college_id =".$_POST['college_id'];
    $rst=mysqli_query($con,$sql);
    $col=mysqli_fetch_array($rst);
    $org=$col[org];
    print htmlheader("確認メール送信");
    $body="氏　　　　名：　". $_POST['name']
     ."\r\n氏名ふりがな：　". $_POST['kana']
 //    ."\r\n郵 便 番 号：　". $_POST['postal']
 //    ."\r\n住　　　　所：　". $_POST['address']
 //    ."\r\n電 話 番 号：　". $_POST['tel']
     ."\r\n電子メールアドレス：　".$_POST['email']
     ."\r\n出身校：　".dfirst($con,"name","college","college_id=".$_POST['college_id'])
     ." ".dfirst($con,"name","college_dept","college_id=".$_POST['college_id']." and dept_id=".$_POST['dept'])."　";
    //if($_POST['gengo']=='s') $body.= "昭和"; else $body.= "平成";
    $body .= $_POST['year']."年度卒業"
     ."\r\n備考：　".$_POST['bikou'];

    //管理者あてにメール送信
    //差出人
    $header = "From: "."hokkaido-nct@hokkaido-nct.sakura.ne.jp";
    //件名
    $subject = "【UIturn:新規ユーザ申請】".date("Y-m-d H:i");
    //あて先
    $to = "$TO,$col[alumni_email]";
    if(mb_send_mail($to,$subject,$body,$header)){
      //仮パスワード生成（乱数）
      $cfm_key=mt_rand(1000000000,9999999999);
      //情報をDBに登録(auth=0で仮の状態)
/*
      $sql="INSERT INTO user_tbl(name,kana,postal,address,tel,email,bikou,year,passwd,auth,college_id,dept_id,regdate)"
       ." VALUES (\"".$_POST['name']."\",\"".$_POST['kana']."\",\"".$_POST['postal']."\",\"".$_POST['address']."\","
       ."\"".$_POST['tel']."\",\"".$_POST['email']."\",\"".$_POST['bikou']."\",".$_POST['year'].",\"$cfm_key\",0,"
       .$_POST['college_id'].",".$_POST['dept'].",now())";
*/
      $sql="INSERT INTO user_tbl"
       ."(name,kana,email,bikou,year,passwd,auth,college_id,dept_id,regdate)"
       ." VALUES (\"".$_POST['name']."\",\"".$_POST['kana']."\",\"".$_POST['email']
       ."\",\"".$_POST['bikou']."\",".$_POST['year'].",\"$cfm_key\",0,"
       .$_POST['college_id'].",".$_POST['dept'].",now())";
      $rst=mysqli_query($con,$sql);
      //自動発行IDを取り出して一時リンクを作成
      $sql="SELECT user_id from user_tbl WHERE name LIKE \"".$_POST['name']."\" ORDER BY user_id DESC";
      $rst=mysqli_query($con,$sql);
      $col=mysqli_fetch_array($rst);
      $link = "https://hokkaido-nct.sakura.ne.jp/uiturn/user_reg_exe2.php?user_id=$col[user_id]&key=$cfm_key";
      //ユーザへのメッセージ
      echo "新規登録申請を受け付けました。確認メールをお送りしましたので、到着しているかご確認ください。<BR><BR>"
       ."もし、メールが届かなかった場合は、入力されたメールアドレスが間違っている可能性があります。<br>"
       ."不明な点があれば、管理者までご連絡ください。<br><br><br>".nl2br($body);
      //ユーザへの確認メール
      //差出人
      $header = "From: "."info@hokkaido-nct.sakura.ne.jp";
      //件名
      $subject = "メールアドレス確認";
      //本文
      $mailbody = $_POST['name']."様\r\n"
      ."　下記のリンクをクリックして、本登録を行ってください。\r\n"
      .$link ."\r\n\r\n\r\n　この度のお取扱いに関し、ご不明な点がございましたら、info@hokkaido-nct.sakura.ne.jp までご連絡をお願いいたします。\r\n\r\n"
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
