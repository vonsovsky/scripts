<?php
  /*
   *    Stáhne poziční data podle hvězd z ephem.txt z GCVS katalogu
   *    rektascenzi, deklinaci a vlastní pohyb hvězdy 
   * 
   *    Reference
   *    Samus N.N., Durlevich O.V., Kazarovets E V., Kireeva N.N., Pastukhova E.N., Zharova A.V.,
   *    et al. General Catalog of Variable Stars (GCVS database, Version 2011 Jan), CDS B/gcvs 
   */

  set_time_limit(0);
  error_reporting(E_ALL);

  require '../db/connection.php';
  include '../includes/deg.php';
  
  $variables = dibi::query('SELECT [id], [name] FROM [ephem] WHERE [id] >= 1')->fetchAll();
  
  // pro každou hvězdu stáhne data a vypreparuje z nich potřebné informace
  //foreach ($variables as $variable) {
    //$parts = explode(' ', $variable->name);
    //if (strlen($parts[1]) > 3)
      //$parts[1] = substr($parts[1], 0, 3);
    //$name = $parts[1].'+'.$parts[0];
    
    //$file = fopen('http://www.sai.msu.su/gcvs/cgi-bin/search.cgi?search='.$name, 'r');
    $file = fopen('http://www.sai.msu.su/gcvs/cgi-bin/search.cgi?search=V994+HER', 'r');
    if ($file == null)
      echo 'Varování: soubor '.$name.' nenalezen.<br />';
    
    $remlines = 0;
    while (($line = fgets($file)) !== false) {
      if (strpos($line, 'The positional information') !== false) $remlines = 5;
      
      if ($remlines > 0) $remlines--;
      // dorazili jsme na potřebný řádek
      if ($remlines == 1) {
        // [0] => 010012 RT And [1] => 231110.10 +530133.0 [2] => -0.007 -0.021 [3] => 2000.0 [4] => [5] => Hip
        $p = explode('|', $line);
        $pos = explode(' ', trim($p[1]));
        $mv = explode(' ', trim($p[2]));
        if (!isset($mv[1]))
          $mv[1] = '';
        
        // hodnota na webu je ve stupních
        $ra = new Deg(substr($pos[0], 0, 2), substr($pos[0], 2, 2), substr($pos[0], 4, 5));
        $dec = new Deg(substr($pos[1], 0, 3), substr($pos[1], 3, 2), substr($pos[1], 5, 4));
        
        //dibi::query('UPDATE [ephem] SET [ra] = %f, [dec] = %f, [mv1] = %s, [mv2] = %s, [epoch] = %s WHERE [id] = %i',
         //           $ra->degToDec(), $dec->degToDec(), $mv[0], $mv[1], trim($p[3]), $variable->id);
        dibi::query('UPDATE [ephem] SET [ra] = %f, [dec] = %f, [mv1] = %s, [mv2] = %s, [epoch] = %s WHERE [id] = %i',
                    $ra->degToDec(), $dec->degToDec(), $mv[0], $mv[1], trim($p[3]), 1704);
        
        break; // dál už nemusíme html soubor číst
      }
    }
    fclose($file);
  //}
?>