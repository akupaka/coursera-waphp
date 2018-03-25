<?php

#$pdo = new PDO('mysql:host=localhost;port=8889;dbname=misc', 'fred', 'zap');
$pdo = new PDO('mysql:host=db;port=3306;dbname=autosdb', 'autos_user', 'autos_pass');

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
