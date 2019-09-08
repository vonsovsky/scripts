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
  
  include '../includes/deg.php';
  
  $file = fopen('stars.txt', 'r');
  $i = 0;
  dibi::query('DELETE FROM [stars]');
  while (($line = fgets($file)) != NULL) {
    $i++;
    if ($i <= 2) continue;
    
    $line = str_replace("\t", ' ', $line);
    while (strpos($line, '  ') !== false)
      $line = str_replace('  ', ' ', $line);
    $p = explode(' ', $line);
    
    $RA = new Deg($p[9], $p[10], $p[11]);
    $D = new Deg($p[12], $p[13], $p[14]);
    
    $p[1] = trim(str_replace('_', ' ', $p[1]));

    dibi::query('INSERT INTO [stars] ([name], [ident], [const], [vmag], [dist],
                             [prec], [amag], [spectral], [ra], [dec], [hip])
                 VALUES (%s, %s, %s, %f, %f, %f, %f, %s, %f, %f, %i)',
                 $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7],
                 $p[8], $RA->degToDec(), $D->degToDec(), $p[15]);
  }
  fclose($file);
?>