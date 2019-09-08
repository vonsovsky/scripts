<?php
  include 'includes/variable.php';
  
  if (isset($_POST['name'])) {
    dibi::query('UPDATE [ephem] SET [name] = %s, [minima_info] = %s, [minima] = %f, [period] = %f,
                 [ra] = %f, [dec] = %f, [mv1] = %s, [mv2] = %s, [epoch] = %s WHERE [id] = %i',
                 $_POST['name'], $_POST['minima_info'], $_POST['minima'], $_POST['period'],
                 $_POST['ra'], $_POST['dec'], $_POST['mv1'], $_POST['mv2'], $_POST['epoch'], $_GET['edit']);
    echo '<div class="ok">Uloženo</div>';
  }

  $star = dibi::query('SELECT [name], [minima_info], [minima], [period],
                       [ra], [dec], [mv1], [mv2], [epoch] FROM [ephem] WHERE [id] = %i',
                       $_GET['edit'])->fetch();
?>

<h1>Editace proměnné hvězdy <?php echo $star->name; ?></h1>
<form method="post" action="index.php?page=variable&edit=<?php echo $_GET['edit']; ?>">
  <div class="left_input">
    Identifikace:
  </div>
  <div class="right_input">
    <input name="name" type="text" value="<?php echo $star->name; ?>" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Minima info:
  </div>
  <div class="right_input">
    <select name="minima_info">
      <option value="PRI" <?php if ($star->minima_info == 'PRI') echo 'selected="selected"'; ?>>PRI</option>
      <option value="SEC" <?php if ($star->minima_info == 'SEC') echo 'selected="selected"'; ?>>SEC</option>
      <option value="ALL" <?php if ($star->minima_info == 'ALL') echo 'selected="selected"'; ?>>ALL</option>
    </select>
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Čas minima:
  </div>
  <div class="right_input">
    <input name="minima" type="text" value="<?php echo $star->minima; ?>" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Perioda (ve dnech):
  </div>
  <div class="right_input">
    <input name="period" type="text" value="<?php echo $star->period; ?>" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Rektascenze:
  </div>
  <div class="right_input" style="width: 400px;">
    <input name="ra" type="text" value="<?php echo $star->ra; ?>" />&nbsp;(v desetinném čísle)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Deklinace:
  </div>
  <div class="right_input" style="width: 400px;">
    <input name="dec" type="text" value="<?php echo $star->dec; ?>" />&nbsp;(v desetinném čísle)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Vlastní pohyb - rektascenze:
  </div>
  <div class="right_input">
    <input name="mv1" type="text" value="<?php echo $star->mv1; ?>" />&nbsp;(arcsec / rok)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Vlastní pohyb - deklinace:
  </div>
  <div class="right_input">
    <input name="mv2" type="text" value="<?php echo $star->mv2; ?>" />&nbsp;(arcsec / rok)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Rok měření &alpha; / &delta;:
  </div>
  <div class="right_input">
    <input name="epoch" type="text" value="<?php echo $star->epoch; ?>" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">&nbsp;</div>
  <div class="right_input">
    <input type="submit" value="uložit" />
  </div>
  <div class="clear"></div>
</form>