<?php
  $path = '';
  if (file_exists('../includes/functions.php'))
    $path = '../';

  include_once $path.'includes/functions.php';
  include_once $path.'includes/objects.php';
  include_once $path.'includes/ecliptic_obliquity.php';
  include_once $path.'includes/nutation.php';
?>