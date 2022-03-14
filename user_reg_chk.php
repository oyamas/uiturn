<?php
/****************************************/
/* 新規ユーザ登録確認ページ           */
/****************************************/
  session_start();
  require_once("ini.php");
  require_once("setting.php");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");

  //ヘッダ出力
  print htmlheader("新規ユーザ登録");

  //各入力データのチェック
  $errmsg = "";
  if (strlen($_POST['name']) == 0) {
    $errmsg .= "氏名が入力されていません。<BR>";
  }
  if (strlen($_POST['kana']) == 0) {
    $errmsg .= "氏名ふりがなが入力されていません。<BR>";
  } else {
    $_POST['kana']=mb_convert_kana($_POST['kana'],'cKV',"UTF-8");
    if (!preg_match("/^[ぁ-ん]+$/u", $_POST['kana'])){
      $errmsg .= "氏名ふりがなに、かな以外の文字が使われています。<br>";
    }
  }
/*
  if((strlen($_POST['postal'])==0)||(!preg_match("/^[0-9]{3}-?[0-9]{4}$/",$_POST['postal']))){
	$errmsg .= "「郵便番号」が所定の形式以外の書式で入力されています。<br>";
  }
  if (strlen($_POST['address']) == 0) {
    $errmsg .= "住所が入力されていません。<BR>";
  }
  if ((strlen($_POST['tel'])==0)||(!preg_match("/^0[0-9]{1,4}-?[0-9]{1,4}-?[0-9]{3,4}$/",$_POST['tel']))){
	$errmsg.="「電話番号」が所定の形式以外の書式で入力されています。<br>";
  }
*/
  if ((strlen($_POST['email'])==0)||(!preg_match("|^[0-9a-zA-Z_./?-]+@([0-9a-zA-Z-]+\.)+[0-9a-zA-Z-]+$|",$_POST['email']))){
	$errmsg .= "「電子メールアドレス」の書式が有効ではありません。<br>";
  }
  if ($_POST['college_id'] == 0) {
    $errmsg .= "出身校が選択されていません。<BR>";
  }
  if ($_POST['dept'] == 0) {
    $errmsg .= "出身学科が選択されていません。<BR>";
  }
  if (!preg_match("/^[0-9]{4}$/",$_POST['year'])) {
    $errmsg .= "卒業年度が西暦で正しく入力されていません。<BR>";
  }

  if ($errmsg != "") {
    //いずれかの入力エラーがあったときは戻るボタン表示
    print $errmsg . "<BR>前の画面に戻って入力内容を確認してください。
     <FORM  name='check' action='user_reg.php' method='POST' enctype='multipart/form-data'>
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
  document.regform.action="user_reg.php";
  document.regform.submit();
}
</script>

  下記の登録内容を確認してください。<BR><BR>
  <FORM name='regform' action='user_reg_exe1.php' method='POST'>
  <table class='formtable'>
 <tr><th>氏　　　　名</th><td><?php echo $_POST['name'] ?></td></tr>
 <tr><th>氏名ふりがな</th><td><?php echo $_POST['kana'] ?></td></tr>
<!-- 
 <tr><th> 郵 便 番 号</th><td><?php echo $_POST['postal'] ?></td></tr>
 <tr><th>住　　　　所</th><td><?php echo $_POST['address'] ?></td></tr>
 <tr><th>電 話 番 号</th><td><?php echo $_POST['tel'] ?></td></tr>
-->
 <tr><th>電子メールアドレス</th><td><?php echo $_POST['email'] ?></td></tr>
 <tr><th>出身校・学科</th><td>
<?php
/*
 $sql="SELECT name FROM college WHERE college_id=".$_POST['college_id'];
 $rst=mysqli_query($con,$sql);
 $col=mysqli_fetch_array($rst);
 print $col["name"];
 $sql="SELECT name FROM college_dept WHERE college_id=".$_POST['college_id']." and dept_id=".$_POST['dept'];
 $rst=mysqli_query($con,$sql);
 $col=mysqli_fetch_array($rst);
 print "&nbsp;".$col["name"];
*/
 print dfirst($con,"name","college","college_id=".$_POST['college_id'])
    ."&nbsp;".dfirst($con,"name","college_dept","college_id=".$_POST['college_id']." and dept_id=".$_POST['dept']);

?></td></tr>
<tr><th>卒業年</th><td>
<?php
// if($_POST['gengo']=='s') print "昭和"; else print "平成";
 print $_POST['year']."年度";
?></td></tr>
<tr><th>備考</th><td><?=$_POST['bikou']?></td></tr>
</table><br><br>
 <INPUT type='submit' value='確認しました。登録します。'>&nbsp;&nbsp;<br><br>
 <input type='button' value='前のページに戻り、入力内容を訂正します。' onclick='edit()'>
<?php
 foreach($_POST as $key=>$val){
   print "<input type='hidden' name='$key' value='$val'>";
 }
?>
 </FORM>

<?php
  print htmlfooter();
?>
