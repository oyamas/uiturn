<?php
/****************************************/
/* 共通インクルードファイル   */
/****************************************/
  $SEL_PREF = 1; //デフォルトを「北海道」に
  $CHR_MAX=100;   //一覧表示時の文字数制限
  $YEAR = 2021;  //採用年度のデフォルト値
  $TO = "info@hokkaido-nct.sakura.ne.jp";   //問い合わせメール送付先
//var_dump($_SERVER);
 if(preg_match("/.*sakura.ne.jp*/",$_SERVER["SERVER_NAME"]))
 {
   $PDF_DIR	= "/home/hokkaido-nct/www/uiturn/pdf_file/";
   $DBSERVER = "mysql458.db.sakura.ne.jp";    //MySQLサーバー名
   $DBNAME   = "hokkaido-nct_uiturn";   //データベース名
   $DBUSER     = "*************";
   $DBPASSWORD = "************";
 } else {
   $PDF_DIR	= "C:\\xampp\\htdocs\\uiturn\\pdf_file\\";
   $DBSERVER = "localhost";
   $DBNAME   = "uiturn";   //データベース名
   $DBUSER     = "*************";
   $DBPASSWORD = "************";
 }
function htmlfooter() {
//各ページのフッタ部のHTMLを組み立てる

  $strret = "<BR> <BR> <BR> <BR> <hr> <address> National Institute of Technology, HAKODATE College All Rights Reserved.<BR>"
   ."お問い合わせ：　info [at] hokkaido-nct.sakura.ne.jp　（[at]を@に変換してください）</address>";
  return $strret;
}
function dfirst($con, $fldname, $tblname, $critera,$closeflg=0) {
//指定テーブルからcriteraに一致する先頭レコードを抽出しその指定フィールドの値を返す

  //引数に応じてSQL文を組み立てます
  if (strlen($critera) > 0) {
    $sql = "SELECT $fldname FROM $tblname WHERE $critera";
  }
  else {
    $sql = "SELECT $fldname FROM $tblname";
  }
  //結果セットを取得します
  $rst = mysqli_query($con,$sql);
  if(!$rst){
    $strret = "error";
  } else {  
    $col = mysqli_fetch_array($rst);
    //最初のレコードの指定フィールドの値を取り出します
    $strret = $col[$fldname];
  }
  //結果セットを破棄します
  mysqli_free_result($rst);
  return $strret;
}

function debugprint($data) {
//デバッグ用HTML出力
  print "<font color='red'>" . $data . "</font><br>";

}

function char_proc($field_name,$keyword){
//検索キーワードの文字処理→WHERE句の一部を作る
      //$keywordからエスケープ文字を取り除きます
      $keyword = stripcslashes($keyword);
      //$keywordの前後のスペースを取り除きます
      $keyword = trim($keyword);
      //$keywordの半角変換と半角カナの全角変換を行います
      $keyword = mb_convert_kana($keyword, "sKV", "UTF-8");
      //キーワードをカンマかスペースで分解して配列に代入します
      if(!strrchr($keyword, " ")){
        //キーワードに半角スペースが含まれていないとき
        $keyword = str_replace("、", ",", $keyword);
        $keyword = str_replace("，", ",", $keyword);
        $arykey = explode(",", $keyword);
        $tmpkey = "Or";
      }
      else{
        //キーワードに半角スペースが含まれているとき
        $arykey = explode(" ", $keyword);
        $tmpkey = "And";
      }
      //分解された各キーワードが空でないかチェックします
      for ($i=0; $i < sizeof($arykey); $i++) {
        if (strlen($arykey[$i]) == 0) {
          //分解されたキーワードのいずれかが空のとき
          $body = $field_name . "キーワードの指定が正しくありません！
                  <INPUT type='button' value='ホームへ戻る'
                  onclick='window.location=\"index.html\"'>";
          print htmlheader("検索結果") . $body . htmlfooter();
          exit();
        }
      }
      //最初のキーワードをWHERE句に追加する
      $where = "((" . $field_name . " Like \"%$arykey[0]%\")";
      //２つめ以降のキーワードをWHERE句に追加します
      for ($i=1; $i<sizeof($arykey); $i++) {
        $where .= " " . $tmpkey;
        $where .= "(" . $field_name . " Like \"%$arykey[$i]%\")";
      }
	 return $where.")";
}

//$_GET配列を組み立ててURL形式にする関数
function struct_kw(){
   $n = 0;
   $chr = "&";
  //入力データの保存
  for($i=0;$i<sizeof($_GET);$i++){
    $cur = each($_GET);
    if(!is_array($cur[value])){
      if(strlen($cur[value])>0){
       if($n>0) $chr .= "&"; else $n++;
       $chr .= $cur[key]."=".urlencode($cur[value]);
      }
    } else {
      $size = sizeof($cur[value]);
      for($j=0;$j<$size;$j++){
        $ccur = each($cur[value]);
        if($n>0) $chr .= "&"; else $n++;
        $chr .= $cur[key]."[$j]"."=".urlencode($ccur[value]);
      }
    }
  }
  return $chr;
}

//数値部分を自動的に桁区切り
function int2comma($n) {
  preg_match("/([0-9]+)(\.[0-9]*)/", $n, $matches);
  if (isset($matches[2])) return $n;  //小数はそのまま返す
  //いったんコンマを削除してから桁区切り
  $n = mb_ereg_replace(',','',$n);
  return number_format($n);
}

function ary2hidden($ary){
 $str="";
 foreach($ary as $key => $value){
  if(is_array($value)){
    foreach($value as $kkey => $vvalue){
      $str .="<INPUT type='hidden' name='".$key."[".$kkey."]' value='$vvalue'>";
    }
   } else $str .="<INPUT type='hidden' name='$key' value='$value'>";
 }
 return $str;
}
?>
