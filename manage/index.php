<?php 
/****************************************/
/* 管理者用トップページ           */
/****************************************/
  session_start();
  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  //ログインチェック
  if ($_SESSION['mng_id']==0) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print htmlheader("システム管理者用トップページ（エラー）");
    print "<br><BR><hr><DIV class='largefont' align='center'>
     エラー<br>システム管理者の権限確認がまだされていません</DIV><hr><br>
     <DIV align='center'>
     <FORM action='input_login.php' method='GET'>
      下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
    </FORM></div>";
    print htmlfooter();
  } else {
    print htmlheader("システム管理者用トップページ");
    print" <DIV class='maincontents'> <H2>メニュー</H2>
     <A href='edt_com_mgz.php'><img src='../fig/button1.gif' align='center'>
      企業向けメールマガジンの編集・更新</A><br>
     <A href='edt_usr_mgz.php'><img src='../fig/button1.gif' align='center'>
      ユーザ向けメールマガジンの編集・更新</A><br>
     <A href='passwd.php'><img src='../fig/button3.gif' align='center'>
      パスワードの変更</A><br>  <hr>  </div>";
    print htmlfooter();
  }
?>
