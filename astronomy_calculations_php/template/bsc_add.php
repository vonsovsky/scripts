<?php
  include 'includes/variable.php';
  
  if (isset($_POST['name'])) {
    dibi::query('INSERT INTO [ephem] ([name], [dm], [hd], [sao], [fk5], [varid], [ra],
                 [dec], [vmag], [sptype], [pmra], [pmde], [dmag])
                 VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)',
                 $_POST['name'], $_POST['dm'], $_POST['hd'], $_POST['sao'], $_POST['fk5'],
                 $_POST['varid'], $_POST['ra'], $_POST['dec'], $_POST['vmag'], $_POST['sptype'],
                 $_POST['pmra'], $_POST['pmde'], $_POST['dmag']);
    $id = dibi::getInsertId();
    echo '<div class="ok">Objekt <a href="index.php?page=bsc&edit='.$id.'">přidán</a></div>';
  }
?>

<h1>Přidat proměnnou hvězdu</h1>
<form method="post" action="index.php?page=bsc&add=1">
  <div class="left_input">
    Název (Flam. / Bay.):
  </div>
  <div class="right_input">
    <input name="name" type="text" value="" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Durchmusterung:
  </div>
  <div class="right_input">
    <input name="dm" type="text" value="" />
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Henry Draper:
  </div>
  <div class="right_input">
    <input name="hd" type="text" value="" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    SAO číslo:
  </div>
  <div class="right_input">
    <input name="sao" type="text" value="" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    FK5 číslo:
  </div>
  <div class="right_input">
    <input name="fk5" type="text" value="" />
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Pr. hv. identifikace:
  </div>
  <div class="right_input">
    <input name="varid" type="text" value="" />
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
    Vizuální magnituda:
  </div>
  <div class="right_input">
    <input name="vmag" type="text" value="" />
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Spektrální typ:
  </div>
  <div class="right_input">
    <input name="sptype" type="text" value="" />
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Vlastní pohyb - rektascenze:
  </div>
  <div class="right_input">
    <input name="pmra" type="text" value="" />&nbsp;(arcsec / rok)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Vlastní pohyb - deklinace:
  </div>
  <div class="right_input">
    <input name="pmde" type="text" value="" />&nbsp;(arcsec / rok)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Rozdílová magnituda:
  </div>
  <div class="right_input">
    <input name="dmag" type="text" value="" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">&nbsp;</div>
  <div class="right_input">
    <input type="submit" value="přidat" />
  </div>
  <div class="clear"></div>
</form>