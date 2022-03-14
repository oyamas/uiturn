<?php //@include_once("../w3a/writelog.php");
/****************************************/
/* データ詳細表示ページ                 */
/****************************************/

  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  session_start();
  $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
  mysqli_query($con,"set names utf8");
  //  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //  mysql_query("set names utf8");
  //  $selectdb = mysql_select_db($DBNAME, $con);

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
  //ページヘッダを出力します
  print htmlheader("企業データ詳細");
  $com_id = $_SESSION['com_id'];
  //com_tblテーブルから該当する企業データを取得
  $sql = "SELECT com_tbl.*, ctg_name, pref_name FROM com_tbl
          RIGHT JOIN ctg_tbl ON com_tbl.ctg_id = ctg_tbl.ctg_id
          RIGHT JOIN pref ON com_tbl.pref_id = pref.pref_id
	      WHERE com_id=$com_id";
  $rst = mysqli_query($con,$sql);
  $col = mysqli_fetch_array($rst);
  //offersheetテーブルから求人情報を取得
  $sql = "SELECT * FROM offersheet
           WHERE offersheet.com_id = $com_id";
  $rst0 = mysqli_query($con,$sql);
  $col0 = mysqli_fetch_array($rst0);
  //求人票が無かったときの処理
  if(!$col0){
    print "この企業の求人情報は登録されていませんので、表示できません。<br><br><br>
      <a href='index.php'>トップページに戻る</a>".htmlfooter();
    exit();
  }
  $offer_id = $col0[offer_id];
  $year = $col[year];

?>
ご登録いただいた内容は、閲覧者には下記のように表示されています。<br><br>

●検索一覧表示画面（※他社の一覧が他の行にも連なっている状態で表示されます。詳細ボタンは押せません）<br><br>
<TABLE class='list'><TR>
      <TH>No.</TH><TH>会社名</TH><TH>業務内容</TH>
      <TH>募集職種</TH><TH>更新日</TH><TH>求人有効期限</TH><TH>募集対象専攻</TH><TH>PDF資料</TH><TH></TH></tr>
<?php
    print "<TR>
      <TD width='70' align='right'> $com_id </TD>
      <TD width='150' align='center'> $col[com_name] </TD>
      <TD width='220'> $col[business] </TD>
	  <TD width='220'> $col0[work] </TD>
      <TD width='80' align='center'> $col0[upddate] </TD>
      <TD width='80' align='center'> $col0[available] </TD>
      <TD>";
    
    $dept = "";
    $sql = "SELECT dept_sbl,dept_tbl.dept_id FROM dept_tbl
            INNER JOIN offer_dept ON dept_tbl.dept_id = offer_dept.dept_id
            WHERE offer_id = $offer_id ORDER BY dept_tbl.dept_id";
    $rst2 = mysqli_query($con,$sql);
    while($col2 = mysqli_fetch_array($rst2))
     $dept.=$col2[dept_sbl];
    print $dept ."</TD><TD>";
    //PDFが存在すればリンク表示
    if(strlen($col0[pdf_file])>0){
      print "<a href='../pdf_file/$col0[pdf_file]' target='_blank'>"
        ."<img src='../fig/pdf.gif'></a>";
    }
    print "</td><TD><INPUT type='button' value='詳細'></TD></TR>";
?>
</table><br><br><br>
●求人情報詳細表示画面（上記の詳細ボタンを押すと表示されます）<br><br>

 <TABLE class='formtable'>
    <TR>
      <TH colspan='2'>企業情報</TH>
    </TR>
    <TR>
     <TH width='100'>情報更新日 </TH>
     <TD width='500'><?php echo $col[upddate]?></TD>
    </TR>
    <TR> 
	 <TH>企業ID番号</TH>
     <TD><b><?php echo $col[com_id]?></b> </TD>
    </TR>
    <TR>
     <TH>企業名<BR></TH>
     <TD><?php echo $col[com_name]?></TD>
    </TR>
    <TR>
     <TH>フリガナ<BR></TH>
     <TD><?php echo $col[com_kana]?></TD>
    </TR>
    <TR>
     <TH>業務内容</TH>
     <TD><?php echo nl2br($col[business])?></TD>
    </TR>
    <TR>
     <TH>業種</TH>
     <TD><?php echo $col[ctg_name]?></TD>
    </TR>
    <TR>
     <TH>資本金</TH>
     <TD>
<?php
  if($col[capital]>=10000) echo floor($col[capital]/10000)."億";
  if(($col[capital]%10000)==0) echo "円";
   else echo ($col[capital]%10000)."万円";
?>
     </TD>
    </TR>
    <TR>
     <TH>従業員数</TH>
     <TD><?php echo $col[worknum]?></TD>
    </TR>
    <TR>
     <th colspan='2'>本社</Th>
    </TR>
    <TR>
     <TH>郵便番号</TH>
     <TD><?php echo $col[zipcode]?></TD>
    </TR>
    <TR>
     <TH>所在地</TH>
     <TD><?php echo $col[pref_name].$col[address]?></TD>
    </TR>
    <TR>
     <TH>代表電話番号</TH>
     <TD><?php echo $col[tel]?></TD>
    </TR>
    <TR>
     <TH>ホームページ</TH>
     <TD><a href="<?php echo $col[url]?>" target='_blank'>
                 <?php echo $col[url]?></a></TD>
    </TR></table>

  <br><TABLE class='formtable'>
    <TR>
     <Th colspan='2'>求人情報</Th>
    </TR>
    <TR>
     <TH width='100'>情報更新日</TH>
     <TD width='500'><?php echo $col0[upddate]?></TD>
    </TR>
      <TR>
       <TH>募集職種<BR></th>
       <TD><?php echo nl2br($col0[work]) ?></TD>
      </TR>
      <TR>
       <TH>募集学科</TH>
       <TD>
<?php
    //求人票内の募集学科を取得
    $sql = "SELECT dept_id FROM offer_dept
            INNER JOIN offersheet ON offersheet.offer_id = offer_dept.offer_id
   		    WHERE offer_dept.offer_id = $offer_id
            ORDER BY dept_id";
    $rst2 = mysqli_query($con,$sql);
    //募集学科をdept[]に保存
    $dept[0] = 0;
    if($rst2){
      $i=0;
  	  while($col2 = mysqli_fetch_array($rst2)){
  	    $dept[$i] = $col2[dept_id];
		$i++;
  	  }
    }
    //結果セットを破棄します
    mysqli_free_result($rst2);
    //全ての学科名を取得
    $rst2 = mysqli_query($con,"SELECT * FROM dept_tbl ORDER BY dept_id");
    $i=0; $j=0;
    //学科表示(募集学科は黒色、非募集学科は灰色)
    while($col2 = mysqli_fetch_array($rst2)){
	  if($col2[dept_id]==$dept[$j]){
	  	print $col2[dept_name]."　";
	  	$j++;
	  } else {
	  	print "<SPAN class=nocheck>$col2[dept_name]</SPAN>　";
   	  }
	  $i++;
      if($i==5) print "<br>";
    }
    //結果セットを破棄します
    mysqli_free_result($rst2);
?>
   </TD>
  </TR>
  <TR>
   <TH>勤務地</TH>
   <TD>
<?php
    //求人票内の勤務地を取得
    $sql = "SELECT wplc_id FROM offer_wplc
           INNER JOIN offersheet ON offersheet.offer_id = offer_wplc.offer_id
 		   WHERE offer_wplc.offer_id =$offer_id
           ORDER BY wplc_id";
    $rst2 = mysqli_query($con,$sql);
    //勤務地をsel_wplc[]に保存
    $sel_wplc[0] = 0;
    if($rst2){
      $i=0;
 	  while($col2 = mysqli_fetch_array($rst2)){
	   	$sel_wplc[$i] = $col2[wplc_id];
		$i++;
	  }
    }
    //結果セットを破棄します
    mysqli_free_result($rst2);
    $sql = "SELECT * FROM wplc_tbl ORDER BY wplc_id";
    $rst2 = mysqli_query($con,$sql);
    $i=0; $j=0;
    //募集勤務地表示(募集勤務地は黒、非募集勤務地は灰色)
    while($col2=mysqli_fetch_array($rst2)){

	  if($col2[wplc_id]==$sel_wplc[$j]){
		print "$col2[wplc_name]　";
		$j++;
	  } else {
		print "<SPAN class = nocheck> $col2[wplc_name] </SPAN>　";
	  }
	  $i++;
      if($i==6) print "<br>";
    }
    //結果セットを破棄します
    mysqli_free_result($rst2);
    mysqli_free_result($rst);
?>
  </TD>
      </TR>
      <TR>
       <TH>選考方法</TH>
       <TD><?php echo nl2br($col0[recruit])?></TD>
    </TR>
    <TR>
       <TH>求人情報の有効期限</TH>
       <TD><?php echo nl2br($col0[available])?></TD>
    </TR>
      <TR>
       <TH>備考</TH>
       <TD><?php echo nl2br($col0[remarks])?></TD>
    </TR>
  <TR><TH>PDFファイル</TH><TD>
<?php
    //PDFが存在すればリンク表示
    if(strlen($col0[pdf_file])>0){
      print "<a href='../pdf_file/$col0[pdf_file]' target='_blank'>$col0[pdf_file]</a>";
    } else {
      print "この求人票のPDFファイルはありません。";
    }
?>
  </TD>
  </TABLE>
  <br><br>
   <FORM name='mainfrm' action='datamnt.php' method='GET'
         enctype='multipart/form-data'>
   <INPUT type='button' value='このウィンドウを閉じる' Onclick='window.close()'>
   </FORM>

<?php
  //ページフッタを出力します
  print htmlfooter();

?>
