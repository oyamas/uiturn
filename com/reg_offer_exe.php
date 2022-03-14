<?php
/****************************************/
/* 求人情報登録実行ページ               */
/****************************************/
  session_start();
  //共通データをインクルードします
  require_once("../ini.php");
  require_once("setting.php");
 $con = mysqli_connect($DBSERVER,$DBUSER,$DBPASSWORD,$DBNAME);
 mysqli_query($con,"set names utf8");
 //  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
//  mysql_query("set names utf8");
//  $selectdb = mysql_select_db($DBNAME, $con);
  if (isset($_POST['cancel'])) {
    //キャンセルボタンが押されたとき
    //新規登録ページへリダイレクト
    header("Location: index.php");
    exit();
  }
  //リロード時は処理を無効に
  if($_SESSION['regoffer']==1){
    unset($_SESSION['regoffer']);
  } else {
    print htmlheader("求人情報更新実行済み");
    print "この情報はすでに更新処理が終わっています。<br><br>
       <INPUT type='button' value='入力者用ホームへ戻る'
       onclick=\"location.href='index.php'\">";
    exit();
  }
  //ページヘッダを出力します
  print htmlheader("求人情報登録実行");
  //$_POST['edit']…1：新規、2:更新、3:削除
  if($_POST['edit']==1)
  {
    //PDFファイルがあればアップロード
    if(file_exists($_FILES['pdf_file']['tmp_name']))
    {
       $filename = $_SESSION['com_id']."_s_".date("YndHi").".pdf";
       move_uploaded_file($_FILES['pdf_file']['tmp_name'],$PDF_DIR.$filename);
       chmod($PDF_DIR.$filename,0777);
       print "PDFファイルをアップロードしました。<a href='../pdf_file/$filename' target='_blank'>ファイルの確認</a><br><br>";
       //元のファイルを削除
       $sql="SELECT pdf_file from offersheet where com_id=".$_SESSION['com_id'];
       $rst=mysqli_query($con,$sql);
       $col=mysqli_fetch_array($rst);
       if(strlen($col[pdf_file])!=0)
		{
	       unlink($PDF_DIR.$col[pdf_file]);
    	   print "元のPDFファイルは削除されました。<br>";
		}
     }
     else
       $filename=""; 
    //offersheetに新規登録する
    $sql="INSERT offersheet
       (com_id,work,recruit,remarks,pdf_file,regdate,upddate,available,editor_id)
      VALUES
       (". $_SESSION['com_id'].",\"" . $_POST['work']."\",\""
         . $_POST['recruit']."\",\""
         . $_POST['remarks']."\",\"$filename\", now(), now(),\""
         . $_POST['available']."\",\"".$_SESSION['com_id']. "\")";
    $rst = mysqli_query($con,$sql);
    if($rst){
     print "求人情報を登録しました。<br>";
     //追加したレコードのoffer_id取得
     $offer_id=dfirst($con,"offer_id","offersheet","com_id=".$_SESSION['com_id']);
     //求人対象専攻の登録
     $sql="INSERT INTO offer_dept (offer_id, dept_id) VALUES ";
     for($i=0;$i<sizeof($_POST['sel_dept']);$i++){
      if($i!=0) $sql .= ",";
      $sql .= "($offer_id,".$_POST['sel_dept'][$i].")";
     }
     $rst=mysqli_query($con,$sql);
     if($rst){
       print "募集対象専攻を登録しました。<br>";
     } else{
       print "募集対象専攻の登録に失敗しました。<br>"; 
       exit();
     } 
     //求人対象勤務地の登録
     $sql="INSERT INTO offer_wplc (offer_id, wplc_id) VALUES ";
     for($i=0;$i<sizeof($_POST['sel_wplc']);$i++){
      if($i!=0) $sql .= ",";
      $sql .= "($offer_id,".$_POST['sel_wplc'][$i].")";
     }
     $rst=mysqli_query($con,$sql);
     if($rst){
       print "勤務地を登録しました。<br>";
     } else{
       print "勤務地の登録に失敗しました。<br>"; 
       exit();
     }
   }
  }
  else if($_POST['edit']==2)
  {
   //del_pdfがチェックされていればPDFファイルを削除
   if($_POST['del_pdf']==5){
    $filename=dfirst($con,"pdf_file","offersheet","com_id=".$_SESSION['com_id']);
    if($filename!=""){
      unlink("../pdf_file/$filename");
      print "アップロードされていたPDFファイルを削除しました。<br>";
    }
    $filename="";
   } else if(file_exists($_FILES['pdf_file']['tmp_name'])){
       //PDFファイルがあればアップロード
       $filename =  $_SESSION['com_id']."_s_".date("YndHi").".pdf";
       move_uploaded_file($_FILES['pdf_file']['tmp_name'],$PDF_DIR.$filename);
       chmod($PDF_DIR.$filename,0777);
       print "PDFファイルをアップロードしました。　"
        ."<a href='../pdf_file/$filename' target='_blank'>ファイルの確認</a><br><br>";
       //元のファイルを削除
       $sql="SELECT pdf_file from offersheet where com_id=".$_SESSION['com_id'];
       $rst=mysqli_query($con,$sql);
       $col=mysqli_fetch_array($rst);
       if(strlen($col[pdf_file])>0){
          unlink($PDF_DIR.$col[pdf_file]);
          print "既存のPDFファイルは削除されました。<br>";
       }
    } else {
      //ファイルは元のまま
      $filename=$_POST['pdf_file']; 
    }
    //対象企業のoffer_id取得
    $offer_id=dfirst($con,"offer_id","offersheet","com_id=".$_SESSION['com_id']);
    //offersheetのレコードを更新する
    $sql = "UPDATE offersheet
       SET work = \"".$_POST['work']."\",remarks= \"".$_POST['remarks']."\","
         ."pdf_file=\"$filename\",recruit= \"".$_POST['recruit']."\","
         ."upddate= now(),available=\"".$_POST['available']."\",editor_id=\"".$_SESSION['com_id']."\""
         .",delflag=0 WHERE offer_id=$offer_id";
    $rst2 = mysqli_query($con,$sql);
    if($rst2){ //旧データの更新に成功のとき
       print "求人票を更新しました。<br>";
    } else{
       print "求人票の更新に失敗しました。<br>"; 
       exit();
    }
    //募集専攻をいったん削除
    $sql="DELETE FROM offer_dept WHERE offer_id=$offer_id";
    $rst2=mysqli_query($con,$sql);
    if($rst2){
       //print "募集専攻を一時削除しました。<br>";
    } else{
       print "募集専攻の一時削除に失敗しました。<br>"; 
       exit();
    }
    //募集専攻再登録
    $sql="INSERT INTO offer_dept (offer_id, dept_id) VALUES ";
    for($i=0;$i<sizeof($_POST['sel_dept']);$i++){
     if($i!=0) $sql .= ",";
     $sql .= "($offer_id,". $_POST['sel_dept'][$i]. ")";
    }
    $rst2=mysqli_query($con,$sql);

    if($rst2){
       print "募集専攻を登録しました。<br>";
    } else{
       print "募集専攻の登録に失敗しました。<br>"; 
       exit();
    }
    //募集勤務地をいったん削除
    $sql="DELETE FROM offer_wplc WHERE offer_id=$offer_id";
    $rst2=mysqli_query($con,$sql);
    if($rst2){
       //print "勤務地を一時削除しました。<br>";
    } else{
       print "勤務地の一時削除に失敗しました。<br>"; 
       exit();
    }
    //募集勤務地レコード再登録
    $sql="INSERT INTO offer_wplc (offer_id, wplc_id) VALUES ";
    for($i=0;$i<sizeof($_POST['sel_wplc']);$i++){
     if($i!=0) $sql .= ",";
     $sql .= "($offer_id,". $_POST['sel_wplc'][$i]. ")";
    }
    $rst2=mysqli_query($con,$sql);
    if($rst2){
       print "勤務地を登録しました。<br>";
    } else{
       print "勤務地の登録に失敗しました。<br>"; 
       exit();
    }
  }
  else if($_POST['edit']==3) 
  {
    //PDFファイルを削除
    $filename=dfirst($con,"pdf_file","offersheet","com_id=".$_SESSION['com_id']);
    if($filename!=""){
      unlink("../pdf_file/$filename");
      print "アップロードされていたPDFファイルを削除しました。<br>";
    }
    $sql="DELETE FROM offersheet WHERE offer_id=".$_POST['offer_id'];
    $rst = mysqli_query($con,$sql);
    if($rst){
     print "求人情報を削除しました。<BR><BR>";
     //募集専攻を削除
     $sql="DELETE FROM offer_dept WHERE offer_id=".$_POST['offer_id'];
     $rst2=mysqli_query($con,$sql);
     if($rst2){
       print "募集専攻を削除しました。<br>";
     } else{
       print "募集専攻の削除に失敗しました。<br>"; 
       exit();
     }
     //募集勤務地をいったん削除
     $sql="DELETE FROM offer_wplc WHERE offer_id=".$_POST['offer_id'];
     $rst2=mysqli_query($con,$sql);
     if($rst2){
       print "勤務地を削除しました。<br>";
     } else{
       print "勤務地の削除に失敗しました。<br>"; 
       exit();
     }
    }
  }
  //MySQLとの接続を解除します
  $con = mysqli_close($con);

?>
<br>
<INPUT type='button' value='入力者用ホームへ戻る'
   onclick="location.href='index.php'">
<?php  print htmlfooter(); ?>
