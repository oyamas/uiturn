<?php 
/****************************************/
/* 管理者用トップページ           */
/****************************************/
  session_start();
  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  //ログインチェック
  if ($_SESSION['adm_id']==0) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print htmlheader("各校管理者用トップページ（エラー）");
    print "<br><BR><hr><DIV class='largefont' align='center'>
     エラー<br>各校管理者の権限確認がまだされていません</DIV><hr><br>
     <DIV align='center'>
     <FORM action='input_login.php' method='GET'>
      下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
    </FORM></div>";
    print htmlfooter();
  } else {
    print htmlheader("各校管理者用トップページ");
    print" <DIV class='maincontents'> <H2>メニュー</H2>
     <A href='com_manage.php'><img src='../fig/button1.gif' align='center'>
      登録企業の表示・更新</A><br>
     <A href='stats.php'><img src='../fig/button1.gif' align='center'>
      現在の登録企業数、登録ユーザ数などの統計情報</A><br>
     <A href='passwd.php'><img src='../fig/button3.gif' align='center'>
      パスワードの変更</A><br>  <hr>  </div>";
    print htmlfooter();


  }
?>
