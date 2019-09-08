<style type="text/css">
  @import "./js/JSCalendar/skins/aqua/theme.css";
</style>
<script type="text/javascript" src="./js/JSCalendar/calendar.js"></script>
<script type="text/javascript" src="./js/JSCalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="./js/JSCalendar/lang/calendar-cs-utf8.js"></script>
<script type="text/javascript" src="./js/JSCalendar/calendar-setup.js"></script>
<script type="text/javascript" src="./js/functions.js"></script>

<div class="left_input"><a href="index.php?page=places">Změnit</a></div>
<div class="right_input">&nbsp;</div>
<div class="clear"></div>

<div class="left_input">Zeměpisná délka:</div>
<div class="right_input"><?php echo $_SESSION['longitude']; ?></div>
<div class="clear"></div>

<div class="left_input">Zeměpisná šířka:</div>
<div class="right_input"><?php echo $_SESSION['latitude']; ?></div>
<div class="clear"></div>

<div class="left_input">Časové pásmo:</div>
<div class="right_input"><?php echo $_SESSION['localoffset']; ?></div>
<div class="clear"></div>

<div class="left_input">Letní čas:</div>
<div class="right_input"><?php echo $_SESSION['DST'] != '' ? 'ano' : 'ne'; ?></div>
<div class="clear"></div>

<div class="left_input">Datum od:</div>
<div class="right_input" style="width: 300px;">
  <input id="f_date_b" type="text" class="i_s_middle" name="date_from"
         value="<?php echo $_SESSION['date_from']; ?>" />
  <button type="reset" id="f_trigger_b">...</button>
</div>
<div class="clear"></div>



<!--
<div class="left_input">Datum do:</div>
<div class="right_input"><?php echo $_SESSION['date_to']; ?></div>
<div class="clear"></div>
-->

<script type="text/javascript"> 
    Calendar.setup({
        inputField     :    "f_date_b",
        ifFormat       :    "%d. %m. %Y",
        showsTime      :    true,
        button         :    "f_trigger_b",
        singleClick    :    true,
        step           :    1
    });
</script> 
