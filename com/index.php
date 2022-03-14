<?php 
/****************************************/
/* データ入力者用トップページ           */
/****************************************/
  session_start();
  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  //ログインチェック
  if ($_SESSION['com_id']==0) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print htmlheader("企業入力者用トップページ（エラー）");
    print "<br><BR><hr><DIV class='largefont' align='center'>
     エラー<br>企業入力者の権限確認がまだされていません</DIV><hr><br>
     <DIV align='center'>
     <FORM action='input_login.php' method='GET'>
      下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
    </FORM></div>";
    print htmlfooter();
  } else {
    print htmlheader("企業入力者用トップページ");
    print" <DIV class='maincontents'> <H2>企業向け情報入力メニュー</H2>
     <A href='reg_com.php'><img src='../fig/button1.gif' align='center'>
      企業情報の更新</A><br>
     <A href='reg_offer.php'><img src='../fig/button2.gif' align='center'>
      【中途採用】求人情報の登録、更新</A><br>
     <A href='com_dtl.php' target='_blank'><img src='../fig/button3.gif' align='center'>
       現在登録している求人情報の確認（別ウィンドウで開きます）</A><br>
     <A href='passwd.php'><img src='../fig/button1.gif' align='center'>
      パスワードの変更</A><br>  <hr>  </div>";
    print htmlfooter();
  }
?>
