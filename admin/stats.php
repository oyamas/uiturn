<?php
/****************************************/
/* 集計ページ（求人検索）           */
/****************************************/
session_start();
//共通データをインクルードします
require_once("../ini.php");
require_once("setting.php");
//ログインチェック
if ($_SESSION['adm_id']==0) {
  //所定のセッション変数が定義されていない（＝未ログイン）のとき
  //ログインページへジャンプします
  print htmlheader("各校管理者用トップページ（エラー）");
  print "<br><BR><hr><DIV class='largefont' align='center'>
   エラー<br>各校管理者の権限確認がまだされていません</DIV><hr><br>
   <DIV align='center'>
   <FORM action='input_login.php' method='GET'>
    下のボタンを押してログイン画面に戻ってください。<br><br>
    <INPUT type='submit' value='ログイン画面へ'>
  </FORM></div>";
  print htmlfooter();
  exit();

} else {

  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");

  print htmlheader("集計結果");

  //学校ごと企業総件数（アクティブ）
  $sql1="SELECT name, count(*) as cnt
  FROM com_tbl inner join college on college.college_id = com_tbl.college_id
  where act_flg=1 group by com_tbl.college_id";

  print "学校ごと企業総件数<br><table border='1'>";
  $rst = mysqli_query($con,$sql1);
  while($col = mysqli_fetch_array($rst)){
  print "<tr><td>$col[name]</td><td>$col[cnt]</td></tr>";
  }
  print "</table><br>";

  //学校ごと求人件数
  $sql2 = "SELECT name, count(*) as cnt FROM offersheet
  inner join com_tbl on com_tbl.com_id = offersheet.com_id
  inner join college on college.college_id = com_tbl.college_id
  where offersheet.delflag=0 group by com_tbl.college_id";

  print "学校ごと求人件数<br><table border='1'>";
  $rst = mysqli_query($con,$sql2);
  while($col = mysqli_fetch_array($rst)){
  print "<tr><td>$col[name]</td><td>$col[cnt]</td></tr>";
  }
  print "</table><br>";


  //学校ごとユーザ登録件数
  $sql3 = "SELECT college.name, count(*) as cnt FROM user_tbl
  inner join college on college.college_id = user_tbl.college_id
  where user_tbl.college_id > 0 group by user_tbl.college_id";

  print "学校ごとユーザ登録件数<br><table border='1'>";
  $rst = mysqli_query($con,$sql3);
  while($col = mysqli_fetch_array($rst)){
  print "<tr><td>".$col[name]."</td><td>$col[cnt]</td></tr>";
  }
  print "</table><br>";
  print htmlfooter();
}
?>
