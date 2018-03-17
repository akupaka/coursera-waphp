<?php
$debug = false;

include_once 'pdo.php';

$userName = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
if (!$userName) {
  die("<h2>Fatal: Name parameter is missing!</h2>");
}
if (isset($_POST['logout'])) {
  header('Location: index.php');
}

$sort_options = array('make', 'year', 'mileage');
if (isset($_POST['set_sort'])) {
  $sort = filter_input(INPUT_POST, 'sort_order', FILTER_SANITIZE_STRING);
  if (!($sort && in_array($sort, $sort_options))) {
    $sort = 'make';
  }
} else {
  $sort = 'make';
}


if (isset($_POST['add_auto'])) {
  $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_STRING);
  $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_STRING);
  $mileage = filter_input(INPUT_POST, 'mileage', FILTER_SANITIZE_STRING);
  if ($make === "") {
    $errorAddAuto = 'Make is required';
  } else if (!(is_numeric($year) && is_numeric($mileage))) {
    $errorAddAuto = 'Mileage and year must be numeric';
  } else {
    $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)');
    $stmt->execute(array(':mk' => $make, ':yr' => $year, ':mi' => $mileage));
    $successAddAuto = true;
  }
}

if (isset($_POST['rem_auto'])) {
  $rem_id = filter_input(INPUT_POST, 'rem_id', FILTER_SANITIZE_STRING);
  $stmt = $pdo->prepare('DELETE FROM autos WHERE auto_id = :rem_id');
  $stmt->execute(array(':rem_id' => $rem_id));
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
    <?php if ($debug): ?>
      <pre>
        <?php var_dump($_GET); ?>
        <?php var_dump($_POST); ?>
      </pre>
    <?php endif; ?>

    <h1>Welcome <?= $userName ?> to the AutosDB web application</h1>
    <form method="POST">
      <p><input type="submit" value="Logout" name="logout"></p>
    </form>
    <p class="alert" style="display: <?= isset($errorAddAuto)?'block':'none' ?>"><?= $errorAddAuto ?></p>
    <p class="success" style="display: <?= isset($successAddAuto)?'block':'none' ?>">Record inserted</p>
    <div class="table result">
      <div class="tr">
        <span class="th">Make<form method="POST"><input type="hidden" value="make" name="sort_order"><input type="submit" value="Sort" name="set_sort"></form></span>
        <span class="th">Year<form method="POST"><input type="hidden" value="year" name="sort_order"><input type="submit" value="Sort" name="set_sort"></form></span>
        <span class="th">Mileage<form method="POST"><input type="hidden" value="mileage" name="sort_order"><input type="submit" value="Sort" name="set_sort"></form></span>
        <span class="th">Operation</span>
      </div>
      <form method="POST" class="tr">
        <span class="td">
          <input type="text" name="make">
        </span>
        <span class="td">
          <input type="text" name="year">
        </span>
        <span class="td">
          <input type="text" name="mileage">
        </span>
        <span class="td">
          <input type="submit" value="Add" name="add_auto">
        </span>
      </form>
      <?php
      $stmt = $pdo->query("SELECT * FROM `autos` ORDER BY $sort");
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (count($rows) > 0) {
        foreach ($rows as $row) {
          echo "<div class='tr'>"
          , "<span class='td'>{$row['make']}</span>"
          , "<span class='td'>{$row['year']}</span>"
          , "<span class='td'>{$row['mileage']}</span>"
          , "<form class='td' method='POST'><input type='hidden' name='rem_id' value='{$row['auto_id']}'><input type='submit' value='Remove' name='rem_auto'></form>"
          , "</div>";
        }
      } else {
        //echo "<div class='tr'><span class='td'>No data so far</span><span class='td'></span><span class='td'></span><span class='td'></span></div>";
      }
      ?>
    </div>
  </body>
</html>