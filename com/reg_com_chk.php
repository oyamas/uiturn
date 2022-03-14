<?php
/****************************************/
/* 企業情報更新確認ページ               */
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
  print htmlheader("企業情報更新確認");
    //ログインチェック
  if ($_SESSION['com_id']==0) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print "<br><br><hr><DIV class='largefont' align='center'>
     エラー<br>管理者の権限確認がまだされていません</DIV><hr><br>
    <FORM name='error' action='input_login.php' method='GET'>
    <div align='center'>下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
      <INPUT type='hidden' name='id' value='1'>
      </FORM></div>";
    exit();
  }

?>
<SCRIPT language='JavaScript'>
  <!--
  function pageback(){
   document.check.action='reg_com.php';
   document.check.target = '_self';
   document.check.back.value = 1;
   document.check.submit();
  }
  //-->  </SCRIPT>
<?php
  //全角数字を半角数字に変換
  $_POST['capital'] = mb_convert_kana($_POST['capital'],'a');
  $_POST['worknum'] = mb_convert_kana($_POST['worknum'],'a');
  $_POST['zipcode'] = mb_convert_kana($_POST['zipcode'],'a');
  $_POST['tel'] = mb_convert_kana($_POST['tel'],'a');
  //数値データのコンマを削除
  $_POST['capital'] = mb_ereg_replace(",","",$_POST['capital']);
  $_POST['worknum'] = mb_ereg_replace(",","",$_POST['worknum']);
  //各入力データのチェック
  $errmsg = "";
  if (strlen($_POST['com_name']) == 0) {
    $errmsg .= "企業名が入力されていません。<BR>";
  }
  if (strlen($_POST['com_kana']) == 0) {
    $errmsg .= "企業名フリガナが入力されていません。<BR>";
  }
  if (strlen($_POST['contact']) == 0) {
    $errmsg .= "企業担当者名が入力されていません。<BR>";
  }
  if (strlen($_POST['capital']) == 0){ $_POST['capital'] = 0; }
  if (!mb_ereg("^0$|^[1-9][0-9]*$",$_POST['capital'])){
	$errmsg .= "「資本金」に数字以外の文字が入力されています。<br>";
  }
  if (strlen($_POST['worknum']) == 0){ $_POST['worknum'] = 0; }
  if (!mb_ereg("^0$|^[1-9][0-9]*$",$_POST['worknum'])){
	$errmsg .= "「従業員数」に数字以外の文字が入力されています。<br>";
  }
  if (strlen($_POST['address']) == 0) {
    $errmsg .= "所在地が入力されていません。<BR>";
  }
  if((strlen($_POST['zipcode'])>0) and (!mb_ereg("^[0-9]{3}-+[0-9]{4}$",$_POST['zipcode']))){
	$errmsg .= "「郵便番号」が所定の形式以外の書式で入力されています。「042-8501」の形式で入力してください。<br>";
  }
  if ((strlen($_POST['tel'])>0) and (!mb_ereg("^0[0-9]{1,4}-+[0-9]{1,4}-+[0-9]{3,4}$",$_POST['tel']))){
	$errmsg.="「電話番号」が所定の形式以外の書式で入力されています。「0138-12-3456」の形式で入力してください。<br>";
  }
  if ((strlen($_POST['url'])>0) and (!mb_ereg("[a-zA-Z0-9;/?:@&=\+$,\-_\.!~*'\(\)%#]+",$_POST['url']))){
	$errmsg .= "「ホームページアドレス」に半角文字以外の文字が使われています。<br>";
  }
  if ((strlen($_POST['email'])>0) and (!mb_ereg("^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$",$_POST['email']))){
	$errmsg .= "「Eメールアドレス」が所定の形式以外の書式で入力されています。<br>";
  }
  if ($_POST['ctg_id'] == 0) {
    $errmsg .= "業種が選択されていません<BR>";
  }
  if ($errmsg != "") {
    //いずれかの入力エラーがあったときは戻るボタン表示
    print $errmsg . "<BR>前の画面に戻って入力内容を確認してください。
     <FORM  name='check' action='reg_com.php' method='POST' enctype='multipart/form-data'>
     <INPUT type='submit' value='　　戻る　 '>
     <INPUT type='hidden' name='back' value='1'>";
    //入力情報を次のページへ引き渡す
    print ary2hidden($_POST);
    print htmlfooter();
    exit();
  }
  //エラーが無ければ確認用画面を作成
  //企業名で使用する文字を統一
  $_POST['com_name'] = mb_convert_kana($_POST['com_name'],'KVrns');
  $_POST['com_name'] = mb_ereg_replace('（','(',$_POST['com_name']);
  $_POST['com_name'] = mb_ereg_replace('）',')',$_POST['com_name']);
  $_POST['com_name'] = mb_ereg_replace('㈱','(株)',$_POST['com_name']);
  $_POST['com_name'] = mb_ereg_replace('株式会社','(株)',$_POST['com_name']);
  $_POST['com_kana'] = mb_convert_kana($_POST['com_kana'],'KC');
  $ctg_name=dfirst($con,"ctg_name","ctg_tbl","ctg_id=\"".$_POST['ctg_id']."\"");
  $pref=dfirst($con,"pref_name", "pref", "pref_id=\"". $_POST['pref_id']. "\"");
?>
 登録内容を確認してください。
 この内容でよろしければ、[OK]ボタンをクリックしてください。<BR><BR>
 <FORM  name='check'action='reg_com_exe.php' method='POST'
       enctype='multipart/form-data'>
 <TABLE class='formtable'>
 <TR>
 <TH>企業名</TH>
 <TD><?php print  $_POST['com_name'] ?><BR></TD>
 </TR>
 <TR>
 <TH>企業名<br>フリガナ</TH>
 <TD><?php print  $_POST['com_kana'] ?><BR></TD>
 </TR>
 <TR>
 <TH>担当者所属</TH>
 <TD><?php print  $_POST['aff'] ?><BR></TD>
 </TR>
 <TR>
 <TH>担当者氏名</TH>
 <TD><?php print  $_POST['contact'] ?><BR></TD>
 </TR>
 <TR>
 <TH>業種</TH>
 <TD><?php print  $ctg_name ?></TD>
 </TR>
 <TR>
 <TH>業務内容</TH>
 <TD><?php print nl2br($_POST['business']) ?><BR></TD>
 </TR>
 <TR>
 <TH>資本金</TH>
 <TD><?php print  $_POST['capital'] ?>　万円<BR></TD>
 </TR>
 <TR>
 <TH>従業員数</TH>
 <TD><?php print  $_POST['worknum'] ?>　名<BR></TD>
 </TR>
 <TR>
 <TH>郵便番号</TH>
 <TD><?php print  $_POST['zipcode'] ?><BR></TD>
 </TR>
 <TR>
 <TH>所在地</TH>
 <TD><?php print  $pref.$_POST['address'] ?><BR></TD>
 </TR>
 <TR>
 <TH>代表電話番号</TH>
 <TD><?php print  $_POST['tel'] ?><BR></TD>
 </TR>
 <TR>
 <TH>ホームページアドレス</TH>
 <TD><?php print  $_POST['url'] ?><BR></TD>
 </TR>
 <TR>
 <TH>Eメールアドレス</TH>
 <TD><?php print  $_POST['email'] ?><BR></TD>
 </TR>
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
  //リロード対策のためセッションに値を渡す
  $_SESSION['regcom']=1;
  //フッタ出力
  print "</FORM>".htmlfooter();
?>
