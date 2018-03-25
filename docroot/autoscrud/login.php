<?php
$debug = true;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userName = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
  $userPass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
  $authTry = (isset($userName) && ($userName != "")) && (isset($userPass) && ($userPass != ""));
  if (!$authTry) {
    $_SESSION['error'] = 'User name and password are required';
    header("Location: login.php");
    return;
  } else if (strpos($userName, '@') === false) {
    $_SESSION['error'] = 'User name must have an at-sign (@)';
    header("Location: login.php");
    return;
  } else {
    $salt = 'XyZzy12*_';
    $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
    $passMD5 = hash('md5', $salt . $userPass);
    $authOK = $stored_hash === $passMD5;
    if (!$authOK) {
      error_log("Login fail $userName $passMD5");
      $_SESSION['error'] = 'Incorrect password';
      header("Location: login.php");
      return;
    } else {
      error_log("Login success $userName");
      $_SESSION['name'] = $userName;
      header("Location: .");
      return;
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>AutosDB by Roman Kozar</title>
    <style>
      .alert {
        color: darkred;
        font-weight: bold;
      }
    </style>
  </head>
  <body>
    <h1>Welcome to the AutosDB web application</h1>
    <form method="POST">
      <h2>Login form</h2>
      <?php
      if (isset($_SESSION['error'])) {
        echo '<p class="alert" style="display: block;">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
      }
      ?>
      
      <p><label>User name: <input type="text" name="email"/></label></p>
      <p><label>Password: <input type="password" name="pass"/></label></p>
      <input type="submit" value="Log in"/>
      <input type="submit" name="cancel" value="Cancel"/>
      <p style="font-style: italic">
        Select next line for help<br>
        <span style="color: white">who@where/php123</span>
      </p>
    </form>
    <?php if ($debug): ?>
      <pre>
        <?php var_dump($_GET); ?>
        <?php var_dump($_POST); ?>
        <?= var_dump($userName) ?>
        <?= var_dump($userPass) ?>
        <?= var_dump($authTry) ?>
      </pre>
    <?php endif; ?>
  </body>
</html>
