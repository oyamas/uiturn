<?php
/****************************************/
/* 集計ページ（求人検索）           */
/****************************************/

  require_once("../ini.php");
  require_once("setting.php");
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  mysql_query("set names utf8");
  $selectdb = mysql_select_db($DBNAME, $con);

  print htmlheader("集計結果");
  
//協力会加盟企業でシステムに登録した企業の件数（アクティブ）
$sql1="SELECT name, count(*) as cnt
FROM com_tbl inner join college on college.college_id = com_tbl.college_id
where act_flg=1 group by com_tbl.college_id";

print "協力会加盟企業でシステムに登録した企業の件数<br><table border='1'>";
$rst = mysql_query($sql1,$con);
while($col = mysql_fetch_array($rst)){
 print "<tr><td>$col[name]</td><td>$col[cnt]</td></tr>";
}
print "</table><br>";

//上記のうち現在求人を登録している企業の件数
$sql2 = "SELECT name, count(*) as cnt FROM offersheet
 inner join com_tbl on com_tbl.com_id = offersheet.com_id
 inner join college on college.college_id = com_tbl.college_id
where offersheet.delflag=0 group by com_tbl.college_id";

print "上記のうち現在求人を登録している企業の件数<br><table border='1'>";
$rst = mysql_query($sql2,$con);
while($col = mysql_fetch_array($rst)){
 print "<tr><td>$col[name]</td><td>$col[cnt]</td></tr>";
}
print "</table><br>";


//学校ごと卒業生・修了生ユーザ登録件数
$sql3 = "SELECT college.name, count(*) as cnt FROM user_tbl
 inner join college on college.college_id = user_tbl.college_id
where auth = 1 and user_tbl.college_id > 0 group by user_tbl.college_id";

print "学校ごと卒業生・修了生ユーザ登録件数<br><table border='1'>";
$rst = mysql_query($sql3,$con);
while($col = mysql_fetch_array($rst)){
 print "<tr><td>".$col[name]."</td><td>$col[cnt]</td></tr>";
}
print "</table><br>";


  print htmlfooter();
  ?>
