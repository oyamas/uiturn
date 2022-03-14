<?php
/****************************************/
/*　パスワード変更ページ               */
/****************************************/

  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  mysql_query("set names utf8");
  $selectdb = mysql_select_db($DBNAME, $con);
  print htmlheader("パスワードの変更");
  $err = 0;
  //セッションを開始します
  session_start();
  if((strlen($_POST['password1'])>0)&&(strlen($_POST['password2'])>0)) {
    //現在のパスワードが入力されたとき
    if(strlen($_SESSION['org'])>0){
	  //DBからパスワード抽出し比較
      $sql="SELECT passwd FROM adm_tbl WHERE adm_id =".$_SESSION['adm_id'];
      $rst=mysql_query($sql,$con);
      $col=mysql_fetch_array($rst);
      if($_POST['password1']!=$col['passwd']){
        debugprint("現在のパスワードが違います。もう一度入力してください<br>");
        $err = 1;
      } else if($_POST['password2']==$_POST['password3']){
        //ユーザ名に対応したパスワードを更新
  	    $sql= "UPDATE adm_tbl SET
               passwd = \"".$_POST['password2']."\"
               WHERE adm_id=".$_SESSION['adm_id'];
	    $rst = mysql_query($sql,$con);
	    if($rst){
			print "パスワードが更新されました。<br>";
        } else {
            print "パスワードの更新に失敗しました。もう一度入力してください。<BR><BR>";
            $err = 1;
        }
      } else {
        debugprint("新しいパスワードが確認入力内容と一致しません。もう一度入力してください。<BR>");
        $err = 1;
      }
    } else {
       debugprint("パスワードが未入力です。もう一度入力してください。<BR><BR>");
       $err = 1;
    }
  } else {
    //パスワード入力フォームを表示します
    print "新旧パスワードを入力して[送信]ボタンをクリックしてください。
            <BR><BR>";
    $err = 1;
  }
  if($err==1){
    print" <FORM action='$PHP_SELF' method='POST'>
     ユーザ名  <b>".$_SESSION['org'].
    "</b>&nbsp;&nbsp;&nbsp;ユーザID  <b>".$_SESSION['adm_id']."</b><br><br>
                現在のパスワード<br>
                <INPUT size='20' type='password' name='password1'><br><br>
                新しいパスワード<br>
                <INPUT size='20' type='password' name='password2'><br><br>
                新しいパスワードをもう一度入力してください。<br>
                <INPUT size='20' type='password' name='password3'><br><br>
                                <INPUT type='submit' value='送　信'>";
  }
  print "<BR><BR><BR><BR><INPUT type='button' value='トップページに戻る'
                        onclick='location.href=\"index.php\"'></FORM>";

  //ページフッタを出力します
  print htmlfooter();

?>
