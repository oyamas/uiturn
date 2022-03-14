<?php
/****************************************/
/* 新規ユーザ登録ページ           */
/****************************************/
  session_start();
  require_once("ini.php");
  require_once("setting.php");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");

  //ログイン画面出力
  print htmlheader("新規求人企業登録");
  //協力会用セレクトボックス
  $option = "";
  $sql="SELECT * FROM college ORDER BY college_id";
  $rst=mysqli_query($con,$sql);;
  while($col=mysqli_fetch_array($rst)){
    $option .= "<option value=\"".$col['college_id']."\"";
    if($_POST['party']==$col['college_id']) $option .= " SELECTED";
    $option .= "> $col[org] </option>";
  }
    
?>
<SCRIPT language='javascript'>
  function windowOpen(link){
    htwin = window.open(link,'howto','width=500,height=400,scrollbars=yes');
    htwin.focus();
  }
  </script>
  下記の登録内容を入力し、送信ボタンを押してください。<BR><BR>
  <FORM name='regform' action='com_reg_chk.php' method='POST'>
 貴社名
　<INPUT size='20' type='text' name='com_name' value='<?=$_POST['com_name']?>'><br>
 所　在　地
　<INPUT size='50' type='text' name='address' value='<?=$_POST['address']?>'><br>
ご担当者のご所属
　<INPUT size='20' type='text' name='aff' value='<?=$_POST['aff']?>'><br>
ご担当者のご氏名
　<INPUT size='20' type='text' name='name' value='<?=$_POST['name']?>'><br>
電話番号
　<INPUT size='20' type='text' name='tel' value='<?=$_POST['tel']?>'><br>
電子メールアドレス
　<INPUT size='40' type='text' name='email' value='<?=$_POST['email']?>'><br><br>
会員登録している会&nbsp;&nbsp;<a href='#' onclick='windowOpen("notice.html")'>会員未登録の企業の皆様へ</a><br><br>
　<select name='party'><option>--選択してください--</option>
  <?php print $option; ?>
<!--
　<option value='1' <?php if($_POST['party']==1) print "SELECTED"; ?> >函館高専地域連携協力会</option>
　<option value='2' <?php if($_POST['party']==2) print "SELECTED"; ?> >苫小牧高専協力会</option>
　<option value='3' <?php if($_POST['party']==3) print "SELECTED"; ?> >釧路高専地域振興協力会</option>
　<option value='4' <?php if($_POST['party']==4) print "SELECTED"; ?> >旭川高専産業技術振興会</option>
　　-->
</select><br><br>
 <INPUT type='submit' value='確認画面へ'>
 </FORM>

※ご登録内容を確認後、追って担当者よりIDとパスワードについて連絡差し上げます。
<br><br>
<?php
  print htmlfooter();
?>
