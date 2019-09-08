#!/packages/run/php/bin/php
<?php
Header("Content-type: image/png");

/*
 * Skript pro vykreslování polohy proměnných hvězd v čase s minimy
 * 
 */ 

require '../db/connection.php';
include '../includes/variable.php';
include '../includes/deg.php';
include '../includes/astrocalc.php';
include '../includes/includes.php';
 
// A4 formát
$w = 4 * 297;
$h = 4 * 210;

$img = ImageCreate($w, $h);
$background = ImageColorAllocate($img, 0, 162, 232);
ImageFill($img, 0, 0, $background);
$sunrises = array();

map($img, $w, $h);

ImagePNG($img);
ImageDestroy($img);

function setJD() {
  $date_from = explode('.', $_GET['date_from']);
  $day_from = trim($date_from[0]);
  $month_from = trim($date_from[1]);

  $date_to = explode('.', $_GET['date_to']);
  $day_to = trim($date_to[0]);
  $month_to = trim($date_to[1]);

  $date_from = explode(' ', trim($date_from[2]));
  $year_from = trim($date_from[0]);

  $date_to = explode(' ', trim($date_to[2]));
  $year_to = trim($date_to[0]);

  $JD_from = julianDateTime($day_from, $month_from, $year_from, 0, 0, 0);
  $JD_to = julianDateTime($day_to, $month_to, $year_to, 24, 0, 0);
  
  // vykreslení max. 3 dnů, pak už není mapa přehledná
  if ($JD_to - $JD_from > 3)
    $JD_to = $JD_from + 3;
  
  return array($JD_from, $JD_to);
}

// časy východů a západů slunce, na tmavé pozadí se postupně
// zanese světlejší astronomický den a nakonec světlý oficiální den
function getSunTimes($img, $astrocalc, $JD_from, $JD_to, $w, $h) {
  $back1 = ImageColorAllocate($img, 160, 160, 160);
  $back2 = ImageColorAllocate($img, 192, 192, 192);
  $back3 = ImageColorAllocate($img, 224, 224, 224);
  
  $sunrises = array();
  $separator = 0; // pokud bude použit neproporcionální čas, bude dobré od sebe dny oddělit
  $hours = 0; // v případě neproporcionálního času musí být počítán čas, který už v jednotlivých dnech uplynul

  for ($i = $JD_from; $i < $JD_to; $i++) {
    $astrocalc->zenith = 90.8333;
    $sunrise = $astrocalc->sunrise($i);
    $sunrise_n = $astrocalc->sunrise($i + 1); // výpočty následujícího dne
    if ($sunrise['vychod'] == 'NS') {
      $sunrises[] = round($hours);
      $sunrises[] = 0; $sunrises[] = 0;
      $hours += (1 + $separator) * ($i - $JD_from);
    } elseif ($sunrise['vychod'] == 'NR') {
      $sunrises[] = round($hours);
      $sunrises[] = $i - $JD_from; $sunrises[] = 1 + $i - $JD_from;
      $hours += (1 + $separator) * ($i - $JD_from);
    } elseif ($sunrise['vychod'] != 'NR') {
      $separator = 1 / 24;

      $sunrises[] = $hours; // na místě miliardtin vznikají nepřesnosti

      $mv_h = 0;
      $mv_h = $sunrise['zapad'] * 24 - (int)($sunrise['zapad'] * 24) < 0.5 ? 1 : 0;
      $sunrises[] = (int)$sunrise['zapad'] + (int)(frac($sunrise['zapad']) * 24) / 24 - $mv_h / 24;

      $mv_h = 0;
      $mv_h = ceil($sunrise_n['vychod'] * 24) - $sunrise['zapad'] * 24 > 0.5 ? 1 : 0;
      $sunrises[] = (int)$sunrise_n['vychod'] + ceil(frac($sunrise_n['vychod']) * 24) / 24 + $mv_h / 24;

      $hours += $sunrises[3 * ($i - $JD_from) + 2] - $sunrises[3 * ($i - $JD_from) + 1] + $separator;
    }
  }
  $hours -= $separator;
  $sunrises[] = $hours;
  
  if (!isset($_GET['tisk'])) {
    for ($i = $JD_from; $i < $JD_to; $i++) {
      imagefilledrectangle($img, timePosition($sunrises[3 * ($i - $JD_from) + 1], $sunrises, $hours, $w), 0,
                                 timePosition($sunrises[3 * ($i - $JD_from) + 2], $sunrises, $hours, $w), $h, $back3);    
  
      $astrocalc->zenith = 90.8333;
      $sunrise = $astrocalc->sunrise($i);
      $sunrise_n = $astrocalc->sunrise($i + 1);
      if ($sunrise['vychod'] == 'NS') {
        imagefilledrectangle($img, timePosition($sunrises[3 * ($i - $JD_from) + 1], $sunrises, $hours, $w), 0,
                                   timePosition($sunrises[3 * ($i - $JD_from) + 2], $sunrises, $hours, $w), $h, $back2);    
      } elseif ($sunrise['vychod'] == 'NR') {
      } elseif ($sunrise['vychod'] != 'NR') {
        imagefilledrectangle($img, timePosition($sunrise['zapad'], $sunrises, $hours, $w), 0,
                                   timePosition($sunrise_n['vychod'], $sunrises, $hours, $w), $h, $back2);
      }
  
      $astrocalc->zenith = 108;
      $sunrise = $astrocalc->sunrise($i);
      $sunrise_n = $astrocalc->sunrise($i + 1);
      if ($sunrise['vychod'] != 'NR' && $sunrise['vychod'] != 'NS') {
        imagefilledrectangle($img, timePosition($sunrise['zapad'], $sunrises, $hours, $w), 0,
                                   timePosition($sunrise_n['vychod'], $sunrises, $hours, $w), $h, $back1);
      }
    }
  } else {
      $white = ImageColorAllocate($img, 255, 255, 255);
      imagefilledrectangle($img, 0, 0, $w, $h, $white);    
  }

  return $sunrises;
}

function getDay($time, $sunrises) {
  for ($i = 0; $i < (int)(count($sunrises) / 3); $i++) {
    if ($time >= $sunrises[$i * 3 + 1] && $time <= $sunrises[$i * 3 + 2])
      return $i;
  }
  return -1;
}

// čas bývá nespojitý, podle předaného času určí den a vrátí pozici na grafu
function timePosition($time, $sunrises, $hours, $w) {
  $day = getDay($time, $sunrises);
  
  // čas je mimo povolený rozsah, tedy ve dne
  if ($day == -1)
    return -1;
  
  $frac = ($sunrises[$day * 3 + 2] - $sunrises[$day * 3 + 1]);
  return (($time - $sunrises[$day * 3 + 1]) / ($sunrises[$day * 3 + 2] - $sunrises[$day * 3 + 1]) * $frac + $sunrises[$day * 3]) / $hours * $w;
}

function map($img, $w, $h) {
  $colors = array(ImageColorAllocate($img, 63, 72, 204), ImageColorAllocate($img, 128, 0, 128),
                  ImageColorAllocate($img, 128, 0, 0), ImageColorAllocate($img, 255, 128, 0),
                  ImageColorAllocate($img, 0, 128, 0));
  $gray = ImageColorAllocate($img, 40, 40, 40);
  $font_file = './FreeSans.ttf';
  $font_file_b = './FreeSansBold.ttf';
  
  $localOffset = $_GET['localOffset'];
  if (isset($_GET['DST'])) $localOffset++;
  $astrocalc = new AstroCalc($_GET['latitude'], $_GET['longitude'], $localOffset);

  $moonSize = 20;
  // maska na Měsíc kvůli alfě
  $mask = imagecreatetruecolor($moonSize, $moonSize);  
  imagealphablending($mask, true);  

  $JD = setJD();
  $JD_from = $JD[0];
  $JD_to = $JD[1];
  
  $sunrises = getSunTimes($img, $astrocalc, $JD_from, $JD_to, $w, $h);
  $hours = end($sunrises);
  $days = (int)(count($sunrises) / 3);

  $recommended = isset($_GET['recommended']) ? 1 : 0;

  $starIds = isset($_GET['star']) ? $_GET['star'] : array();
  
  // log
  $now = date('Y-m-d H:i:s');
  $obj = new Objects(parseDateToJD($now));
  $f = $obj->moonPhase();
  if (substr($_SERVER['REMOTE_ADDR'], 0, 6) != '66.249')
  dibi::query('INSERT INTO [log_star] ([stars], [date_from], [date_to], [datetime], [moonphase], [ip], [recommended])
               VALUES (%s, %s, %s, %s, %f, %s, %i)',
               implode(',', $starIds), $_GET['date_from'], $_GET['date_to'], $now, $f, $_SERVER['REMOTE_ADDR'], $recommended);
  // log

  foreach ($starIds as $index => $varId) {
    // Maximálně 5 hvězd
    if ($index >= 5)
      break;
    
    $var = new Variable($varId);
    $var->getCycle($JD_from, $localOffset);

    $posCorr = $astrocalc->doCorrections($var->getRA(), $var->getDec(), $var->getPMRA(), $var->getPMDec());
    $starRA = $posCorr[0]; $starDec = $posCorr[1];
    //$starRA = $var->getRA(); $starDec = $var->getDec();

    $dates = $var->getMinimaDates($JD_to + 1, $localOffset);

  
    $haveMinima = false;
    // minima vyznačí kroužky odpovídajících barev
    foreach ($dates as $time) {
      $astrocalc->setDateTime($time[0] - $localOffset / 24);
      $pos = $astrocalc->calcObjectPos($starRA, $starDec, false);

      $wEl = 10; $hEl = 10;
      // sekundární minima dostanou menší kotoučky
      if ($time[1] != (int)$time[1]) {
        $wEl = 6; $hEl = 6;
      }
      
      $xpos = timePosition($time[0], $sunrises, $hours, $w);
      if ($xpos >= 0 && $pos[0] >= $_GET['min_altitude']) {
        $haveMinima = true;

        $_pos = getRisePos($astrocalc->JD, 'moon');
        $_pos = $astrocalc->calcObjectPos($_pos[0] / 15, $_pos[1], false);

        imageFilledEllipse($img, timePosition($time[0], $sunrises, $hours, $w),
                                 -$h / 10 + $h - $pos[0] * $h / 100, $wEl, $hEl, $colors[$index]);
        printDescription($img, $w, $h, timePosition($time[0], $sunrises, $hours, $w) - 20,
                         -$h / 10 + $h - $pos[0] * $h / 100 + 20, $colors[$index], $font_file, $time[0]);

        // Měsíc se nahraje na masku a až potom sloučí s obrazem kvůli alpha blendingu
        if ($_pos[0] > -10) {
          moonP($mask, $time[0], 0, 0, $moonSize, $moonSize);
          imagecopymerge($img, $mask, timePosition($time[0], $sunrises, $hours, $w) - 12, -$h / 10 + $h - $_pos[0] * $h / 100 - 12,
                          0, 0, $moonSize, $moonSize, 100);
          
          $delta = new Deg(abs($pos[1] - $_pos[1]) / 15);
          printDescription($img, $w, $h, timePosition($time[0], $sunrises, $hours, $w) - 50,
                           -$h / 10 + $h - $_pos[0] * $h / 100 + 28, $gray, $font_file, '∆A = '.$delta->degrees.'h '.$delta->minutes.'m', false);
        }
      }
    }

    // popis hvězdy v odpovídající barvě
    $outText = $var->getName();
    if (!$haveMinima)
      $outText .= ' (x)';
    imagefttext($img, 13, 0, 5 + $index * $w / 6, 20, $colors[$index], $font_file_b, $outText);

    // křivka pohybu po obloze
    for ($i = 0; $i < $days; $i++) {
      for ($j = $sunrises[$i * 3 + 1]; $j <= $sunrises[$i * 3 + 2]; $j += 1 / 1440) {
        $astrocalc->setDateTime($j - $localOffset / 24, 0);
        $pos = $astrocalc->calcObjectPos($starRA, $starDec, false);
        imageSetPixel($img, timePosition($j, $sunrises, $hours, $w), -$h / 10 + $h - $pos[0] * $h / 100, $colors[$index]);
      }
    }

  }
  
  // čas v hodinách
  for ($i = 0; $i < $days; $i++) {
    // hodiny se budou číst přímo z řetězce přepočtu, přičte se 1 sekunda, která někdy vypadne při zaokrouhlování
    preg_match('#\s([\d]+):#', printJulianDate($sunrises[$i * 3 + 1] + 1 / 86400), $match);
    if (isset($match[1])) {
      $start_hour = $match[1];
      // vypíše hodiny bez jedné ve výplni (round)
      for ($j = $sunrises[$i * 3 + 1]; round($j * 24) < round($sunrises[$i * 3 + 2] * 24); $j += 1 / 24) {
        imageline($img, timePosition($j, $sunrises, $hours, $w), 0,
                        timePosition($j, $sunrises, $hours, $w), $h, $gray);
        imagefttext($img, 10, 0, timePosition($j, $sunrises, $hours, $w) + 5, $h - 5, $gray, $font_file,
                    (round(($j - $sunrises[$i * 3 + 1]) * 24 + $start_hour) % 24).'h');
      }
    }
  }

  // výška
  for ($i = -10; $i < 90; $i += 10) {
    imageline($img, 0, -$h / 10 + $h - $i * $h / 100, $w, -$h / 10 + $h - $i * $h / 100, $gray);
    imagefttext($img, 10, 0, 5, -$h / 10 + $h - $i * $h / 100, $gray, $font_file, $i.'°');
  }
}

// postará se o vypsání malého popisku tak, aby nezajížděl za obraz
// předpokládané rozměry 40x20
function printDescription($img, $w, $h, $x, $y, $color, $font_file, $time, $isTime = true) {
  if ($isTime)
    $outText = str_replace('<br />', '', printJulianDate($time, false));
  else $outText = $time;

  if ($x > $w - 40)
    $x = $w - 40;
  if ($x < 40)
    $x = 40;
  if ($y > $h - 20)
    $y = $h - 20;
  if ($y < 20)
    $y = 20;
  imagefttext($img, 13, 0, $x, $y, $color, $font_file, $outText);
}
?>
