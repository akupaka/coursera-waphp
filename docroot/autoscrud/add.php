<?php
$debug = false;

session_start();

if (!isset($_SESSION['name'])) {
  die('ACCESS DENIED');
} else {
  $userName = $_SESSION['name'];
}

include_once 'pdo.php';

if (isset($_POST['cancel'])) {
  header('Location: .');
  return;
}
if (isset($_POST['add_auto'])) {
  $make = htmlentities($_POST['make']);
  $model = htmlentities($_POST['model']);
  $year = htmlentities($_POST['year']);
  $mileage = htmlentities($_POST['mileage']);
if ($make === '' || $model === '' || $year === '' || $mileage === '') {
    $_SESSION['error'] = 'All fields are required';
    header('Location: add.php');
    return;
  } else if (!is_numeric($year)) {
    $_SESSION['error'] = 'Year must be integer';
    header('Location: add.php');
    return;
  } else if (!is_numeric($mileage)) {
    $_SESSION['error'] = 'Mileage must be integer';
    header('Location: add.php');
    return;
  } else {
    $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)');
    $stmt->execute(array(':mk' => $_POST['make'], ':md' => $_POST['model'], ':yr' => $_POST['year'], ':mi' => $mileage));
    $_SESSION['success'] = 'Record added';
    header('Location: .');
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
    if (isset($_SESSION['error'])) {
      echo '<p class="alert" style="display: block;">' . $_SESSION['error'] . '</p>';
      unset($_SESSION['error']);
    }
    ?>
    <div class="table result">
      <div class="tr">
        <span class="th">Make</span>
        <span class="th">Model</span>
        <span class="th">Year</span>
        <span class="th">Mileage</span>
        <span class="th">Options</span>
      </div>
      <form method="POST" class="tr">
        <span class="td">
          <input type="text" name="make">
        </span>
        <span class="td">
          <input type="text" name="model">
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