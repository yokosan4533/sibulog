<!DOCTYPE html>
<html lang="ja" dir="ltr">
      <head>
        <meta charset="utf-8">
        <link rel='stylesheet' href="style.css">
        <title>portfolio</title>
      </head>
      <body>

      <header class="header">
        <img src="img/log.jpg" class="log1">
          <ul class="headertab-wrap">
            <li class="headertab tab1">ログイン</li>
            <li class="headertab tab2">新規会員登録</li>
          </ul>
      </header>

      <main id="main">
         <div class="signup-background">
             
             <form method="POST" class="form">
             <!--後でトータルのエラー文を出すやつ書く-->
               <h2>ユーザー登録</h2>
               <label>
                 E-mail
                 <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?>">
               </label>
                
                <label>
                  パスワード <span style="font-size:12px">※英数字６文字以上</span>
                  <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
                </label>
                 
                <label>
                  パスワード(再入力)
                  <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
                </label>
                 
                 <div>
                  <input type="submit" value="登録する">     
                 </div>
             </form>
             
         </div>
      </main>

      <footer class="footer">
      </footer>
  </body>
</html>