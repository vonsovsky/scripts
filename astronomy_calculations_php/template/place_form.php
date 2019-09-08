<?php
  /*
   * Formulář výběru místa
   */

    $degLong = new Deg($_SESSION['longitude']);
    $degLat = new Deg($_SESSION['latitude']);
?>

<div class="left_input">
  Předdefinované souřadnice
</div>
<div class="right_input">
  <select id="place" name="place" onchange="selectPlace();">';
  <?php
  foreach ($places as $index => $place) {
    $selected = '';
    if (!isset($_POST['place']) && $_SESSION['place'] == $place->longitude.','.$place->latitude)
      $selected = ' selected="selected"';
    if (isset($_POST['place']) && $_POST['place'] == $place->longitude.','.$place->latitude)
      $selected = ' selected="selected"';
    echo '<option value="'.$place->longitude.','.$place->latitude.'"'.$selected.'>'.$place->location.'</option>';
  }
  ?>
  </select>
</div>
<div class="clear"></div>

<div class="left_input">
  Zeměpisná délka:
</div>
<div class="right_input" style="width: 490px;">
  <input type="text" name="longitude" id="longitude" value="<?php echo $_SESSION['longitude']; ?>" />
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
  <input type="text" name="latitude" id="latitude" value="<?php echo $_SESSION['latitude']; ?>" />
  <acronym title="Např. 60.212">N</acronym>
  &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="přepočítat" onclick="recountDegrees('latitude', 'deglat')" />&nbsp;
      <acronym title="Pokud se vyplní hodnota na jedné straně, je možné ji nechat přepočítat. Vlevo se zadává decimální hodnota, vpravo hodnota ve stupních.">?</acronym>&nbsp;&nbsp;&nbsp;
  <input type="text" name="deglat" id="deglat"
         value="<?php echo $degLat->degrees.' '.$degLat->minutes.' '.round($degLat->seconds, 1); ?>" />&nbsp;
  <acronym title="Např. 60 12 43">N</acronym>
</div>
<div class="clear"></div>

<div class="left_input">
  Časové pásmo:
</div>
<div class="right_input">
  <select name="localOffset">
    <?php
      for ($i = -12; $i <= 12; $i++) {
        $repr = $i;
        if ($i > 0) $repr = '+'.$repr;
        $selected = ($_SESSION['localoffset'] == $i) ? ' selected="selected"' : '';
        echo '<option value="'.$i.'"'.$selected.'>GMT '.$repr.'</option>';
      }
    ?>
  </select>
</div>
<div class="clear"></div>

<div class="left_input">
  Letní čas:
</div>
<div class="right_input">
  <input type="checkbox" name="DST" <?php echo $_SESSION['DST']; ?> />
</div>
<div class="clear"></div>

<div class="left_input">
  Datum od:
</div>
<div class="right_input" style="width: 490px;">
  <input id="f_date_b" type="text" class="i_s_middle" name="date_from"
         value="<?php echo $_SESSION['date_from']; ?>" />
  <button type="reset" id="f_trigger_b">...</button>
  
  <span style="<?php if ($page == 'rising' || $page == 'planets') echo 'display: none;'; ?>">
  &nbsp;Do:&nbsp; 
  <input id="f_date_t" type="text" class="i_s_middle" name="date_to"
         value="<?php echo $_SESSION['date_to']; ?>" />
  <button type="reset" id="f_trigger_t">...</button>
  </span> 

  <script type="text/javascript"> 
      Calendar.setup({
          inputField     :    "f_date_b",
          ifFormat       :    "%d. %m. %Y",
          showsTime      :    true,
          button         :    "f_trigger_b",
          singleClick    :    true,
          step           :    1
      });
      Calendar.setup({
          inputField     :    "f_date_t",
          ifFormat       :    "%d. %m. %Y",
          showsTime      :    true,
          button         :    "f_trigger_t",
          singleClick    :    true,
          step           :    1
      });
  </script> 
</div>
<div class="clear"></div>

<div class="left_input">
  Minimální výška:
</div>
<div class="right_input" style="width: 630px;">
  <input type="text" name="min_altitude" value="<?php echo $_SESSION['min_altitude']; ?>" />
  <font color="gray">Minimální výška pro vykreslení kotoučků na mapě minim proměnných hvězd</font>
</div>
<div class="clear"></div>
