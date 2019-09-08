// Čísla na stupně, vrací řetězec ve formě X Y Z
function toDegree(dec) {
  degrees = '';
  
  degrees = parseInt(dec);
	frac = (dec - parseInt(dec)) * 60;
	if (frac == 0) return degrees;
  
  if (frac < 0) frac *= -1;
	minutes = parseInt(frac);
	frac = (frac - parseInt(frac)) * 60;
	if (frac < 0) frac *= -1;

  if (frac == 0) return degrees + ' ' + minutes;
	seconds = Math.round(frac * 10) / 10;
  
  if (seconds == 60) {
    seconds = 0; minutes++;
  }
  
  if (minutes == 60) {
    minutes = 0; degrees++;
  }
  
  return degrees + ' ' + minutes + ' ' + seconds;
}

function toDec(deg) {
  degrees = deg.split(' ');
  
  for (i = 0; i <= 2; i++)
    if (!degrees[i]) degrees[i] = 0;

  frac = parseFloat(degrees[1]) + parseFloat(degrees[2] / 60.0);
	if (parseFloat(degrees[0]) >= 0)
		frac = parseFloat(degrees[0]) + frac / 60.0;
	// zaporne stupne
	else frac = parseFloat(degrees[0]) - frac / 60.0;
  
  frac = Math.round(frac * 1e4) / 1e4;

	return frac;
}

// Přepočítává stupně na čísla a naopak z polí na stránce
function recountDegrees(decimalElm, degreeElm) {
  aVal = $('#' + decimalElm).val();
  bVal = $('#' + degreeElm).val();
  
  if (aVal.length == 0 && bVal.length == 0) {
    alert('Není vyplněna žádná strana.');
    return;
  }

  if (aVal.length > 0 && bVal.length > 0) {
    alert('Jsou vyplněny obě strany. Nejdříve jednu smažte.');
    return;
  }
  
  if (aVal.length > 0) {
    $('#' + degreeElm).val(toDegree(aVal));
  }

  if (bVal.length > 0) {
    $('#' + decimalElm).val(toDec(bVal));
  }
}

function selectPlace() {
  optVal = $('#place option:selected').val();
  loc = optVal.split(',');

  $('#longitude').val(loc[0]);
  $('#deglong').val(toDegree(loc[0]));

  $('#latitude').val(loc[1]);
  $('#deglat').val(toDegree(loc[1]));
}

function toggleDiv(elm) {
  $('#' + elm).slideToggle('fast');
  if ($('#h' + elm + ' span').html() == '-')
    $('#h' + elm + ' span').html('+');
  else $('#h' + elm + ' span').html('-');
}