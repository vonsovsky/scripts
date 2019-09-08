<?php
  if (isset($_POST['name'])) {
    dibi::query('UPDATE [bsc] SET [name] = %s, [dm] = %s, [hd] = %s, [sao] = %s, [fk5] = %s, [varid] = %s,
                 [ra] = %s, [de] = %s, [vmag] = %s, [sptype] = %s, [pmra] = %s, [pmde] = %s, [dmag] = %s WHERE [id] = %i',
                 $_POST['name'], $_POST['dm'], $_POST['hd'], $_POST['sao'], $_POST['fk5'], $_POST['varid'],
                 $_POST['ra'], $_POST['de'], $_POST['vmag'], $_POST['sptype'], $_POST['pmra'],
                 $_POST['pmde'], $_POST['dmag'], $_GET['edit']);
    echo '<div class="ok">Uloženo</div>';
  }

  $star = dibi::query('SELECT [id], [name], [dm], [hd], [sao], [fk5], [varid],
                       [ra], [de], [vmag], [sptype], [pmra], [pmde], [dmag]
                       FROM [bsc] WHERE [id] = %i',
                       $_GET['edit'])->fetch();
?>

<h1>Editace proměnné hvězdy <?php echo $star->name; ?></h1>
<form method="post" action="index.php?page=bsc&edit=<?php echo $_GET['edit']; ?>">
  <div class="left_input">
    ID:
  </div>
  <div class="right_input" style="width: 500px;">
    <input name="name" type="text" value="<?php echo $star->id; ?>" disabled="disabled" />&nbsp;Do čísla 9110 jde o Harvardskou identifikaci
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Název (Flam. / Bay.):
  </div>
  <div class="right_input">
    <input name="name" type="text" value="<?php echo $star->name; ?>" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Durchmusterung:
  </div>
  <div class="right_input">
    <input name="dm" type="text" value="<?php echo $star->dm; ?>" />
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Henry Draper:
  </div>
  <div class="right_input">
    <input name="hd" type="text" value="<?php echo $star->hd; ?>" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    SAO číslo:
  </div>
  <div class="right_input">
    <input name="sao" type="text" value="<?php echo $star->sao; ?>" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    FK5 číslo:
  </div>
  <div class="right_input">
    <input name="fk5" type="text" value="<?php echo $star->fk5; ?>" />
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Pr. hv. identifikace:
  </div>
  <div class="right_input">
    <input name="varid" type="text" value="<?php echo $star->varid; ?>" />
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
    <input name="de" type="text" value="<?php echo $star->de; ?>" />&nbsp;(v desetinném čísle)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Vizuální magnituda:
  </div>
  <div class="right_input">
    <input name="vmag" type="text" value="<?php echo $star->vmag; ?>" />
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Spektrální typ:
  </div>
  <div class="right_input">
    <input name="sptype" type="text" value="<?php echo $star->sptype; ?>" />
  </div>
  <div class="clear"></div>

  <div class="left_input">
    Vlastní pohyb - rektascenze:
  </div>
  <div class="right_input">
    <input name="pmra" type="text" value="<?php echo $star->pmra; ?>" />&nbsp;(arcsec / rok)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Vlastní pohyb - deklinace:
  </div>
  <div class="right_input">
    <input name="pmde" type="text" value="<?php echo $star->pmde; ?>" />&nbsp;(arcsec / rok)
  </div>
  <div class="clear"></div>
  
  <div class="left_input">
    Rozdílová magnituda:
  </div>
  <div class="right_input">
    <input name="dmag" type="text" value="<?php echo $star->dmag; ?>" />
  </div>
  <div class="clear"></div>
  
  <div class="left_input">&nbsp;</div>
  <div class="right_input">
    <input type="submit" value="uložit" />
  </div>
  <div class="clear"></div>
</form>