<?php
  if (isset($_POST['longitude'])) {
    // Pokud jsou vyplněny jen stupně, převede se na desetinné číslo
    if (strlen($_POST['longitude']) == 0 && strlen($_POST['deglong']) > 0) {
      fromPostDeg('longitude', 'deglong');
    }
    if (strlen($_POST['latitude']) == 0 && strlen($_POST['deglat']) > 0) {
      fromPostDeg('latitude', 'deglat');
    }

    if (!($_POST['longitude'] >= -180 && $_POST['longitude'] <= 180)) {
      echo '<div class="error">Zeměpisná délka je mimo povolený rozsah</div>';
    } elseif (!($_POST['latitude'] >= -90 && $_POST['latitude'] <= 90)) {
      echo '<div class="error">Zeměpisná šířka je mimo povolený rozsah</div>';
    } else {
      dibi::query('UPDATE [places] SET [longitude] = %f, [latitude] = %f WHERE [location] = %s',
                  $_POST['longitude'], $_POST['latitude'], $_GET['set']);
      echo '<div class="ok">Místo bylo změněno</div>';
    }
  }
  
  $detail = dibi::query('SELECT [location], [longitude], [latitude] FROM [places] WHERE [location] = %s', $_GET['set'])->fetch();
  $degLong = new Deg($detail->longitude);
  $degLat = new Deg($detail->latitude);
?>

<script type="text/javascript" src="js/functions.js"></script>

<h1 onclick="toggleDiv('place_canvas');" id="hplace_canvas" class="cHeader"><span>-</span> Detail místa <?php echo $detail->location; ?></h1>

<div id="place_canvas">
<form action="index.php?page=places&set=<?php echo $_GET['set']; ?>" method="post" id="addplace">
  <div class="left_input">
    Zeměpisná délka:
  </div>
  <div class="right_input" style="width: 490px;">
    <input type="text" name="longitude" id="longitude" value="<?php echo $detail->longitude; ?>" />
    <acronym title="Např. -16.608">W</acronym>
    &nbsp;&nbsp;&nbsp;<input type="button" value="přepočítat" onclick="recountDegrees('longitude', 'deglong')" />&nbsp;
    <acronym title="Pokud se vyplní hodnota na jedné straně, je možné ji nechat přepočítat. Vlevo se zadává decimální hodnota, vpravo hodnota ve stupních.">?</acronym>&nbsp;&nbsp;&nbsp;
    <input type="text" name="deglong" id="deglong"
           value="<?php echo $degLong->degrees.' '.$degLong->minutes.' '.round($degLong->seconds, 1); ?>" />&nbsp;
    <acronym title="Např. -16 45 33">W</acronym>
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Zeměpisná šířka:
  </div>
  <div class="right_input" style="width: 490px;">
    <input type="text" name="latitude" id="latitude" value="<?php echo $detail->latitude; ?>" />
    <acronym title="Např. 60.212">N</acronym>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="přepočítat" onclick="recountDegrees('latitude', 'deglat')" />&nbsp;
        <acronym title="Pokud se vyplní hodnota na jedné straně, je možné ji nechat přepočítat. Vlevo se zadává decimální hodnota, vpravo hodnota ve stupních.">?</acronym>&nbsp;&nbsp;&nbsp;
    <input type="text" name="deglat" id="deglat"
           value="<?php echo $degLat->degrees.' '.$degLat->minutes.' '.round($degLat->seconds, 1); ?>" />&nbsp;
    <acronym title="Např. 60 12 43">N</acronym>
  </div>
  <div class="clear"></div>

  <center>
    <input type="submit" value="Změnit" />
  </center>
</form>

</div>