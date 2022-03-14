<?php
/****************************************/
/* 共通インクルードファイル(ユーザ用)   */
/****************************************/

function htmlheader($pagetitle) {
//各ページのヘッダ部のHTMLを組み立てる
  $strret = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
   <HTML>
   <HEAD>
   <META http-equiv='Content-Type' content='text/html; charset=utf-8'>
   <META http-equiv='Content-Style-Type' content='text/css'>
   <TITLE>道内高専卒求人(IUターン)検索 - $pagetitle</TITLE>
    <LINK rel='stylesheet' href='top.css' type='text/css'>
    </HEAD>
    <BODY>
    <TABLE border='0' cellpadding='0' cellspacing='0' width='100%'>
    <TR>
     <TD class='maintitle1'>Uターン・Iターン求人情報検索ページ</TD>
     <TD class='maintitle2'>北海道4高専卒者向けIUターンシステム</TD>
    </TR>
    <TR>
     <TD class='pagetitle'>$pagetitle</TD>";
  session_start();
  if(isset($_SESSION['user_id'])){
    $strret.="<TD class='tohomelink'>ログイン中:".$_SESSION['name']
         ."様 &nbsp; <A href='logout.php'>ログアウト</a>&nbsp;";
  } else {
    $strret .= "<TD class='tohomelink'><A href='user_login.php'>ログイン</a>";
  }
  $strret .= "&nbsp;<A href='index.php'>トップページ</A></TD>
              </TR></TABLE><DIV class='maincontents'><BR>";
  return $strret;
}
?>
