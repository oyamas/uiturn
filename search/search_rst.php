<?php //@include_once("../w3a/writelog.php");
/****************************************/
/* 検索結果ページ（求人検索）           */
/****************************************/

  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
  login_check();
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");

  //WHERE条件の初期化
  $where = " WHERE (offersheet.delflag=0) and ";
  //JOIN条件の初期化
  $join = " INNER JOIN com_tbl ON offersheet.com_id = com_tbl.com_id
            INNER JOIN ctg_tbl ON com_tbl.ctg_id = ctg_tbl.ctg_id";
  //リンク用にソート順($seq)がdescなら$aseq=asc、ascなら$aseq=descに
  $seq = $_GET['seq'];
  if($seq=='desc') $aseq = 'asc';
    else $aseq = 'desc';
  //ソートキーワードの処理
  switch ($_GET['sort']){
    case 'id':
	  $orderby = " ORDER BY com_id ".$seq;
      $sort = "id";
	  break;
    case 'com':
	  $orderby = " ORDER BY com_kana ".$seq;
      $sort = "com";
	  break;
    case 'date':
      $orderby = " ORDER BY offersheet.upddate ".$seq;
      $sort = "date";
      break;
  }
  //入力された検索変数の数
  $n = 0;
  //検索キーワードが入力されているか？→where句に追加
  if (isset($_GET['com_name']) and strlen($_GET['com_name']) > 0) {
	$n++;
    //会社名条件追加
    $where .= char_proc("com_name", $_GET['com_name']);
  }
  if (isset($_GET['com_kana']) and strlen($_GET['com_kana']) > 0) {
	//すでにWHERE句が追加されていればandでつなぐ
	if($n>0) $where .= " and ";
	$n++;
    //全角ひらがな、半角カタカナを全角カタカナに変換
    $_GET['com_kana'] = mb_convert_kana($_GET['com_kana'],'KVC');
    //会社カナ条件追加
    $where .= char_proc("com_kana",$_GET['com_kana']);
  }
  if (isset($_GET['business']) and strlen($_GET['business']) > 0) {
	//すでにWHERE句が追加されていればandでつなぐ
	if($n>0) $where .= " and ";
	$n++;
    //業務内容条件追加
    $where .= char_proc("business",$_GET['business']);
  }
  if ($_GET['ctg_id'] > 0)  {
	//すでにWHERE句が追加されていればandでつなぐ
	if($n>0) $where .= " and ";
	$n++;
    //業種名条件追加
	$where .= "(com_tbl.ctg_id=". $_GET['ctg_id'].")";
  }
  if (isset($_GET['work']) and strlen($_GET['work']) > 0) {
	//すでにWHERE句が追加されていればandでつなぐ
	if($n>0) $where .= " and ";
	$n++;
    //募集職種条件追加
    $where .= char_proc("work",$_GET['work']);
  }
  if (isset($_GET['sel_dept'][0])){
	//すでにWHERE句が追加されていればandでつなぐ
	if($n>0) $where .= " and ";
	$n++;
    //募集学科条件追加
    $where .= "((dept_id=". $_GET['sel_dept'][0].")";
    $i=1;
    while($_GET['sel_dept'][$i]!=NULL){
		$where .= " or (dept_id=" . $_GET['sel_dept'][$i].")";
        $i++;
    }
    $where .= ")";
    $join .= " INNER JOIN offer_dept
               ON offersheet.offer_id = offer_dept.offer_id ";
  }
  if (isset($_GET['sel_wplc'][0])){
	//すでにWHERE句が追加されていればandでつなぐ
	if($n>0) $where .= " and ";
	$n++;
    //勤務地条件追加
    $where .= "((wplc_id=". $_GET['sel_wplc'][0].")";
    $i=1;
    while($_GET['sel_wplc'][$i]!=NULL){
		$where .= " or (wplc_id=" . $_GET['sel_wplc'][$i].")";
        $i++;
    }
    $where .= ")";
    $join .= " INNER JOIN offer_wplc
               ON offersheet.offer_id = offer_wplc.offer_id ";
  }

  print htmlheader("検索結果");
  if ($n==0){
	//検索の種類が未指定またはキーワードが空のとき
    print "検索条件が指定されていません。
            <INPUT type='button' value='検索ページへ戻る'
            onclick='window.location=\"search.php\"'>". htmlfooter();
    exit();
  }

  //ページング用のキーワードを作成(page,tcnt,sort,seqは除く)
  $_GET['sort']="";
  $_GET['seq']="";
  $work=$_GET['page'];
  $work2=$_GET['tcnt'];
  $_GET['page']="";
  $_GET['tcnt']="";
  $kw = struct_kw();
  $_GET['page']=$work;
  $_GET['tcnt']=$work2;
?>
  <SCRIPT language='JavaScript'><!--
    function DetailShow(com_id,offer_id) {
      document.mainfrm.action = 'com_dtl.php';
      document.mainfrm.target = '_blank';
      document.mainfrm.com_id.value = com_id;
      document.mainfrm.offer_id.value = offer_id;
      document.mainfrm.submit();
	}
   function htwinOpen(link){
    htwin = window.open(link,'howtorst','width=450,height=400,scrollbars=yes');
    htwin.focus();
   }
// --></SCRIPT>
<?php
  if ($_GET['page']=="") {
    //初めて呼ばれたときは総件数を取得します
    $sql = "SELECT Count(DISTINCT offersheet.offer_id) AS cnt
              FROM offersheet ". $join . $where;
    $rst = mysqli_query($con,$sql);
    $col = mysqli_fetch_array($rst);
    $tcnt = $col['cnt'];
    mysqli_free_result($rst);
    //現在ページを初期設定します
    $page = 1;
    //該当件数をチェックします
    if ($tcnt == 0) {
      //検索がマッチしないとき
      //「戻る」ボタンを表示
      print "ご指定の検索条件に該当する企業はありません。<br>
         検索条件を変えてみてください。<br>
         <INPUT type='button' value='検索ページへ戻る'
            Onclick='location.href=\"search.php?".$kw."\"'>". htmlfooter();
      exit();
    }
  } else {
    //2回目以降に呼ばれた場合は、GET変数からの値を格納
    $page = $_GET['page'];
    $tcnt = $_GET['tcnt'];
  }
  //総ページ数を計算します
  $totalpage = ceil($tcnt / $PAGESIZE);

  //ページ上部の表示を組み立てます
  print "$tcnt 件の求人情報が見つかりました。 "
     . "[" . ($PAGESIZE * ($page - 1) + 1) . "-";
  if ($page < $totalpage) {
    //最終ページより前のページのとき
    print ($PAGESIZE * $page) . "] を表示";
  }
  else {
    //最終ページのとき
    print "$tcnt] を表示";
  }
?>
 <br>
 <font class='smallfont'><a href='#' onclick='htwinOpen("howtorst.html")'>ソート記号<IMG src='../fig/sort_asc.JPG' align='middle'><IMG src='../fig/sort_desc.JPG' align='middle'>の意味</a></font>
  &nbsp &nbsp 
  <INPUT type='button' value='条件を変えて検索'
       Onclick='location.href="search.php?<?php echo $kw ?>"'>
  <div class='pagenavi'>
<?php
  //ページのナビゲーション(テーブル上部)を追加します
  if ($page > 1) {
    //２ページ以降の場合は[前]を表示します
    print "<A href = 'search_rst.php?page=".($page-1)."&tcnt=$tcnt&sort=$sort&seq=$seq$kw'>&lt;前の $PAGESIZE 件</A>&nbsp;&nbsp;&nbsp;";
  }
  if ($totalpage > 1 and $page < $totalpage) {
    //全部で２ページ以上あってかつ現在が最終ページより
    //前のときは[次]を表示します
    print "<A href = 'search_rst.php?page=".($page+1)."&tcnt=$tcnt&sort=$sort&seq=$seq$kw'>次の $PAGESIZE 件&gt;</A>";
  }
/* print "</div>検索した全件データを印刷する：
    <a href='output_list.php?sort=$sort&seq=$seq$kw' target='_blank'>
      縦長(簡易版)</a>
    <a href='output_dlist.php?sort=$sort&seq=$seq$kw' target='_blank'>
      横長(詳細版)</a><br>
    CSV形式で<a href='outcsv.php?set=1$kw'>ファイル出力</a>";
*/
  //１ページ分だけ抽出するSQL文を組み立てます
  $sql = "SELECT DISTINCT offersheet.offer_id, offersheet.com_id, com_name
      , url,pdf_file, ctg_name, business, work, offersheet.upddate, available
      , offersheet.editor_id
      FROM offersheet " . $join . $where . $orderby .
          " LIMIT " . $PAGESIZE * ($page - 1) . ", $PAGESIZE";
  //結果セットを取得します
  $rst = mysqli_query($con,$sql);
  //ページ本文を組み立てます
  print "<FORM name='mainfrm' method='GET'>
     <TABLE class='list'>
     <TR>
      <TH>No.</TH><TH>会社名</TH><TH>業務内容</TH>
      <TH>募集職種</TH><TH>更新日</TH><TH>求人有効期限</TH><TH>募集対象専攻</TH><TH>PDF資料</TH><TH></TH>
     </TR><TH>";
  //会社IDソートボタンの設定
  if($sort=='id'){
    if($seq=='desc')
      print "<A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=id&seq=asc$kw'>
        <IMG src='../fig/sort_asc.JPG' align='middle'></a>▼";
     else 
      print "▲<A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=id&seq=desc$kw'><IMG src='../fig/sort_desc.JPG' align='middle'></a>";
  } else {
    print "<A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=id&seq=asc$kw'><IMG src='../fig/sort_asc.JPG' align='middle'></a>
    <A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=id&seq=desc$kw'>
     <IMG src='../fig/sort_desc.JPG' align='middle'></a>";
  }
  //会社名ソートボタンの設定
  print "</TH><TH>";
  if($sort=='com'){
    if($seq=='desc')
      print "<A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=com&seq=asc$kw'>
                <IMG src='../fig/sort_asc.JPG' align='middle'></a>▼";
     else 
      print "▲<A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=com&seq=desc$kw'><IMG src='../fig/sort_desc.JPG' align='middle'></a>";
  } else {
    print "<A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=com&seq=asc$kw'><IMG src='../fig/sort_asc.JPG' align='middle'></a>
    <A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=com&seq=desc$kw'>
     <IMG src='../fig/sort_desc.JPG' align='middle'></a>";
  }
  print "</TH><TH></TH><TH></TH><TH>";
  //更新日ソートボタンの設定
  if($sort=='date'){
    if($seq=='desc')
      print "<A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=date&seq=asc$kw'>
      <IMG src='../fig/sort_asc.JPG' align='middle'></a>▼";
     else
      print "▲<A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=date&seq=desc$kw'><IMG src='../fig/sort_desc.JPG' align='middle'></a>";
  } else {
    print "<A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=date&seq=asc$kw'><IMG src='../fig/sort_asc.JPG' align='middle'></a>
    <A href = 'search_rst.php?page=$page&tcnt=$tcnt&sort=date&seq=desc$kw'>
     <IMG src='../fig/sort_desc.JPG' align='middle'></a>";
  }
  print "</TH><TH></TH><TH></TH><TH></TH><TH></TH></TR>";
  //結果セットからデータをループで読み込みます
  while($col = mysqli_fetch_array($rst)) {
    //「業務内容」「募集職種」｢備考」内の改行文字の前にBRタグを置く
    $col['business'] = nl2br($col['business']);
    $col['work'] = nl2br($col['work']);
    $col['remarks'] = nl2br($col['remarks']);
    //com_nameにURLリンクを付加
    if(strlen($col['url'])>0)
     $col['com_name']="<a href=\"".$col['url']."\" target='_blank'>".$col['com_name']."</a>"; 
    //「業務内容」「募集職種」の制限文字数以上の文字はカットして...をつける
    /*if(strlen($col[business])>$CHR_MAX){
     $col[business] = mb_substr($col[business],0,$CHR_MAX). ".....";
    }
    if(strlen($col[work])>$CHR_MAX){
     $col[work] = mb_substr($col[work],0,$CHR_MAX). ".....";
    }*/
    //各企業情報を表示するリストを表示
    print "<TR>
      <TD width='70' align='right'> $col[com_id] </TD>
      <TD width='150' align='center'> $col[com_name] </TD>
      <TD width='220'> $col[business] </TD>
	  <TD width='220'> $col[work] </TD>
      <TD width='80' align='center'> $col[upddate] </TD>
      <TD width='80' align='center'> $col[available] </TD>
      <TD>";
    //募集学科の表示
    $dept = "";
    $sql = "SELECT dept_sbl,dept_tbl.dept_id FROM dept_tbl
            INNER JOIN offer_dept ON dept_tbl.dept_id = offer_dept.dept_id
            WHERE offer_id = $col[offer_id] ORDER BY dept_tbl.dept_id";
    $rst2 = mysqli_query($con,$sql);
    while($col2 = mysqli_fetch_array($rst2))
     $dept.=$col2['dept_sbl'];
    print $dept ."</TD><TD>";
    //PDFが存在すればリンク表示
    if(strlen($col['pdf_file'])>0){
      print "<a href='../pdf_file/$col[pdf_file]' target='_blank'>"
        ."<img src='../fig/pdf.gif'></a>";
    }
    print "</td><TD><INPUT type='button' value='詳細'
         onclick='DetailShow(\"$col[com_id]\",\"$col[offer_id]\")'></TD></TR>";
  }
  //com_id,offer_idをPOSTで引渡し
  print "</TABLE>
    <INPUT type='hidden' name='com_id'>
    <INPUT type='hidden' name='offer_id'>
   </FORM>";
  //結果セットを破棄します
  mysqli_free_result($rst);
  //MySQLとの接続を解除します
  $con = mysqli_close($con);
  //ページのナビゲーションを追加します
  print "<div class='pagenavi'>";
  if ($page > 1) {
    //２ページ以降の場合は[前]を表示します
    print "<A href = 'search_rst.php?page=".($page-1)
       ."&tcnt=$tcnt&sort=$sort&seq=$seq&year=".$_GET['year']."$kw'>" 
       ."&lt;前の $PAGESIZE 件</A>&nbsp;&nbsp;&nbsp;";
  }
  if ($totalpage > 1 and $page < $totalpage) {
    //全部で２ページ以上あってかつ現在が最終ページより
    //前のときは[次]を表示します
    print "<A href = 'search_rst.php?page=".($page+1)
       ."&tcnt=$tcnt&sort=$sort&seq=$seq&year=".$_GET['year']."$kw'>"
       ."次の $PAGESIZE 件&gt;</A>";
  }
  print "</div>";
  //csv出力リロード防止用セッション情報
  //$_SESSION['outcsv']=1;
  //ページフッタを出力します
  print htmlfooter();
?>
