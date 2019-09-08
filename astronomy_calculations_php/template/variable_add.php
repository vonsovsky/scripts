<?php
  include 'includes/variable.php';
  
  if (isset($_POST['name'])) {
    dibi::query('INSERT INTO [ephem] ([name], [minima_info], [minima], [period], [ra], [dec], [mv1], [mv2], [epoch])
                 VALUES (%s, %s, %f, %f, %f, %f, %s, %s, %s)',
                 $_POST['name'], $_POST['minima_info'], $_POST['minima'], $_POST['period'],
                 $_POST['ra'], $_POST['dec'], $_POST['mv1'], $_POST['mv2'], $_POST['epoch']);
    $id = dibi::getInsertId();
    echo '<div class="ok">Hvězda <a href="index.php?page=variable&edit='.$id.'">přidána</a></div>';
  }
?>

<h1>Přidat proměnnou hvězdu</h1>
<form method="post" action="index.php?page=variable&add=1">
  <div class="left_input">
    Identifikace:
  </div>
  <div class="right_input">
    <input name="name" type="text" value="" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Minima info:
  </div>
  <div class="right_input">
    <select name="minima_info">
      <option value="PRI">PRI</option>
      <option value="SEC">SEC</option>
      <option value="ALL">ALL</option>
    </select>
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Čas minima:
  </div>
  <div class="right_input">
    <input name="minima" type="text" value="" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Perioda (ve dnech):
  </div>
  <div class="right_input">
    <input name="period" type="text" value="" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Rektascenze:
  </div>
  <div class="right_input" style="width: 400px;">
    <input name="ra" type="text" value="" />&nbsp;(v desetinném čísle)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Deklinace:
  </div>
  <div class="right_input" style="width: 400px;">
    <input name="dec" type="text" value="" />&nbsp;(v desetinném čísle)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Vlastní pohyb - rektascenze:
  </div>
  <div class="right_input">
    <input name="mv1" type="text" value="" />&nbsp;(arcsec / rok)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Vlastní pohyb - deklinace:
  </div>
  <div class="right_input">
    <input name="mv2" type="text" value="" />&nbsp;(arcsec / rok)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Rok měření &alpha; / &delta;:
  </div>
  <div class="right_input">
    <input name="epoch" type="text" value="" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">&nbsp;</div>
  <div class="right_input">
    <input type="submit" value="přidat" />
  </div>
  <div class="clear"></div>
</form>