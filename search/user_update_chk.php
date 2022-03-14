<?php
/****************************************/
/* ユーザ情報更新確認ページ           */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");

  //ログインチェック画面出力
  login_check();
  print htmlheader("新規ユーザ登録");

  //各入力データのチェック
  $errmsg = "";
  if (strlen($_POST['name']) == 0) {
    $errmsg .= "氏名が入力されていません。<BR>";
  }
  if (strlen($_POST['kana']) == 0) {
    $errmsg .= "氏名ふりがなが入力されていません。<BR>";
  }
  /*
  if((strlen($_POST['postal'])>0) and (!preg_match("/^[0-9]{3}-?[0-9]{4}$/",$_POST['postal']))){
	$errmsg .= "「郵便番号」が所定の形式以外の書式で入力されています。<br>";
  }
  if (strlen($_POST['address']) == 0) {
    $errmsg .= "住所が入力されていません。<BR>";
  }
  if ((strlen($_POST['tel'])>0) and (!preg_match("/^0[0-9]{1,4}-?[0-9]{1,4}-?[0-9]{3,4}$/",$_POST['tel']))){
	$errmsg.="「電話番号」が所定の形式以外の書式で入力されています。<br>";
  }
  */
  if ((strlen($_POST['email'])>0) and (!preg_match("|^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$|",$_POST['email']))){
	$errmsg .= "「電子メールアドレス」の書式が有効ではありません。<br>";
  }

  if ($errmsg != "") {
    //いずれかの入力エラーがあったときは戻るボタン表示
    print $errmsg . "<BR>前の画面に戻って入力内容を確認してください。
     <FORM  name='check' action='user_update.php' method='POST' enctype='multipart/form-data'>
     <INPUT type='submit' value='　　戻る　 '>
     <INPUT type='hidden' name='back' value='1'>";
    //入力情報を次のページへ引き渡す
    print ary2hidden($_POST);
    print htmlfooter();
    exit();
  }
?>
<script type="text/javascript">
  <!--
  function pageback(){
   document.regform.action='user_update.php';
   document.regform.target = '_self';
   document.regform.back.value = 1;
   document.regform.submit();
  }
  //-->
</script>

  下記の登録内容を確認してください。<BR><BR>
  <FORM name='regform' action='user_update_exe.php' method='POST'>
 氏　　　　名　<?php echo $_POST['name'] ?><br><br>
 氏名ふりがな <?php echo $_POST['kana'] ?><br><br>
 <!--
 郵 便 番 号　<?php echo $_POST['postal'] ?><br><br>
 住　　　　所　<?php echo $_POST['address'] ?><br><br>
 電 話 番 号　<?php echo $_POST['tel'] ?><br><br>
 -->
 電子メールアドレス　<?php echo $_POST['email'] ?><br><br>


 <INPUT type='submit' value='確認しました。登録します。'>&nbsp;&nbsp;<br><br>
 <input type='button' value='前のページに戻り、入力内容を訂正します。' onclick='pageback()'>
<?php
 foreach($_POST as $key=>$val){
   print "<input type='hidden' name='$key' value='$val'>";
 }
?>
<input type='hidden' name='back'>
 </FORM>

<?php
  //リロード対策のためセッションに値を渡す
  $_SESSION['regcom']=1;
  print htmlfooter();
?>
