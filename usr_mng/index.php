<?php 
/****************************************/
/* ユーザ管理者用トップページ           */
/****************************************/
  session_start();
  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  //ログインチェック
  if ($_SESSION['login_id']==0) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print htmlheader("各校ユーザ管理者用トップページ（エラー）");
    print "<br><BR><hr><DIV class='largefont' align='center'>
     エラー<br>各校ユーザ管理者の権限確認がまだされていません</DIV><hr><br>
     <DIV align='center'>
     <FORM action='input_login.php' method='GET'>
      下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
    </FORM></div>";
    print htmlfooter();
  } else {
    print htmlheader("各校ユーザ管理者用トップページ");
    print" <DIV class='maincontents'> <H2>メニュー</H2>
     <A href='usr_manage.php'><img src='../fig/button1.gif' align='center'>
      登録ユーザの表示・管理</A><br>
     <A href='passwd.php'><img src='../fig/button3.gif' align='center'>
      パスワードの変更</A><br>  <hr>  </div>";
    print htmlfooter();
  }
?>
