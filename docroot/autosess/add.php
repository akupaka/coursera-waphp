<?php
$debug = false;

session_start();

if (!isset($_SESSION['name'])) {
  die('Not logged in');
} else {
  $userName = $_SESSION['name'];
}

include_once 'pdo.php';

if (isset($_POST['cancel'])) {
  header('Location: view.php');
  return;
}
if (isset($_POST['add_auto'])) {
  /*
  $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_STRING);
  $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_STRING);
  $mileage = filter_input(INPUT_POST, 'mileage', FILTER_SANITIZE_STRING);
  */
  $make = htmlentities($_POST['make']);
  $year = htmlentities($_POST['year']);
  $mileage = htmlentities($_POST['mileage']);
  if ($make === "") {
    $_SESSION['error_add'] = 'Make is required';
    header('Location: add.php');
    return;
  } else if (!(is_numeric($year) && is_numeric($mileage))) {
    $_SESSION['error_add'] = 'Mileage and year must be numeric';
    header('Location: add.php');
    return;
  } else {
    $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)');
    $stmt->execute(array(':mk' => $make, ':yr' => $year, ':mi' => $mileage));
    $_SESSION['success_add'] = 'Record inserted';
    header('Location: view.php');
    return;
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
    <h2>Add new car:</h2>
    <?php
    if (isset($_SESSION['error_add'])) {
      echo '<p class="alert" style="display: block;">' . $_SESSION['error_add'] . '</p>';
      unset($_SESSION['error_add']);
    }
    ?>
    <div class="table result">
      <div class="tr">
        <span class="th">Make</span>
        <span class="th">Year</span>
        <span class="th">Mileage</span>
        <span class="th">Options</span>
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
          <input type="submit" value="Cancel" name="cancel">
        </span>
      </form>
    </div>
    <?php if ($debug): ?>
      <pre>
        <?php var_dump($_GET); ?>
        <?php var_dump($_POST); ?>
      </pre>
    <?php endif; ?>
  </body>
</html>