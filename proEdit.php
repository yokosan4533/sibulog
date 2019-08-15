<?php

require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　プロフィール編集ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

//ここからプロフ編集機能

//dbからユーザーデータを作成
$dbFormData = getUser($_SESSION['user_id']);

debug('取得したユーザー情報:'.print_r($dbFormData.true));

// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報:'.print_r($_POST,true));
    
  //変数にユーザー情報を代入
  $username = $_POST['username'];
  $tel = $_POST['tel'];
  $zip = (!empty($_POST['zip'])) ? $_POST['zip']:0;//後続のバリデーションにひっかかるため、空で送信されてきたら0を入れる
  $addr = $_POST['addr'];
  $age = $_POST['age'];
  $email = $_POST['email'];
    
  //DBへ登録している情報と変わった場合もう一度バリテーションを通す様にする。
  if($dbFormData['username'] !== '$username'){
      validMaxLen($username, 'username');
  }
  if($dbFormData['tel'] !== $tel){
    //TEL形式チェック*後で追加
    validTel($tel,'tel');
  }
  if($dbFormData['addr'] !== $addr){
    validMaxLen($addr,'addr');
  }
  if( (int)$dbFormData['zip'] !== $zip){ //DBデータをint型にキャスト（型変換）して比較
    //郵便番号形式チェック
    validZip($zip, 'zip');
  }
  if($dbFormData['age'] !== $age){
    //年齢の最大文字数チェック
    validMaxLen($age, 'age');
    //年齢の半角数字チェック
    validNumber($age, 'age');
  }
  if($dbFormData['email'] !== $email){
    //emailの最大文字数チェック
    validMaxLen($email, 'email');
    if(empty($err_msg['email'])){
      //emailの重複チェック
      validEmailDup($email);
    }
    //emailの形式チェック
    validEmail($email, 'email');
    //emailの未入力チェック
    validRequired($email, 'email');
  }
    
//再バリテーション処理関係はここまで
    
 if(empty($err_msg)){
     debug('バリテーション通りました.');
     
  //例外処理
  try {
    $dbh = dbConnect();
    //再度SQL文作成
    $sql = 'UPDATE users SET username = :u_name,tel = :tel,
    zip = :zip,addr = :addr,age = :age,email = :email WHERE id = :u_id';
    $data = array(':u_name' => $username,':tel' => $tel,
    )
  }
 }
}

?>