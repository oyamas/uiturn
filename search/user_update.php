<?php
/****************************************/
/* ユーザ情報更新ページ           */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");

  //ログイン画面出力
  print htmlheader("ユーザ情報の更新");
  //確認ページから戻ってきた場合($_POST[back]==1)→$info=$REQUEST
  if($_POST['back']==1){
    $info = $_POST;
  } else {
    //そうでないときは現在登録されている企業情報をDBから取得
    $sql="SELECT * FROM user_tbl WHERE user_id=".$_SESSION['user_id'];
    $rst=mysqli_query($con,$sql);
    $info=mysqli_fetch_array($rst);
  }
?>
  下記の登録内容をご確認いただき、内容を更新後、送信ボタンを押してください。<br><br>
  ※出身校・学科、卒業年度の変更については、問い合わせ先までご連絡ください。<BR><BR>
  <FORM name='regform' action='user_update_chk.php' method='POST'>
 氏　　　　名
　<INPUT size='20' type='text' name='name' value='<?=$info['name']?>'><br>
 氏名ふりがな
　<INPUT size='20' type='text' name='kana' value='<?=$info['kana']?>'><br>
<!--
 郵 便 番 号
　<INPUT size='10' type='text' name='postal' value='<?=$info['postal']?>'><br>
 住　　　　所
　<INPUT size='50' type='text' name='address' value='<?=$info['address']?>'><br>
 電 話 番 号
　<INPUT size='20' type='text' name='tel' value='<?=$info['tel']?>'><br>
　-->
 電子メールアドレス
　<INPUT size='40' type='text' name='email' value='<?=$info['email']?>'><br><br><br>
 出身校・出身学科　　
 <?php
  print dfirst($con,"name","college","college_id=".$info['college_id'])
    ."&nbsp;".dfirst($con,"name","college_dept","college_id=".$info['college_id']." and dept_id=".$info['dept_id']);
?>
 <br><br>
 卒業年度　　
<?php
 //if($info['gengo']=='s') print "昭和"; else print "平成";
 print $info['year']."年度<br>";
 print "※変換式・・・・昭和x年度＝1925+x、平成y年度＝1988+y、令和z年度＝2018+z<br>";
?>
<INPUT type='hidden' name='college_id' value='<?=$info['college_id']?>'>
<INPUT type='hidden' name='dept_id' value='<?=$info['dept_id']?>'>
<INPUT type='hidden' name='gengo' value='<?=$info['gengo']?>'>
<INPUT type='hidden' name='year' value='<?=$info['year']?>'>
<br><br>
 <INPUT type='submit' value='確認画面へ'>
 </FORM>

<?php
  print htmlfooter();
?>
