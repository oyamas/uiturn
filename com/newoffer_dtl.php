<?php 
/****************************************/
/* 【新卒】データ詳細表示ページ                 */
/****************************************/
  session_start();
  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //MySQL読み込み時の文字コードを設定
  mysql_query("set names utf8");
  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);
  $com_id = $_SESSION['com_id'];
  $newoffer_id = $_GET['newoffer_id'];
  //com_tblテーブルから該当する企業データを取得
  $sql = "SELECT com_tbl.*, ctg_name, pref_name FROM com_tbl
          RIGHT JOIN ctg_tbl ON com_tbl.ctg_id = ctg_tbl.ctg_id
          RIGHT JOIN pref ON com_tbl.pref_id = pref.pref_id
	      WHERE com_id=$com_id";
  $rst = mysql_query($sql, $con);
  $col = mysql_fetch_array($rst);
  //ページヘッダを出力します
  print htmlheader("企業データ詳細");
?>
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
<?php
  //newoffersheetテーブルから新卒求人情報を取得
  $sql = "SELECT * FROM newoffersheet
           WHERE com_id = $com_id and newoffer_id = $newoffer_id";
  $rst = mysql_query($sql,$con);
  if(!$rst){
    //求人票が無かったときの処理
    print "<TR>
      <TD colspan = '2'>この企業の求人情報は登録されていません。</TD>
     </TR></TABLE>".htmlfooter();
    exit();
  }
  $col = mysql_fetch_array($rst);
  $year = $col[year];
?>
  <br><TABLE class='formtable'>
    <TR>
     <Th colspan='2'>求人情報　　求人ID：<?php echo $col[newoffer_id];?></Th>
    </TR>
    <TR>
     <TH width='100'>情報更新日</TH>
     <TD width='500'><?php echo $col[upddate]?></TD>
    </TR>
      <TR>
       <TH>募集職種<BR></th>
       <TD><?php echo nl2br($col[work]) ?></TD>
      </TR>
      <TR>
       <TH>初任給<BR>(月額)</th>
       <TD>本科卒……<?php echo $col[income] ?><br>
           専攻科卒…<?php echo $col[income_s]?></TD>
      </TR>
      <TR>
       <TH>募集対象分野</TH>
       <TD>
<?php
    //求人票内の募集学科を取得
    $sql = "SELECT dept_id FROM newoffer_dept
   		    WHERE newoffer_id = $newoffer_id ORDER BY dept_id";
    $rst2 = mysql_query($sql,$con);
    //募集学科をdept[]に保存
    $dept[0] = 0;
    if($rst2){
      $i=0;
  	  while($col2 = mysql_fetch_array($rst2)){
  	    $dept[$i] = $col2[dept_id];
		$i++;
  	  }
    }
    //結果セットを破棄します
    mysql_free_result($rst2);
    //全ての学科名を取得
    $rst2 = mysql_query("SELECT * FROM dept_tbl ORDER BY dept_id",$con);
    $i=0; $j=0;
    //専攻表示(募集対象専攻は黒色、非対象専攻は灰色)
    while($col2 = mysql_fetch_array($rst2)){
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
    mysql_free_result($rst2);
?>
   </TD>
  </TR>
  <TR>
   <TH>勤務地</TH>
   <TD>
<?php
    //求人票内の勤務地を取得
    $sql = "SELECT wplc_id FROM newoffer_wplc
           WHERE newoffer_id =$newoffer_id ORDER BY wplc_id";
    $rst2 = mysql_query($sql,$con);
    //勤務地をsel_wplc[]に保存
    $sel_wplc[0] = 0;
    if($rst){
      $i=0;
 	  while($col2 = mysql_fetch_array($rst2)){
	   	$sel_wplc[$i] = $col2[wplc_id];
		$i++;
	  }
    }
    //結果セットを破棄します
    mysql_free_result($rst2);
    
    $sql = "SELECT * FROM wplc_tbl ORDER BY wplc_id";
    $rst2 = mysql_query($sql,$con);
    $i=0; $j=0;
    //募集勤務地表示(募集勤務地は黒、非募集勤務地は灰色)
    while($col2=mysql_fetch_array($rst2)){
	  if($col2[wplc_id]==$sel_wplc[$j]){
		print "$col2[wplc_name]　";
		$j++;
	  } else {
		print "<SPAN class = nocheck> $col2[wplc_name] </SPAN>　";
	  }
	  $i++;
      if($i==9) print "<br>";
    }
    //結果セットを破棄します
    mysql_free_result($rst2);
    mysql_free_result($rst);
?>
  </TD>
      </TR>
      <TR>
       <TH>備考</TH>
       <TD><?php echo nl2br($col[remarks])?></TD>
    </TR>
  <TR><TH>PDFファイル</TH><TD>
<?php
    //PDFが存在すればリンク表示
    if(strlen($col[pdf_file])>0){
      print "<a href='../pdf_file/$col[pdf_file]' target='_blank'>求人票PDFファイル</a>";
    } else {
      print "この求人票のPDFファイルはありません。";
    }
?>
  </TD>
  </TABLE>
  <br><br>
   <FORM name='mainfrm' enctype='multipart/form-data'>
   <INPUT type='button' value='このウィンドウを閉じる' Onclick='window.close()'>
   </FORM>
<?php
  //ページフッタを出力します
  print htmlfooter();

?>
