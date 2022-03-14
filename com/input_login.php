<?php
/****************************************/
/* 企業入力者用ログインページ           */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  /*
  if(isset($_POST['password'])) {
    //パスワードが入力されたとき
    if(mb_ereg("^[0-9]*$",$_POST['com_id'])&&strlen($_POST['password']) > 0){
      $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
      mysqli_query($con,"set names utf8");
	  //comテーブルでパスワードを照合
      $com_id = $_POST['com_id'];    //ログインユーザー名
      $passwd = $_POST['password']; //パスワード
      $sql="SELECT com_name,passwd FROM com_tbl WHERE com_id=$com_id";
      $rst=mysqli_query($con,$sql);
      $col=mysqli_fetch_array($rst);
	  if(!password_verify($passwd,$col['passwd'])){
		//パスワードが違う場合はエラーメッセージを出す;
		$errmsg = "<b>ユーザ名またはパスワードが違います。</b><BR><BR>";
	  } else {
        //認証OKのときセッション変数にユーザIDを格納
 		$_SESSION['com_id'] = $com_id;
 		$_SESSION['com_name'] = $col[com_name];
      }
    } else {
        $errmsg = "<b>ユーザ名またはパスワードが違います。</b><BR><BR>";
    }
  } else if($_GET['set']==1){
     $errmsg = "<b>パスワードが未入力です</b><BR><BR>";
  }
  //セッション変数によってログインをチェックします
  if (strlen($_SESSION['com_id'])>0) {
    //所定のセッション変数が定義済み（＝ログイン済み）のときはindexへジャンプ
    header("location: index.php");
    exit();
  } else {
    */
   //ログイン画面出力
   print htmlheader("情報登録者用ログイン");
   print "<p>
   会員登録企業各位<br>
高専卒業者各位<br>
<P align='right'>UI ターンシステム管理責任者<br>
函館高専地域連携協力会<br>
会長 川島 眞一</p>
<P align='center'>UI ターンシステムの一時休止について </p>
拝啓<br>ますますご清祥のこととお慶び申し上げます。日頃から大変お世話になっておりまして心より御礼申し上げます。<br>
さて、これまで運用してきました UI ターンシステムについては、スタートしてから相当の時間が経過している事を踏まえ、システムの更なる充実と利便性の向上、管理態勢の強化のためにメンテナンスを行うことと致しました。<br>
この為、<b>令和 3 年 12 月 17 日</b>よりシステムを休止させて頂きます。<br>
参加の皆様にはご不便をおかけ致しますが、ご理解のほどよろしくお願いいたします。システムの再構築には相当の期間が必要となることから、再開に付きましては来年〈令和 4 年〉の春以降を予定しております。その際には、再度ご連絡させて頂きます。趣旨ご理解の上、ご協力のほど何とぞよろしくお願い申し上げます。<br>
尚、休止期間中のお問い合わせ等に付きましては、<a href='https://hakodate-ct-cooperative.jp'>函館高専地域連携協力会</a>のホームペ
ージよりメールにてお問い合わせください。</p><P align='right'>敬具</p>
<p><a href='../pdf_file/pending.pdf'>PDFファイル案内文</a></p>";
   /*
   if(strlen($errmsg)>0) print $errmsg;
   print "このサイトでは、北海道内高専卒者向け求人情報を登録できます。<br><br>ユーザ名とパスワードを入力して[ログイン]ボタンをクリックしてください。<BR><BR>
         <FORM action='$PHP_SELF?set=1' method='POST'>
		 ユーザ名　<INPUT size='20' type='text' name='com_id'><br>
         パスワード　<INPUT size='20' type='password' name='password'>
         <INPUT type='submit' value='ログイン'>
         </FORM><br><br>";
   print "【ご注意】求人情報を検索する場合（高専卒者用サイト）は、<a href='../user_login.php'>こちらのリンク</a>をクリックしてください。<br><br><br>";
   print "【登　録】まだ企業登録をされていない方は、下記リンク先から登録申請を行ってください。（道内各高専の協力会への会員企業である必要があります）<br><br>";
   print "　<a href='../com_reg.php'>企業登録申請へのリンク</a><br><br><br>";
   print "<div style='background:#eeeeee;'>【お知らせ】 </div><br>";
   print "";
   */
  //}
  //ページフッタを出力します
  print htmlfooter();
?>
