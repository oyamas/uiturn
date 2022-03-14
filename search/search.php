<?php 
/****************************************/
/* 検索入力ページ                       */
/****************************************/
  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  login_check();
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");
  print htmlheader("求人企業の検索");
?>
<SCRIPT language='JavaScript'><!--
 function htwinOpen(link){
   htwin = window.open(link,'howto','width=600,height=400,scrollbars=yes');
   htwin.focus();
 }
 function chBox(ary,check){
   len = document.search.elements.length;
   for(i=0; i<len; i++)
    if(document.search.elements[i].name==ary){
      document.search.elements[i].checked = check;
   }
 }//           --></SCRIPT>
  以下の項目に<b>検索キーワードを入力し、検索ボタンを押して</b>下さい。
 <a href='#' onclick='htwinOpen("howto.html")'>検索システムの使い方はこちら</a>
 <FORM name='search' action='search_rst.php' method='GET'>
 <br>
   <b>企業名　</B>
   <a href='#' onclick='htwinOpen("howto.html#words")'>入力方法</a><br>
   <INPUT type='text' name='com_name'
     value='<?php echo $_GET['com_name'] ?>' size='60'><p>
  <b>企業名フリガナ　</B>
   <a href='#' onclick='htwinOpen("howto.html#words")'>入力方法</a><br>
   <INPUT type='text' name='com_kana'
     value='<?php echo $_GET['com_kana'] ?>' size='60'><p>
  <b>業務の内容　</B>
   <a href='#' onclick='htwinOpen("howto.html#words")'>入力方法</a><br>
   <INPUT type='text' name='business'
     value='<?php echo $_GET['business'] ?>' size='60'><p>
  <b>業種　</b> <SELECT name='ctg_id'>
    <OPTION value='0'>--業種を選択してください--</OPTION>
<?php
  //すべての業種を読み込むSQLを組み立てます
  $sql = "SELECT * FROM ctg_tbl ORDER BY ctg_id";
  $rst = mysqli_query($con,$sql);
  //業種のオプションメニューを組み立てます
  while ($col = mysqli_fetch_array($rst)) {
    //カテゴリをSELECT文の値に
    if($col[ctg_id]==$_GET['ctg_id'])
       print "<OPTION value='$col[ctg_id]' SELECTED>$col[ctg_name]</OPTION>";
	 else
       print "<OPTION value='$col[ctg_id]'>$col[ctg_name]</OPTION>";
  }
  //結果セットを破棄します
  mysqli_free_result($rst);
?>
  </SELECT>
    <a href='#' onclick='htwinOpen("howto.html#category")'>入力方法</a><p>
   <b>募集職種　</B>
    <a href='#' onclick='htwinOpen("howto.html#words")'>入力方法</a><br>
    <INPUT type='text' name='work'
      value='<?php echo $_GET['work'] ?>' size='60'><p>
   <b>募集対象者の専攻(複数選択可)　</b>
    <a href='#' onclick='htwinOpen("howto.html#dept")'>入力方法</a><br>
<?php
  //すべての学科を読み込むSQLを組み立てます
  $sql = "SELECT * FROM dept_tbl ORDER BY dept_id";
  $rst = mysqli_query($con,$sql);
  //学科チェックボックスの表示
  $i=0; $j=0;
  while($col = mysqli_fetch_array($rst)) {
    if($_GET['sel_dept'][$j]==$col['dept_id']){
      print"<INPUT type='checkbox' name='sel_dept[]'
                   value='$col[dept_id]' CHECKED>$col[dept_name]　";
      $j++;
    } else {
      print "<INPUT type='checkbox' name='sel_dept[]'
                   value='$col[dept_id]' >$col[dept_name]　";
    }
    $i++;
    //if($i==5) print " <BR>";
  }
  //結果セットを破棄します
  mysqli_free_result($rst);
?>
<br>
<INPUT type='button' value='全てチェック' onClick='chBox("sel_dept[]",true)'>
<INPUT type='button' value='チェック解除' onClick='chBox("sel_dept[]",false)'>
<p>
<b>勤務地(複数選択可)　</b>
   <a href='#' onclick='htwinOpen("howto.html#wplc")'>入力方法</a><br>
<?php
  //すべての勤務地を読み込むSQLを組み立てます
  $sql = "SELECT * FROM wplc_tbl ORDER BY wplc_id";
  $rst = mysqli_query($con,$sql);
  //勤務地チェックボックスの表示
  $i=0; $j=0;
  while ($col=mysqli_fetch_array($rst)) {
    if($_GET['sel_wplc'][$j]==$col['wplc_id']){
       print "<INPUT type='checkbox' name='sel_wplc[]'
                   value='$col[wplc_id]' CHECKED>$col[wplc_name]　";
       $j++;
    } else {
       print "<INPUT type='checkbox' name='sel_wplc[]'
                   value='$col[wplc_id]'>$col[wplc_name]　";
    }
    $i++;
    if($i==4) print " <BR>";
  }
  mysqli_free_result($rst);
  //MySQLとの接続を解除します
  $con = mysqli_close($con);
?><br>
 <INPUT type='button' value='全てチェック' onClick='chBox("sel_wplc[]",true)'>
 <INPUT type='button' value='チェック解除' onClick='chBox("sel_wplc[]",false)'>
 <br><br>
  <INPUT type='hidden' name='sort' value='date'>
  <INPUT type='hidden' name='seq' value='desc'>
  <INPUT type='submit' value='　検索　'>
  <INPUT type='button' value='　クリア　' Onclick='location.href="search.php"'>
       <a href='#' onclick='htwinOpen("howto.html#caution")'>※検索時の注意</a>
  </FORM>
<?php
  //ページフッタを出力します
  print htmlfooter();
?>
