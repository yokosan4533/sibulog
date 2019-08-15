<?php

//いつもの関数呼び出し
require 'function.php';

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　退会ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン機能
require('auth.php');


// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
try {
 $dbh = dbConnect();
 //DBの編集文作成 ＊今回は物理削除ではなく論理削除を採用している為,delete_flgを立てて対象のデータを削除扱いにしている。(DBにはデータは残っている。)
 $sql1 = 'UPDATE users SET delete_flg = 1 WHERE id = :us_id';
 $sql2 = 'UPDATE product SET delete_flg = 1 WHERE  user_id = :us_id';
 //likeは予約語の為バッククオートで囲まないといけない.
 $sql3 = 'UPDATE `like` SET delete_flg = 1 WHERE user_id = :us_id';
 //セッション変数にキーの値を持たせて$dataに入れる.
 $date = array(':us_id' => $_SESSION['user_id']);
 // クエリ実行
 $stmt1 = queryPOST($dbh,$sql1,$data);
 $stmt2 = queryPOST($dbh,$sql2,$data);
 $stml3 = queryPOST($dbh,$sql3,$data);
 
 // クエリ実行成功の場合 (今回は最低でもuserテーブルのみ削除できていれば成功とする.product.likeテーブルも条件に含めたい場合はif文に&&$stml2.3を加える。)
 if($stml1){
  //セッション削除
   session_destroy();
   debug('セッション変数の中身:'.print_r($_SESSION.true));
   debug('トップページへ移動します。');
   header("Location:index.php");
 }else{
   debug('クエリが失敗しました。');
   $err_msg['common'] = MSG07;
 }
 
  //getMessageは例外処理関係のエラーメッセージを引っ張ってくる関数
} catch (Exception $e) {
  error_log('エラー発生:'. $e->getMessage());
  $err_msg['common'] = MSG07;
 }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<!--退会処理関係はここまで-->


<!--ヘッドの調整もする-->
<?php
$siteTitle = '退会';
require('head.php'); 
?>

<body>

 <style>
 /* もしかすると使わないかも*/
 </style>

<!--ヘッダーも調整する-->
<?php
 require('header.php');
?>
    
<!-- メインコンテンツ -->
<div id="" class="">
    <!-- Main -->
    <section id="" >
    <div class="">
        <form action="" method="post" class="form">
        <h2 class="">退会</h2>
        <div class="">
            <?php 
            if(!empty($err_msg['common'])) echo $err_msg['common'];
            ?>
        </div>
        <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="退会する" name="submit">
        </div>
        </form>
    </div>
    <a href="mypage.php">&lt; マイページに戻る</a>
    </section>
</div>

<?php
 require('footer.php');
?>

</body>























