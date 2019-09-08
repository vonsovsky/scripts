<?php
  if (!isset($_SESSION['place'])) {
    $place = '';
    if (isset($_COOKIE['place']) && strlen($_COOKIE['place']) > 0)
      $place = $_COOKIE['place'];
    if (isset($_POST['place']) && strlen($_POST['place']) > 0)
      $place = $_POST['place'];
    
    $_SESSION['place'] = $place;
  }

  if (!isset($_SESSION['longitude'])) {
    $longitude = '-16.608';
    if (isset($_COOKIE['longitude']) && strlen($_COOKIE['longitude']) > 0)
      $longitude = $_COOKIE['longitude'];
    if (isset($_POST['longitude']) && strlen($_POST['longitude']) > 0)
      $longitude = $_POST['longitude'];
    
    $_SESSION['longitude'] = $longitude;
  }
  
  if (!isset($_SESSION['latitude'])) {
    $latitude = '49.1952';
    if (isset($_COOKIE['latitude']) && strlen($_COOKIE['latitude']) > 0)
      $latitude = $_COOKIE['latitude'];
    if (isset($_POST['latitude']) && strlen($_POST['latitude']) > 0)
      $latitude = $_POST['latitude'];
    $degLat = new Deg($latitude);

    $_SESSION['latitude'] = $latitude;
    $_SESSION['degLat'] = $degLat;
  }

  if (!isset($_SESSION['localoffset'])) {
    $loffset = 1;
    if (isset($_COOKIE['localoffset']))
      $loffset = $_COOKIE['localoffset'];
    if (isset($_POST['localOffset']))
      $loffset = $_POST['localOffset'];
      
    $_SESSION['localoffset'] = $loffset;
  }

  if (!isset($_SESSION['date_from'])) {
    $date_from = date('d. m. Y');
    if (isset($_COOKIE['date_from']) && strlen($_COOKIE['date_from']) > 0)
      $date_from = $_COOKIE['date_from'];
    if (isset($_POST['date_from']) && strlen($_POST['date_from']) > 0)
      $date_from = $_POST['date_from'];
  
    $_SESSION['date_from'] = $date_from;
  }

  if (!isset($_SESSION['date_to'])) {
    $date_to = date('d. m. Y');
    if (isset($_COOKIE['date_to']) && strlen($_COOKIE['date_to']) > 0)
      $date_to = $_COOKIE['date_to'];
    if (isset($_POST['date_to']) && strlen($_POST['date_to']) > 0)
      $date_to = $_POST['date_to'];
    
    $_SESSION['date_to'] = $date_to;
  }

  if (!isset($_SESSION['DST'])) {
    $checked = '';
    if (!isset($_POST['calculate']) && isset($_COOKIE['DST']) && $_COOKIE['DST'] == '1')
      $checked = 'checked="checked"';
    if (isset($_POST['DST']))
      $checked = 'checked="checked"';
    // pokud není nic nastaveno v POST a COOKIES, provede se hrubý odhad letního času na základě aktuálního měsíce
    if ($checked == '') {
      $date = explode('.', $date_from);
      $month = trim($date[1]);
    }      
    if ($checked == '' &&
        (isset($_POST['DST']) && $month >= 4 && $month <= 10 ||
        !isset($_POST['DST']) && !isset($_POST['calculate']) && date('m') >= 4 && date('m') <= 10))
          $checked = 'checked="checked"';
  
    $_SESSION['DST'] = $checked;
  }

  if (!isset($_SESSION['min_altitude'])) {
    $min_altitude = 0;
    if (isset($_COOKIE['min_altitude']) && strlen($_COOKIE['min_altitude']) > 0)
      $min_altitude = $_COOKIE['min_altitude'];
    if (isset($_POST['min_altitude']) && strlen($_POST['min_altitude']) > 0)
      $min_altitude = $_POST['min_altitude'];
  
    $_SESSION['min_altitude'] = $min_altitude;
  }

  // parametrické hledání
  if (!isset($_SESSION['time_from'])) {
    $time_from = date('d. m. Y').' 18:00';
    if (isset($_COOKIE['time_from']) && strlen($_COOKIE['time_from']) > 0)
      $time_from = $_COOKIE['time_from'];
    if (isset($_POST['time_from']) && strlen($_POST['time_from']) > 0)
      $time_from = $_POST['time_from'];
  
    $_SESSION['time_from'] = $time_from;
  }

  if (!isset($_SESSION['time_to'])) {
    // zítra v 6:00
    $time_to = date('d. m. Y', mktime(0, 0, 0, date("m"), date("d") + 1, date("y"))).' 6:00';
    if (isset($_COOKIE['time_to']) && strlen($_COOKIE['time_to']) > 0)
      $time_to = $_COOKIE['time_to'];
    if (isset($_POST['time_to']) && strlen($_POST['time_to']) > 0)
      $time_to = $_POST['time_to'];

    $_SESSION['time_to'] = $time_to;
  }
  
  if (!isset($_SESSION['phase_from'])) {
    $phase_from = '0.9';
    if (isset($_COOKIE['phase_from']) && strlen($_COOKIE['phase_from']) > 0)
      $phase_from = $_COOKIE['phase_from'];
    if (isset($_POST['phase_from']) && strlen($_POST['phase_from']) > 0)
      $phase_from = $_POST['phase_from'];
    
    $_SESSION['phase_from'] = $phase_from;
  }

  if (!isset($_SESSION['phase_to'])) {
    $phase_to = '1.1';
    if (isset($_COOKIE['phase_to']) && strlen($_COOKIE['phase_to']) > 0)
      $phase_to = $_COOKIE['phase_to'];
    if (isset($_POST['phase_to']) && strlen($_POST['phase_to']) > 0)
      $phase_to = $_POST['phase_to'];
    
    $_SESSION['phase_to'] = $phase_to;
  }
?>