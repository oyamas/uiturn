<?php
/****************************************/
/* 各校ユーザ管理者用ログインページ           */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  if(isset($_POST['password'])) {
    //パスワードが入力されたとき
    if(mb_ereg("^[0-9]*$",$_POST['login_id'])&&strlen($_POST['password']) > 0){
	  $con = mysql_connect($DBSERVER,$DBUSER,$DBPASSWORD);
      mysql_query("set names utf8");
      $selectdb = mysql_select_db($DBNAME, $con);
	  //admテーブルでパスワードを照合
      $login_id = $_POST['login_id'];    //ログインユーザー名
      $passwd = $_POST['password']; //パスワード
      $sql="SELECT * FROM college WHERE login_id=$login_id";
      $rst=mysql_query($sql,$con);
      $col=mysql_fetch_array($rst);
	  if($col[password] != $passwd){
		//パスワードが違う場合はエラーメッセージを出す;
		$errmsg = "<b>ユーザ名またはパスワードが違います。</b><BR><BR>";
	  } else {
        //認証OKのときセッション変数にユーザIDを格納
 		$_SESSION['login_id'] = $login_id;
 		$_SESSION['college_id']=$col[college_id];
 		$_SESSION['almuni_org'] = $col[alumni_org];
 		$_SESSION['almuni_email'] = $col[alumni_email];
      }
    } else {
        $errmsg = "<b>ユーザ名またはパスワードが違います。</b><BR><BR>";
    }
  } else if($_GET['set']==1){
     $errmsg = "<b>パスワードが未入力です</b><BR><BR>";
  }
  //セッション変数によってログインをチェックします
  if (strlen($_SESSION['login_id'])>0) {
    //所定のセッション変数が定義済み（＝ログイン済み）のときはindexへジャンプ
    header("location: index.php");
    exit();
  } else {
   //ログイン画面出力
   print htmlheader("各校ユーザ管理者用ログイン");
   if(strlen($errmsg)>0) print $errmsg;
   print "ユーザ名とパスワードを入力して[ログイン]ボタンをクリックしてください。<BR><BR>
         <FORM action='$PHP_SELF?set=1' method='POST'>
		 ユーザ名　<INPUT size='20' type='text' name='login_id'><br>
         パスワード　<INPUT size='20' type='password' name='password'>
         <INPUT type='submit' value='ログイン'>
         </FORM><br><br>";
   print "<div style='background:#eeeeee;'>【お知らせ】 </div><br>";
   print "2015.01.07 運用開始しておりますが、初期の不具合があるかもしれませんので、何かございましたら下記問い合わせ先までご連絡をお願いいたします。<br>";
  }
  //ページフッタを出力します
  print htmlfooter();
?>
