<?php
/****************************************/
/* 登録企業の重複確認検索               */
/****************************************/
  session_start();
  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");
  //  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //  mysql_query("set names utf8");
  //  $selectdb = mysql_select_db($DBNAME, $con);

  //１ページ分だけ抽出するSQL文を組み立てます
  $sql = "SELECT Count(*) as cnt FROM newoffersheet WHERE com_id=".$_SESSION['com_id'];
  //結果セットを取得します
  $rst = mysqli_query($con,$sql);
  $col = mysqli_fetch_array($rst);
  if($col[cnt]==0){
    //該当する情報がなかったときはreg_newoffer.phpへジャンプ
    header("Location: ./reg_newoffer.php");
    exit();
  }

?>
   <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
   <HTML>
   <HEAD>
   <META http-equiv='Content-Type' content='text/html; charset=utf-8'>
   <META http-equiv='Content-Style-Type' content='text/css'>
   <TITLE>既登録情報の確認</TITLE>
   <LINK rel='stylesheet' href='com.css' type='text/css'>
   <SCRIPT language='JavaScript'><!--
    function DetailShow(newoffer_id) {
      document.chkwin.action = 'newoffer_dtl.php';
      document.chkwin.target = '_blank';
      document.chkwin.newoffer_id.value = newoffer_id;
      document.chkwin.submit();
	}
    function EditExec(newoffer_id) {
      document.chkwin.action = 'reg_newoffer.php';
      document.chkwin.target = '_self';
      document.chkwin.newoffer_id.value = newoffer_id;
      document.chkwin.submit();
    }
//           --></SCRIPT>
    </HEAD>
    <BODY>
<?php
?>
 <FORM name='chkwin' method='GET'>
 <center><H3>＊＊＊下記の求人情報が既に登録されています＊＊＊
 </center><hr>
<br><br>
    <b>現在の登録内容を確認したい場合は「詳細」ボタン</b><br><br>
    <b>求人票の内容を新規追加・変更・削除するときは「編集」ボタン</b>
　を押してください。<br><br>
 <TABLE class='list'>
  <TR>
   <TH>年度</TH>
   <TH>募集職種</TH>
   <TH></TH>   <TH></TH>
  </TR>
<?php
  $pre_com_id = 0;
  $sql = "SELECT * FROM newoffersheet WHERE com_id=".$_SESSION['com_id']." ORDER BY year DESC";
  $rst = mysqli_query($con,$sql);
  while($col = mysqli_fetch_array($rst)) {
    //「業務内容」「募集職種」｢備考」内の改行文字の前にBRタグを置く
    $col[work] = nl2br($col[work]);
    //年度が$YEAR(=最新年度)の場合は背景を緑色に
    //年度が$YEAR未満の場合は背景を黄色に
    if($col[year]==$YEAR) $bgclr; else $bgclr='#FFFFFFF';
    //各企業情報を表示するリストを表示
    print "<TR  bgcolor=$bgclr>
      <TD width='30' align='center'> $col[year]</TD>
	  <TD width='250'> $col[work] </TD>
      <TD><INPUT type='button' value='詳細'
       onclick='DetailShow(\"$col[newoffer_id]\")'></TD>
      <TD align='center'><INPUT type='button' value='編集'
       onclick='EditExec(\"$col[newoffer_id]\")'><BR>
      </TD></TR>";
   }
   mysqli_free_result($rst);
   $con = mysqli_close($con);
?>
 </TABLE>
     <INPUT type='hidden' name='newoffer_id'>
 </FORM>
<?php print htmlfooter(); ?>
