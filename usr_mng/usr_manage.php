<?php
/****************************************/
/* ユーザ情報一覧表示ページ　　　　　　　　　 */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");
  print htmlheader("登録ユーザの一覧表示と編集");
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
?>
 <script language='javascript'>
 function edit(id){
   document.manage.user_id.value=id;
   //alert(document.manage.user_id.value);
   document.manage.submit();
 }
 </script>
      <FORM name='manage' action='user_upd.php' method='POST'>
 現在登録されているユーザは以下のとおりです。（五十音順）<br>
 ※「有効」＝メールアドレスの確認が取れています。<br><span class='empfont'> ※「未確認」</span>＝メールアドレスの確認が取れていないユーザです。<br><br>
<?php
   $sql = "SELECT Count(*) as cnt FROM user_tbl WHERE college_id =".$_SESSION['college_id'];
   $rst = mysqli_query($con,$sql);
   $row = mysqli_fetch_array($rst);
   print "総ユーザ件数：$row[cnt] 件<br>";
 ?>
   <table class='formtable'>
   <tr> <th width='50'> </th>
   <th width='50'></th><th width='100'>氏名</th><th width='120'>よみがな</th><th width='80'>ID</th><th width='80'>卒業年度</th><th width='200'>出身学科</th>
 <!--  <th width='60'>郵便番号</th><th width='150'>住所</th><th width='100'>電話番号</th> -->
   <th width='250'>メール</th></tr>
 <?php
   $sql = "SELECT * FROM user_tbl WHERE college_id =".$_SESSION['college_id']." ORDER BY kana";
   $rst = mysqli_query($con,$sql);
   while ($col = mysqli_fetch_array($rst)) {
     if($col[auth]==0){
       $status="<span class='empfont'>未確認</span>";
     } else {
       $status="有効";
     }
     //$year=$col[gengo].$col[year];
     $year = $col[year];
     $dept=dfirst($con,"name","college_dept","college_id=$col[college_id] and dept_id=$col[dept_id]");
     $check="<input type='button' value='編集' onclick='edit($col[user_id])'>";
     print "<tr><td>$check</td><td>$status</td><td>$col[name]</td><td>$col[kana]</td><td>$col[user_id]</td>
           <td>$year</td><td>$dept</td>"
      //     ."<td>$col[postal]</td><td>$col[address]</td><td>$col[tel]</td>"
           ."<td>$col[email]</td></tr>";
   }
  mysqli_free_result($rst);
?>
 </table>
 <input type='hidden' name='user_id'>
  </FORM>
   ※「有効」＝メールアドレスの確認が取れています。<br><span class='empfont'> ※「未確認」</span>＝メールアドレスの確認が取れていないユーザです。
<?php
 //MySQLとの接続を解除します
 $con = mysqli_close($con);
 //ページフッタを出力します
 print htmlfooter();
?>
