<?php
/****************************************/
/* 共通インクルードファイル(システム管理者用)   */
/****************************************/

function htmlheader($pagetitle) {
//各ページのヘッダ部のHTMLを組み立てる
  $strret = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
   <HTML>
   <HEAD>
   <META http-equiv='Content-Type' content='text/html; charset=utf-8'>
   <META http-equiv='Content-Style-Type' content='text/css'>
   <TITLE>システム管理 - $pagetitle</TITLE>
    <LINK rel='stylesheet' href='com.css' type='text/css'>
    </HEAD>
    <BODY>
    <TABLE border='0' cellpadding='0' cellspacing='0' width='100%'>
    <TR>
     <TD class='maintitle1'>システム管理ページ</TD>
     <TD class='maintitle2'>道内高専卒者向け求人検索システム</TD>
    </TR>
    <TR>
     <TD class='pagetitle'>$pagetitle</TD>";
  session_start();
  if(isset($_SESSION['mng_id'])){
    $strret.="<TD class='tohomelink'>ログイン中"
         ." &nbsp; <A href='logout.php'>ログアウト</a>&nbsp;"
         ."<A href='index.php'>システム管理者TOP</A>";
  } else {
    $strret .= "<TD class='tohomelink'><A href='input_login.php'>ログイン</a>";
  }
  $strret .= "</TD></TR></TABLE><DIV class='maincontents'><BR>";
  return $strret;
}
function login_check(){
  //ログインチェック
  if ($_SESSION['mng_id']==0) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print "<br><br><hr><DIV class='largefont' align='center'>
     エラー<br>システム管理者の権限確認がまだされていません</DIV><hr><br>
    <FORM name='error' action='input_login.php' method='GET'>
    <div align='center'>下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
      <INPUT type='hidden' name='id' value='1'>
      </FORM></div>";
    exit();
  }
}
?>
