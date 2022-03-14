<?php
/****************************************/
/* パスワードの再発行（管理者→ユーザ）           */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");
?>
<?php
print htmlheader("パスワードの再発行");
if($_GET['com_id']>10000){
    //パスワード変更
    $pw=mt_rand(10000000,99999999);
    $pw_h=password_hash( $pw, PASSWORD_DEFAULT);
    $sql="UPDATE com_tbl SET passwd=\"$pw_h\", upddate=now() WHERE com_id=".$_GET['com_id'];
    //print $sql;
    $rst=mysqli_query($con,$sql);
    //メッセージ
    $com_name=dfirst($con,"com_name","com_tbl","com_id=".$_GET['com_id']);
    $aff=dfirst($con,"aff","com_tbl","com_id=".$_GET['com_id']);
    $contact=dfirst($con,"contact","com_tbl","com_id=".$_GET['com_id']);
    $email=dfirst($con,"email","com_tbl","com_id=".$_GET['com_id']);
    //差出人
    $header = "From: "."info@hokkaido-nct.sakura.ne.jp";
    //件名
    $subject = "登録完了しました【道内4高専卒者向け求人検索サイト】";
    //本文
    $mailbody = "{$com_name} {$aff} {$contact}様\r\n"
      ."　パスワードを再発行しました。\r\n下記のIDと仮パスワードでログインして下さい。\r\n\r\n"
      ."ユーザID：".$_GET['com_id']."\r\n仮パスワード：$pw \r\n\r\n"
      ."　仮パスワードはすぐ変更するようお願いします。\r\n"
      ."　また、この度のお取扱いに関し、ご不明な点がございましたら、info@hokkaido-nct.sakura.ne.jp までご連絡をお願いいたします。\r\n\r\n"
      ."　道内高専卒者向け求人検索システム　管理者\r\nログイン画面 https://hokkaido-nct.sakura.ne.jp/uiturn/com/input_login.php \r\n\r\n";
    mb_send_mail($email,$subject,$mailbody,$header);
    //画面表示
    print "下記のメールを{$email}あてに送付しました。<p>".nl2br($mailbody)."</p>";
}
?>
<h2>パスワードの再発行</h2>
<p>パスワードの再発行をする企業を下記セレクトボックスから選択し、「再発行」ボタンを押してください。</p>
<form name='selectcom' action='passwd_resend.php' method='get'>
<select name='com_id'><option value=''>--企業名を選択--</option>
<?php
$sql = "SELECT com_id,com_name FROM com_tbl WHERE com_name NOT LIKE \"dummy\" ORDER BY com_kana";
$rst = mysqli_query($con,$sql);
while($col = mysqli_fetch_assoc($rst)){
    print "<option value='{$col['com_id']}'>{$col['com_name']}</option>";
}
?>
</select>
<input type='submit' value='再発行'>
</form>
<?php
  print htmlfooter();
?>
