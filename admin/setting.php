<?php
/****************************************/
/* 共通インクルードファイル(管理者用)   */
/****************************************/

function htmlheader($pagetitle) {
//各ページのヘッダ部のHTMLを組み立てる
  $strret = "<!DOCTYPE HTML>
   <HTML>
   <HEAD>
   <META http-equiv='Content-Type' content='text/html; charset=utf-8'>
   <META http-equiv='Content-Style-Type' content='text/css'>
   <TITLE>各校管理 - $pagetitle</TITLE>
    <LINK rel='stylesheet' href='com.css' type='text/css'>
    </HEAD>
    <BODY>
    <TABLE border='0' cellpadding='0' cellspacing='0' width='100%'>
    <TR>
     <TD class='maintitle1'>各校管理ページ</TD>
     <TD class='maintitle2'>道内高専卒者向け求人検索システム</TD>
    </TR>
    <TR>
     <TD class='pagetitle'>$pagetitle</TD>";
  session_start();
  if(isset($_SESSION['adm_id'])){
    $strret.="<TD class='tohomelink'>ログイン中:".$_SESSION['org']
         ."様 &nbsp; <A href='logout.php'>ログアウト</a>&nbsp;"
         ."<A href='index.php'>管理者TOP</A>";
  } else {
    $strret .= "<TD class='tohomelink'><A href='input_login.php'>ログイン</a>";
  }
  $strret .= "</TD></TR></TABLE><DIV class='maincontents'><BR>";
  return $strret;
}
?>
