<?php
  /*
   *   Katalog proměnných hvězd z adresy
   *   http://www.as.up.krakow.pl/ephem/
   *
   *   Prý mám nechat i citaci na
   *   Up-to-date Linear Elements of Close Binaries", J.M. Kreiner, 2004, Acta Astronomica, vol. 54, pp 207-210
   *   
   *   Tento skript převede data z testového souboru ephem.txt do SQLite databáze
   */   
  
  set_time_limit(0);
  error_reporting(E_ALL);

  require '../db/connection.php';
  
  $file = fopen('ephem.txt', 'r');
  $i = 0;
  dibi::query('DELETE FROM [ephem]');
  while (($line = fgets($file)) != NULL) {
    $i++;
    if ($i <= 2) continue;
    
    $line = str_replace('(', ' ', $line);
    $line = str_replace(')', ' ', $line);
    while (strpos($line, '  ') !== false)
      $line = str_replace('  ', ' ', $line);
    $p = explode(' ', $line);

    dibi::query('INSERT INTO [ephem] ([name], [minima_info], [minima], [mean_error],
                             [period], [period_error], [all], [pri], [sec], [e],
                             [ccd], [v], [pg], [p]) VALUES (%s, %s, %f, %s, %f,
                             %s, %i, %i, %i, %i, %i, %i, %i, %i)',
                             $p[0].' '.$p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7],
                             $p[8], $p[9], $p[10], $p[11], $p[12], $p[13], $p[14]);
  }
  fclose($file);
?>