<?php if (isset($_POST['calculate'])) { ?>
  <h1 onclick="toggleDiv('helpdiv');" id="hhelpdiv" class="cHeader"><span>-</span> Výpočet východu / západu slunce</h1>
<?php
  setcookie('date_from', $_POST['date_from'], time() + 86400 * 365);
  $_SESSION['date_from'] = $_POST['date_from'];
} else { ?>
  <h1>Výpočet východu / západu slunce</h1>
<?php } ?>

<?php
  if (isset($_POST['calculate'])) {
    echo '<h1 onclick="toggleDiv(\'helpdiv\');" id="hhelpdiv" class="cHeader"><span>-</span> Planety a Měsíc</h1>';

    setcookie('date_from', $_POST['date_from'], time() + 86400 * 365);
    $_SESSION['date_from'] = $_POST['date_from'];
  } else echo '<h1>Planety a Měsíc</h1>';
  
  $places = dibi::query('SELECT [location], [longitude], [latitude] FROM [places] ORDER BY [location]')->fetchAll();
?>

<div id="helpdiv">
<h2 onclick="toggleDiv('planets_place');" id="hplanets_place" class="cHeader"><span>-</span> Místo a čas pozorování</h2>
<div id="planets_place">
  <form method="post" action="index.php?page=planets">
    <?php require('./template/place_info.php'); ?>

    <div class="left_input">&nbsp;</div>
    <div class="right_input"><input type="submit" name="calculate" value="změnit datum" /></div>
    <div class="clear"></div>
  </form>
</div>

<?php
  include('includes/includes.php');

  $date = explode('.', $_SESSION['date_from']);
  $day = trim($date[0]);
  $month = trim($date[1]);
  $year = trim($date[2]);

  $localOffset = $_SESSION['localoffset'];
  if ($_SESSION['DST'] != '') $localOffset++;
  $astroCalc = new AstroCalc($_SESSION['latitude'], $_SESSION['longitude'], 0);
  $astroCalc->setDateTime($day, $month, $year, 0);
  $baseTime = $astroCalc->JD; // základní čas do nultého kroku iterativního přibližování v pozicích
  
  $names = array(
    'mercury' => 'Merkur',
    'venus' => 'Venuše',
    'mars' => 'Mars',
    'jupiter' => 'Jupiter',
    'saturn' => 'Saturn',
    'uranus' => 'Uran',
    'neptune' => 'Neptun',
    'moon' => 'Měsíc',
  );
  
  
  $astroCalc->zenith = 90.83333;
  $sunrise = $astroCalc->sunrise($day, $month, $year);
  
  // astronomický soumrak
  $astroCalc->zenith = 108;
  $sunriseAstro = $astroCalc->sunrise($day, $month, $year);
  ?>
  <?php
  echo '<h2 onclick="toggleDiv(\'sunofficial\');" id="hsunofficial" class="cHeader"><span>-</span> Západ</h2>';
  echo '<div id="sunofficial">';
  if ($sunrise['vychod'] == 'NR') {
    echo 'Slunce v tento den nevychází nad zadanou hodnotu<br />';
   	printf("Průchod nebeským poledníkem:\t"); echo printJulianDate($sunrise['pruchod']);
  } elseif ($sunrise['vychod'] == 'NS') {
    echo 'Slunce v tento den nezapadá pod zadanou hodnotu<br />';
   	printf("Průchod nebeským poledníkem:\t"); echo printJulianDate($sunrise['pruchod']);
  } else {
  	printf("Východ Slunce:\t\t\t"); echo printJulianDate($sunrise['vychod']);
  	printf("Průchod nebeským poledníkem:\t"); echo printJulianDate($sunrise['pruchod']);
  	printf("Západ Slunce:\t\t\t"); echo printJulianDate($sunrise['zapad']);
  }
  echo '</div>';

  echo '<h2 onclick="toggleDiv(\'sunastro\');" id="hsunastro" class="cHeader"><span>-</span> Astronomický soumrak</h2>';
  echo '<div id="sunastro">';
  if ($sunriseAstro['vychod'] == 'NR') {
    echo 'Slunce v tento den nevychází nad zadanou hodnotu<br />';
   	printf("Průchod nebeským poledníkem:\t"); echo printJulianDate($sunriseAstro['pruchod']);
  } elseif ($sunriseAstro['vychod'] == 'NS') {
    echo 'Slunce v tento den nezapadá pod zadanou hodnotu<br />';
   	printf("Průchod nebeským poledníkem:\t"); echo printJulianDate($sunriseAstro['pruchod']);
  } else {
  	printf("Východ Slunce:\t\t\t"); echo printJulianDate($sunriseAstro['vychod']);
  	printf("Průchod nebeským poledníkem:\t"); echo printJulianDate($sunriseAstro['pruchod']);
  	printf("Západ Slunce:\t\t\t"); echo printJulianDate($sunriseAstro['zapad']);
  }
  echo '</div>';
?>
  
  <h2 onclick="toggleDiv('moonphase');" id="hmoonphase" class="cHeader"><span>-</span> Fáze Měsíce</h2>
  <div id="moonphase">
    Fáze Měsíce k zadanému datu<br />
    <img src="includes/moonphase.php?date=<?php echo julianDateTime($day, $month, $year, 24, 0, 0); ?>" width="100" height="100" />
  </div>

  <h2 onclick="toggleDiv('legend');" id="hlegend" class="cHeader"><span>-</span> Legenda</h2>
  <div id="legend">
    <div class="legend" style="background-color: <?php echo ERR_COLOR0; ?>;"></div>&nbsp;&nbsp;
    Hvězda je pozorovatelná za astronomického soumraku<br />
    <div class="legend" style="background-color: <?php echo ERR_COLOR1; ?>;"></div>&nbsp;&nbsp;
    Hvězda je pozorovatelná alespoň za občanského soumraku (vlastní jasnost se nebere v potaz)<br />
    <div class="legend" style="background-color: <?php echo ERR_COLOR2; ?>;"></div>&nbsp;&nbsp;
    Hvězda není pozorovatelná za tmy nebo nevychází vůbec<br />
    <br />
  </div>
  
  <!-- helpdiv obaluje vršek tak, aby byl klikatelný -->
  </div>
  
  <table border="1" cellpadding="4" style="width: 100%;">
    <tr>
      <th>Název</th>
      <th>Rektascenze</th>
      <th>Deklinace</th>
      <th>Východ</th>
      <th>Průchod nebeským poledníkem</th>
      <th>Západ</th>
    </tr>
  <?php
    $astroCalc->zenith = 90;
    $riseData = array();

    foreach($names as $name => $czName) {
      if ($name == 'earth' || $name == 'sun') continue;

      $astroCalc->setDateTime($baseTime);
      
      $maxIter = 1;
      // a Měsíc se pohybuje celkem rychle, je dobré použít iterativní metodu pro zpřesnění polohy
      if ($name == 'moon')
        $maxIter = 9;

      $horizonDelta = 0;
      $riseTime = 0;
      $setTime = 0;
      $noRise = false;
      $noSet = false;

      // iterativně počítaná poloha objektu, za časy se dosazují průchody nebeským poledníkem
      for ($iter = 0; $iter < $maxIter; $iter++) {
        if ($iter > 0 && $iter < $maxIter / 3) {
          // pokud další přiblížení překročí půlnoc, Měsíc ten den nevychází
          if (frac($astroCalc->JD) < 0.5 && frac($riseData['vychod']) > 0.5)
            $noRise = true;

          $astroCalc->setDateTime($baseTime + frac($riseData['vychod'] - $baseTime));
        }
        if ($iter == $maxIter / 3) {
          // pokud další přiblížení překročí půlnoc, Měsíc ten den nevychází
          if (frac($astroCalc->JD) < 0.5 && frac($riseData['vychod']) > 0.5)
            $noRise = true;

          $riseTime = $baseTime + frac($riseData['vychod'] - $baseTime);
          $astroCalc->setDateTime($baseTime);
        }
        if ($iter > $maxIter / 3 && $iter < $maxIter * 2 / 3) {
          // pokud další přiblížení překročí půlnoc, Měsíc ten den nezapadá
          if (frac($astroCalc->JD) < 0.5 && frac($riseData['zapad']) > 0.5)
            $noSet = true;

          $astroCalc->setDateTime($baseTime + frac($riseData['zapad'] - $baseTime));
        }
        if ($iter == $maxIter * 2 / 3) {
          // pokud další přiblížení překročí půlnoc, Měsíc ten den nezapadá
          if (frac($astroCalc->JD) < 0.5 && frac($riseData['zapad']) > 0.5)
            $noSet = true;

          $setTime = $baseTime + frac($riseData['zapad'] - $baseTime);
          $astroCalc->setDateTime($baseTime);
        }
        if ($iter > $maxIter * 2 / 3) {
          $astroCalc->setDateTime($riseData['pruchod']);
        }

        $pos = getRisePos($astroCalc->JD, $name);
        $riseData = $astroCalc->objRise(new Deg($pos[0] / 15), new Deg($pos[1]), 3);

        // rektascenze a deklinace pochází ze začátku dne
        if ($iter == 0) {
          $ra = new Deg($pos[0] / 15); $dec = new Deg($pos[1]);
        }
      }
      
      // Měsíc neopisuje kružnici, od průchodu nebeského poledníku zapadne rychleji
      if ($name == 'moon') {
        if ($riseTime > $riseData['pruchod'])
          $riseTime--;
        if ($setTime < $riseTime)
          $setTime++;
        
        // Měsíc tento den neprochází nebeským poledníkem
        if ($riseData['pruchod'] >= $baseTime + 1) {
          $riseData['pruchod'] = '***';
        }

        if ($noRise) $riseData['vychod'] = '***';
        else $riseData['vychod'] = $riseTime;

        if ($noSet) $riseData['zapad'] = '***';
        else $riseData['zapad'] = $setTime;
      }
      
      foreach ($riseData as $index => $data)
        if ($data > 0)
          $riseData[$index] += $localOffset / 24;                  

      $error = 0;
      // slunce nezajde nebo planeta nevyjde
      if ($sunrise['vychod'] == 'NS' || $riseData['vychod'] == 'NR') $error = 2;
      // hvězda vychází jenom když je slunce na obloze
      if ($riseData['vychod'] > 0 && $sunrise['vychod'] > 0 && $riseData['vychod'] >= $sunrise['vychod'] &&
          $riseData['zapad'] > 0 && $sunrise['zapad'] > 0 && $riseData['zapad'] <= $sunrise['zapad'])
            $error = 2;
  
      if ($error == 0) {
        // slunce nezajde do astronomického soumraku
        if ($sunriseAstro['vychod'] == 'NS') $error = 1;
        // hvězda vychází jenom když je slunce na obloze
        if ($riseData['vychod'] > 0 && $sunriseAstro['vychod'] > 0 && $riseData['vychod'] >= $sunriseAstro['vychod'] &&
            $riseData['zapad'] > 0 && $sunriseAstro['zapad'] > 0 && $riseData['zapad'] <= $sunriseAstro['zapad'])
              $error = 1;
      }
      
      $_pos = $astroCalc->calcObjectPos(new Deg($pos[0]), new Deg($pos[1]), $riseData['pruchod']);
              
      if ($error == 0) $color = ERR_COLOR0;
      if ($error == 1) $color = ERR_COLOR1;
      if ($error == 2) $color = ERR_COLOR2;
    ?>
    <tr style="background-color: <?php echo $color; ?>;">
      <td><?php echo $czName; ?></td>
      <td><?php echo $ra->degrees.'h'.$ra->minutes.'m'; ?></td>
      <td><?php echo $dec->degrees.'°'.$dec->minutes."'"; ?></td>
      <?php if ($riseData['vychod'] == 'NR') { ?>
        <td><acronym title="<?php echo $czName; ?> v daný den na daném místě nevychází">NR</acronym></td>
        <td><?php echo printJulianDate($riseData['pruchod']); ?></td>
        <td><acronym title="<?php echo $czName; ?> v daný den na daném místě nevychází">NR</acronym></td>
      <?php } elseif ($riseData['vychod'] == 'NS') { ?>
        <td><acronym title="<?php echo $czName; ?> v daný den na daném místě nezapadá">NS</acronym></td>
        <td><?php echo printJulianDate($riseData['pruchod']); ?></td>
        <td><acronym title="<?php echo $czName; ?> v daný den na daném místě nezapadá">NS</acronym></td>
      <?php } else { ?>
        <td><?php echo printJulianDate($riseData['vychod']); ?></td>
        <td><?php echo printJulianDate($riseData['pruchod']); ?></td>
        <td><?php echo printJulianDate($riseData['zapad']); ?></td>
      <?php } ?>
    </tr>
  <?php } ?>
  </table>

