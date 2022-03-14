<?php
/****************************************/
/* 管理者ユーザ登録・更新実行ページ     */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");


  if(strlen($_POST['name'])==0){
    //直リン禁止
    print htmlheader("アクセスエラー");
    print "<br><br><hr><DIV class='largefont' align='center'>
     エラー<br>入力値が不正です。</DIV><hr><br>
    <FORM name='error' action='index.php' method='GET'>
    <div align='center'>下のボタンを押してトップ画面に戻ってください。<br><br>
      <INPUT type='submit' value='トップ画面へ'>
      </FORM></div>";
    exit();
  }
  if($_POST['proc']=='del'){
   print htmlheader("ユーザ情報の削除");
   //ユーザ削除処理
   $sql = "DELETE FROM user_tbl WHERE user_id=".$_POST['user_id'];
   $rst = mysqli_query($con,$sql);
   if($rst) print "ユーザ情報を削除しました。<br>"."氏名：".$_POST['name']."<br><br>";
     else print "ユーザ情報の削除に失敗しました<br>"."氏名：".$_POST['name']."<br><br>";     
   print "<INPUT type='button' value='管理者ホームへ戻る' onclick=\"location.href='index.php'\">";
   print htmlfooter();
   exit();
  } else if($_POST['proc']=='update'){
    print htmlheader("ユーザ情報の更新");
 /*   $sql="UPDATE user_tbl SET "
     ."name = \"".$_POST['name']."\""
     .",kana = \"".$_POST['kana']."\""
     .",postal = \"".$_POST['postal']."\""
     .",address = \"".$_POST['address']."\""
     .",tel = \"".$_POST['tel']."\""
     .",email = \"".$_POST['email']."\""
     .",dept_id = ".$_POST['dept_id']
     .",gengo = \"".$_POST['gengo']."\""
     .",year = ".$_POST['year']
     .",bikou = \"".$_POST['bikou']."\""
     ." WHERE user_id = ".$_POST['user_id'];
 */
    $sql="UPDATE user_tbl SET "
     ."name = \"".$_POST['name']."\""
     .",kana = \"".$_POST['kana']."\""
     .",email = \"".$_POST['email']."\""
     .",dept_id = ".$_POST['dept_id']
     .",year = ".$_POST['year']
     .",bikou = \"".$_POST['bikou']."\""
     ." WHERE user_id = ".$_POST['user_id'];
   $rst = mysqli_query($con,$sql);
   if($rst) print "ユーザ情報を更新しました。<br>"."氏名：".$_POST['name']."<br><br>";
     else print "ユーザ情報の更新に失敗しました<br>"."氏名：".$_POST['name']."<br><br>";
   print "<INPUT type='button' value='管理者ホームへ戻る' onclick=\"location.href='index.php'\">";
   print htmlfooter();
 }
?>

