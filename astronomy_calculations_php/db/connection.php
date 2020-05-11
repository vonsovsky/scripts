<?php
  if (file_exists('db/astro.db')) {
    dibi::connect(array(
        'driver'   => 'sqlite3',
        'database' => 'db/astro.db',
    ));
  }

  if (file_exists('../db/astro.db')) {
    dibi::connect(array(
        'driver'   => 'sqlite3',
        'database' => '../db/astro.db',
    ));
  }
?>
