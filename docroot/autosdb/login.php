<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userName = filter_input(INPUT_POST, 'who', FILTER_SANITIZE_STRING);
  $userPass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
  $authTry = (isset($userName) && ($userName != "")) && (isset($userPass) && ($userPass != ""));
  if (!$authTry) {
    $errorAuth = 'Email and password are required';
  } else if (strpos($userName, '@') === false) {
    $errorAuth = 'Email must have an at-sign (@)';
  } else {
    # $authOK = false;
    $salt = 'XyZzy12*_';
    $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
    $passMD5 = hash('md5', $salt . $userPass);
    $authOK = $stored_hash === $passMD5;
    if (!$authOK) {
      $errorAuth = 'Incorrect password';
      error_log("Login fail $userName $passMD5");
    } else {
      header("Location: autos.php?name=" . urlencode($userName));
      error_log("Login success $userName");
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
      <p class="alert" style="display: <?= isset($errorAuth)?'block':'none' ?>"><?= $errorAuth ?></p>
      <p><label>Email: <input type="text" name="who"/></label></p>
      <p><label>Password: <input type="password" name="pass"/></label></p>
      <input type="submit" value="Log in"/>
      <input type="submit" name="cancel" value="Cancel"/>
      <p style="font-style: italic">
        Select next line for help<br>
        <span style="color: white">who@where/php123</span>
      </p>
    </form>
  </body>
</html>
