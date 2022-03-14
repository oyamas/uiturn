<?php
/****************************************/
/* 求人情報登録確認ページ               */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");
 //  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
//  mysql_query("set names utf8");
//  $selectdb = mysql_select_db($DBNAME, $con);
  //ページヘッダ出力
  print htmlheader("求人情報登録確認");
//var_dump($_POST);
?>
<SCRIPT language='JavaScript'>
  <!--
  function pageback(){
   document.check.action='reg_offer.php';
   document.check.target = '_self';
   document.check.back.value = 1;
   document.check.submit();
  }
  //-->  </SCRIPT>
<?php
  //各入力データのチェック
  $errmsg = "";
  if (strlen($_POST['work']) == 0) {
    $errmsg .= "募集職種が入力されていません。<BR>";
  }
  if (sizeof($_POST['sel_dept']) == 0) {
    $errmsg .= "募集学科・専攻が入力されていません。<BR>";
  }
  if (sizeof($_POST['sel_wplc']) == 0) {
    $errmsg .= "勤務地が入力されていません。<BR>";
  }
  if(strlen($_POST['available'])>0 && strtotime($_POST['available'])<time()){
    $errmsg .= "有効期限の日付が、現在より前になっています。<br>";
  }
  if ($errmsg != "") {
    //いずれかの入力エラーがあったときは戻るボタン表示
    print $errmsg . "<BR>前の画面に戻って入力内容を確認してください。
     <FORM  name='check' action='reg_offer.php' method='POST' enctype='multipart/form-data'>
     <INPUT type='submit' value='　　戻る　 '>
     <INPUT type='hidden' name='back' value='1'>";
    //入力情報を次のページへ引き渡す
    print ary2hidden($_POST);
    print htmlfooter();
    exit();
  }
  //エラーが無ければ確認用画面を作成
  //配列変数deptにある学科名すべてを$deptに
  $dept="";
  for($i=0;$i<sizeof($_POST['sel_dept']);$i++)
   $dept .= dfirst($con,"dept_name", "dept_tbl",
                  "dept_id=\"". $_POST['sel_dept'][$i]. "\""). "<br>";
  //配列変数wplcにある勤務地すべてを$wplcに
  $wplc="";
  for($i=0;$i<sizeof($_POST['sel_wplc']);$i++)
	$wplc .= dfirst($con,"wplc_name", "wplc_tbl",
	              "wplc_id=\"". $_POST['sel_wplc'][$i]. "\""). "<br>";
?>
 登録内容を確認してください。
 この内容でよろしければ、[OK]ボタンをクリックしてください。<BR><BR>
 <FORM  name='check'action='reg_offer_exe.php' method='POST'
       enctype='multipart/form-data'>
 <TABLE class='formtable'>
 <TR>
 <TH>募集職種</TH>
 <TD><?php print nl2br($_POST['work']) ?><BR></TD>
 </TR>
 <TR>
 <TH>募集学科</TH>
 <TD><?php print  $dept ?></TD>
 </TR>
 <TR>
 <TH>勤務地</TH>
 <TD><?php print $wplc?></TD>
 </TR>
 <TR>
 <TH>選考方法</TH>
 <TD><?php print nl2br($_POST['recruit']) ?><BR>
 </TD>
 </TR>
<TR>
 <TH>求人情報の有効期限</TH>
 <TD><?php print $_POST['available'] ?><BR>
 </TD>
 </TR>
 <TR>
 <TH>求人に関する備考</TH>
 <TD><?php print nl2br($_POST['remarks']) ?><BR>
 </TD>
 </TR>
 <tr><th>PDF<br>ファイル</th>
 <td>
<?php
 $filename=$_POST['pdf_file'];
 if($filename!=""){
  print "<a href='../pdf_file/$filename' target='_blank'>現在登録されている求人書類PDFファイル</a>←このファイルを削除する<input type='checkbox' name='del_pdf' value='5'>";
 } else {
  print "登録されていません";
 }
?>
 <br>PDFファイルを更新する場合は、下欄からアップロードしてください。<br>
   <input type='file' name='pdf_file' size='30'>
 </td></tr>
 </table><br>
 <table class='formtable'>
 </TABLE>
 <center>
  <INPUT type='submit' name='regok' value='  OK  '>
  <INPUT type='button' value='  戻る　' onclick='pageback()'>
  <INPUT type='submit' name='cancel' value='キャンセル'>
  <INPUT type='hidden' name='back'>
 </center>
<?php
  //入力情報を次のページへ引き渡す
  print ary2hidden($_POST);
  //フッタ出力
  print "</FORM>".htmlfooter();
?>
