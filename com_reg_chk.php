<?php
/****************************************/
/* 新規企業登録確認ページ           */
/****************************************/
  session_start();
  require_once("ini.php");
  require_once("setting.php");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");

  print htmlheader("新規企業登録申請内容確認");

  //記載内容チェック
  $errmsg = "";
  if(strlen($_POST['com_name'])==0) $errmsg .= "貴社名が入力されていません。<br>";
  if(strlen($_POST['address'])==0)  $errmsg .= "ご住所が入力されていません。<br>";
  if(strlen($_POST['aff'])==0)  $errmsg .= "ご担当者の所属が入力されていません。<br>";
  if(strlen($_POST['name'])==0)  $errmsg .= "ご担当者のお名前が入力されていません。<br>";
  if((strlen($_POST['tel'])==0) || (!preg_match("/^0[0-9]{1,4}-?[0-9]{1,4}-?[0-9]{3,4}$/",$_POST['tel']))){
    $errmsg .= "電話番号が正しく入力されていません。<br>";
  }
  if ((strlen($_POST['email'])==0)||(!preg_match("|^[0-9a-zA-Z_./?-]+@([0-9a-zA-Z-]+\.)+[0-9a-zA-Z-]+$|",$_POST['email']))){
	$errmsg .= "「電子メールアドレス」の書式が有効ではありません。<br>";
  }
  if(!($_POST['party']>0)) $errmsg .= "登録されている会が選択されていません。<br>";
  if ($errmsg != "") {
    //いずれかの入力エラーがあったときは戻るボタン表示
    print $errmsg . "<BR>前の画面に戻って入力内容を確認してください。
     <FORM  name='check' action='com_reg.php' method='POST' enctype='multipart/form-data'>
     <INPUT type='submit' value='　　戻る　 '>
     <INPUT type='hidden' name='back' value='1'>";
    //入力情報を次のページへ引き渡す
    print ary2hidden($_POST);
    print htmlfooter();
    exit();
  }
?>
<script type="text/javascript">
function edit()
{
  document.regform.action="com_reg.php";
  document.regform.submit();
}
</script>

  <span style='color:red;'>下記の登録内容を確認してください。</span><BR><BR>
  <FORM name='regform' action='com_reg_exe.php' method='POST'>
 貴社名：　<?php echo $_POST['com_name'] ?><br><br>
 所在地：　<?php echo $_POST['address'] ?><br><br>
ご担当者のご所属：　<?php echo $_POST['aff']?><br><br>
ご担当者のご氏名：　<?php echo $_POST['name']?><br><br>
 電話番号：　<?php echo $_POST['tel'] ?><br><br>
 電子メールアドレス：　<?php echo $_POST['email'] ?><br><br>
 会員登録している会：　
 <?php
  switch($_POST['party']){
   case 1: print "函館高専地域連携協力会"; break;
   case 2: print "苫小牧高専協力会"; break; 
   case 3: print "釧路高専地域振興協力会"; break;
   case 4: print "旭川高専産業技術振興会"; break; 
   case 100: print "どこにも登録していない"; break; 
  }
?>
 <br><br><br>
 <INPUT type='submit' value='確認しました。登録します。'>&nbsp;&nbsp;
 <input type='button' value='入力内容の訂正' onclick='edit()'>
<?php
 foreach($_POST as $key=>$val){
   print "<input type='hidden' name='$key' value='$val'>";
 }
?>
 </FORM>

<?php
  print htmlfooter();
?>
