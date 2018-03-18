<?php
$debug = false;

session_start();

if ( !isset($_SESSION['name']) ) {
  die('Not logged in');
} else {
  $userName = $_SESSION['name'];
}

include_once 'pdo.php';

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
      
      .success {
        color: green;
        font-weight: bold;
      }
      
      div.table.result {
        border-collapse: collapse;
        display: table;
      }

      div.table.result .tr {
        display: table-row;
      }

      div.table.result .th,
      div.table.result .td {
        border: 1px solid black;
        display: table-cell;
        padding: 0.25em;
      }

      div.centered {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <h1>Welcome <?= $userName ?> to the AutosDB web application</h1>
    <p><a href="add.php">Add new</a> | <a href="logout.php">Logout</a></p>
    <?php
    if (isset($_SESSION['success_add'])) {
      echo '<p class="success" style="display: block;">' . $_SESSION['success_add'] . '</p>';
      unset($_SESSION['success_add']);
    }
    ?>
    <div class="table result">
      <div class="tr">
        <span class="th">Make</span>
        <span class="th">Year</span>
        <span class="th">Mileage</span>
      </div>
      <?php
      $stmt = $pdo->query("SELECT * FROM `autos`");
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (count($rows) > 0) {
        foreach ($rows as $row) {
          echo "<div class='tr'>"
          , "<span class='td'>{$row['make']}</span>"
          , "<span class='td'>{$row['year']}</span>"
          , "<span class='td'>{$row['mileage']}</span>"
          , "</div>\n";
        }
      } else {
        //echo "<div class='tr'><span class='td'>No data so far</span><span class='td'></span><span class='td'></span><span class='td'></span></div>";
      }
      ?>
    </div>
    <?php if ($debug): ?>
      <pre>
        <?php var_dump($_GET); ?>
        <?php var_dump($_POST); ?>
      </pre>
    <?php endif; ?>
  </body>
</html>