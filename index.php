<?php
 session_start();
/****************************************/
/* トップページ                         */
/****************************************/
 require_once("ini.php");
 require_once("setting.php");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");
 //$selectdb = mysqli_select_db($DBNAME, $con);
 //ログインチェック
 if ($_SESSION['user_id']==0) {
    header("Location: user_login.php");
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print htmlheader("ログインエラー");
    print "<br><br><hr><DIV class='largefont' align='center'>
     エラー<br>ログインしていません</DIV><hr><br>
    <FORM name='error' action='user_login.php' method='GET'>
    <div align='center'>下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
      <INPUT type='hidden' name='id' value='1'>
      </FORM></div>";
    exit();
 }

 //現在の登録件数を調べる
 $rst=mysqli_query($con,"SELECT Count(*) FROM offersheet WHERE delflag=0");
 $col1=mysqli_fetch_array($rst);
 $rst=mysqli_query($con,"SELECT year, Count(*) FROM newoffersheet GROUP BY year");
 print htmlheader("トップページ");
?>

<DIV class='maincontents'>
<br>
このサイトでは、北海道内4高専卒業生対象に寄せられている求人情報を検索することができます。<br>
<DIV class='maincontents'> <br><br>
 <A href='search/search.php'>
 <img src='fig/button2.gif' align='center'>
 【高専卒対象】求人情報について検索する</A><br>
<?php
 print "－登録件数：".$col1["Count(*)"]."件<br><br>";
?>
  <A href='search/user_passwd.php'><img src='fig/button3.gif' align='center'>
パスワードの変更</A><br><br>
 <A href='search/user_update.php'> <img src='fig/button1.gif' align='center'>
 メールアドレスなど登録情報の確認・変更</A><br><br>
<p>【個人情報の取り扱いについて】<br>
2020年5月9日以前にお申込みされた方につきましては、住所と電話番号の入力をお願いしておりましたが、本システムでの個人情報の取り扱いを最小限に抑えることとし、メールアドレスのみ保管させていただきます。<br>
今までいただいた住所、電話番号の情報は、こちらで責任をもって消去いたします。</p>
</div>
<?php print htmlfooter();?>
</html>
