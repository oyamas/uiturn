<?php
/****************************************/
/* 【新卒】求人情報登録確認ページ               */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  mysql_query("set names utf8");
  $selectdb = mysql_select_db($DBNAME, $con);
  //ページヘッダ出力
  print htmlheader("【新卒】求人情報登録確認");
//var_dump($_POST);
?>
<SCRIPT language='JavaScript'>
  <!--
  function pageback(){
   document.check.action='reg_newoffer.php';
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
  if ($errmsg != "") {
    //いずれかの入力エラーがあったときは戻るボタン表示
    print $errmsg . "<BR>前の画面に戻って入力内容を確認してください。
     <FORM  name='check' action='reg_offer.php' method='POST' enctype='multipart/form-data'>
     <INPUT type='submit' value='　　戻る　 '>
     <INPUT type='hidden' name='back'>";
    //入力情報を次のページへ引き渡す
    print ary2hidden($_POST);
    print htmlfooter();
    exit();
  }
  //エラーが無ければ確認用画面を作成
  //数値部分を自動的に桁区切り
  $fc = 'return int2comma($matches[0]);';
  $_POST['income'] = preg_replace_callback("/([0-9]{1,3}(,[0-9]{3})*)+[\.0-9]*/", create_function('$matches',$fc),$_POST['income']);
  $_POST['income_s'] = preg_replace_callback("/([0-9]{1,3}(,[0-9]{3})*)+[\.0-9]*/", create_function('$matches',$fc),$_POST['income_s']);
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
  //その企業の同年度に関する求人票の取得
  $newoffer_id=dfirst($con,"newoffer_id","newoffersheet"
      ,"year=".$_POST['year']." and com_id=".$_SESSION['com_id']);
  if($newoffer_id=="")
  {
    //同年度の求人票がないなら新規レコード登録扱いに変更
    $_POST['edit']=1;
  } 
   else
  {
    $_POST['newoffer_id']=$newoffer_id;
  }
?>
 登録内容を確認してください。
 この内容でよろしければ、[OK]ボタンをクリックしてください。<BR><BR>
 <FORM  name='check'action='reg_newoffer_exe.php' method='POST'
       enctype='multipart/form-data'>
 <TABLE class='formtable'>
 <TR>
 <TH>採用年度</TH>
 <TD><?php print nl2br($_POST['year']) ?>年度<BR></TD>
 </TR>
 <TR>
 <TH>募集職種</TH>
 <TD><?php print nl2br($_POST['work']) ?><BR></TD>
 </TR>
 <TR>
 <TH>初任給</TH>
 <TD>本科卒……<?php print  $_POST['income'] ?><br>
    専攻科卒…<?php print  $_POST['income_s'] ?></TD>
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
 <TH>求人に関する備考</TH>
 <TD><?php print nl2br($_POST['remarks']) ?><BR>
 </TD>
 </TR>
 <tr><th>PDF<br>ファイル</th>
 <td>
<?php
 $filename=$_POST['pdf_file'];
 if($filename!=""){
  print "<a href='../pdf_file/$filename' target='_blank'>現在登録されている求人書類PDFファイル</a>←このファイルを削除する<input type='checkbox' name='del_pdf' value='5'><br>
   PDFファイルを更新する場合は、下欄からアップロードしてください。";
 } else {
  print "登録されていません<br>PDFファイルを追加する場合は、下欄からアップロードしてください。";
 }
?>
   </font><br>
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
  <INPUT type='hidden' name='newoffer_id' value='<?php echo $newoffer_id ?>'>
 </center>

<?php
  //入力情報を次のページへ引き渡す
  print ary2hidden($_POST);
  //フッタ出力
  print "</FORM>".htmlfooter();
?>
