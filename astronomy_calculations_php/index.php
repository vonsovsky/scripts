#!/packages/run/php/bin/php

<?php
  session_start();
  error_reporting(E_ALL);
  set_time_limit(0);

  require 'db/connection.php';
  
  include 'includes/config.php';
  include 'includes/includes.php';
  include 'includes/deg.php';
  include 'includes/astrocalc.php';
  
  require 'includes/loadform.php';

  $page = isset($_GET['page']) ? $_GET['page'] : '';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Astronomické výpočty</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
</head>
<body>
  <div id="page">
    <div id="menu">
      <a href="index.php">Hlavní strana</a>&nbsp;&nbsp;|&nbsp;
      <a href="index.php?page=sunrise">Východ / západ slunce</a>&nbsp;&nbsp;&nbsp;
      <a href="index.php?page=rising">Nejjasnější hvězdy s časy</a>&nbsp;&nbsp;&nbsp;
      <a href="index.php?page=bsc">Bright Star Catalogue</a>&nbsp;&nbsp;&nbsp;
      <a href="index.php?page=variable">Proměnné hvězdy</a>&nbsp;&nbsp;&nbsp;
      <a href="index.php?page=planets">Planety</a>&nbsp;&nbsp;|&nbsp;
      <a href="index.php?page=places">Nastavení</a>&nbsp;&nbsp;&nbsp;
    </div>
    <?php
      if ($page == '') require('./template/main.php');
      if ($page == 'sunrise') require('./template/sunrise.php');
      if ($page == 'rising') require('./template/rising.php');
      if ($page == 'planets') require('./template/planets.php');

      if ($page == 'bsc' && isset($_GET['edit'])) require('./template/bsc_edit.php');
      elseif ($page == 'bsc' && isset($_GET['add'])) require('./template/bsc_add.php');
      elseif ($page == 'bsc') require('./template/bsc.php');

      if ($page == 'variable' && isset($_GET['edit'])) require('./template/variable_edit.php');
      elseif ($page == 'variable' && isset($_GET['add'])) require('./template/variable_add.php');
      elseif ($page == 'variable') require('./template/variable.php');

      if ($page == 'places' && isset($_GET['set'])) require('./template/place_detail.php');
      elseif ($page == 'places') require('./template/place.php');
    ?>
  </div>
</body>
</html>