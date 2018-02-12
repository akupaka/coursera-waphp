<!DOCTYPE html>
<html>
  <head>
    <title>Rock-Paper-Scissors by Roman Kozar</title>
    <style>
      table.result {
        border-collapse: collapse;
      }

      table.result th,
      table.result td {
        border: 1px solid black;
        padding: 0.25em;
      }
    </style>
  </head>
  <body>

    <pre>
      <?php var_dump($_GET); ?>
      <?php var_dump($_POST); ?>
    </pre>

    <h1>Welcome to the Rock-Paper-Scissors by Roman Kozar</h1>
    <p>
      <?php
      $userName = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
      if (!$userName) {
        die("<h2>Fatal: Name parameter missing!</h2>");
      } else {
        print "Hello <b>$userName</b>, please make your move:";
      }
      ?>
    </p>
    <form method="POST">
      <select name="choosen">
        <option value="0">Select</option>
        <option value="1">Rock</option>
        <option value="2">Paper</option>
        <option value="3">Scissors</option>
        <option value="4">Test</option>
      </select>
      <input type="submit" value="Play" name="play">
      <input type="submit" value="Logout" name="logout">
    </form>
    <p>
      <?php
      //if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $human = filter_input(INPUT_POST, 'choosen', FILTER_SANITIZE_NUMBER_INT);
      if (isset($_POST['logout'])) {
        header('Location: index.php');
      } else if (isset($human)) {
        $names = [1 => "Rock", 2 => "Paper", 3 => "Scissors"];
        $human = (int) $human;
        if ($human === 0) {
          print "Please, make your choise.";
        } else {
          print '<table class="result"><tr><th>Computer</th><th>Human</th><th>Result</th></tr>';
          if ($human > 0 && $human < 4) {
            $computer = rand(1, 3);
            $r = check($computer, $human);
            print $computer % $human;
            print "<tr><td>$names[$computer]</td><td>$names[$human]</td><td>$r</td></tr>";
          } else if ($human === 4) {
            for ($c = 1; $c < 4; $c++) {
              for ($h = 1; $h < 4; $h++) {
                $r = check($c, $h);
                print "<tr><td>$names[$c]</td><td>$names[$h]</td><td>$r</td></tr>";
              }
            }
          }
          print '</table>';
        }
      }

      //}

      function check($computer, $human) {
        if ($computer === $human) {
          return "Tie";
        } else if (($computer - $human === 1) || ($human - $computer === 2)) {
          return "You Lose";
        } else if (($computer - $human === 2) || ($human - $computer === 1)) {
          return "You Win";
        }
      }
      ?>
    </p>
  </body>
</html>
