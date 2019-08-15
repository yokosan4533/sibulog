<?php

require 'function.php';
//POST送信されているかどうかの確認
if(!empty($_POST)){
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];
    
//主要要素が空かどうか確認
  validRequired($email,'email');
  validRequired($pass,'pass');
  validRequired($pass_re,'pass_re');
    
 //エラーメッセージが入っているかどうかの確認(バリテーションメソッドを経由していて何かあった際は必ず$err_msgに定数が入る様になっている)
  if(empty($err_msg)){     
     
     //emailの確認
     validEmail($email,'email');
     //最大文字数の確認
     validMaxLen($email,'email');
     //email重複確認
     validEmailDup($email);
      
     //パスワードの半角英数字の確認
     validHalf($pass);
     //パスワードの最大文字数確認
     validMaxLen($pass, 'pass');
     //パスワードの最小文字数確認
     validMinLen($pass, 'pass');
      
     //パスワード(再入力)の最大文字数確認
     validMaxLen($pass_re,'pass_re');
     //パスワード(再入力)の最小文字数確認
     validMinLen($pass_re,'pass_re');
      
  }
    
      if(empty($err_msg)){

      //パスワードとパスワード再入力が合っているかチェック
      validMatch($pass, $pass_re, 'pass_re');

      if(empty($err_msg)){

        //例外処理
        try {
          // DBへ接続
          $dbh = dbConnect();
          // SQL文作成
          $sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';
          $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                        ':login_time' => date('Y-m-d H:i:s'),
                        ':create_date' => date('Y-m-d H:i:s'));
            
          // クエリ実行 *ここ確認
          queryPost($dbh, $sql, $data);

          header("Location:mypage.html"); //マイページへ

        } catch (Exception $e) {
          error_log('エラー発生:' . $e->getMessage());
          $err_msg['common'] = MSG07;
        }
      }
    }
}
?>


<!--メインコンテンツ *入力フォームのエラー出力処理がまだ-->
<!DOCTYPE html>
<html lang="ja" dir="ltr">
      <head>
        <meta charset="utf-8">
        <link rel='stylesheet' href="style.css">
        <title>portfolio</title>
      </head>
    
      <body>

        <?php   
         require'header.php';
        ?>

　　　<!--メイン-->
      <main class="main">
         <div class="signup-background">
             
             <form method="POST" class="form">
               
              <div class="area-msg">
              <?php if(!empty($err_msg['common'])) echo $err_msg['common'];?>
              </div>
                    
               <h2 class="signup_title">ユーザー登録</h2>
               <label class="<?php if(!empty($err_msg['pass'])) echo 'err';?>">
                 E-mail
                 <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?>">
               </label>
                 
                
                <label class="<?php if(!empty($err_msg['pass'])) echo 'err';?>">
                  パスワード <span style="font-size:12px">※英数字６文字以上</span>
                  <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
                </label>
                 
                 
                <label class="<?php if(!empty($err_msg['pass'])) echo 'err';?>">
                  パスワード(再入力)
                  <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
                </label>
                 
                 <div>
                  <input type="submit" value="登録する">     
                 </div>
             </form>
             
         </div>
      </main>
          
       <?php   
     require'footer.php';
    ?>
          
  </body>
</html>