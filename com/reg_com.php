<?php
/****************************************/
/* 企業情報更新ページ　　　　　　　　　 */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");

  //$con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //mysql_query("set names utf8");
  //$selectdb = mysql_select_db($DBNAME, $con);
  print htmlheader("企業情報の登録");
  //ログインチェック
  if ($_SESSION['com_id']==0) {
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
<SCRIPT language='javascript'>
　function regist(){
   document.newcom.action = 'reg_com_chk.php';
   document.newcom.target = '_self';
   document.newcom.submit();
  }
  function htwinOpen(link){
    htwin = window.open(link,'howto','width=500,height=400,scrollbars=yes');
    htwin.focus();
  }
</script>
<?php
  //確認ページから戻ってきた場合($_POST[back]==1)→$info=$REQUEST
  if($_POST['back']==1){
    $info = $_POST;
  } else {
    //そうでないときは現在登録されている企業情報をDBから取得
    $sql="SELECT * FROM com_tbl WHERE com_id=".$_SESSION['com_id'];
    $rst=mysqli_query($con,$sql);
    $info=mysqli_fetch_array($rst);
  }
?>
 現在登録されている企業情報は以下のとおりです。内容修正後、[登録]ボタンをクリックしてください。
   <span class='empfont'><b>*印の付いた項目は入力必須です。</b></span>
   <FORM name='newcom' method='POST' enctype='multipart/form-data'>
    <TABLE class='formtable'>
   <TR>
      <TH colspan='2'>企　業　情　報</TH>
     </TR>
    <TR>
     <TH>企業名 <span class='empfont'>*</span><BR></TH>
     <TD><span class='remarkfont'>株式会社→(株)のように略記して下さい。</span><br>
     <INPUT type='text' name='com_name' size='60'
              value='<?php echo $info['com_name'] ?>'>
     </TD>
    </TR>
    <TR>
     <TH>企業名<br>フリガナ <span class='empfont'>*</span><BR></TH>
     <TD><span class='remarkfont'>中点「・」などの記号は不要。<br>株式会社・有限会社等のフリガナは入れないで下さい。</span><br>
     <INPUT type='text' name='com_kana' size='80'
            value='<?php echo $info['com_kana'] ?>'><br>
     </TD>
    </TR>
    <tr>
     <th>担当者所属</th>
     <td>
     <INPUT type='text' name='aff' size='80'
            value='<?php echo $info['aff'] ?>'><br>
     </td>
    </tr>
    <tr>
     <th>担当者氏名<span class='empfont'>*</span></th>
     <td>
     <INPUT type='text' name='contact' size='80'
            value='<?php echo $info['contact'] ?>'><br>
     </td>
    </tr>
    <TR>
     <TH>業種 <span class='empfont'>*</span></TH>
     <TD>
<?php
  //すべての業種を読み込むSQLを組み立てます
  $sql = "SELECT * FROM ctg_tbl ORDER BY ctg_id";
  $rst = mysqli_query($con,$sql);
  //業種のオプションメニューを組み立てます
  print "<SELECT name='ctg_id'>
    <OPTION value='0'>--業種を選択してください--</OPTION>";
  while ($col = mysqli_fetch_array($rst)) {
   if($col[ctg_id]==$info[ctg_id])
     print "<OPTION value='$col[ctg_id]' SELECTED>$col[ctg_name]</OPTION>";
    else 
     print "<OPTION value='$col[ctg_id]'>$col[ctg_name]</OPTION>";
  }
  //結果セットを破棄
  mysqli_free_result($rst);
?>
 </SELECT>
  </TD>
  </TR>
  <TR>
   <TH>業務内容<br></th>
   <TD>
    <span class='remarkfont'>(全角400字以内)</span><br>
      <TEXTAREA rows='4' cols='80' name='business'><?php echo $info['business'] ?></TEXTAREA>
  </TD>
  </TR>
  <TR>
  <TH>資本金</TH> <TD>
  <INPUT type='text' name='capital' size='10'
             value='<?php echo $info['capital'] ?>'>万円&nbsp;&nbsp;&nbsp;
  <span class='remarkfont'>1万円未満の入力は不要です。</span>
  </TD>
  </TR>
  <TR>
  <TH>従業員数</TH>
  <TD>
  <INPUT type='text' name='worknum' size='10'
             value='<?php echo $info['worknum'] ?>'>名
  </TD>
  </TR>
  <TR>
  <TH>郵便番号</TH>
  <TD>
  <INPUT type='text' name='zipcode' size='9'
             value='<?php echo $info['zipcode'] ?>'>
  <span class='remarkfont'>&nbsp;(半角数字ハイフン付、"042-8501"の形式で入力)</span>
  </TD>
  </TR>
  <TR>
  <TH>所在地 <span class='empfont'>*</span></TH>
  <TD><SELECT name='pref_id'>
<?php
  //都道府県名を読み込み
  $sql = "SELECT * FROM pref WHERE 1 ORDER BY pref_id";
  $rst = mysqli_query($con,$sql);
  while($col = mysqli_fetch_array($rst)){
   if($col[pref_id]==$info['pref_id']){
    print "<OPTION value='$col[pref_id]' SELECTED>$col[pref_name]</OPTION>";
    $SEL_PREF = 0;
   } else if($col[pref_id]==$SEL_PREF)
       print "<OPTION value='$col[pref_id]' SELECTED>$col[pref_name]</OPTION>";
      else 
       print " <OPTION value='$col[pref_id]'>$col[pref_name]</OPTION>";
  }
  //結果セットを破棄
  mysqli_free_result($rst);
?>
  </SELECT>　以下に区市町村名以降を記入
   <INPUT type='text' name='address' size='100' value='<?php echo $info['address'] ?>'>
  </TD>
  </TR>
  <TR>
  <TH>代表電話番号</TH>
  <TD>
  <INPUT type='text' name='tel' size='15' value='<?php echo $info['tel'] ?>'>
    <span class='remarkfont'>&nbsp;(半角数字ハイフン付、"0138-12-3456"の形式で入力)</span>
  </TD>
  </TR>
  <TR>
  <TH>ホームページ<br>アドレス</TH>
   <TD>
<?php
  if(strlen($info['url'])>0){
    print "<INPUT type='text' name='url' size='70' value='".$info['url']."'>";
  } else {
    print "<INPUT type='text' name='url' size='70' value='http://'>";
  }
?>
  </TD>
   </TR>
  <TR>
  <TH>Eメールアドレス</TH>
   <TD>
<?php
    print "<INPUT type='text' name='email' size='70' value='".$info['email']."'>";
?>
  </TD>
   </TR>  <TR>
  <TD colspan='2' align='center'>
  <INPUT type='button' name='reg' value='  登録  ' Onclick='regist()'>
  <INPUT type='button' value='  クリア  '
    Onclick='window.location="reg_com.php"'>
  </TD>
  </TR>
  </TABLE>
  </FORM>
<?php
 //MySQLとの接続を解除します
 $con = mysqli_close($con);
 //ページフッタを出力します
 print htmlfooter();
?>
