<?php
/****************************************/
/* 卒業生ユーザ向けメールマガジン編集ページ  */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  $con = mysqli_connect($DBSERVER, $DBUSER, $DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");
  //$selectdb = mysql_select_db($DBNAME, $con);
  print htmlheader("メールマガジンの編集");
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
  $sql = "SELECT u_mgz_cnt FROM mng_tbl WHERE mng_id =".$_SESSION['mng_id'];
  $rst = mysqli_query($con,$sql);
  $col = mysqli_fetch_array($rst);
  $mgz_cnt = $col[u_mgz_cnt]+1;
  
 //$tplate=file_get_contents("template.txt");
 $title1="＜現在の求人登録総数＞";
 //現在の勤務地別求人総数
 $cnt_other=0;
 $offer_wplc="・勤務地別\r\n";
 $sql = "SELECT wplc_name,wplc_tbl.wplc_id,Count(*) as cnt FROM offersheet
  INNER JOIN offer_wplc ON offersheet.offer_id = offer_wplc.offer_id
  INNER JOIN wplc_tbl ON offer_wplc.wplc_id = wplc_tbl.wplc_id
  WHERE delflag=0 GROUP BY wplc_tbl.wplc_id";
  $rst = mysqli_query($con,$sql);
  while($col= mysqli_fetch_array($rst)){
     if($col[wplc_id]>9) $cnt_other += $col[cnt];
      else $offer_wplc .= "$col[wplc_name]：$col[cnt]件\r\n";
  }
  mysqli_free_result($rst);
  $offer_wplc.="道外：".$cnt_other."件";
  
  //現在の専門分野別求人総数
  $cnt_other=0;
  $offer_dept="・専門分野別\r\n";
  $sql = "SELECT dept_name,dept_tbl.dept_id,Count(*) as cnt FROM offersheet
    INNER JOIN offer_dept ON offersheet.offer_id = offer_dept.offer_id
    INNER JOIN dept_tbl ON offer_dept.dept_id = dept_tbl.dept_id
    WHERE delflag=0 GROUP BY dept_tbl.dept_id";
  $rst = mysqli_query($con,$sql);
  while($col= mysqli_fetch_array($rst)){
     $offer_dept .= "$col[dept_name]： $col[cnt]件\r\n";
  }
  mysqli_free_result($rst);
  $title2="＜最近の求人登録状況＞\r\n";
   //直近1カ月の求人登録数
  $from = date("Y-m-d H:i:s",strtotime('-1 month'));
  $now = date("Y-m-d H:i:s");
  $sql = "SELECT Count(*) as cnt FROM offersheet WHERE delflag=0 and upddate>='$from' and upddate<='$now'";
  $rst = mysqli_query($con,$sql);
  $col= mysqli_fetch_array($rst);
  $rcnt_1m="・直近1カ月の求人登録件数 ： $col[cnt]件\r\n\r\n";
  mysqli_free_result($rst);
  //直近1カ月の登録企業リスト
  $com_list="・直近1カ月に求人登録した企業（所在地）\r\n";
  $sql="SELECT *  FROM offersheet INNER JOIN com_tbl ON offersheet.com_id = com_tbl.com_id
     WHERE offersheet.delflag=0 and offersheet.upddate>='$from' and offersheet.upddate<='$now'";
  $rst = mysqli_query($con,$sql);
  while($col= mysqli_fetch_array($rst)){
    $com_list .= "$col[com_name]（$col[address]）\r\n";
  }
  mysqli_free_result($rst);
  
  //メールマガジン組み立て
  $header="道内高専卒者求人検索システム　ユーザ向けメールマガジン
--------------第".$mgz_cnt."号　　".date("Y 年 n 月 j 日")."発行--------------
　道内高専卒者向け求人検索システムをご利用いただきありがとうございます。
　求人登録状況などをお知らせするため、メールマガジンを今後発行します。
　月1回程度のペースで発行する予定です。\r\n\r\n";
  $sec1="【1】現在の求人登録状況\r\n$title1\r\n$offer_wplc\r\n\r\n$offer_dept\r\n\r\n$title2$rcnt_1m$com_list\r\n";
  $sec2="【2】ご意見募集
　求人情報に掲載してほしい内容など、本システムに対するご要望をお知らせください。
　（例）「高専OB/OGの在籍数は？」「高専OB/OGは、現在どのような仕事をしている？」
　管理者が集計し、企業の人事担当者に直接要望します。
　皆様のご意見をお待ちしています！\r\n\r\n";
  $sec3="【3】お知らせ
・パスワードを失念された方は、改めて新規登録をして下さい。以前使用していたIDについては管理者側で削除します。
・メールアドレスなどの変更は、ログイン後の「ユーザ情報の変更」ページからできます。
・その他のお問い合わせや、システム改善に向けたご意見は、info@hokkaido-nct.sakura.ne.jpまでお願いします。\r\n\r\n";
  $footer="　皆様の積極的なご利用をよろしくお願いします。
　★システムのアドレス…‥・→　http://hokkaido-nct.sakura.ne.jp/\r\n
/////////////////////////////////////////////////////////////////////
発行者：道内高専卒者向け求人検索システム　管理責任者　小山慎哉
  〒042-8501　北海道函館市戸倉町14-1　　函館工業高等専門学校
  Email:info@hokkaido-nct.sakura.ne.jp";
  
?>
 <FORM name='usr_mgz' action='send_usr_mgz.php' method='POST'>
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
