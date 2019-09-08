<script type="text/javascript" src="js/functions.js"></script>
<style type="text/css">
  @import "./js/JSCalendar/skins/aqua/theme.css";
</style>
<script type="text/javascript" src="./js/JSCalendar/calendar.js"></script>
<script type="text/javascript" src="./js/JSCalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="./js/JSCalendar/lang/calendar-cs-utf8.js"></script>
<script type="text/javascript" src="./js/JSCalendar/calendar-setup.js"></script>
<script type="text/javascript" src="./js/functions.js"></script>
<script type="text/javascript">
  <?php if (!isset($_POST['calculate']) && !isset($_COOKIE['longitude'])) { ?>
    $(document).ready(function() {
      selectPlace();
    });
  <?php } ?>
</script>

<?php
  /* tenhle flag určí jestli vyplnit nebo nevyplnit hodnoty z postu,
     po úspěchu se nevyplní */
  $added = false;
  
  // pokud není zapnuté COOKIES, tak aspoň do SESSION
  if (isset($_POST['setlocation'])) {
    setcookie('place', $_POST['place'], time() + 86400 * 365);
    $_SESSION['place'] = $_POST['place'];

    setcookie('longitude', $_POST['longitude'], time() + 86400 * 365);
    $_SESSION['longitude'] = $_POST['longitude'];

    setcookie('latitude', $_POST['latitude'], time() + 86400 * 365);
    $_SESSION['latitude'] = $_POST['latitude'];

    setcookie('localoffset', $_POST['localOffset'], time() + 86400 * 365);
    $_SESSION['localoffset'] = $_POST['localOffset'];

    setcookie('DST', isset($_POST['DST']) ? '1' : '0', time() + 86400 * 365);
    $_SESSION['DST'] = isset($_POST['DST']) ? 'checked="checked"' : '';

    setcookie('date_from', $_POST['date_from'], time() + 86400 * 365);
    $_SESSION['date_from'] = $_POST['date_from'];

    setcookie('date_to', $_POST['date_to'], time() + 86400 * 365);
    $_SESSION['date_to'] = $_POST['date_to'];

    setcookie('min_altitude', $_POST['min_altitude'], time() + 86400 * 365);
    $_SESSION['min_altitude'] = $_POST['min_altitude'];

    echo '<div class="ok">Místo bylo nastaveno</div>';
    //setcookie('min_altitude', $_POST['min_altitude']);
  }

  if (isset($_POST['location'])) {
    $loc = dibi::query('SELECT [location] FROM [places] WHERE [location] = %s', $_POST['location'])->fetchSingle();
    
    // Pokud jsou vyplněny jen stupně, převede se na desetinné číslo
    if (strlen($_POST['longitude_e']) == 0 && strlen($_POST['deglong']) > 0) {
      fromPostDeg('longitude_e', 'deglong');
    }
    if (strlen($_POST['latitude_e']) == 0 && strlen($_POST['deglat']) > 0) {
      fromPostDeg('latitude', 'deglat');
    }
    
    if ($loc) {
      echo '<div class="error">Toto místo již existuje</div>';
    } elseif (strlen($_POST['location']) == 0) {
      echo '<div class="error">Není vyplněno jméno místa</div>';
    } elseif (!($_POST['longitude_e'] >= -180 && $_POST['longitude_e'] <= 180)) {
      echo '<div class="error">Zeměpisná délka je mimo povolený rozsah</div>';
    } elseif (!($_POST['latitude_e'] >= -90 && $_POST['latitude_e'] <= 90)) {
      echo '<div class="error">Zeměpisná šířka je mimo povolený rozsah</div>';
    } else {
      dibi::query('INSERT INTO [places] ([location], [longitude], [latitude]) VALUES (%s, %f, %f)',
                  $_POST['location'], $_POST['longitude_e'], $_POST['latitude_e']);
      echo '<div class="ok">Místo bylo přidáno</div>';
      $added = true;
    }
  }
  
  if (isset($_GET['delete'])) {
    dibi::query('DELETE FROM [places] WHERE [location] = %s', $_GET['delete']);
    
    echo '<div class="ok">Místo bylo smazáno</div>';
  }

  $places = dibi::query('SELECT [location], [longitude], [latitude] FROM [places] ORDER BY [location]')->fetchAll();
?>

<h1 onclick="toggleDiv('setplace_canvas');" id="hsetplace_canvas" class="cHeader"><span>-</span> Nastavit pozorovací místo</h1>

<div id="setplace_canvas">
<h2 onclick="toggleDiv('timediv');" id="htimediv" class="cHeader"><span>-</span> Čas</h2>
<div id="timediv">
  <form action="index.php?page=places" method="post">
    <?php require('./template/place_form.php'); ?>
    <center>
      <input type="submit" name="setlocation" value="Nastavit" />
    </center>
  </form>
</div>
</div>


<h1 onclick="toggleDiv('place_canvas');" id="hplace_canvas" class="cHeader"><span>-</span> Správa lokací</h1>

<div id="place_canvas">
<h2 onclick="toggleDiv('addplace');" id="haddplace" class="cHeader"><span>-</span> Přidat místo</h2>
<form action="index.php?page=places" method="post" id="addplace">
  <div class="left_input">
    Název místa:
  </div>
  <div class="right_input">
    <input type="text" name="location"
           value="<?php echo isset($_POST['location']) && !$added ? $_POST['location'] : ''; ?>" />
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Zeměpisná délka:
  </div>
  <div class="right_input" style="width: 490px;">
    <input type="text" name="longitude_e" id="longitude"
           value="<?php echo isset($_POST['longitude_e']) && !$added ? $_POST['longitude_e'] : ''; ?>" />
    <acronym title="Západní délka v desetinném čísle. Např. 16.608">W</acronym>
    &nbsp;&nbsp;&nbsp;<input type="button" value="přepočítat" onclick="recountDegrees('longitude', 'deglong')" />&nbsp;
    <acronym title="Pokud se vyplní hodnota na jedné straně, je možné ji nechat přepočítat. Vlevo se zadává decimální hodnota, vpravo hodnota ve stupních. Pokud jsou vyplněna obě pole, přednost má levé.">?</acronym>&nbsp;&nbsp;&nbsp;
    <input type="text" name="deglong" id="deglong"
           value="<?php echo isset($_POST['deglong']) && !$added ? $_POST['deglong'] : ''; ?>" />&nbsp;
    <acronym title="Západní délka ve stupních. Např. 16 45 33">W</acronym>
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Zeměpisná šířka:
  </div>
  <div class="right_input" style="width: 490px;">
    <input type="text" name="latitude_e" id="latitude"
           value="<?php echo isset($_POST['latitude_e']) && !$added ? $_POST['latitude_e'] : ''; ?>" />
    <acronym title="Severní šířka v desetinném čísle. Např. 60.212">N</acronym>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="přepočítat" onclick="recountDegrees('latitude', 'deglat')" />&nbsp;
        <acronym title="Pokud se vyplní hodnota na jedné straně, je možné ji nechat přepočítat. Vlevo se zadává decimální hodnota, vpravo hodnota ve stupních. Pokud jsou vyplněna obě pole, přednost má levé.">?</acronym>&nbsp;&nbsp;&nbsp;
    <input type="text" name="deglat" id="deglat"
           value="<?php echo isset($_POST['deglat']) && !$added ? $_POST['deglat'] : ''; ?>" />&nbsp;
    <acronym title="Západní délka ve stupních. Např. 60 12 43">N</acronym>
  </div>
  <div class="clear"></div>

  <center>
    <input type="submit" value="Přidat" />
  </center>
</form>

<h2 onclick="toggleDiv('listplaces');" id="hlistplaces" class="cHeader"><span>-</span> Seznam míst</h2>
<div id="listplaces">
<?php
  foreach ($places as $index => $place) {
    $degLong = new Deg($place->longitude);
    $degLat = new Deg($place->latitude);
?>
  <span class="right">
    <a href="index.php?page=places&delete=<?php echo $place->location; ?>" onclick="return confirm('Opravdu smazat toto místo?');">smazat</a>
  </span>
  <a href="index.php?page=places&set=<?php echo $place->location; ?>"><?php echo $place->location; ?></a>
  <font color="666666">
    <?php echo ' ('.$degLong->degrees.'° '.$degLong->minutes.'′ '.round($degLong->seconds, 1).'″ W&nbsp;&nbsp;'.
                    $degLat->degrees.'° '.$degLat->minutes.'′ '.round($degLat->seconds, 1).'″ N)'; ?>
  </font>
  <?php if ($index != count($places) - 1) { ?>
    <hr style="border: 1px dashed black;" />
  <?php } ?>
<?php } ?>
</div>
</div>