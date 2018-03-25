<?php
$debug = false;

session_start();

if ( isset($_SESSION['name']) ) {
  $userName = $_SESSION['name'];
  include_once 'pdo.php';
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
    <h1>Welcome to the AutosDB web application</h1>
    <?php if(!isset($userName)): ?>
    
    <p>
      <span>In order to use the application <a href="login.php">please log in</a></span>
    </p>
    <?php else: ?>
    <?php
    if (isset($_SESSION['error'])) {
      echo '<p class="alert" style="display: block;">' . $_SESSION['error'] . '</p>';
      unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
      echo '<p class="success" style="display: block;">' . $_SESSION['success'] . '</p>';
      unset($_SESSION['success']);
    }
    ?>
    <?php
    $stmt = $pdo->query("SELECT * FROM `autos`");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) > 0) {
    ?>
    
    <div class="table result">
      <div class="tr">
        <span class="th">Make</span>
        <span class="th">Model</span>
        <span class="th">Year</span>
        <span class="th">Mileage</span>
        <span class="th">Action</span>
      </div>
      <?php
        foreach ($rows as $row) {
          echo '<div class="tr">'
          , '<span class="td">' . htmlentities($row['make']) . '</span>'
          , '<span class="td">' . htmlentities($row['model']) . '</span>'
          , '<span class="td">' . htmlentities($row['year']) . '</span>'
          , '<span class="td">' . htmlentities($row['mileage']) . '</span>'
          , '<span class="td"><a href="edit.php?id=' . $row['auto_id'] . '">Edit</a> / <a href="delete.php?id=' . $row['auto_id'] . '">Delete</a></span>'
          , "</div>\n";
        }
      ?>
      
    </div>
    <?php } else { ?>
    
    <p>No rows found</p>
    <?php }; ?>
    <p>
      <a href="add.php">Add New Entry</a>
    </p>
    <p>
      <a href="logout.php">Logout</a>
    </p>
    <?php endif; ?>
    <?php if ($debug): ?>
      <pre>
        <?php var_dump($_GET); ?>
        <?php var_dump($_POST); ?>
      </pre>
    <?php endif; ?>
    
  </body>
</html>