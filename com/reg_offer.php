<?php
/****************************************/
/* 【中途】求人情報登録ページ　　　　　　　　　 */
/****************************************/
  session_start();
  require_once("../ini.php");
  require_once("setting.php");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");
 //  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
//  mysql_query("set names utf8");
//  $selectdb = mysql_select_db($DBNAME, $con);
  print htmlheader("【中途採用】求人情報の登録");
  //ログインチェック
  if ($_SESSION['com_id']==0) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    print "<br><br><hr><DIV class='largefont' align='center'>
     エラー<br>管理者の権限確認がまだされていません</DIV><hr><br>
    <FORM name='error' action='input_login.php' method='GET'>
    <div align='center'>下のボタンを押してログイン画面に戻ってください。<br><br>
      <INPUT type='submit' value='ログイン画面へ'>
      </FORM></div>";
    exit();
  }
?>
<!--

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css" >
-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>


<SCRIPT type="text/javascript" >
  function chBox(ary,check){
   len = document.form1.elements.length;
   for(i=0; i<len; i++)
    if(document.form1.elements[i].name==ary){
      document.form1.elements[i].checked = check;
   }
  }
  function DonaiChBox(ary,max){
   var j=1;
   len = document.form1.elements.length;
   for(var i=0; i<len; i++){
    if(document.form1.elements[i].name==ary){
      document.form1.elements[i].checked = true;
      j++;
      if(j>max) break;
    }
   }
  }
　function regist(){
   document.form1.action = 'reg_offer_chk.php';
   document.form1.target = '_self';
   document.form1.submit();
  }
  function htwinOpen(link){
    htwin = window.open(link,'howto','width=500,height=400,scrollbars=yes');
    htwin.focus();
  }
 function DeleteCheck(offer_id) {
   if(confirm('本当に削除しますか？')){
    document.form1.action = 'reg_offer_exe.php';
    document.form1.offer_id.value = offer_id;
    document.form1.edit.value = 3;
    document.form1.submit();
   }
 }
 </SCRIPT>
<SCRIPT type="text/javascript" >
  $(function() {
    $("#datepicker").datepicker();
  });
</script>


<?php
  //確認ページから戻ってきた場合($_POST[back]==1)→$info=$POST
  if($_POST['back']==1){
    $info = $_POST;
    $edit = $_POST['edit'];
  } else {
    //そうでないときは現在登録されている求人情報をDBから取得
    $sql="SELECT * FROM offersheet WHERE com_id=".$_SESSION['com_id'];
    $rst=mysqli_query($con,$sql);
    if($info=mysqli_fetch_array($rst))
    {
      $edit=2;//edit=2(更新処理)に
      //対象専攻の取得
      $sql="SELECT * FROM offer_dept WHERE offer_id=".$info[offer_id]." ORDER BY dept_id";
      $rst=mysqli_query($con,$sql);
      $i=0;
      while($col=mysqli_fetch_array($rst)){
        $info['sel_dept'][$i]=$col['dept_id'];
        $i++;
      }
      //対象勤務地の取得
      $sql="SELECT * FROM offer_wplc WHERE offer_id=".$info[offer_id]." ORDER BY wplc_id";
      $rst=mysqli_query($con,$sql);
      $i=0;
      while($col=mysqli_fetch_array($rst)){
        $info['sel_wplc'][$i]=$col['wplc_id'];
        $i++;
      }
     } else {
      $edit=1;//未登録時はedit=1(新規登録)に（$infoは空）
     }
  }
?>
 現在登録されている【中途採用者向け】求人情報は以下のとおりです。内容修正後、[登録]ボタンをクリックしてください。<br>
   <span class='empfont'><b>*印の付いた項目は入力必須です。</b></span>
 <br>
<?php
  if($edit==2)
   print "この求人情報を削除する場合は、右の[削除]ボタンを押して下さい。(企業情報は削除されません)
 <INPUT type='button' value='削除' onclick='DeleteCheck($info[offer_id])'>";
?>
   <FORM name='form1' method='POST' enctype='multipart/form-data'>
    <TABLE class='formtable'>
  <Th colspan='2'>求　人　情　報</Th>
  </TR>
  <TR>
  <TH><a name='work'>募集職種</a><br><span class="empfont">*</span></th>
  <TD><span class='remarkfont'><a href='#work' onclick='htwinOpen("howtoinput.html#work")'>募集職種の記入例（全角500字以内）</a></span>
   <TEXTAREA rows='6' cols='80' name='work'><?php echo $info['work'] ?></TEXTAREA>
  </TD>
  </TR>
  <TR>
  <TH>募集対象の専攻<span class="empfont">*</span></TH>
  <TD><span class='remarkfont'>上記募集職種に該当する専攻をすべてチェックして下さい。</span><br>
<?php
  //すべての専攻名を読み込む
  $sql = "SELECT * FROM dept_tbl ORDER BY dept_id";
  $rst = mysqli_query($con,$sql);
  //専攻のオプションメニューを組み立て
   $i = 0;
   while($col=mysqli_fetch_array($rst)) {
    if($col[dept_id]==$info['sel_dept'][$i]){
      print "<INPUT type='checkbox' name='sel_dept[]'
                     value='$col[dept_id]' CHECKED>$col[dept_name]　";
      $i++;
    } else {
      print "<INPUT type='checkbox' name='sel_dept[]'
                     value='$col[dept_id]'>$col[dept_name]　";
    }
   }
  //学科全てチェック／解除するボタンの作成
?>
 <br>
 <INPUT type='button' value='全てチェック' onClick='chBox("sel_dept[]",true)'>
 <INPUT type='button' value='チェック解除' onClick='chBox("sel_dept[]",false)'>
 </TD>
 </TR>
  <TR>
  <TH><a name='district'>勤務地</a><span class="empfont">*</span></TH>
  <TD><span class='remarkfont'>上記募集職種に該当する勤務予定地を全てチェックして下さい。
  <a href='#district' onclick='htwinOpen("howtoinput.html#district")'>勤務地の区分について</a></span><br>
<?php
  //すべての勤務地を読み込む
  $sql = "SELECT * FROM wplc_tbl ORDER BY wplc_id";
  $rst = mysqli_query($con,$sql);
  //勤務地のオプションメニューを組み立てます
  $i=0; $j=0;
  while($col=mysqli_fetch_array($rst)) {
    if($col[wplc_id]==$info['sel_wplc'][$i]){
      print "<INPUT type='checkbox' name='sel_wplc[]'
                      value='$col[wplc_id]' CHECKED>$col[wplc_name]　";
      $i++;
    } else{
      print "<INPUT type='checkbox' name='sel_wplc[]'
                      value='$col[wplc_id]' >$col[wplc_name]　";
    }
    $j++;
	if($j==9) print "<br>";
  }
  mysqli_free_result($rst);
  //勤務地全てチェック／解除するボタンの作成
?>
  <br>
<INPUT type='button' value='道内全てチェック' onClick='DonaiChBox("sel_wplc[]",4)'>
  <INPUT type='button' value='全てチェック' onClick='chBox("sel_wplc[]",true)'>
  <INPUT type='button' value='チェック解除' onClick='chBox("sel_wplc[]",false)'>
  </TD>
  </TR>
  <TR>
  <TH>選考方法</TH>
  <TD><font class='remarkfont'>採用選考方法（書類選考、面接実施の方法）および締切など選考に関わる情報を記入してください。（全角500字以内）。</font><br>
  <TEXTAREA rows='6' cols='80' name='recruit'><?php print $info['recruit'] ?></TEXTAREA>
  </TD>
  </TR>
  <tr>
  <th>この求人情報の有効期限</th>
  <td><input type='text' id='datepicker' name='available' value="<?= $info['available'] ?>">
  </td>
  <TR>
  <TH>求人に関する<br>備考</TH>
  <TD><font class='remarkfont'>応募の条件（資格や知識）や勤務に関する特記事項など、参考となるデータがあれば記入してください（全角500字以内）。</font><br>
  <TEXTAREA rows='6' cols='80' name='remarks'><?php print $info['remarks'] ?></TEXTAREA>
  </TD>
  </TR>
  <tr>
  <th>求人書類のPDFファイル</th>
  <td>
<?php 
  if($info['pdf_file']!="")
    print "<a href='../pdf_file/".$info['pdf_file']."' target='_blank'>".$info['pdf_file']."</a>";
   else
    print "登録されていません";
?>
  <br>※変更する場合は次ページで
  <input type='hidden' name='pdf_file' value='<?php print $info['pdf_file'];?>'>
  </td>
  </tr>
  <TR>
  <TD colspan='2' align='center'>
  <INPUT type='button' name='reg' value='  登録  ' Onclick='regist()'>
  <INPUT type='button' value='  クリア  '
    Onclick='window.location="reg_offer.php"'>
  <INPUT type='hidden' name='offer_id'>
  <INPUT type='hidden' name='edit' value='<?php print $edit ?>'>
  </TD>
  </TR>
  </TABLE>
  </FORM>
<?php
 //MySQLとの接続を解除します
 $con = mysqli_close($con);
 //リロード対策のため一連の更新処理に対しセッションに値を設定
 $_SESSION['regoffer']=1;
 //ページフッタを出力します
 print htmlfooter();
?>
