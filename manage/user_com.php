<?php
/****************************************/
/* 登録データ推移（ユーザ、企業）      */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  mysql_query("set names utf8");
  $selectdb = mysql_select_db($DBNAME, $con);
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
 <FORM name='manage' action='user_com.php' method='POST'>
 <input type='submit' value='変更'><br><br>

<?php
//ユーザ数取り出し
  $sum=array(0,0,0,0);
  $year=2014;
  $header="$year 年 ユーザ登録数<br><table class='formtable'><tr><th width='200'>学校名</th>";
  for($month=1;$month<=12;$month++){
      $header.="<th width='50'>$month 月</th>";
      $sql="SELECT college_id, count(*) as cnt FROM user_tbl
       WHERE auth=1 and regdate > \"$year"."-".$month."-01\" and regdate < \"$year"."-".($month+1)."-01\"
       GROUP BY college_id";
      $rst=mysql_query($sql,$con);
      while($col=mysql_fetch_array($rst)){
        $user[$month][$col[college_id]]=$col[cnt];
        $sum[$col[college_id]]+=$col[cnt];
      }
  }
  $sql="SELECT * FROM college WHERE college_id<10 ORDER BY college_id";
  $rst=mysql_query($sql,$con);
  while($col=mysql_fetch_array($rst)){
    $name[$col[college_id]]=$col[name];
  }
  $header.="<th width='50'>合計人数</th></tr>";
  $data="";
  for($college=1;$college<=4;$college++){
    $data.="<tr><td>$name[$college]</td>";
    for($month=1;$month<=12;$month++){
      $data.= "<td>".$user[$month][$college]."</td>";
    }
    $data.="<td>$sum[$college]</td></tr>";
  }
  print $header.$data."</table>";


//企業数取り出し
  $sum=array(0,0,0,0);
  $year=2014;
  $header="$year 年 企業登録数<br><table class='formtable'><tr><th width='200'>学校名</th>";
  for($year=2013;$year<=2014;$year++){
   for($month=1;$month<=12;$month++){
      $header.="<th width='50'>$month 月</th>";
      $sql="SELECT college_id, count(*) as cnt FROM com_tbl
       WHERE regdate > \"$year"."-".$month."-01\" and regdate < \"$year"."-".($month+1)."-01\"
       GROUP BY college_id";
      $rst=mysql_query($sql,$con);
      while($col=mysql_fetch_array($rst)){
        $com[$year][$month][$col[college_id]]=$col[cnt];
        $sum[$col[college_id]]+=$col[cnt];
      }
   }
  }
  $header.="<th width='50'>合計企業数</th></tr>";
  $data="";
  for($college=1;$college<=4;$college++){
   $data.="<tr><td>$name[$college]</td>";
   for($year=2013;$year<=2014;$year++){
    for($month=1;$month<=12;$month++){
      $data.= "<td>".$com[$year][$month][$college]."</td>";
    }
   }
   $data.="<td>$sum[$college]</td></tr>";
  }
  print $header.$data."</table>";
  
  //ページフッタを出力します
  print htmlfooter();
?>