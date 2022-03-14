<?php
/****************************************/
/* 企業情報更新実行ページ               */
/****************************************/
  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  session_start();
  //ログインチェック
  if ($_SESSION['com_id']==0) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print htmlheader("未ログインエラー");
    print "<br><br><hr><DIV class='largefont' align='center'>
     エラー<br>管理者の権限確認がまだされていません</DIV><hr><br>
    <FORM name='error' action='input_login.php' method='GET'>
    <div align='center'>下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
      <INPUT type='hidden' name='id' value='1'>
      </FORM></div>";
    exit();
  }
  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");
  //  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //  mysql_query("set names utf8");
  //  $selectdb = mysql_select_db($DBNAME, $con);

  if (isset($_POST['cancel'])) {
    //キャンセルボタンが押されたとき
    //新規登録ページへリダイレクト
    header("Location: index.php");
    exit();
  }
  //リロード時は処理を無効に
  if($_SESSION['regcom']==1){
    unset($_SESSION['regcom']);
  } else {
    print htmlheader("企業情報更新実行済み");
    print "この情報はすでに更新処理が終わっています。<br><br>
       <INPUT type='button' value='入力者用ホームへ戻る'
       onclick=\"location.href='index.php'\">";
    exit();
  }
  //ページヘッダを出力します
  print htmlheader("企業情報更新実行");
  //com_tblのレコードを更新する
  $sql = "UPDATE com_tbl SET "
  		 ."com_name=\"". $_POST['com_name']. "\","
         ."com_kana=\"". $_POST['com_kana']. "\","
         ."aff=\"".$_POST['aff']."\","
         ."contact =\"". $_POST['contact']. "\","
         ."ctg_id=".$_POST['ctg_id'].","
         ."business=\"". $_POST['business']."\","
         ."capital=".$_POST['capital'].","
         ."worknum=".$_POST['worknum'].","
         ."zipcode=\"". $_POST['zipcode']. "\","
         ."pref_id=".$_POST['pref_id'].","
         ."address=\"". $_POST['address']. "\","
         ."tel=\"". $_POST['tel']. "\","
         ."url=\"". $_POST['url']."\","
         ."email=\"".$_POST['email']."\","
         ."upddate=now(), editor_id=\"".$_SESSION['com_id']."\""
         ." WHERE com_id=".$_SESSION['com_id'];
  //SQL文を発行します
  $rst = mysqli_query($con,$sql);
  if ($rst) {
    print $_POST['com_name']."の企業の情報を更新しました。<br><br>";
  } else {
    print $_POST['com_name']."の情報の更新に失敗しました。<br><br>";
    exit();
  }
  //MySQLとの接続を解除します
  $con = mysqli_close($con);

?>
<br>
引き続き、求人情報を登録してください。<br>
<INPUT type='button' value='【中途】求人情報を登録'
   onclick="location.href='reg_offer.php'">&nbsp;
<br><br>
<br><br>
<INPUT type='button' value='入力者用ホームへ戻る'
   onclick="location.href='index.php'">
<?php  print htmlfooter(); ?>
