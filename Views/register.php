<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo $title; ?></title>
</head>
<body>
  <div class="main">
    <?php
      if(!empty($message)){
    ?> <p> <?php echo $message; ?> </p>
       <a href="./">Back to Home</a>
    <?php
      }
    ?>
    <form action="register.php" method="post">
      user: <input type="text" name="user">
      password: <input type="password" name="password">
      Confirm Password: <input type="password" name="confirm">
      <input type="submit" name="Register">
    </form>
  </div>
</body>
</html>