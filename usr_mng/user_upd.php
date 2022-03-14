<?php
/****************************************/
/* ユーザ情報更新ページ           */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");
  print htmlheader("登録ユーザの編集");
  $user_id=$_POST['user_id'];
  //ログインチェック
  if ($_SESSION['login_id']==0) {
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
  //user_upd_chk.phpから戻ってきたかチェック
  if($_POST['back']==1){
    $col = $_POST;
  } else {
    $sql= "SELECT * FROM user_tbl WHERE user_id = $user_id";
    $rst= mysqli_query($con,$sql);
    $col= mysqli_fetch_array($rst);
  }
?>
<script type='text/javascript'>
function user_del(user_id){
   if(confirm('このユーザ情報を本当に削除しますか？')){
    document.updform.action = 'user_upd_exe.php';
    document.updform.user_id.value = user_id;
    document.updform.proc.value = 'del';
    document.updform.submit();
   }
 }
</script>
<DIV class='maincontents'><BR>
  下記の登録内容を編集し、送信ボタンを押してください。<BR><BR>
 　このユーザの情報を削除する場合→<input type='button' value='削除' onclick='user_del(<?=$user_id?>)'><br><br>
  <FORM name='updform' action='user_upd_chk.php' method='POST'>
 氏　　　　名
　<INPUT size='20' type='text' name='name' value='<?=$col[name]?>'><br>
 氏名ふりがな
　<INPUT size='20' type='text' name='kana' value='<?=$col[kana]?>'><br>

<!-- 郵 便 番 号
　<INPUT size='10' type='text' name='postal' value='<?=$col[postal]?>'><br>
 住　　　　所
　<INPUT size='50' type='text' name='address' value='<?=$col[address]?>'><br>
 電 話 番 号
　<INPUT size='20' type='text' name='tel' value='<?=$col[tel]?>'><br>
-->
 電子メールアドレス
　<INPUT size='40' type='text' name='email' value='<?=$col[email]?>'><br>
出身学科　<select name="dept_id"><option value="">------</option>
<?php
 $sql2="SELECT * FROM college_dept WHERE college_id=$col[college_id] ORDER BY dept_id";
 $rst2=mysqli_query($con,$sql2);
 while($col2=mysqli_fetch_array($rst2)){
   print "<option value='".$col['dept_id']."'";
   if($col[dept_id]==$col2[dept_id]) print " SELECTED ";
   print " >".$col2['name']."</option>";
 }
?></select>
<br><br>
 卒業年度（西暦）
<!--
<input type='radio' name='gengo' value='s'
<?php if($col['gengo']=='s') print "CHECKED";?>
>昭和
<input type='radio' name='gengo' value='h'
<?php if($col['gengo']=='h') print "CHECKED";?>
>平成
 -->
<INPUT size='5' type='text' name='year' value='<?=$col['year']?>'>年度<br>
※変換式・・・・昭和x年度＝1925+x、平成y年度＝1988+y、令和z年度＝2018+z<br><br>
 備　　　　考
　<INPUT size='50' type='text' name='bikou' value='<?=$col['bikou']?>'><br><br><br>

 <INPUT type='submit' value='確認画面へ'>
 <INPUT type='hidden' name='user_id' value='<?=$user_id ?>'>
 <INPUT type='hidden' name='college_id' value='<?=$col[college_id] ?>'>
 <INPUT type='hidden' name='proc'>
 </FORM>

<?php
  print htmlfooter();
?>
