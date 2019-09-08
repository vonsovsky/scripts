<style type="text/css">
  @import "./js/JSCalendar/skins/aqua/theme.css";
</style>
<script type="text/javascript" src="./js/JSCalendar/calendar.js"></script>
<script type="text/javascript" src="./js/JSCalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="./js/JSCalendar/lang/calendar-cs-utf8.js"></script>
<script type="text/javascript" src="./js/JSCalendar/calendar-setup.js"></script>
<script type="text/javascript" src="./js/functions.js"></script>

<div class="left_input">
  Minimální výška:
</div>
<div class="right_input">
  <input type="text" name="min_altitude" value="<?php echo $_SESSION['min_altitude']; ?>" />
</div>
<div class="clear"></div>

<div class="left_input">
  Fáze od:
</div>
<div class="right_input">
  <input type="text" name="phase_from" value="<?php echo $_SESSION['phase_from']; ?>" />
</div>
<div class="clear"></div>

<div class="left_input">
  Fáze do:
</div>
<div class="right_input">
  <input type="text" name="phase_to" value="<?php echo $_SESSION['phase_to']; ?>" />
</div>
<div class="clear"></div>

<div class="left_input">
  Čas od:
</div>
<div class="right_input" style="width: 550px;">
  <input id="f_date_b" type="text" class="i_s_middle" name="time_from"
         value="<?php echo $_SESSION['time_from']; ?>" />
  <button type="reset" id="f_trigger_b">...</button>
  <font color="gray">pokud se vleze více period a f<sub>2</sub> < 1, bere se ta poslední,</font>
</div>
<div class="clear"></div>

<div class="left_input">
  Čas do:
</div>
<div class="right_input" style="width: 490px;"> 
  <input id="f_date_t" type="text" class="i_s_middle" name="time_to"
         value="<?php echo $_SESSION['time_to']; ?>" />
  <button type="reset" id="f_trigger_t">...</button>
  <font color="gray">proto je lepší volit kratší intervaly</font>
</div>
<div class="clear"></div>

<div class="left_input">
  Souhvězdí:
</div>
<div class="right_input" style="width: 490px"> 
  <input type="text" name="search_query" value="<?php if (isset($_POST['search_query'])) echo $_POST['search_query']; ?>" />
  <font color="gray">nepovinné, ale může vyjít příliš mnoho výsledků</font>
</div>
<div class="clear"></div>

<script type="text/javascript"> 
    Calendar.setup({
        inputField     :    "f_date_b",
        ifFormat       :    "%d. %m. %Y %I:%M",
        showsTime      :    true,
        button         :    "f_trigger_b",
        singleClick    :    true,
        step           :    1
    });
    Calendar.setup({
        inputField     :    "f_date_t",
        ifFormat       :    "%d. %m. %Y %I:%M",
        showsTime      :    true,
        button         :    "f_trigger_t",
        singleClick    :    true,
        step           :    1
    });
</script> 
