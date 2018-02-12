<!DOCTYPE html>
<html>
  <head>
    <title>Rock-Paper-Scissors by Roman Kozar</title>
  </head>
  <body>
    
    <pre>
    <?php var_dump($_POST);?>
    </pre>
    
    <h1>Welcome to the Rock-Paper-Scissors by Roman Kozar</h1>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $userName = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
      if (!$userName) {
        die("<h2>Fatal: Name parameter missing!</h2>");
      } else {
        print "$userName please make your move:";
      }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['logout'])) {
        header('Location: index.php');
      }
    }
    ?>
    <form method="POST">
      <select name="Choosen">
        <option>Select</option>
        <option>Rock</option>
        <option>Paper</option>
        <option>Scissors</option>
        <option>Test</option>
      </select>
      <input type="submit" value="Play" name="play">
      <input type="submit" value="Logout" name="logout">
    </form>
  </body>
</html>
