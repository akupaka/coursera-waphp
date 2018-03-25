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
if (isset($_POST['edit'])) {
  $id = htmlentities($_POST['id']);
  $make = htmlentities($_POST['make']);
  $model = htmlentities($_POST['model']);
  $year = htmlentities($_POST['year']);
  $mileage = htmlentities($_POST['mileage']);
  if ($id === '') {
    $_SESSION['error'] = 'Missing auto id';
    header('Location: .');
    return;
  } else if ($make === '' || $model === '' || $year === '' || $mileage === '') {
    $_SESSION['error'] = 'All fields are required';
    header('Location: edit.php?id=' . $id);
    return;
  } else if (!is_numeric($year)) {
    $_SESSION['error'] = 'Year must be integer';
    header('Location: edit.php?id=' . $id);
    return;
  } else if (!is_numeric($mileage)) {
    $_SESSION['error'] = 'Mileage must be integer';
    header('Location: edit.php?id=' . $id);
    return;
  } else {
    $stmt = $pdo->prepare('UPDATE `autos` SET make = :mk, model = :md, year = :yr, mileage = :mi WHERE auto_id = :id');
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':md' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage'],
        ':id' => $id
        ));
    $_SESSION['success'] = 'Record edited';
    header('Location: .');
    return;
  }
}
if (!isset($_GET['id'])) {
  $_SESSION['error'] = 'Missing auto id';
  header('Location: .');
  return;
}
$id = htmlentities($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM `autos` WHERE auto_id = :id");
$stmt->execute(array(':id' => $id));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
  $_SESSION['error'] = 'Bad value for auto id';
  header('Location: .');
  return;
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
    <h2>Edit car:</h2>
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
        <span class="th">Actions</span>
      </div>
      <form method="POST" class="tr">
        <input type="hidden" name="id" value="<?= $id ?>">
        <span class="td">
          <input type="text" name="make" value="<?= htmlentities($row['make']) ?>">
        </span>
        <span class="td">
          <input type="text" name="model" value="<?= htmlentities($row['model']) ?>">
        </span>
        <span class="td">
          <input type="text" name="year" value="<?= htmlentities($row['year']) ?>">
        </span>
        <span class="td">
          <input type="text" name="mileage" value="<?= htmlentities($row['mileage']) ?>">
        </span>
        <span class="td">
          <input type="submit" value="Edit" name="edit">
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