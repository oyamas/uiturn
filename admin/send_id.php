<?php
/****************************************/
/* IDメール通知ページ　　　　　　　　　 */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  //$con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //mysql_query("set names utf8");
  //$selectdb = mysql_select_db($DBNAME, $con);
  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  print htmlheader("企業へのID通知");
  //ログインチェック
  if ($_SESSION['adm_id']==0) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print "<br><br><hr><DIV class='largefont' align='center'>
     エラー<br>管理者の権限確認がまだされていません</DIV><hr><br>
    <FORM name='error' action='input_login.php' method='GET'>
    <div align='center'>下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
      <INPUT type='hidden' name='id' value='1'>
      </FORM></div>";
    exit();
  }

 foreach($_POST['cert'] as $com_id)
 {
 	$sql = "SELECT * FROM com_tbl WHERE com_id=$com_id";
 	$rst = mysqli_query($con,$sql);
 	$col = mysqli_fetch_array($rst);
 	//パスワードハッシュ化してDBに格納
 	$pw_h=password_hash( $col['passwd'], PASSWORD_DEFAULT);
    $sql="UPDATE com_tbl SET passwd=\"$pw_h\", regdate=now() WHERE com_id=".$col['com_id'];
  	$rst = mysqli_query($con,$sql);  
 	
 	//メール本文（ID,パスワード付）
    $body=" $col[com_name]  $col[aff]様\r\n"
         ."　道内高専卒者向け求人検索システム（IUターンシステム）管理者です。\r\n"
         ."　この度は、システムへのご登録をいただき、ありがとうございます。\r\n"
         ."　貴社用に下記ID とパスワードを準備いたしましたので、ご使用くださいますようお願いいたします。\r\n"
         ."　　　ログインID　:　$col[com_id] \r\n"
         ."　　　パスワード　:　$col[passwd] \r\n\r\n"
         ."＜ログインサイトURL＞\r\n"
         ."  https://hokkaido-nct.sakura.ne.jp/uiturn/com/input_login.php\r\n"
         ."上記ログインID とパスワードを入力し、ログインしてください。\r\n\r\n"
         ."※釧路高専地域振興協力会加盟企業の場合は、まず事務局で基本情報を入力するとのことですので、事務局からの通知をお待ちください。\r\n"
         ."※システムに関するお問い合わせは、下記e-mailまたはご所属の会の事務局までお願いいたします。\r\n\r\n\r\n"
         ."＊＊＊＊＊＊＊＊＊＊\r\n"
         ."道内高専卒者向け求人検索システム\r\n"
         ."e-mail info@hokkaido-nct.sakura.ne.jp\r\n";
	$header = "From: $TO";
	$subject = "道内高専卒者向け求人検索システム：ID通知";
	$to = $col[email];
	if(mb_send_mail($to,$subject,$body,$header)){
    	print "$col[com_name] にIDを通知しました。<br>";
    	$rst_act = mysqli_query($con,"UPDATE com_tbl SET act_flg=1 WHERE com_id=$com_id");
    	//管理者への確認メール
    	$header = "From:$TO";
	    $subject = "企業新規ID発行：$col[com_name]";
	    $mailbody = $_SESSION['org']."様\r\n"
	      ."　下記内容を、企業に連絡しましたのでお知らせいたします。\r\n\r\n-----------------\r\n".$body;
	    $to = $TO.",".$_SESSION['email'];
    	mb_send_mail($to,$subject,$mailbody,$header);
    } else {
        print "$col[com_name] へメール送信できませんでした。<br>管理者へ問い合わせてください。<br>";
    }
 }
 //MySQLとの接続を解除します
 $con = mysqli_close($con);
 ?>
 <p><a href='index.php'>管理者トップページへ戻る</a></p>
 <?php
 //ページフッタを出力します
 print htmlfooter();
?>
