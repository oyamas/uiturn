<?php
/****************************************/
/* ユーザ用ログインページ           */
/****************************************/
  session_start();
  require_once("ini.php");
  require_once("setting.php");
  /*
  if(isset($_POST['password'])) {
    //パスワードが入力されたとき
    if(mb_ereg("^[0-9]*$",$_POST['user_id'])&&strlen($_POST['password'])>0){
	  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
      mysqli_query($con,"set names utf8");
      //$selectdb = mysqli_select_db($DBNAME, $con);
	  //userテーブルでパスワードを照合
      $user_id = $_POST['user_id'];    //ログインユーザー名
      $passwd = $_POST['password']; //パスワード
      $sql="SELECT name,passwd,auth FROM user_tbl WHERE user_id=$user_id";
      $rst=mysqli_query($con,$sql);
      if($col=mysqli_fetch_array($rst)){
        if($col['auth']==0){
          $errmsg = "このIDは仮登録の状態です。システムからのメールにあるリンクをクリックして本登録を完了してください。";
        } else if(!password_verify($passwd,$col['passwd'])) {
		  //パスワードが違う場合
		  $errmsg = "ユーザ名またはパスワードが違います。<BR><BR>";
	    } else {
          //認証OKの場合：セッション変数にユーザIDを格納
 		  $_SESSION['user_id'] = $user_id;
 		  $_SESSION['name'] = $col[name];
        }
    } else {
        //ユーザIDが存在しない場合
        $errmsg = "ユーザ名またはパスワードが違います。<BR><BR>";
      }
    }
  } else if($_GET['set']==1){
     $errmsg = "パスワードが未入力です<BR><BR>";
  }
  //セッション変数によってログインをチェックします
  if (strlen($_SESSION['user_id'])>0) {
    //所定のセッション変数が定義済み（＝ログイン済み）のときはindexへジャンプ
    header("location: index.php");
    exit();
  } else {
    */
   //ログイン画面出力
   print htmlheader("ユーザログイン");
   if(strlen($errmsg)>0) print "<div style='color:red;'>【ログインエラー】$errmsg</div>";
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
<p><a href='pdf_file/pending.pdf'>PDFファイル案内文</a></p>

<!-- <img src='fig/uiturn.png' alt='IUターンバナー'><br>このサイトは、主に北海道内の高等専門学校（高専）を卒業した方々への求人情報を提供している、「Iターン」「Uターン」支援サイトです。<br><br>ユーザIDとパスワードを入力して[ログイン]ボタンをクリックしてください。<BR><BR>
        <FORM action='user_login.php?set=1' method='POST'>
		 ユーザID　　<INPUT size='20' type='text' name='user_id'><br>
         パスワード　<INPUT size='20' type='password' name='password'>
         <INPUT type='submit' value='ログイン'>
         </FORM></p>-->";
/*
   print "<div style='background:#eeeeee; font-weight: bold;'>【お知らせ】</div><br>";
   print "まだユーザ登録をされていない方は、下記リンク先から登録申請を行ってください。<br><br>";
   print "　●道内高専卒業生で求人検索したい………<a href='user_reg.php'>ユーザ登録申請へのリンク</a><br><br>";
   print "　●企業として求人情報を掲載したい………<a href='com_reg.php'>企業登録申請へのリンク</a>（道内各高専の協力会への会員企業である必要があります）<br><br><br>";
   print "<div style='background:#eeeeee; font-weight: bold;'>【お知らせ】</div><br>";
   $news = file_get_contents("news.txt");
   print $news;
   */
 // }
  //ページフッタを出力します
  print htmlfooter();
?>
</body></html>
