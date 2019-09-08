<script type="text/javascript" src="js/functions.js"></script>

<?php
  include 'includes/variable.php';
  
  if (isset($_POST['calculate'])) { ?>
    <h1 onclick="toggleDiv('settingsdiv');" id="hsettingsdiv" class="cHeader"><span>-</span> Bright Star Catalogue</h1>
  <?php
    setcookie('date_from', $_POST['date_from'], time() + 86400 * 365);
    $_SESSION['date_from'] = $_POST['date_from'];

    setcookie('date_to', $_POST['date_to'], time() + 86400 * 365);
    $_SESSION['date_to'] = $_POST['date_to'];
  } else { ?>
    <h1>Bright Star Catalogue</h1>
  <?php }

  if (isset($_POST['search'])) {
    if ($_POST['search'] == '')
      $stars = dibi::query('SELECT [id], [name], [dm], [hd], [sao], [fk5], [varid], [ra], [de] FROM [bsc]')->fetchAll();
    else {
      // mezera v hledání funguje jako AND
      $parts = explode(' ', $_POST['search']);
      
      // proměnný počet výrazů, musím si dotaz poskládat sám
      $gpc = get_magic_quotes_gpc();
      $patterns = array();
      $patterns_2 = array();
      foreach ($parts as $i => $part) {
        $part = trim($part);
        if ($part == '') continue;
        if ($gpc == 0) $part = addslashes($part);
        $patterns[] = '[name] LIKE "%'.$part.'%"';
        $patterns_2[] = '[id] LIKE "%'.$part.'%"';
        $patterns_3[] = '[dm] LIKE "%'.$part.'%"';
        $patterns_4[] = '[hd] LIKE "%'.$part.'%"';
        $patterns_5[] = '[sao] LIKE "%'.$part.'%"';
        $patterns_6[] = '[fk5] LIKE "%'.$part.'%"';
        $patterns_7[] = '[varid] LIKE "%'.$part.'%"';
      }
      
      $stars = dibi::query('SELECT [id], [name], [dm], [hd], [sao], [fk5], [varid], [ra], [de] FROM [bsc]
                            WHERE '.implode(' AND ', $patterns).' OR '.implode(' AND ', $patterns_2).
                            ' OR '.implode(' AND ', $patterns_3).' OR '.implode(' AND ', $patterns_4).
                            ' OR '.implode(' AND ', $patterns_5).' OR '.implode(' AND ', $patterns_6).
                            ' OR '.implode(' AND ', $patterns_7).' LIMIT 1000')->fetchAll();
    }
  }
  
  if (isset($_GET['star'])) {
    $star = dibi::query('SELECT [name], [dm], [hd], [sao], [fk5], [varid], [ra], [de] FROM [bsc] WHERE [id] = %d', $_GET['star'])->fetch();
  }
  
  $search = isset($_POST['search']) ? $_POST['search'] : '';
  if ($search == '' && isset($star->name))
    $search = $star->name;

  $places = dibi::query('SELECT [location], [longitude], [latitude] FROM [places] ORDER BY [location]')->fetchAll();
?>

<div id="settingsdiv">
<?php if (!isset($_POST['getlist'])) { ?>
<h2 onclick="toggleDiv('adddiv');" id="hadddiv" class="cHeader"><span>+</span> Přidat hvězdu</h2>
<div id="adddiv" style="display: none;">
  <a href="index.php?page=variable&add=1">Přidat</a>
</div>
<h2 onclick="toggleDiv('searchdiv');" id="hsearchdiv" class="cHeader"><span>-</span> Vyhledat hvězdu</h2>
<div id="searchdiv">
  <form method="post" action="index.php?page=bsc">
    <div class="left_input">
      Katalogové číslo nebo jméno (např. CD-4015239)
    </div>
    <div class="right_input">
      <input type="text" name="search" value="<?php echo $search; ?>" />
    </div>
    <div class="clear"></div>
  
    <input type="submit" value="hledat" />
  </form>
</div>
<?php } ?>

<?php
  if (isset($_POST['getlist'])) {
    
    // nejdříve získat id vybraných hvězd
    $ids = $_POST['id'];
    $selected = array();
    foreach ($ids as $id) {
      if (isset($_POST['sel_star'.$id]) && $_POST['sel_star'.$id] == 'on')
        $selected[] = $id;
    }
    $stars = dibi::query('SELECT [id], [name], [ra], [de], [pmra], [pmde], [vmag]
                          FROM [bsc] WHERE [id] IN ('.implode(',', $selected).') ORDER BY [id]')->fetchAll();
  }
?>

<?php if (isset($_POST['search'])) { ?>
<form method="post" action="index.php?page=bsc">
  <h2>Nalezené záznamy (<?php echo count($stars); ?>)</h2>
  <input type="submit" name="getlist" value="zobrazit" />
  <br /><br />
  <table border="1" cellpadding="5" cellspacing="5">
    <tr>
      <th>Výběr</th>
      <th>Harvardské číslo</th>
      <th>Jméno (Bayer / Flamsteed)</th>
      <th>Durchmusterung</th>
      <th>Henry Draper</th>
      <th>SAO</th>
      <th>FK5</th>
      <th>ID prom. hv.</th>
      <th>Rektascenze (J2000)</th>
      <th>Deklinace (J2000)</th>
      <th>&nbsp;</th>
    </tr>
  <?php foreach ($stars as $star) {
    $ra = new Deg($star->ra);
    $dec = new Deg($star->de);
  ?>
    <tr>
      <td><input type="checkbox" name="sel_star<?php echo $star->id; ?>" /></td>
      <td><?php echo $star->id; ?>&nbsp;</td>
      <td><?php echo $star->name; ?>&nbsp;</td>
      <td><?php echo $star->dm; ?>&nbsp;</td>
      <td><?php echo $star->hd; ?>&nbsp;</td>
      <td><?php echo $star->sao; ?>&nbsp;</td>
      <td><?php echo $star->fk5; ?>&nbsp;</td>
      <td><?php echo $star->varid; ?>&nbsp;</td>
      <td><?php echo $ra->degrees.'h&nbsp;'.$ra->minutes.'m&nbsp;'.round($ra->seconds, 2).'s'; ?></td>
      <td><?php echo $dec->degrees.'°&nbsp;'.$dec->minutes.'′&nbsp;'.round($dec->seconds, 2).'″'; ?></td>
      <td><a href="index.php?page=bsc&edit=<?php echo $star->id; ?>">upravit</a></td>
      <input type="hidden" name="id[]" value="<?php echo $star->id; ?>" />
    </tr>
  <?php } ?>
  </table>
  <br />
  <input type="submit" name="getlist" value="zobrazit" />
</form>
<?php } ?>

<?php
  if (isset($_POST['getlist'])) {
    $localOffset = $_SESSION['localoffset'];
    if ($_SESSION['DST'] != '') $localOffset++;
    $astroCalc = new AstroCalc($_SESSION['latitude'], $_SESSION['longitude'], $localOffset);
    
    //$stars = dibi::query('SELECT [id], [name], [ra], [de], [pmra], [pmde], [vmag] FROM [bsc]
      //                    WHERE [id] IN ('.implode(',', $_POST['star']).') ORDER BY [id]')->fetchAll();
    $starcount = count($stars);
    
    $date_from = explode('.', $_SESSION['date_from']);
    $day_from = trim($date_from[0]);
    $month_from = trim($date_from[1]);

    $date_to = explode('.', $_SESSION['date_to']);
    $day_to = trim($date_to[0]);
    $month_to = trim($date_to[1]);

    $date_from = explode(' ', trim($date_from[2]));
    $year_from = trim($date_from[0]);

    $date_to = explode(' ', trim($date_to[2]));
    $year_to = trim($date_to[0]);

    $JD_from = julianDate($day_from, $month_from, $year_from);
    $JD_to = julianDate($day_to, $month_to, $year_to);
  ?>
  
  <h2 onclick="toggleDiv('rising_place');" id="hrising_place" class="cHeader"><span>-</span>
    Místo a čas pozorování
    <?php if (isset($_POST['calculate'])) { ?>
      &nbsp;<img src="css/pencil.png" />
    <?php } ?>
  </h2>
  <div id="rising_place">
    <form method="post" action="index.php?page=bsc">
      <?php require('./template/place_info.php'); ?>
  
      <div class="left_input">Datum do:</div>
      <div class="right_input" style="width: 300px;">
        <input id="f_date_t" type="text" class="i_s_middle" name="date_to"
               value="<?php echo $_SESSION['date_to']; ?>" />
        <button type="reset" id="f_trigger_t">...</button>
      </div>
      <div class="clear"></div>
      
      <input type="hidden" name="calculate" value="1" />
      <?php foreach ($stars as $star) { ?>
        <input type="hidden" name="id[]" value="<?php echo $star->id; ?>" />
        <input type="hidden" name="sel_star<?php echo $star->id; ?>" value="on" />
      <?php } ?>

      <div class="left_input">&nbsp;</div>
      <div class="right_input"><input type="submit" name="getlist" value="změnit datum" /></div>
      <div class="clear"></div>
    </form>
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

  <h2 onclick="toggleDiv('legend');" id="hlegend" class="cHeader"><span>-</span> Legenda</h2>
  <div id="legend">
    <div class="legend" style="background-color: <?php echo ERR_COLOR0; ?>;"></div>&nbsp;&nbsp;
    Hvězda je pozorovatelná za astronomického soumraku<br />
    <div class="legend" style="background-color: <?php echo ERR_COLOR1; ?>;"></div>&nbsp;&nbsp;
    Hvězda je pozorovatelná alespoň za oficiálního soumraku<br />
    <div class="legend" style="background-color: <?php echo ERR_COLOR2; ?>;"></div>&nbsp;&nbsp;
    Hvězda není pozorovatelná za tmy nebo nevychází vůbec<br />
    <br />
    <a href="javascript:history.go(-1)">Zpět</a>
  </div>
  
  <!-- helpdiv obaluje vršek tak, aby byl klikatelný -->
  </div>
  
  <table border="1" cellpadding="4" style="margin: auto;">
    <tr>
      <th>Harvardské číslo</th>
      <th>Název</th>
      <th>Rektascenze</th>
      <th>Deklinace</th>
      <th>Zdánlivá magnituda</th>
      <th>Východ</th>
      <th>Průchod nebeským poledníkem</th>
      <th>Západ</th>
      <th>Východ Slunce</th>
      <th>Západ Slunce</th>
    </tr>
  <?php
    $j = 0;
    for ($i = $JD_from + 0.5; $i <= $JD_to + 0.5; $i++) {
      $j += $starcount;
      $astroCalc->setDateTime($i);
      if ($j > 15) {
        echo '<tr>
                <th>Harvardské číslo</th>
                <th>Název</th>
                <th>Rektascenze</th>
                <th>Deklinace</th>
                <th>Zdánlivá magnituda</th>
                <th>Východ</th>
                <th>Průchod nebeským poledníkem</th>
                <th>Západ</th>
                <th>Východ Slunce</th>
                <th>Západ Slunce</th>
              </tr>';
        $j = 0;
      }
      
      $date = printJulianDate($i); // chci jen datum bez času
      echo '<tr><td colspan="10">'.substr($date, 0, strlen($date) - 12).'</td></tr>';
      $first = true;
      foreach ($stars as $star) {
        // oficiální západ
        $astroCalc->zenith = 90.8333;
        $sunrise = $astroCalc->sunrise($i);
        
        // astronomický soumrak
        $astroCalc->zenith = 108;
        $sunriseAstro = $astroCalc->sunrise($i);
        
        $posCorr = $astroCalc->doCorrections($star->ra, $star->de, $star->pmra, $star->pmde);
        $ra = $posCorr[0]; $dec = $posCorr[1];

        $astroCalc->zenith = 90;
        $riseData = $astroCalc->objRise(new Deg($ra), new Deg($dec));
  
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
      ?>
      <?php $ra = new Deg($ra); $dec = new Deg($dec); ?>
      <tr style="background-color: <?php echo $color; ?>;">
        <td><?php echo $star->id; ?></td>
        <td><?php echo $star->name; ?></td>
        <td><?php echo $ra->degrees.'h'.$ra->minutes.'m'.round($ra->seconds, 1).'s'; ?></td>
        <td><?php echo $dec->degrees.'°'.$dec->minutes."'".round($dec->seconds, 1)."''"; ?></td>
        <td><?php echo $star->vmag; ?></td>

        <?php if ($riseData['vychod'] == 'NR') { ?>
          <td><acronym title="Objekt <?php echo $star->id; ?> v daný den na daném místě nevychází">NR</acronym></td>
          <td><?php echo printJulianDate($riseData['pruchod'], false); ?></td>
          <td><acronym title="Objekt <?php echo $star->id; ?> v daný den na daném místě nevychází">NR</acronym></td>
        <?php } elseif ($riseData['vychod'] == 'NS') { ?>
          <td><acronym title="Objekt <?php echo $star->id; ?> v daný den na daném místě nezapadá">NS</acronym></td>
          <td><?php echo printJulianDate($riseData['pruchod'], false); ?></td>
          <td><acronym title="Objekt <?php echo $star->id; ?> v daný den na daném místě nezapadá">NS</acronym></td>
        <?php } else { ?>
          <td><?php echo printJulianDate($riseData['vychod'], false); ?></td>
          <td><?php echo printJulianDate($riseData['pruchod'], false); ?></td>
          <td><?php echo printJulianDate($riseData['zapad'], false); ?></td>
        <?php } ?>

        <?php
        if ($first) {
          if ($sunrise['vychod'] == 'NR') {
            echo '<td rowspan="'.$starcount.'" colspan="2">
                    <acronym title="Slunce v tento den nevychází nad zadanou hodnotu">NR</acronym>
                  </td>';
          } elseif ($sunrise['vychod'] == 'NS') {
            echo '<td rowspan="'.$starcount.'" colspan="2">
                    <acronym title="Slunce v tento den nezapadá pod zadanou hodnotu">NS</acronym>
                  </td>';
          } else {
          	echo '<td rowspan="'.$starcount.'">'.printJulianDate($sunrise['vychod'], false).'</td>';
          	echo '<td rowspan="'.$starcount.'">'.printJulianDate($sunrise['zapad'], false).'</td>';
          }
        }
        ?>
      </tr>
      <?php $first = false; ?>
    <?php } ?>
  <?php } ?>
  </table>
<?php } ?>
