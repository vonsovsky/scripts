<?php
  $err = array();
?>
<script type="text/javascript" src="js/functions.js"></script>

<?php
  if (isset($_POST['calculate'])) { ?>
    <h1 onclick="toggleDiv('settingsdiv');" id="hsettingsdiv" class="cHeader"><span>-</span> Proměnné hvězdy</h1>
  <?php
    setcookie('date_from', $_POST['date_from'], time() + 86400 * 365);
    $_SESSION['date_from'] = $_POST['date_from'];

    setcookie('date_to', $_POST['date_to'], time() + 86400 * 365);
    $_SESSION['date_to'] = $_POST['date_to'];

    setcookie('min_altitude', $_POST['min_altitude'], time() + 86400 * 365);
    $_SESSION['min_altitude'] = $_POST['min_altitude'];
  } elseif (isset($_GET['star'])) { ?>
    <h1 onclick="toggleDiv('settingsdiv'); toggleDiv('helpdiv');" id="hsettingsdiv" class="cHeader"><span>-</span> Proměnné hvězdy</h1>
  <?php } else { ?>
    <h1>Proměnné hvězdy</h1>
  <?php }

  $places = dibi::query('SELECT [location], [longitude], [latitude] FROM [places] ORDER BY [location]')->fetchAll();
?>

<?php
  include 'includes/variable.php';
  
  $now = date('d. m. Y H:i:s');
  $obj = new Objects(parseDateToJD($now));
  $f = $obj->moonPhase();

  // Pokud se nachází požadovaná fáze v daném rozsahu a minimální výšce,
  // bude její objekt navrácen k možnosti výběru
  // z SQL se vyberou takové, kde f1 < t2 ^ f2 > t1
  if (isset($_POST['phase_from'])) {

    // log
    dibi::query('INSERT INTO [log_param] ([search], [time_from], [time_to], [phase_from], [phase_to], [min_altitude], [offset], [dst],
                             [datetime], [moonphase], [ip])
                 VALUES (%s, %s, %s, %s, %s, %f, %i, %i, %s, %f, %s)',
                 $_POST['search_query'], $_POST['time_from'], $_POST['time_to'], $_POST['phase_from'], $_POST['phase_to'],
                 $_POST['min_altitude'], $_SESSION['localoffset'], $_SESSION['DST'] != '' ? '1' : '0', $now, $f, $_SERVER['REMOTE_ADDR']);

    setcookie('time_from', $_POST['time_from'], time() + 86400 * 365);
    $_SESSION['time_from'] = $_POST['time_from'];

    setcookie('time_to', $_POST['time_to'], time() + 86400 * 365);
    $_SESSION['time_to'] = $_POST['time_to'];

    setcookie('phase_from', $_POST['phase_from'], time() + 86400 * 365);
    $_SESSION['phase_from'] = $_POST['phase_from'];

    setcookie('phase_to', $_POST['phase_to'], time() + 86400 * 365);
    $_SESSION['phase_to'] = $_POST['phase_to'];

    setcookie('min_altitude', $_POST['min_altitude'], time() + 86400 * 365);
    $_SESSION['min_altitude'] = $_POST['min_altitude'];

    $t1 = parseDateToJD($_POST['time_from']);
    $t2 = parseDateToJD($_POST['time_to']);
    $offset = $_SESSION['localoffset'];
    if ($_SESSION['DST'] != '') $offset++;
    $f1 = $_POST['phase_from'];
    $f2 = $_POST['phase_to'];
    
    if ($f1 < 0 || $f1 >= 1)
      $err[] = '<div class="error">f<sub>1</sub> musí ležet v intervalu [0, 1)</div>';
    
    if ($f2 < 0 || $f2 >= 2)
      $err[] = '<div class="error">f<sub>2</sub> musí ležet v intervalu [0, 2)</div>';

    if ($f2 < $f1)
      $err[] = '<div class="error">f<sub>2</sub> musí být větší než f<sub>1</sub></div>';

    if (empty($err)) {
      $astroCalc = new AstroCalc($_SESSION['latitude'], $_SESSION['longitude'], $offset);

      $stars = getParametrizedVariables($astroCalc, $t1, $t2, $f1, $f2, $offset, $_POST['search_query'],
                                        $_SESSION['min_altitude']);
    }
  }
  
  if (isset($_POST['search'])) {

    // log
    dibi::query('INSERT INTO [log_search] ([search], [date_from], [date_to], [datetime], [moonphase], [ip])
                 VALUES (%s, %s, %s, %s, %f, %s)',
                 $_POST['search'], $_SESSION['date_from'], $_SESSION['date_to'], $now, $f, $_SERVER['REMOTE_ADDR']);

    if ($_POST['search'] == '')
      $stars = dibi::query('SELECT [id], [name], [minima_info], [minima], [period], [ra], [dec], [mv1], [mv2] FROM [ephem]')->fetchAll();
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
      }
      
      $stars = dibi::query('SELECT [id], [name], [minima_info], [minima], [period], [ra], [dec], [mv1], [mv2] FROM [ephem]
                            WHERE '.implode(' AND ', $patterns).' OR '.implode(' AND ', $patterns_2))->fetchAll();
    }
  }
  
  if (isset($_GET['star'])) {
    // log
    if (substr($_SERVER['REMOTE_ADDR'], 0, 6) != '66.249')
    dibi::query('INSERT INTO [log_star] ([star], [date_from], [date_to], [datetime], [moonphase], [ip])
                 VALUES (%i, %s, %s, %s, %f, %s)',
                 $_GET['star'], $_SESSION['date_from'], $_SESSION['date_to'], $now, $f, $_SERVER['REMOTE_ADDR']);

    $var = new Variable($_GET['star']);
  }
  
  // na Unixu cd ml && ./k-neighbors, na Windows cd ml && k-neighbors.exe
  $recomIds = explode(' ', exec('cd ml && k-neighbors.exe '.round($f, 5).' '.$_SESSION['longitude'].' '.$_SESSION['latitude']));
  $first10 = array_slice($recomIds, 0, 10);
  $last90 = array_slice($recomIds, 10, 90);
  $recommended = dibi::query('SELECT [id], [name] FROM [ephem] WHERE [id] IN ('.implode(',', $first10).')');
  $recommended_ext = dibi::query('SELECT [id], [name] FROM [ephem] WHERE [id] IN ('.implode(',', $last90).')');
  
  $deltaJD = julianDate(date('j'), date('n'), date('Y')) - julianDate(1, 1, 2000);

  $search = isset($_POST['search']) ? $_POST['search'] : '';
  if ($search == '' && isset($star->name))
    $search = $star->name;

  $places = dibi::query('SELECT [location], [longitude], [latitude] FROM [places] ORDER BY [location]')->fetchAll();
  
  foreach ($err as $msg)
    echo $msg;
?>

<div id="settingsdiv">
<h2 onclick="toggleDiv('adddiv');" id="hadddiv" class="cHeader"><span>+</span> Přidat hvězdu</h2>
<div id="adddiv" style="display: none;">
  <a href="index.php?page=variable&add=1">Přidat</a>
</div>

<h2 onclick="toggleDiv('recomdiv');" id="hrecomdiv" class="cHeader"><span>+</span> Doporučené</h2>
<div id="recomdiv" style="display: none;">

<form method="get" action="includes/map.php" target="_blank">  
  <input type="submit" name="getlist" value="Vykreslit" />
  &nbsp;
  <input type="checkbox" id="tisk" name="tisk" /><label for="tisk">Tisková verze</label>
  <br /><br />
  <table border="1" cellpadding="5" cellspacing="5" style="float: left; margin-right: 20px;">
    <tr>
      <th>Výběr</th>
      <th>Název</th>
    </tr>
    <?php foreach ($recommended as $recom) { ?>
      <tr>
        <td><input type="checkbox" name="star[]" value="<?php echo $recom->id; ?>" /></td>
        <td><a href="index.php?page=variable&star=<?php echo $recom->id; ?>"><?php echo $recom->name; ?></a></td>
      </tr>
    <?php } ?>
  </table>
  
  <div style="color: 666;">
    Jedná se o experimentální součást systému na bázi strojového učení. Srovnávání s uživatelem vybranými hvězdami v minulosti
    probíhá na základě 5 parametrů
    a to konkrétně rektascenze, fáze Měsíce, perioda, fáze hvězdy o půlnoci daného dne a její výška o půlnoci. Data se zpětně
    sbírají a vyhodnocuje se efektivita. Funkčnost zajišťuje externí program, jehož běh nelze s určitostí zaručit.
  </div>
  <div class="clear"></div>
  

  <span class="link" onclick="$('#rec_extended').slideToggle('fast');">další</span>
  <table border="1" cellpadding="5" cellspacing="5" style="display: none;" id="rec_extended">
    <tr>
      <th>Výběr</th>
      <th>Název</th>
    </tr>
    <?php foreach ($recommended_ext as $recom) { ?>
      <tr>
        <td><input type="checkbox" name="star[]" value="<?php echo $recom->id; ?>" /></td>
        <td><a href="index.php?page=variable&star=<?php echo $recom->id; ?>"><?php echo $recom->name; ?></a></td>
      </tr>
    <?php } ?>
  </table>

  <?php
    $date_from = trim(substr($_SESSION['time_from'], 0, strpos($_SESSION['time_from'], ':') - 2));
    $date_to = trim(substr($_SESSION['time_to'], 0, strpos($_SESSION['time_to'], ':') - 2));
  ?>
  <input type="hidden" name="date_from" value="<?php echo $date_from; ?>" />
  <input type="hidden" name="date_to" value="<?php echo $date_to; ?>" />
  <input type="hidden" name="localOffset" value="<?php echo $_SESSION['localoffset']; ?>" />
  <?php if ($_SESSION['DST'] != '') { ?>
    <input type="hidden" name="DST" value="1" />
  <?php } ?>
  <input type="hidden" name="latitude" value="<?php echo $_SESSION['latitude']; ?>" />
  <input type="hidden" name="longitude" value="<?php echo $_SESSION['longitude']; ?>" />
  <input type="hidden" name="min_altitude" value="<?php echo $_SESSION['min_altitude']; ?>" />

  <input type="hidden" name="recommended" value="1" />
</form>
</div>


<h2 onclick="toggleDiv('paramdiv');" id="hparamdiv" class="cHeader"><span>-</span> Parametrické hledání</h2>
<div id="paramdiv">
  <form method="post" action="index.php?page=variable">
    <?php require('./template/param_form.php'); ?>
    <input type="submit" value="prohledat" />
  </form>
</div>

<h2 onclick="toggleDiv('searchdiv');" id="hsearchdiv" class="cHeader"><span>-</span> Vyhledat hvězdu</h2>
<div id="searchdiv">
  <form method="post" action="index.php?page=variable">
    <div class="left_input">
      Název hvězdy (např. AND RT)
    </div>
    <div class="right_input">
      <input type="text" name="search" value="<?php echo $search; ?>" />
    </div>
    <div class="clear"></div>
  
    <input type="submit" value="hledat" />
  </form>
</div>

<?php
  if (isset($_POST['getlist'])) {
    
    // nejdříve získat id vybraných hvězd
    $ids = $_POST['id'];
    $selected = array();
    foreach ($ids as $id) {
      if (isset($_POST['sel_star'.$id]) && $_POST['sel_star'.$id] == 'on')
        $selected[] = $id;
    }
    $stars = dibi::query('SELECT * FROM [ephem] WHERE [id] IN ('.implode(',', $selected).') ORDER BY [id]')->fetchAll();
  }
?>

<?php if (isset($_POST['phase_from']) && empty($err)) { ?>
<form method="get" action="includes/map.php" target="_blank">  
  <h2>Nalezené záznamy (<?php echo count($stars); ?>)</h2>
  <input type="submit" name="getlist" value="Vykreslit" />
  &nbsp;
  <input type="checkbox" id="tisk" name="tisk" /><label for="tisk">Tisková verze</label><br /><br />
  <?php
   echo '<a href="includes/'.('xml_var.php?time_from='.$_SESSION['time_from'].'&time_to='.$_SESSION['time_to'].
            '&offset='.$offset.'&phase_from='.$_SESSION['phase_from'].'&phase_to='.$_SESSION['phase_to'].
            '&search_query='.$_POST['search_query'].'&latitude='.$_SESSION['latitude'].'&longitude='.$_SESSION['longitude'].
            '&min_altitude='.$_SESSION['min_altitude']).'">XML Export</a>';
  ?>
  <br /><br />
  <table border="1" cellpadding="5" cellspacing="5">
    <tr>
      <th>Výběr</th>
      <th>Název</th>
      <th>Průběh</th>
      <th>&nbsp;</th>
    </tr>
  <?php foreach ($stars as $star) {
    $ra = new Deg($star->ra);
    $dec = new Deg($star->dec);
  ?>
    <tr>
      <td><input type="checkbox" name="star[]" value="<?php echo $star->id; ?>" /></td>
      <td><a href="index.php?page=variable&star=<?php echo $star->id; ?>"><?php echo $star->name; ?></a></td>
      <td>
      <?php
        //$timeCount = count($star->from);
        
        foreach ($star->from as $i => $from) {
          $timeFrom = substr(printJulianDate($star->from[$i]), 0, -6);
          // stejný den, stačí vypsat jen čas
          if ((int)($star->from[$i] - 0.5) == (int)($star->to[$i] - 0.5)) {
            $part = explode(' ', printJulianDate($star->to[$i]));
            $timeTo = substr($part[3], 0, 5);
          } else {
            $timeTo = substr(printJulianDate($star->to[$i]), 0, -6);
          }
          
          $astroCalc->setDateTime($star->from[$i] - $offset / 24);
          $pos_from = $astroCalc->calcObjectPos($star->ra, $star->dec, false);
          $astroCalc->setDateTime($star->to[$i] - $offset / 24);
          $pos_to = $astroCalc->calcObjectPos($star->ra, $star->dec, false);
          echo '<acronym title="Výška '.round($pos_from[0], 1).'°">'.$timeFrom.'</acronym> - ';
          echo '<acronym title="Výška '.round($pos_to[0], 1).'°">'.$timeTo.'</acronym>&nbsp;';

          $astroCalc->setDateTime($star->from[$i] + ($star->to[$i] - $star->from[$i]) / 2 - $offset / 24);
          $pos = $astroCalc->calcObjectPos($star->ra, $star->dec, false);
          $time = substr(printJulianDate($star->from[$i] + ($star->to[$i] - $star->from[$i]) / 2), 0, -6);
          echo '<acronym title="Výška nad obzorem v '.$time.'">'.round($pos[0], 1).'°</acronym><br />';
        }
      ?>
      </td>
      <td><a href="index.php?page=variable&edit=<?php echo $star->id; ?>">upravit</a></td>
    </tr>
  <?php } ?>
  </table>
  <?php
    $date_from = trim(substr($_SESSION['time_from'], 0, strpos($_SESSION['time_from'], ':') - 2));
    $date_to = trim(substr($_SESSION['time_to'], 0, strpos($_SESSION['time_to'], ':') - 2));
  ?>
  <input type="hidden" name="date_from" value="<?php echo $date_from; ?>" />
  <input type="hidden" name="date_to" value="<?php echo $date_to; ?>" />
  <input type="hidden" name="localOffset" value="<?php echo $_SESSION['localoffset']; ?>" />
  <?php if ($_SESSION['DST'] != '') { ?>
    <input type="hidden" name="DST" value="1" />
  <?php } ?>
  <input type="hidden" name="latitude" value="<?php echo $_SESSION['latitude']; ?>" />
  <input type="hidden" name="longitude" value="<?php echo $_SESSION['longitude']; ?>" />
  <input type="hidden" name="min_altitude" value="<?php echo $_SESSION['min_altitude']; ?>" />
  
  <br />
  <input type="submit" name="getlist" value="Vykreslit" />
  &nbsp;
  <input type="checkbox" id="tisk2" name="tisk" /><label for="tisk2">Tisková verze</label>
</form>
<?php } ?>

<?php if (isset($_POST['search'])) { ?>
<h2 onclick="toggleDiv('rising_place');" id="hrising_place" class="cHeader"><span>-</span>
  Místo a čas pozorování
  <?php if (isset($_POST['calculate'])) { ?>
    &nbsp;<img src="css/pencil.png" />
  <?php } ?>
</h2>
<div id="rising_place">
  <form method="post" action="index.php?page=variable">
    <?php require('./template/place_info.php'); ?>

    <div class="left_input">Datum do:</div>
    <div class="right_input" style="width: 300px;">
      <input id="f_date_t" type="text" class="i_s_middle" name="date_to"
             value="<?php echo $_SESSION['date_to']; ?>" />
      <button type="reset" id="f_trigger_t">...</button>
    </div>
    <div class="clear"></div>

    <div class="left_input">
      Minimální výška:
    </div>
    <div class="right_input">
      <input type="text" name="min_altitude" value="<?php echo $_SESSION['min_altitude']; ?>" />
    </div>
    <div class="clear"></div>

    <input type="hidden" name="search" value="<?php echo $_POST['search']; ?>" />

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

<form method="get" action="includes/map.php" target="_blank">  
  <h2>Nalezené záznamy (<?php echo count($stars); ?>)</h2>
  <input type="submit" name="getlist" value="Vykreslit" />
  &nbsp;
  <input type="checkbox" id="tisk" name="tisk" /><label for="tisk">Tisková verze</label>
  <br /><br />
  <table border="1" cellpadding="5" cellspacing="5">
    <tr>
      <th>Výběr</th>
      <th>Název</th>
      <th>Použitá minima</th>
      <th>m<sub>0</sub></th>
      <th>Perioda</th>
      <th>Rektascenze (J2000)</th>
      <th>Deklinace (J2000)</th>
      <th>&nbsp;</th>
    </tr>
  <?php foreach ($stars as $star) {
    $ra = new Deg($star->ra);
    $dec = new Deg($star->dec);
  ?>
    <tr>
      <td><input type="checkbox" name="star[]" value="<?php echo $star->id; ?>" /></td>
      <td><a href="index.php?page=variable&star=<?php echo $star->id; ?>"><?php echo $star->name; ?></a></td>
      <td><?php echo $star->minima_info; ?></td>
      <td><?php echo $star->minima; ?></td>
      <td><?php echo round($star->period, 4); ?>&nbsp;d</td>
      <td><?php echo $ra->degrees.'h '.$ra->minutes.'m '.round($ra->seconds, 2).'s'; ?></td>
      <td><?php echo $dec->degrees.'° '.$dec->minutes.'′ '.round($dec->seconds, 2).'″'; ?></td>
      <td><a href="index.php?page=variable&edit=<?php echo $star->id; ?>">upravit</a></td>
    </tr>
  <?php } ?>
  </table>
  <input type="hidden" name="date_from" value="<?php echo $_SESSION['date_from']; ?>" />
  <input type="hidden" name="date_to" value="<?php echo $_SESSION['date_to']; ?>" />
  <input type="hidden" name="localOffset" value="<?php echo $_SESSION['localoffset']; ?>" />
  <?php if ($_SESSION['DST'] != '') { ?>
    <input type="hidden" name="DST" value="1" />
  <?php } ?>
  <input type="hidden" name="latitude" value="<?php echo $_SESSION['latitude']; ?>" />
  <input type="hidden" name="longitude" value="<?php echo $_SESSION['longitude']; ?>" />
  <input type="hidden" name="min_altitude" value="<?php echo $_SESSION['min_altitude']; ?>" />
  
  <br />
  <input type="submit" name="getlist" value="Vykreslit" />
  &nbsp;
  <input type="checkbox" id="tisk2" name="tisk" /><label for="tisk2">Tisková verze</label>
</form>
<?php } else { ?>
  </div> <!-- Tohle je druhý konec k settingsdiv -->
<?php } ?>

<?php 
  if (isset($_POST['getlist']) || isset($_GET['star'])) {
  ?>
    <div id="helpdiv">
  <?php
    $localOffset = $_SESSION['localoffset'];
    if ($_SESSION['DST'] != '') $localOffset++;
    $astroCalc = new AstroCalc($_SESSION['latitude'], $_SESSION['longitude'], $localOffset);

    echo '<h2>'.$var->getName().'</h2>';
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

    $JD_from = julianDateTime($day_from, $month_from, $year_from, 0, 0, 0);
    $JD_to = julianDateTime($day_to, $month_to, $year_to, 23, 59, 59);
    
    if (!($JD_from > 0) || !($JD_to > 0) || $JD_from >= $JD_to) {
      echo '<div class="chyba">Neplatný rozsah</div>';
    }

    $JD_now = julianDateTime(date('d'), date('m'), date('Y'), date('H'), date('i'), date('s'));
    echo printJulianDate($JD_now);
    $var->getCycle($JD_now, $localOffset);
    echo 'Cyklus: '.$var->getE().'<br />';
    $phase = $var->getC() - (int)$var->getC();
    echo 'Aktuální fáze na křivce: '.round($phase, 6).'<br /><br />';

    $dates = $var->getMinimaDates($JD_to, $localOffset, false);
?>

  <h2 onclick="toggleDiv('legend');" id="hlegend" class="cHeader"><span>-</span> Legenda</h2>
  <div id="legend">
    <div class="legend" style="background-color: <?php echo ERR_COLOR0; ?>;"></div>&nbsp;&nbsp;
    Minimum je pozorovatelné za astronomického soumraku<br />
    <div class="legend" style="background-color: <?php echo ERR_COLOR1; ?>;"></div>&nbsp;&nbsp;
    Minimum je pozorovatelné alespoň za občanského soumraku (vlastní jasnost se nebere v potaz)<br />
    <div class="legend" style="background-color: <?php echo ERR_COLOR2; ?>;"></div>&nbsp;&nbsp;
    Minimum není pozorovatelné za tmy nebo hvězda nevychází vůbec<br />
    <br />
  </div>
</div>

Od <strong><?php echo $_SESSION['date_from']; ?></strong> do <strong><?php echo $_SESSION['date_to']; ?></strong>
<table cellspacing="2" cellpadding="2" border="1">
<tr>
  <th>Datum</th>
  <th>Typ</th>
  <th>Výška nad obzorem</th>
  <th>Azimut (N&nbsp;→&nbsp;E)</th>
  <th>Východ</th>
  <th>Průchod nebeským poledníkem</th>
  <th>Západ</th>
  <th>Východ slunce</th>
  <th>Západ slunce</th>
</tr>
<?php
    /*
    $dates = array();
    $i = 0;
    // počítá výskyty dat, pro pozdější použití v rowspan
    while ($m0 + ($E + $i) * $P + $offset / 24.0 <= $JD_to) {
      if (!isset($dates[round($m0 + ($E + $i) * $P + $offset / 24.0)]))
        $dates[round($m0 + ($E + $i) * $P + $offset / 24.0)] = 1;
      else $dates[round($m0 + ($E + $i) * $P + $offset / 24.0)]++;
      $i += $step;
    }
    */
    
    $m0 = $var->star->minima;
    $E = $var->E;
    $P = $var->star->period;
    
    $i = 0;
    $j = 0;
    while ($m0 + ($E + $i) * $P + $localOffset / 24.0 <= $JD_to) {
      $JD = round($m0 + ($E + $i) * $P + $localOffset / 24.0);
      $astroCalc->setDateTime($m0 + ($E + $i) * $P + $localOffset / 24.0);

      if ($j >= 10 && isset($dates[$JD]) && $dates[$JD] > 0) { ?>        
        <tr>
          <th>Datum</th>
          <th>Typ</th>
          <th>Výška nad obzorem</th>
          <th>Azimut (N&nbsp;→&nbsp;E)</th>
          <th>Východ</th>
          <th>Průchod nebeským poledníkem</th>
          <th>Západ</th>
          <th>Východ slunce</th>
          <th>Západ slunce</th>
        </tr>
      <?php
        $j = 0;
      }

      $posCorr = $astroCalc->doCorrections($var->getRA(), $var->getDec(), $var->getPMRA(), $var->getPMDec());
      $starRA = $posCorr[0]; $starDec = $posCorr[1];
      //$starRA = $var->getRA(); $starDec = $var->getDec();
      
      $astroCalc->LST = normalizeAngle($astroCalc->LST + 180);
      $riseData = $astroCalc->objRise($starRA, $starDec);
      $astroCalc->LST = normalizeAngle($astroCalc->LST - 180);

      // oficiální západ
      $astroCalc->zenith = 90.8333;
      $sunrise = $astroCalc->sunrise($JD - 0.5);
      // astronomický soumrak
      $astroCalc->zenith = 108;
      $sunriseAstro = $astroCalc->sunrise($JD - 0.5);

      // získá výšku a azimut
      $pos = $astroCalc->calcObjectPos(new Deg($starRA), new Deg($starDec));

      $error = 0;
      // slunce nezajde nebo hvězda nevyjde
      if ($sunrise['vychod'] == 'NS' || $riseData['vychod'] == 'NR') $error = 2;
      // hvězda vychází jenom když je slunce na obloze
      if ($m0 + ($E + $i) * $P + $localOffset / 24.0 > 0 && $sunrise['vychod'] > 0 && $sunrise['zapad'] > 0 &&
          $m0 + ($E + $i) * $P + $localOffset / 24.0 > $sunrise['vychod'] && $m0 + ($E + $i) * $P + $localOffset / 24.0 < $sunrise['zapad'] ||
          $pos[0]->degrees < $_SESSION['min_altitude'])
            $error = 2;
  
      if ($error == 0) {
        // slunce nezajde do astronomického soumraku
        if ($sunriseAstro['vychod'] == 'NS') $error = 1;
        // hvězda vychází jenom když je slunce na obloze
        if ($m0 + ($E + $i) * $P + $localOffset / 24.0 > 0 && $sunriseAstro['vychod'] > 0 && $sunriseAstro['zapad'] > 0 &&
            $m0 + ($E + $i) * $P + $localOffset / 24.0 > $sunriseAstro['vychod'] && $m0 + ($E + $i) * $P + $localOffset / 24.0 < $sunriseAstro['zapad'])
              $error = 1;
      }
              
      if ($error == 0) $color = ERR_COLOR0;
      if ($error == 1) $color = ERR_COLOR1;
      if ($error == 2) $color = ERR_COLOR2;

      // pokud není více stejných dat, může se obarvit celý řádek
      if (isset($dates[$JD]) && $dates[$JD] == 1)
        echo '<tr style="background-color: '.$color.';">';
      else echo '<tr>';
      echo '<td style="background-color: '.$color.';">'.printJulianDate($m0 + ($E + $i) * $P + $localOffset / 24.0).'</td>';
      //if (isset($dates[$JD]) && $dates[$JD] > 0) {
        //echo '<td rowspan="'.$dates[$JD].'">'.printJulianDate($m0 + ($E + $i) * $P + $localOffset / 24.0).'</td>';
        //$dates[$JD] = 0;
      //}

      if ($var->star->minima_info == 'SEC' || (int)$i != $i)
        echo '<td style="background-color: '.$color.';">SEC</td>';
      else echo '<td style="background-color: '.$color.';">PRI</td>';
      
      echo '<td style="background-color: '.$color.';">'.$pos[0]->degrees.'° '.$pos[0]->minutes.'′ '.round($pos[0]->seconds, 2).'″'.'</td>';
      echo '<td style="background-color: '.$color.';">'.$pos[1]->degrees.'° '.$pos[1]->minutes.'′ '.round($pos[1]->seconds, 2).'″'.'</td>';
      ?>

      <?php if (isset($dates[$JD]) && $dates[$JD] > 0) { ?>
        <?php if ($riseData['vychod'] == 'NR') { ?>
          <td rowspan="<?php echo $dates[$JD]; ?>"><acronym title="<?php echo $var->getName(); ?> v daný den na daném místě nevychází">NR</acronym></td>
          <td rowspan="<?php echo $dates[$JD]; ?>"><?php echo printJulianDate($riseData['pruchod']); ?></td>
          <td rowspan="<?php echo $dates[$JD]; ?>"><acronym title="<?php echo $var->getName(); ?> v daný den na daném místě nevychází">NR</acronym></td>
        <?php } elseif ($riseData['vychod'] == 'NS') { ?>
          <td rowspan="<?php echo $dates[$JD]; ?>"><acronym title="<?php echo $var->getName(); ?> v daný den na daném místě nezapadá">NS</acronym></td>
          <td rowspan="<?php echo $dates[$JD]; ?>"><?php echo printJulianDate($riseData['pruchod']); ?></td>
          <td rowspan="<?php echo $dates[$JD]; ?>"><acronym title="<?php echo $var->getName(); ?> v daný den na daném místě nezapadá">NS</acronym></td>
        <?php } else { ?>
          <td rowspan="<?php echo $dates[$JD]; ?>"><?php echo printJulianDate($riseData['vychod']); ?></td>
          <td rowspan="<?php echo $dates[$JD]; ?>"><?php echo printJulianDate($riseData['pruchod']); ?></td>
          <td rowspan="<?php echo $dates[$JD]; ?>"><?php echo printJulianDate($riseData['zapad']); ?></td>
        <?php } ?>

      <?php
      
        if ($sunrise['vychod'] == 'NR') {
          echo '<td rowspan="'.$dates[$JD].'" colspan="2">
                  <acronym title="Slunce v tento den nevychází nad zadanou hodnotu">NR</acronym>
                </td>';
        } elseif ($sunrise['vychod'] == 'NS') {
          echo '<td rowspan="'.$dates[$JD].'" colspan="2">
                  <acronym title="Slunce v tento den nezapadá pod zadanou hodnotu">NS</acronym>
                </td>';
        } else {
        	echo '<td rowspan="'.$dates[$JD].'">'.printJulianDate($sunrise['vychod']).'</td>';
        	echo '<td rowspan="'.$dates[$JD].'">'.printJulianDate($sunrise['zapad']).'</td>';
        }
        $dates[$JD] = 0;
      }
      
      echo '</tr>';
      $i += $var->step;
      $j++;
    }
?>
</table>
<?php } ?>

<script type="text/javascript">
  $(document).ready(function() {
    <?php if (empty($err) && (isset($_GET['star']) || isset($_POST['search']) || isset($_POST['search_query']))) { ?>
      toggleDiv('paramdiv');
      toggleDiv('searchdiv');
    <?php } ?>
  });
</script>
