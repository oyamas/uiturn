<?php
/****************************************/
/* 新規企業登録実行(メール送信)ページ           */
/****************************************/
  session_start();
  require_once("ini.php");
  require_once("setting.php");
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  print htmlheader("送信完了");
?>
<?php
 if($_POST['party']<100){
    //DBに接続
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");
    //協力会名の取得
    $rst=mysqli_query($con,"SELECT * FROM college WHERE college_id =".$_POST['party']);
    $col=mysqli_fetch_array($rst);
    $college_mail = $col[email];
    $org=$col[org];
    //企業IDの最大値を取得
    $min_id = $_POST['party']*10000;  $max_id= $_POST['party']*10000+9999;
    $sql = "SELECT max(com_id) FROM com_tbl WHERE com_id >= $min_id and com_id <=$max_id";
    $rst = mysqli_query($con,$sql);
    $col = mysqli_fetch_array($rst);
    $com_id = $col['max(com_id)']+1;
    //乱数をパスワードに
    $passwd = mt_rand(100000,999999);
    //DBに仮登録
    $sql = "INSERT INTO com_tbl (com_id, com_name, address, aff, contact, tel, email, college_id, passwd, regdate, upddate)
            VALUES ($com_id,\"". $_POST['com_name']."\",\"". $_POST['address']."\","
                ."\"".$_POST['aff']."\",\"".$_POST['name']."\",\"". $_POST['tel']."\",\"".$_POST['email']."\","
                .$_POST['party'].", $passwd, now(),now())";
    $rst = mysqli_query($con,$sql);

    //管理者あてに送るメール本文（ID,パスワード付）
    $body="貴社名：". $_POST['com_name']
         ."\r\n所在地：". $_POST['address']
         ."\r\nご担当者のご所属：".$_POST['aff']
         ."\r\nご担当者のご氏名：".$_POST['name']
         ."\r\n電 話 番 号：". $_POST['tel']
         ."\r\n電子メールアドレス：".$_POST['email']
         ."\r\n登録会：$org";
  } else {
    //管理者あてに送るメール本文（未加盟）
    $body="貴社名：". $_POST['com_name']
         ."\r\n所在地：". $_POST['address']
         ."\r\nご担当者のご所属：".$_POST['aff']
         ."\r\nご担当者のご氏名：".$_POST['name']
         ."\r\n電 話 番 号：". $_POST['tel']
         ."\r\n電子メールアドレス：".$_POST['email']
         ."\r\n登録会：未加入";
     $college_mail="oyama@hakodate-ct.ac.jp";
  }
  //管理者あて（info、各校）にメール送信
  //差出人
  $header = "From:$TO";
  //あて先
  $to = "$TO,$college_mail";
  //件名
  $subject = "企業新規登録申請".date("Y-m-d H:i");
  //本文
  if(mb_send_mail($to,$subject,$body,$header)){
    echo "下記の登録内容を管理者にメール送信しました。確認メールをお送りしましたので、到着しているかご確認ください。<br>"
     ."追って管理者からご連絡いたします。<BR><BR>".nl2br($body);
    //ユーザへの確認メール
    //差出人
    $header = "From:$TO";
    //件名
    $subject = "企業新規登録申請を受け付けました";
    //本文
    $mailbody = $_POST['com_name']."　".$_POST['aff']."　".$_POST['name']."様\r\n"
      ."　道内高専卒者向け求人検索システムへの登録申請いただきありがとうございます。\r\n"
      ."　下記内容を、管理者に連絡しましたのでお知らせいたします。後ほど、管理者から連絡がありますので、しばらくお待ちください。\r\n"
      ."　この度のお取扱いに関し、ご不明な点がございましたら、$TO までご連絡をお願いいたします。\r\n\r\n"
      ."　道内高専卒者向け求人検索システム　管理者\r\n\r\n\r\n"
      ."--------ご登録内容--------\r\n".$body;
    mb_send_mail($_POST['email'],$subject,$mailbody,$header);

    
  }else{
    echo "メール送信失敗しました。<br><br>管理者に連絡しましたので、しばらくお待ち下さい。";
    //管理人への変更通知メール
    $header = "From:$TO";
    $subject = "メール送信失敗（企業新規登録）".date("Y-m-d H:i");
    //本文
    $body = "下記の登録メール送信に失敗しました。\r\n　".$body;
    mb_send_mail($TO,$subject,$body,$header);
 
  }
  print htmlfooter();
?>
</body></html>
