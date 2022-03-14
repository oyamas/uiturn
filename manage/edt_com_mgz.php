<?php
/****************************************/
/* 企業向けメールマガジン編集ページ  */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  $con = mysqli_connect($DBSERVER, $DBUSER, $DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");
  //$selectdb = mysql_select_db($DBNAME, $con);
  print htmlheader("企業向けメールマガジンの編集");
  //mng_idがセッションに含まれているかチェック
  login_check();
  
  //送付先メールアドレス一覧
  $mailto= "";
  $sql = "SELECT name, college_id, email FROM user_tbl WHERE auth=1 and email != \"\" ";
  $rst = mysqli_query($con,$sql);
  while($col= mysqli_fetch_array($rst)){
     $mailto .= $col[email].", ";
  }
  //マガジン発行回数
  $sql = "SELECT c_mgz_cnt FROM mng_tbl WHERE mng_id =".$_SESSION['mng_id'];
  $rst = mysqli_query($con,$sql);
  $col = mysqli_fetch_array($rst);
  $mgz_cnt = $col[c_mgz_cnt]+1;
  
 //$tplate=file_get_contents("template.txt");
  //ユーザ登録総数
  $sql = "SELECT Count(*) as cnt FROM user_tbl WHERE auth=1 and college_id>0";
  $rst = mysqli_query($con,$sql);
  $col= mysqli_fetch_array($rst);
  $user = "現在のユーザ登録総数： $col[cnt]件\r\n";

  //学校別ユーザ登録総数
  $college="・出身学校別ユーザ登録件数\r\n";
  $sql = "SELECT college.name , Count(*) as cnt FROM user_tbl
    INNER JOIN college ON user_tbl.college_id = college.college_id
    WHERE auth=1 GROUP BY college.college_id";
  $rst = mysqli_query($con,$sql);
  while($col= mysqli_fetch_array($rst)){
     $college .= "$col[name]： $col[cnt]名\r\n";
  }
  mysqli_free_result($rst);
  //最近3カ月のユーザ登録数
  $from = date("Y-m-d H:i:s",strtotime('-3 month'));
  $college_1m="（うち、最近3カ月の出身学校別ユーザ登録件数）\r\n";
  $sql = "SELECT college.name , Count(*) as cnt FROM user_tbl
    INNER JOIN college ON user_tbl.college_id = college.college_id
    WHERE auth=1 and regdate > \"$from\" GROUP BY college.college_id";
  $rst = mysqli_query($con,$sql);
  while($col= mysqli_fetch_array($rst)){
     $college_1m .= "$col[name]： $col[cnt]名\r\n";
  }
  mysqli_free_result($rst);
  //専門分野別ユーザ登録総数
  $dept="・専門分野別\r\n";
  $sql = "SELECT Count(*) as cnt,fld_tbl.name FROM user_tbl
    LEFT JOIN college_dept ON user_tbl.dept_id = college_dept.dept_id and user_tbl.college_id =college_dept.college_id
    LEFT JOIN college ON user_tbl.college_id = college.college_id
    INNER JOIN fld_tbl ON college_dept.fld = fld_tbl.id
    WHERE auth=1 GROUP BY fld_tbl.id";
  $rst = mysqli_query($con,$sql);
  while($col= mysqli_fetch_array($rst)){
     $dept .= "$col[name]： $col[cnt]名\r\n";
  }
  mysqli_free_result($rst);
  
  //メールマガジン組み立て
  $header="道内高専卒者求人検索システム　ご登録企業向けメールマガジン\r\n
--------------第".$mgz_cnt."号　　".date("Y 年 n 月 j 日")."発行--------------\r\n
　道内高専卒者向け求人検索システムをご利用いただきありがとうございます。
　ユーザ登録状況などをお知らせするため、メールマガジンを月1回程度で発行しております。\r\n\r\n";
  $sec1="【1】現在のユーザ登録状況\r\n$user\r\n$college\r\n$college_1m\r\n$dept\r\n\r\n";
  $sec2="【2】ご意見募集
  システムの利用を活性化するため、卒業生ユーザからの意見聴取をしておりますが、企業担当者様からのご意見もお待ちしています。
  いただいた内容は、管理者が集計し、システム改良の参考にさせていただきます。
  ご意見ご要望お待ちしております。\r\n\r\n";
  $sec3="【3】お知らせ
・パスワードを失念された方は、管理者にお問い合わせください。
・企業情報、メールアドレスなどの変更は、ログイン後のページからできます。
・その他のお問い合わせや、システム改善に向けたご意見は、info@hokkaido-nct.sakura.ne.jpまでお願いします。\r\n\r\n";
  $footer="　皆様の積極的なご利用をよろしくお願いします。
　★システムのアドレス…　http://www.hokkaido-nct.sakura.ne.jp/uiturn/com/input_login.php\r\n
/////////////////////////////////////////////////////////////////////
発行者：道内高専卒者向け求人検索システム　管理責任者　○○○○
  〒042-8501　北海道函館市戸倉町14-1　　函館工業高等専門学校
  Email:info@hokkaido-nct.sakura.ne.jp";
  
?>
 <FORM name='com_mgz' action='send_com_mgz.php' method='POST'>
 送付するメールマガジンの文面を下記テキストエリアに記述してください。<br><br><br>
<textarea id="magazine" name="magazine" cols="80" rows="50" maxlength="2000">
<?php
  print $header;
  print $sec1;
  print $sec2;
  print $sec3;
  print $footer;
?>
</textarea>
 <input type='submit' value='送信'><input type='button' value='戻る' Onclick='location.href="index.php"'>
  </FORM>
<?php
 //MySQLとの接続を解除します
 mysqli_close($con);
 //ページフッタを出力します
 print htmlfooter();
?>
