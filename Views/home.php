<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo $title; ?></title>
</head>
<body>
  <div  class="main">
    <?php
      if(!empty($message)){
    ?> <p> <?php echo $message; ?> </p>
       <a href="./index.php">Back to Home</a>
    <?php
      }
    ?>
    <?php if( !empty($user) ): ?>

      <br />Welcome <?= $user; ?>
      <br /><br />You are successfully logged in!
      <br /><br />
      <a href="./logout.php">Logout?</a>

    <?php else: ?>

      <form action="#" method="post">
        User: <input type="text" name="user">
        Password: <input type="password" name="password">
        <input type="submit" value="Login">
      </form>
      <a href="./register.php">Register</a>

    <?php endif; ?>
    
  </div>
</body>
</html>