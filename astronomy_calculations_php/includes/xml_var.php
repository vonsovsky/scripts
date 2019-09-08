#!/packages/run/php/bin/php
<?php
header('Content-disposition: attachment; filename=xml_var.xml');
header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');
header('Expires: -1');

require '../db/connection.php';
include '../includes/variable.php';
include '../includes/deg.php';
include '../includes/astrocalc.php';
include '../includes/includes.php';

$t1 = parseDateToJD($_GET['time_from']);
$t2 = parseDateToJD($_GET['time_to']);
$offset = $_GET['offset'];
if ($offset > 0)
  $offset = '+'.$offset;
$f1 = $_GET['phase_from'];
$f2 = $_GET['phase_to'];

$astroCalc = new AstroCalc($_GET['latitude'], $_GET['longitude'], $offset);

$stars = getParametrizedVariables($astroCalc, $t1, $t2, $f1, $f2, $offset, $_GET['search_query'],
                                  $_GET['min_altitude']);

echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<xml>\n";

echo "  <parametry>\n";
echo "    <min_výška>".$_GET['min_altitude']."°</min_výška>\n";
echo "    <fáze_od>".$_GET['phase_from']."</fáze_od>\n";
echo "    <fáze_do>".$_GET['phase_to']."</fáze_do>\n";
echo "    <čas_od>".$_GET['time_from']."</čas_od>\n";
echo "    <čas_do>".$_GET['time_to']."</čas_do>\n";
echo "    <řetězec>".$_GET['search_query']."</řetězec>\n";
echo "    <délka>".$_GET['longitude']."</délka>\n";
echo "    <šířka>".$_GET['latitude']."</šířka>\n";
echo "    <posun_času>".$offset."</posun_času>\n";
echo "  </parametry>\n";

foreach ($stars as $star) {
    $ra = new Deg($star->ra);
    $dec = new Deg($star->dec);

    echo "  <hvězda>\n";
    echo "    <název>".$star->name."</název>\n";
    echo "    <průběh>\n";

    foreach ($star->from as $i => $from) {
      $timeFrom = substr(printJulianDate($star->from[$i]), 0, -6);
      // stejný den, stačí vypsat jen čas
      if ((int)($star->from[$i] - 0.5) == (int)($star->to[$i] - 0.5)) {
        $part = explode(' ', printJulianDate($star->to[$i]));
        $timeTo = substr($part[3], 0, 5);
      } else {
        $timeTo = substr(printJulianDate($star->to[$i]), 0, -6);
      }
      
      echo "      <datum>".$timeFrom.' - '.$timeTo.' ';

      $astroCalc->setDateTime($star->from[$i] + ($star->to[$i] - $star->from[$i]) / 2 - $offset / 24);
      $pos = $astroCalc->calcObjectPos($star->ra, $star->dec, false);
      echo round($pos[0], 1)."°</datum>\n";
    }

    echo "    </průběh>\n";
    echo "  </hvězda>\n";
}

echo "</xml>\n";

?>