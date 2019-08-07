<?php
//ここからログ関係コード
//ログの出力処理
ini_set('log_errors','on');
//ログの出力先
ini_set('error_log','php.log');

//デバッグ
$debug_flg = true;
//デバッグログ関数
function debug($str){
    global debug_flg;
    if(!empty($debug_log)){
     error_log('デバッグ：'.$str);
    }
}
//一旦ここまで

//ここからセッション関係の設定
//セッションの期限を伸ばす.(30日間)
session_save_path("/var/tmp");
//gcがセッションを削除する期限の設定(30日以上経ったもののみ100分の1の確率で削除*確率のいじり方を後で調べる.)
ini_set('session.gc_maxlifetime',60*60*24*30);//秒・分・時間・月
//ブラウザのクッキーの有効期限の設定
ini_set('session.cookie_lifetime ', 60*60*24*30);
//セッションを使用する
session_start();
//セッションIDを定期的に変更する
session_regenerate_id();
//一旦ここまで

//ここからまたログ関係コード
function debugLogstart(){
   debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
  debug('セッションID：'.session_id());
  debug('セッション変数の中身：'.print_r($_SESSION,true));
  debug('現在日時タイムスタンプ：'.time());
  if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
    debug( 'ログイン期限日時タイムスタンプ：'.( $_SESSION['login_date'] + $_SESSION['login_limit'] ) );
  }
}
//終わり

//エラーメッセージの設定
define('MSG01','入力必須事項です');
define('MSG02','E-mailの形式を確認してください');
define('MSG03','再入力欄を確認してください');
define('MSG04','半角英数字のみ利用できます');
define('MSG05','6文字以上で入力してください');
define('MSG06','256文字以内で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEmailは登録されています');

//エラーメッセージ格納用の配列
$err_msg = array();

//バリテーション用関数(入力確認)
function validRequired($str,$key){
 if(empty($str)){
  global $err_msg;
  $err_msg[$key] = MSG01;
 }
}

//バリデーション用関数（Email形式チェック）
function validEmail($str, $key){
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}

function validEmailDup($email){
    global $err_msg;
    //例外処理
    try{
        //DBへ接続
        $dbh = dbConnect();
        //データ作成
        $sql = 'SELECT count(*) FROM users WHERE email = :email';
        $data = array(':email' => $email);
        //クエリ実行
        $stml = queryPost($dbh,$sql,$data);
        //クエリ結果の値を取得*PDO::FETCH_ASSOCはキーのついた要素全てを取り出す.
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
         $err_msg['email'] = MSG08;   
        }
    }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
    }
}
//バリテーション関数(同値チェック)
function validMatch($str1,$str2,$key){
    if($str1 !== $str2){
    global $err_msg;
    $err_msg[$key] = MSG06;
    }
}

//バリテーション関数(文字数チェック*最小) 
function validMinLen($str,$key,$min = 6){
    if(md_strlen($str) < $min){
        global $err_msg;
        $err_msg[$key] = MSG5; 
    }
}

//バリテーション関数(文字数チェック*最大)
function validMaxLen($str, $key, $max = 256){
  if(mb_strlen($str) > $max){
    global $err_msg;
    $err_msg[$key] = MSG06;
  }
}

//バリテーション関数(半角チェック)
function validHalf($str){
    if(!preg_match("/^[a-zA-Z0-9]+$/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
       
}

//DB接続
function dbConnect(){
    //DBへの接続準備
    $dns = 'mysql:dbname=sibuyareview;host=localhost;charet=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
    // ここはそのまま引っ張ってきただけ
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    //pdoオブジェクトの生成
    $dbh = new PDO($dsn,$user,$password,$options);
    return $dbh;
}
       
//SQL実行関数
function queryPost($dbh, $sql, $data){
  //クエリー作成
  $stmt = $dbh->prepare($sql);
  //プレースホルダに値をセットし、SQL文を実行
  $stmt->execute($data);
  return $stmt;
}
        
?>