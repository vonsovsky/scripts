<?php if (isset($_POST['calculate'])) { ?>
  <h1 onclick="toggleDiv('helpdiv');" id="hhelpdiv" class="cHeader"><span>-</span> Výpočet východu / západu slunce</h1>
<?php
  setcookie('date_from', $_POST['date_from'], time() + 86400 * 365);
  $_SESSION['date_from'] = $_POST['date_from'];

  setcookie('date_to', $_POST['date_to'], time() + 86400 * 365);
  $_SESSION['date_to'] = $_POST['date_to'];
} else { ?>
  <h1>Výpočet východu / západu slunce</h1>
<?php } ?>

<div id="helpdiv">
  <h2 onclick="toggleDiv('sunrise_place');" id="hsunrise_place" class="cHeader"><span>-</span>
    Místo a čas pozorování
    <?php if (isset($_POST['calculate'])) { ?>
      &nbsp;<img src="css/pencil.png" />
    <?php } ?>
  </h2>
  <div id="sunrise_place">
    <form method="post" action="index.php?page=sunrise">
      <?php require('./template/place_info.php'); ?>
  
      <div class="left_input">Datum do:</div>
      <div class="right_input" style="width: 300px;">
        <input id="f_date_t" type="text" class="i_s_middle" name="date_to"
               value="<?php echo $_SESSION['date_to']; ?>" />
        <button type="reset" id="f_trigger_t">...</button>
      </div>
      <div class="clear"></div>
  
      <div class="left_input">&nbsp;</div>
      <div class="right_input"><input type="submit" name="calculate" value="změnit datum" /></div>
      <div class="clear"></div>
    </form>
  </div>
</div>

<script type="text/javascript"> 
  Calendar.setup({
      inputField     :    "f_date_t",
      ifFormat       :    "%d. %m. %Y",
      showsTime      :    true,
      button         :    "f_trigger_t",
      singleClick    :    true,
      step           :    1
  });
</script> 

<?php
  function printRisingTime($val) {
    if ($val == 'NR')
      echo '<acronym title="Slunce v tento den nevychází nad zadanou hodnotu">NR</acronym>';
    elseif ($val == 'NS')
      echo '<acronym title="Slunce v tento den nezapadá pod zadanou hodnotu">NS</acronym>';
    else echo printJulianDate($val, false);
  }

  $localOffset = $_SESSION['localoffset'];
  if ($_SESSION['DST'] != '') $localOffset++;

  $zenith90 = new AstroCalc($_SESSION['latitude'], $_SESSION['longitude'], $localOffset, 90.83333);
  $zenith96 = new AstroCalc($_SESSION['latitude'], $_SESSION['longitude'], $localOffset, 96);
  $zenith102 = new AstroCalc($_SESSION['latitude'], $_SESSION['longitude'], $localOffset, 102);
  $zenith108 = new AstroCalc($_SESSION['latitude'], $_SESSION['longitude'], $localOffset, 108);
  
  $date_from = explode('.', $_SESSION['date_from']);
  $date_to = explode('.', $_SESSION['date_to']);
  
  $julianFrom = julianDate($date_from[0], $date_from[1], $date_from[2]);
  $julianTo = julianDate($date_to[0], $date_to[1], $date_to[2]);
  
  $index = 0;
?>
<table cellpadding="2" cellspacing="2" border="1">
  <tr>
    <th>Datum</th>
    <th>Astronomický úsvit</th>
    <th>Nautický úsvit</th>
    <th>Občanský úsvit</th>
    <th>Východ</th>
    <th>Kulminace</th>
    <th>Západ</th>
    <th>Občanský soumrak</th>
    <th>Nautický soumrak</th>
    <th>Astronomický soumrak</th>
  </tr>
  <?php for ($i = $julianFrom; $i <= $julianTo; $i++) {
    $index++;
    $zenith90->setDateTime($i);
    $zenith96->setDateTime($i);
    $zenith102->setDateTime($i);
    $zenith108->setDateTime($i);

    $astro = $zenith108->sunrise($i);
    $nautical = $zenith102->sunrise($i);
    $civil = $zenith96->sunrise($i);
    $official = $zenith90->sunrise($i);
    
    $NR = 'Slunce v tento den nevychází nad zadanou hodnotu';
    $NS = 'Slunce v tento den nezapadá pod zadanou hodnotu';
    
    $date = printJulianDate($i);
    $date = substr($date, 0, strrpos($date, ':') - 3);
    $date = str_replace(' ', '&nbsp;', $date);
  ?>
    <tr>
      <td><?php echo $date; ?></td>
      <td><?php printRisingTime($astro['vychod']); ?></td>
      <td><?php printRisingTime($nautical['vychod']); ?></td>
      <td><?php printRisingTime($civil['vychod']); ?></td>
      <td><?php printRisingTime($official['vychod']); ?></td>
      <td><?php echo printJulianDate($civil['pruchod'], false); ?></td>
      <td><?php printRisingTime(isset($official['zapad']) ? $official['zapad'] : $official['vychod']); ?></td>
      <td><?php printRisingTime(isset($civil['zapad']) ? $civil['zapad'] : $civil['vychod']); ?></td>
      <td><?php printRisingTime(isset($nautical['zapad']) ? $nautical['zapad'] : $nautical['vychod']); ?></td>
      <td><?php printRisingTime(isset($astro['zapad']) ? $astro['zapad'] : $astro['vychod']); ?></td>
    </tr>

    <?php if ($index % 10 == 0) { ?>
    <tr>
      <th>Datum</th>
      <th>Astronomický úsvit</th>
      <th>Nautický úsvit</th>
      <th>Občanský úsvit</th>
      <th>Východ</th>
      <th>Kulminace</th>
      <th>Západ</th>
      <th>Občanský soumrak</th>
      <th>Nautický soumrak</th>
      <th>Astronomický soumrak</th>
    </tr>
    <?php } ?>

  <?php } ?>
</table>
