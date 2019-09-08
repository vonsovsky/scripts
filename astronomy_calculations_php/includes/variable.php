<?php

/*
 * Výpočet minim proměnných hvězd
 *   1.
 *   2. Zavolání getCycle na nastavení aktuální pozice křivky
 *   3. getMinimaDates vrátí všechna minima do určitého data
 */ 

class Variable {
  var $star; // objekt s daty proměnné hvězdy
  var $step; // pokud jsou přítomna obě minima, bude se krokovat po 0.5
  var $E;
  var $c;
  
  public function __construct($id) {
    $this->star = dibi::query('SELECT [id], [name], [minima_info], [minima], [period], [ra], [dec], [mv1], [mv2]
                               FROM [ephem] WHERE [id] = %i', $id)->fetch();

    $this->step = 1;
    if (strtolower($this->star->minima_info) == 'all')
      $this->step = 0.5;
  }

  function curvePos($JD) {
    return ($JD - $this->star->minima) / $this->star->period;
  }
  
  function getCycle($JD_from, $offset) {
    $sec = (strtolower($this->star->minima_info) == 'sec') ? 0.5 : 0.0;

    $this->c = $this->curvePos($JD_from - $offset / 24.0);
    $this->E = ceil($this->c + $sec);
  }
  
  // Vrátí pole časů minim až do určitého data
  function getMinimaDates($JD_to, $offset, $time = true) {
    $dates = array();
    $m0 = $this->star->minima;
    $P = $this->star->period;

    $i = 0;
    while ($m0 + ($this->E + $i) * $P + $offset / 24.0 <= $JD_to) {
      $t = $m0 + ($this->E + $i) * $P + $offset / 24.0;
      if ($time) {
        $dates[] = array($t, $i); // uloží se čas a krok pro rozlišení minima
      } else {
        if (!isset($dates[round($t)]))
          $dates[round($t)] = 1;
        else $dates[round($t)]++;
      }
      $i += $this->step;
    }
    
    return $dates;
  }
  
  // get metoda pro název hvězdy
  function getName() {
    return $this->star->name;
  }

  // get metoda pro rektascenzi
  function getRA() {
    return $this->star->ra;
  }
  
  // get metoda pro deklinaci
  function getDec() {
    return $this->star->dec;
  }
  
  // get metoda pro cyklus
  function getE() {
    return $this->E;
  }
  
  // get metoda pro fázi na křivce
  function getC() {
    return $this->c;
  }
  
  function getPMRA() {
    return $this->star->mv1;
  }
  
  function getPMDec() {
    return $this->star->mv2;
  }

  function imageOutput() {
    $x = 1000;
    $y = 300;
    
    $gd = imagecreatetruecolor($x, $y);
    $red = imagecolorallocate($gd, 255, 0, 0); 
    
    for ($i = 0; $i < 200; $i++) {
      //$fi = julianDateTime()
      $fi = ((2452500.3510 + $i / 200) - 2452500.3510) / 0.62892863;
      $fi = $fi - (int)$fi;
      imagesetpixel($gd, $i, 300 * $fi, $red);
      echo 300 * $fi.'<br />';
    }
     
    header('Content-Type: image/png');
    imagepng($gd);
  }
}
?>