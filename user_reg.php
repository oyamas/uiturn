<?php
/****************************************/
/* 新規ユーザ登録ページ           */
/****************************************/
  session_start();
  require_once("ini.php");
  require_once("setting.php");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");
  //学科をJavascript配列に格納するための処理
  $sql="select * from college_dept where 1 order by college_id, dept_id";
  $rst= mysqli_query($con,$sql);
  $college_id=0;
  while($col=mysqli_fetch_array($rst)){
    if($college_id!=$col['college_id']){
      $college_id = $col['college_id'];
      $dept_id[$college_id] = "";
    }
    $dept_id[$college_id] .= $col['dept_id'].",";
    $dept[$college_id] .= "\"".$col['name']."\",";
  }
?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
   <HTML>
   <HEAD>
   <META http-equiv='Content-Type' content='text/html; charset=utf-8'>
   <META http-equiv='Content-Style-Type' content='text/css'>
   <TITLE>求人情報検索 - 新規ユーザ登録</TITLE>
    <LINK rel='stylesheet' href='top.css' type='text/css'>
    <script type="text/javascript">
    dept = new Array(5);
    dept_id = new Array(5);
 <?php 
    for($i=1;$i<=$college_id;$i++){
      print "dept_id[$i] = new Array(".substr($dept_id[$i],0,strlen($dept_id[$i])-1).");\r\n";
      print "dept[$i] = new Array(".mb_substr($dept[$i],0,strlen($dept[$i])-1).");\r\n";
    }
 ?>
    // 選択ボックスに選択肢を追加する関数
    //	引数: ( selectオブジェクト, value値, text値)
    function addSelOption( selObj, myValue, myText )
    {
        selObj.length++;
        selObj.options[ selObj.length - 1].value = myValue ;
        selObj.options[ selObj.length - 1].text  = myText;
 
    }
    //	選択リストを作る関数 
    //	引数: ( selectオブジェクト, 見出し, value値配列 , text値配列 )
    function createSelection( selObj, midashi, aryValue, aryText )
    {
        selObj.length = 0;
        addSelOption( selObj, 0, midashi);
        // 初期化
        for( var i=0; i < aryValue.length; i++)
        {
            addSelOption ( selObj , aryValue[i], aryText[i]);
        }
    }
    function dept_select(obj)
    {
        // 選択肢を動的に生成
        createSelection(regform.elements['dept'], "--学科選択してください--", dept_id[obj.value], dept[obj.value]);
 
    }
 </script>
 </HEAD>
<?php
   if($_POST['college_id']>0)
      print "<body onload='createSelection(regform.elements[\"dept\"], \"--学科選択してください--\", dept_id[".$_POST['college_id']."], dept[".$_POST['college_id']."])'>";
    else 
      print "<body>";
?>
    <TABLE border='0' cellpadding='0' cellspacing='0' width='100%'>
    <TR>
     <TD class='maintitle1'>求人情報検索ページ</TD>
     <TD class='maintitle2'>道内高専卒者向け中途求人検索システム</TD>
    </TR>
    <TR>
     <TD class='pagetitle'>新規ユーザ登録</TD><TD class='tohomelink'><A href='user_login.php'>ログイン</a>&nbsp;<A href='index.php'>検索TOP</A></TD>
              </TR></TABLE><DIV class='maincontents'><BR>
  下記の登録内容を入力し、送信ボタンを押してください。<BR><BR>
  <FORM name='regform' action='user_reg_chk.php' method='POST'>
<p>氏名
　<INPUT size='20' type='text' name='name' value='<?=$_POST['name']?>'></p>
<p>氏名ふりがな
　<INPUT size='20' type='text' name='kana' value='<?=$_POST['kana']?>'></p>
<!--
<p>郵 便 番 号
　<INPUT size='10' type='text' name='postal' value='<?=$_POST['postal']?>'></p>
<p>住所
　<INPUT size='50' type='text' name='address' value='<?=$_POST['address']?>'></p>
 電 話 番 号
　<INPUT size='20' type='text' name='tel' value='<?=$_POST['tel']?>'><br>
-->
<p>電子メールアドレス
　<INPUT size='40' type='text' name='email' value='<?=$_POST['email']?>'></p>
<p>出身校
 　<select name='college_id' onchange='dept_select(this)'><option value='0'>--選択してください--</option>


<?php
 $sql="SELECT * FROM college WHERE 1 ORDER BY college_id";
 $rst=mysqli_query($con,$sql);
 while($col=mysqli_fetch_array($rst)){
   print "<option value='".$col["college_id"]."'";
   if($_POST['college_id']==$col['college_id']) print " SELECTED ";
   print " >".$col["name"]."</option>";
 }
?></select></p>
<p>出身学科　<select name="dept"><option value="">------</option></select></p>
<p>
 卒業年度(西暦)　
 <!--
<input type='radio' name='gengo' value='s'
<?php if($_POST['gengo']=='s') print "CHECKED";?>
>昭和
<input type='radio' name='gengo' value='h'
<?php if($_POST['gengo']=='h') print "CHECKED";?>
>平成
 -->
<INPUT size='5' type='text' name='year' value='<?=$_POST['year']?>'>

年度<br>※変換式・・・・昭和x年度＝1925+x、平成y年度＝1988+y、令和z年度＝2018+z
<!-- ※元年　の場合は「1」と入力して下さい。--></p>
<p>備考
　<INPUT size='50' type='text' name='bikou' value='<?=$_POST['bikou']?>'></p>

 <INPUT type='submit' value='確認画面へ'>
 </FORM>

※ご登録内容を確認後、追ってIDとパスワードについて連絡差し上げます。<br>
※いただいた個人情報は、本サイトへの利用者の特定のみに利用し、無断で他の目的に使用いたしません。

<?php
  print htmlfooter();
?>
