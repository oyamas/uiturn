<?php
/****************************************/
/* システム管理者用ログインページ           */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  if(isset($_POST['password'])) {
    //パスワードが入力されたとき
    if(mb_ereg("^[0-9]*$",$_POST['adm_id'])&&strlen($_POST['password']) > 0){
	  $con = mysql_connect($DBSERVER,$DBUSER,$DBPASSWORD);
      mysql_query("set names utf8");
      $selectdb = mysql_select_db($DBNAME, $con);
	  //admテーブルでパスワードを照合
      $mng_id = $_POST['mng_id'];    //ログインユーザー名
      $passwd = $_POST['password']; //パスワード
      $sql="SELECT * FROM mng_tbl WHERE mng_id=$mng_id";
      $rst=mysql_query($sql,$con);
      $col=mysql_fetch_array($rst);
	  if($col[passwd] != $passwd){
		//パスワードが違う場合はエラーメッセージを出す;
		$errmsg = "<b>ユーザ名またはパスワードが違います。</b><BR><BR>";
	  } else {
        //認証OKのときセッション変数にmng_idを格納
 		$_SESSION['mng_id'] = $mng_id;
      }
    } else {
        $errmsg = "<b>ユーザ名またはパスワードが違います。</b><BR><BR>";
    }
  } else if($_GET['set']==1){
     $errmsg = "<b>パスワードが未入力です</b><BR><BR>";
  }
  //セッション変数によってログインをチェックします
  if (strlen($_SESSION['mng_id'])>0) {
    //所定のセッション変数が定義済み（＝ログイン済み）のときはindexへジャンプ
    header("location: index.php");
    exit();
  } else {
   //ログイン画面出力
   print htmlheader("システム管理者用ログイン");
   if(strlen($errmsg)>0) print $errmsg;
   print "ユーザ名とパスワードを入力して[ログイン]ボタンをクリックしてください。<BR><BR>
         <FORM action='$PHP_SELF?set=1' method='POST'>
		 ユーザ名　<INPUT size='20' type='text' name='mng_id'><br>
         パスワード　<INPUT size='20' type='password' name='password'>
         <INPUT type='submit' value='ログイン'>
         </FORM><br><br>";
  }
  //ページフッタを出力します
  print htmlfooter();
?>
