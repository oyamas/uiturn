<?php
/****************************************/
/* ログアウト処理ページ               */
/****************************************/

  //セッションを開始します
  session_start();

  //セッション変数をクリア
  $_SESSION = array();
  session_destroy;
  //トップページに戻る
  header("location:input_login.php");
  exit();

?>
