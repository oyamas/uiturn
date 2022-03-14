<?php
/****************************************/
/* 共通インクルードファイル(ユーザ用)   */
/****************************************/
  $PAGESIZE = 50;

function login_check(){
 session_start();
 //ログインチェック
 if ($_SESSION['user_id']==0) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print htmlheader("ログインエラー");
    print "<br><br><hr><DIV class='largefont' align='center'>
     エラー<br>ログインしていません</DIV><hr><br>
    <FORM name='error' action='../user_login.php' method='GET'>
    <div align='center'>下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
      <INPUT type='hidden' name='id' value='1'>
      </FORM></div>";
    exit();
 }
}
function htmlheader($pagetitle) {
//各ページのヘッダ部のHTMLを組み立てる
  $strret = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
   <HTML>
   <HEAD>
   <META http-equiv='Content-Type' content='text/html; charset=utf-8'>
   <META http-equiv='Content-Style-Type' content='text/css'>
   <TITLE>求人検索 - $pagetitle</TITLE>
    <LINK rel='stylesheet' href='com.css' type='text/css'>
    </HEAD>
    <BODY>
    <TABLE border='0' cellpadding='0' cellspacing='0' width='100%'>
    <TR>
     <TD class='maintitle1'>【中途】求人情報検索</TD>
     <TD class='maintitle2'>道内高専卒者向け求人検索サイト</TD>
    </TR>
    <TR>
     <TD class='pagetitle'>$pagetitle</TD>";
  session_start();
  if(isset($_SESSION['user_id'])){
    $strret .= "<TD class='tohomelink'>ログイン中:" . $_SESSION['name']
               . "様 &nbsp; <A href='../logout.php'>ログアウト</a>";
  } else {
    $strret .= "<TD class='tohomelink'><A href='../user_login.php'>ログイン</a>";
  }
  $strret .= "&nbsp;<A href='../'>求人情報TOP</A></TD></TR></TABLE><DIV class='maincontents'><BR>";
  return $strret;
}
?>
