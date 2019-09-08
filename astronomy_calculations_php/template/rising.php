<?php
  if (isset($_POST['calculate'])) { ?>
    <h1 onclick="toggleDiv('helpdiv');" id="hhelpdiv" class="cHeader"><span>-</span> 50 nejjasnějších hvězd</h1>
  <?php
    setcookie('date_from', $_POST['date_from'], time() + 86400 * 365);
    $_SESSION['date_from'] = $_POST['date_from'];
  } else { ?>
    <h1>50 nejjasnějších hvězd</h1>
  <?php }

  $places = dibi::query('SELECT [location], [longitude], [latitude] FROM [places] ORDER BY [location]')->fetchAll();
?>

<div id="helpdiv">

<h2 onclick="toggleDiv('rising_place');" id="hrising_place" class="cHeader"><span>-</span>
  Místo a čas pozorování
  <?php if (isset($_POST['calculate'])) { ?>
    &nbsp;<img src="css/pencil.png" />
  <?php } ?>
</h2>
<div id="rising_place">
  <form method="post" action="index.php?page=rising">
    <?php require('./template/place_info.php'); ?>

    <div class="left_input">&nbsp;</div>
    <div class="right_input"><input type="submit" name="calculate" value="změnit datum" /></div>
    <div class="clear"></div>
  </form>
</div>


<?php
  $date = explode('.', $_SESSION['date_from']);
  $day = trim($date[0]);
  $month = trim($date[1]);
  $year = trim($date[2]);

  $localOffset = $_SESSION['localoffset'];
  if ($_SESSION['DST'] != '') $localOffset++;

  $astroCalc = new AstroCalc($_SESSION['latitude'], $_SESSION['longitude'], $localOffset);
  $astroCalc->setDateTime($day, $month, $year, 0);
  
  $stars = dibi::query('SELECT [name], [ident], [const], [vmag], [ra], [dec] FROM [stars]')->fetchAll();
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

<table border="1" cellpadding="4">
  <tr>
    <th>Název</th>
    <th>Identifikace</th>
    <th>Zdánlivá magnituda</th>
    <th>Rektascenze</th>
    <th>Deklinace</th>
    <th>Východ</th>
    <th>Průchod nebeským poledníkem</th>
    <th>Západ</th>
  </tr>
<?php foreach ($stars as $star) {
    // korekce o precesi
    $posCorr = $astroCalc->doCorrections($star->ra, $star->dec);
    $starRa = $posCorr[0]; $starDec = $posCorr[1];
  
    $astroCalc->zenith = 90;
    $riseData = $astroCalc->objRise(new Deg($starRa), new Deg($starDec), 1);

    $error = 0;
    // slunce nezajde nebo hvězda nevyjde
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
            
    if ($error == 0) $color = ERR_COLOR0;
    if ($error == 1) $color = ERR_COLOR1;
    if ($error == 2) $color = ERR_COLOR2;
  
    $ra = new Deg($starRa); $dec = new Deg($starDec);
  ?>
  <?php $starName = ($star->name != '') ? $star->name : $star->ident.' '.$star->const; ?>
  <tr style="background-color: <?php echo $color; ?>;">
    <td><?php echo $star->name; ?></td>
    <td><?php echo $star->ident.' '.$star->const; ?></td>
    <td><?php echo $star->vmag; ?></td>
    <td><?php echo $ra->degrees.'h'.$ra->minutes.'m'.round($ra->seconds, 1).'s'; ?></td>
    <td><?php echo $dec->degrees.'°'.$dec->minutes."'".round($dec->seconds, 1)."''"; ?></td>
    <?php if ($riseData['vychod'] == 'NR') { ?>
      <td><acronym title="<?php echo $starName; ?> v daný den na daném místě nevychází">NR</acronym></td>
      <td><?php echo printJulianDate($riseData['pruchod']); ?></td>
      <td><acronym title="<?php echo $starName; ?> v daný den na daném místě nevychází">NR</acronym></td>
    <?php } elseif ($riseData['vychod'] == 'NS') { ?>
      <td><acronym title="<?php echo $starName; ?> v daný den na daném místě nezapadá">NS</acronym></td>
      <td><?php echo printJulianDate($riseData['pruchod']); ?></td>
      <td><acronym title="<?php echo $starName; ?> v daný den na daném místě nezapadá">NS</acronym></td>
    <?php } else { ?>
      <td><?php echo printJulianDate($riseData['vychod']); ?></td>
      <td><?php echo printJulianDate($riseData['pruchod']); ?></td>
      <td><?php echo printJulianDate($riseData['zapad']); ?></td>
    <?php } ?>
  </tr>
<?php } ?>
</table>

