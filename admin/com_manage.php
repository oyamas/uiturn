<?php
/****************************************/
/* 企業情報一覧表示ページ　　　　　　　　　 */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  //$con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //mysql_query("set names utf8");
  //$selectdb = mysql_select_db($DBNAME, $con);
  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");
  print htmlheader("登録企業の表示");
  //ログインチェック
  if ($_SESSION['adm_id']==0) {
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
 function all_chk(){
   for(i=0;i<document.manage.elements.length;i++){
     if(document.manage.elements[i].name=="cert[]"){
       document.manage.elements[i].checked = document.manage.check.checked;
     }
   }
 }
 </script>
      <FORM name='manage' action='send_id.php' method='POST'>
 現在登録されている企業は以下のとおりです。<br>
 ↓チェックした企業にIDとパスワードを通知する
 <input type='submit' value='通知'><br><br>

   <table class='formtable'>
   <tr> <th width='50'><input type='checkbox' name='check' onclick='all_chk()'></th>
   <th width='50'>状態</th><th width='300'>企業名</th><th width='200'>部署</th><th width='100'>担当者</th><th width='150'>連絡先</th><th width='250'>eメール</th></tr>
 <?php
   $id_s = $_SESSION['college_id']*10000;
   $id_e = $id_s + 9999;
   $sql = "SELECT * FROM com_tbl WHERE com_id > $id_s and com_id <= $id_e ORDER BY act_flg, com_kana";
   $rst = mysqli_query($con,$sql);
   while ($col = mysqli_fetch_array($rst)) {
     if($col[act_flg]==0){
       $check="<input type='checkbox' name='cert[]' value='$col[com_id]'>";
       $status="<span class='empfont'>未承認</span>";
     } else {
       $check="";
       $status="有効";
     }
     print "<tr><td>$check</td><td>$status</td><td>$col[com_name]</td>"
     	."<td>$col[aff]</td><td>$col[contact]</td><td>$col[tel]</td>"
     	."<td><a href='mailto:$col[email]'>$col[email]</a></td></tr>";
   }
  mysqli_free_result($rst);
?>
 </table>
  </FORM>
<?php
 //MySQLとの接続を解除します
 $con = mysqli_close($con);
 //ページフッタを出力します
 print htmlfooter();
?>
