<?php
//ini_set("precision", 50);
$T = 1 + $t / 36525;

/*
$T = ($JD - 2415020) / 36525;
$T2 = ($JD - 2433282.4234) / 36524.22;
$T3 = ($JD - 2451545) / 36525;
*/

/*
$mercury = array(
  'L' => 178.179078 + 149474.07078 * $T + 0.0003011 * pow($T, 2),
  'a' => 0.38709860,
  'e' => 0.20561421 + 0.00002046 * $T - 0.000000030 * pow($T, 2),
  'i' => 7.002881 + 0.0018608 * $T - 0.0000183 * pow($T, 2),
  'w' => 28.753753 + 0.3702806 * $T + 0.0001208 * pow($T, 2),
  'O' => 47.145944 + 1.1852083 * $T + 0.0001739 * pow($T, 2),
  'pi' => 75.899697 + 1.5554889 * $T + 0.0002947 * pow($T, 2),
  'M' => 102.279381 + 149472.51529 * $T + 0.0000064 * pow($T, 2),

  'ra' => 280.8655 - 0.03296 * $T2,
  'dec' => 61.3977 - 0.00467 * $T2,

  $L1 => 0.700695 + 0.01136771400 * $T3,
  $M1 => 0.485541 + 0.01136759566 * $T3,
);

$venus = array(
  'L' => 342.767053 + 58519.21191 * $T + 0.0003097 * pow($T, 2),
  'a' => 0.72333162,
  'e' => 0.00682069 - 0.00004774 * $T + 0.000000091 * pow($T, 2),
  'i' => 3.393631 + 0.0010058 * $T - 0.0000010,
  'w' => 54.384186 + 0.5081861 * $T - 0.0013864 * pow($T, 2),
  'O' => 75.779647 + 0.8998500 * $T + 0.0004100 * pow($T, 2),
  'pi' => 130.163833 + 1.4080361 * $T - 0.0009764 * pow($T, 2),
  'M' => 212.603220 + 58.517.80387 * $T + 0.00012861 * pow($T, 2),

  'ra' => 273.3,
  'dec' => 67.3,
);
*/

$moon = array(
  'L' => 0.606434 + 0.03660110129 * $t,
  'M' => 0.374897 + 0.03629164709 * $t,
  'u' => 0.259091 + 0.0367481952 * $t,
  'e' => 0.827362 + 0.03386319198 * $t,
  'o' => 0.347343 - 0.00014709391 * $t,
);

//slunce
$sun = array(
  'L' => 0.779072 + 0.00273790931 * $t,
  'M' => 0.993126 + 0.0027377785 * $t,
);

$mercury = array(
  'L' => (0.700695 + 0.011367714 * $t) * 2 * pi(),
  'M' => (0.485541 + 0.01136759566 * $t) * 2 * pi(),
  'u' => (0.5664411 + 0.01136762384 * $t) * 2 * pi(),
);

$venus = array(
  'L' => (0.505498 + 0.00445046867 * $t) * 2 * pi(),
  'M' => (0.140023 + 0.00445036173 * $t) * 2 * pi(),
  'u' => (0.292498 + 0.00445040017 * $t) * 2 * pi(),
);

$mars = array(
  'L' => (0.987353 + 0.00145575328 * $t) * 2 * pi(),
  'M' => (0.053856 + 0.00145561327 * $t) * 2 * pi(),
  'u' => (0.849694 + 0.00145569465 * $t) * 2 * pi(),
);

$jupiter = array(
  'L' => (0.089608 + 0.00023080893 * $t) * 2 * pi(),
  'M' => (0.056531 + 0.00023080893 * $t) * 2 * pi(),
  'u' => (0.814794 + 0.00023080893 * $t) * 2 * pi(),
);

$saturn = array(
  'L' => (0.133295 + 0.00009294371 * $t) * 2 * pi(),
  'M' => (0.882987 + 0.00009294371 * $t) * 2 * pi(),
  'u' => (0.821218 + 0.00009294371 * $t) * 2 * pi(),
);

$uranus = array(
  'L' => (0.870169 + 0.00003269438 * $t) * 2 * pi(),
  'M' => (0.400589 + 0.00003269438 * $t) * 2 * pi(),
  'u' => (0.664614 + 0.00003265562 * $t) * 2 * pi(),
);

$neptune = array(
  'L' => (0.846912 + 0.00001672092 * $t) * 2 * pi(),
  'M' => (0.725368 + 0.00001672092 * $t) * 2 * pi(),
  'u' => (0.480856 + 0.00001663715 * $t) * 2 * pi(),
);

$mercury['l'] = $mercury['L'] + 84378 * sin($mercury['M'])
                              + 10733 * sin(2 * $mercury['M']) 
                              + 1.892 * sin(3 * $mercury['M'])
                              - 646 * sin(2 * $mercury['u'])
                              + 381 * sin(4 * $mercury['M'])
                              - 306 * sin($mercury['M'] - 2 * $mercury['u'])
                              - 274 * sin($mercury['M'] + 2 * $mercury['u'])
                              - 92 * sin(2 * $mercury['M'] + 2 * $mercury['u']);
                              - 83 * sin(5 * $mercury['M']) 
                              - 28 * sin(3 * $mercury['M'] + 2 * $mercury['u'])
                              + 25 * sin(2 * $mercury['M'] - 2 * $mercury['u'])
                              + 19 * sin(6 * $mercury['M'])
                              - 9 * sin(4 * $mercury['M'] + 2 * $mercury['u'])
                              + 8 * $T * sin($mercury['M'])
                              + 7 * cos($mercury['M'] - 5 * $venus['M']);
$mercury['b'] = 24134 * sin($mercury['u'])
              + 5180 * sin($mercury['M'] - $mercury['u'])
              + 4910 * sin($mercury['M'] + $mercury['u'])
              + 1124 * sin(2 * $mercury['M'] + $mercury['u'])
              + 271 * sin(3 * $mercury['M'] + $mercury['u'])
              + 132 * sin(2 * $mercury['M'] - $mercury['u']);
              + 67 * sin(4 * $mercury['M'] + $mercury['u'])
              + 18 * sin(3 * $mercury['M'] - $mercury['u'])
              + 17 * sin(5 * $mercury['M'] + $mercury['u'])
              - 10 * sin(3 * $mercury['u'])
              - 9 * sin($mercury['M'] - 3 * $mercury['u']);
$mercury['r'] = 0.39528 - 0.07834 * cos($mercury['M']) - 0.00795 * cos(2 * $mercury['M']) - 0.00121 * cos(3 * $mercury['M']) - 0.00022 * cos(4 * $mercury['M']);

$venus['l'] = $venus['L'] + 2814 * sin($venus['M'])
            - 181 * sin(2 * $venus['u'])
            - 20 * $T * sin($venus['M']) 
            + 12 * sin(2 * $venus['M'])
            - 10 * cos(2 * $sun['M'] - 2 * $venus['M'])
            + 7 * cos(3 * $sun['M'] - 3 * $venus['M']);
$venus['b'] = 12215 * sin($venus['u'])
            + 83 * sin($venus['M'] + $venus['u'])
            + 83 * sin($venus['M'] - $venus['u']);
$venus['r'] = 0.72335 - 0.00493 * cos($venus['M']);

$mars['l'] = $mars['L'] + 38451 * sin($mars['M'])
           + 2238 * sin(2 * $mars['M'])
           + 181 * sin(3 * $mars['M'])
           + 37 * $T * sin($mars['M'])
           - 52 * sin(2 * $mars['u'])
           - 22 * cos($mars['M'] - 2 * $jupiter['M'])
           - 19 * sin($mars['M'] - $jupiter['M'])
           + 17 * cos($mars['M'] - $jupiter['M'])
           + 17 * sin(4 * $mars['M'])
           - 16 * cos(2 * $mars['M'] - 2 * $jupiter['M'])
           + 13 * cos($sun['M'] - 2 * $mars['M'])
           - 10 * sin($mars['M'] + 2 * $mars['u'])
           - 10 * sin($mars['M'] - 2 * $mars['u']);
           + 7 * cos($sun['M'] - $mars['M'])
           - 7 * cos(2 * $sun['M'] - 3 * $mars['M'])
           - 5 * sin($venus['M'] - 3 * $mars['M'])
           - 5 * sin($sun['M'] - $mars['M'])
           - 5 * sin($sun['M'] - 2 * $mars['M'])
           - 4 * cos(2 * $sun['M'] - 4 * $mars['M'])
           + 4 * $T * sin(2 * $mars['M'])
           + 4 * cos($jupiter['M'])
           + 3 * cos($venus['M'] - 3 * $mars['M'])
           + 3 * sin(2 * $mars['M'] - 2 * $jupiter['M']);
$mars['b'] = 6603 * sin($mars['u'])
           + 622 * sin($mars['M'] - $mars['u'])
           + 615 * sin($mars['M'] + $mars['u'])
           + 64 * sin(2 * $mars['M'] + $mars['u']);
$mars['r'] = 1.53031 - 0.1417 * cos($mars['M'])
                     - 0.0066 * cos(2 * $mars['M'])
                     - 0.00047 * cos(3 * $mars['M']);

$jupiter['l'] = $jupiter['L'] + 19934 * sin($jupiter['M'])
                   + 5023 * $T - 14 * sin($jupiter['M'] - $saturn['M'])
                   + 2511
                   + 1093 * cos(2 * $jupiter['M'] - 5 * $saturn['M'])
                   + 601 * sin(2 * $jupiter['M'])
                   - 479 * sin(2 * $jupiter['M'] - 5 * $saturn['M'])
                   - 185 * sin(2 * $jupiter['M'] - 2 * $saturn['M'])
                   + 137 * sin(3 * $jupiter['M'] - 5 * $saturn['M'])
                   - 131 * sin($jupiter['M'] - 2 * $saturn['M'])
                   + 79 * cos($jupiter['M'] - $saturn['M'])
                   - 76 * cos(2 * $jupiter['M'] - 2 * $saturn['M'])
                   - 74 * $T * cos($jupiter['M'])
                   + 68 * $T * sin($jupiter['M'])
                   + 66 * cos(2 * $jupiter['M'] - 3 * $saturn['M'])
                   + 63 * cos(3 * $jupiter['M'] - 5 * $saturn['M'])
                   + 53 * cos($jupiter['M'] - 5 * $saturn['M'])
                   + 49 * sin(2 * $jupiter['M'] - 3 * $saturn['M'])
                   - 43 * $T * sin(2 * $jupiter['M'] - 5 * 5 * $saturn['M'])
                   - 37 * cos($jupiter['M'])
                   + 25 * sin(2 * $jupiter['L'])
                   + 25 * sin(3 * $jupiter['M'])
                   - 23 * sin($jupiter['M'] - 5 * $saturn['M'])
                   - 19 * $T * cos(2 * $jupiter['M'] - 5 * $saturn['M'])
                   + 17 * cos(2 * $jupiter['M'] - 4 * $saturn['M'])
                   + 17 * cos(3 * $jupiter['M'] - 3 * $saturn['M'])
                   - 13 * sin(3 * $jupiter['M'] - 4 * $saturn['M'])
                   - 9 * cos($jupiter['L'])
                   + 9 * cos($saturn['M'])
                   - 9 * sin($saturn['M'])
                   - 9 * sin(3 * $jupiter['M'] - 2 * $saturn['M'])
                   + 9 * sin(4 * $jupiter['M'] - 5 * $saturn['M'])
                   + 9 * sin(2 * $jupiter['M'] - 6 * $saturn['M'] + 3 * $uranus['M'])
                   - 8 * cos(4 * $jupiter['M'] - 10 * $saturn['M'])
                   + 7 * cos(3 * $jupiter['M'] - 4 * $saturn['M'])
                   - 7 * cos($jupiter['M'] - 3 * $saturn['M'])
                   - 7 * sin(4 * $jupiter['M'] - 10 * $saturn['M'])
                   - 7 * sin($jupiter['M'] - 3 * $saturn['M'])
                   + 6 * cos(4 * $jupiter['M'] - 5 * $saturn['M'])
                   - 6 * sin(3 * $jupiter['M'] - 3 * $saturn['M'])
                   + 5 * cos(2 * $saturn['M'])
                   - 4 * sin(4 * $jupiter['M'] - 4 * $saturn['M'])
                   - 4 * cos(3 * $saturn['M'])
                   + 4 * cos(2 * $jupiter['M'] - $saturn['M'])
                   - 4 * cos(3 * $jupiter['M'] - 2 * $saturn['M'])
                   - 4 * $T * cos(2 * $jupiter['M'])
                   + 3 * $T * sin(2 * $jupiter['M'])
                   + 3 * cos(5 * $saturn['M'])
                   + 2 * sin(2 * $jupiter['L'] + $jupiter['M'])
                   + 3 * cos(5 * $jupiter['M'] - 10 * $saturn['M'])
                   - 2 * $T * sin(3 * $jupiter['M'] - 5 * $saturn['M'])
                   + 3 * sin(2 * $saturn['M'])
                   - 2 * $T * sin($jupiter['M'] - 5 * $saturn['M'])
                   - 2 * sin(2 * $jupiter['L'] - $jupiter['M']);
$jupiter['b'] = - 4692 * cos($jupiter['M'])
                + 259 * sin($jupiter['M'])
                + 227
                - 227 * cos(2 * $jupiter['M'])
                + 30 * $T * sin($jupiter['M'])
                + 21 * $T * cos($jupiter['M'])
                + 16 * sin(3 * $jupiter['M'] - 5 * $saturn['M'])
                - 13 * sin($jupiter['M'] - 5 * $saturn['M'])
                - 12 * cos(3 * $jupiter['M'])
                + 12 * sin(2 * $jupiter['M'])
                + 7 * cos(3 * $jupiter['M'] - 5 * $saturn['M'])
                - 5 * cos($jupiter['M'] - 5 * $saturn['M']);
$jupiter['r'] = 5.20883 - 0.25122 * cos($jupiter['M'])
                        - 0.00604 * cos(2 * $jupiter['M'])
                        + 0.0026 * cos(2 * $jupiter['M'] - 2 * $saturn['M'])
                        - 0.0017 * cos(3 * $jupiter['M'] - 5 * $saturn['M'])
                        - 0.00106 * sin(2 * $jupiter['M'] - 2 * $saturn['M'])
                        - 0.00091 * $T * sin($jupiter['M'])
                        - 0.00084 * $T * cos($jupiter['M'])
                        + 0.00069 * sin(2 * $jupiter['M'] - 3 * $saturn['M'])
                        - 0.00067 * sin($jupiter['M'] - 5 * $saturn['M']);
                        + 0.00066 * sin(3 * $jupiter['M'] - 5 * $saturn['M'])
                        + 0.00063 * sin($jupiter['M'] - $saturn['M'])
                        - 0.00051 * cos(2 * $jupiter['M'] - 3 * $saturn['M'])
                        - 0.00046 * sin($jupiter['M'])
                        - 0.00029 * cos($jupiter['M'] - 5 * $saturn['M'])
                        + 0.00027 * cos($jupiter['M'] - 2 * $saturn['M'])
                        - 0.00022 * cos(3 * $jupiter['M'])
                        - 0.00021 * sin(2 * $jupiter['M'] - 5 * $saturn['M']);

$saturn['l'] = $saturn['L'] + 23045 * sin($saturn['M'])
                  + 22 * $T * sin(2 * $jupiter['M'] - 4 * $saturn['M'])
                  + 5014 * $T
                  - 2689 * cos(2 * $jupiter['M'] - 5 * $saturn['M'])
                  + 2507
                  + 1177 * sin(2 * $jupiter['M'] - 5 * $saturn['M'])
                  - 826 * cos(2 * $jupiter['M'] - 4 * $saturn['M'])
                  + 802 * sin(2 * $saturn['M'])
                  + 425 * sin($jupiter['M'] - 2 * $saturn['M'])
                  - 229 * $T * cos($saturn['M'])
                  - 153 * cos(2 * $jupiter['M'] - 6 * $saturn['M'])
                  - 142 * $T * sin($saturn['M'])
                  - 114 * cos($saturn['M'])
                  + 101 * $T * sin(2 * $jupiter['M'] - 5 * $saturn['M'])
                  - 70 * cos(2 * $saturn['L'])
                  + 67 * sin(2 * $saturn['L'])
                  + 66 * sin(2 * $jupiter['M'] - 6 * $saturn['M'])
                  + 60 * $T * cos(2 * $jupiter['M'] - 5 * $saturn['M'])
                  + 41 * sin($jupiter['M'] - 3 * $saturn['M'])
                  + 39 * sin(3 * $saturn['M'])
                  + 31 * sin($jupiter['M'] - $saturn['M'])
                  + 31 * sin(2 * $jupiter['M'] - 2 * $saturn['M'])
                  - 29 * cos(2 * $jupiter['M'] - 3 * $saturn['M'])
                  - 28 * sin(2 * $jupiter['M'] - 6 * $saturn['M'] + 3 * $uranus['M'])
                  + 28 * cos($jupiter['M'] - 3 * $saturn['M'])
                  - 22 * sin($saturn['M'] - 3 * $uranus['M'])
                  + 20 * sin(2 * $jupiter['M'] - 3 * $saturn['M'])
                  + 20 * cos(4 * $jupiter['M'] - 10 * $saturn['M'])
                  + 19 * cos(2 * $saturn['M'] - 3 * $uranus['M'])
                  + 19 * sin(4 * $jupiter['M'] - 10 * $saturn['M'])
                  - 17 * $T * cos(2 * $saturn['M'])
                  - 16 * cos($saturn['M'] - 3 * $uranus['M'])
                  - 12 * sin(2 * $jupiter['M'] - 4 * $saturn['M'])
                  + 12 * cos($jupiter['M'])
                  - 12 * sin(2 * $saturn['M'] - 2 * $uranus['M'])
                  - 11 * $T * sin(2 * $saturn['M'])
                  - 11 * cos(2 * $jupiter['M'] - 7 * $saturn['M'])
                  + 10 * sin(2 * $saturn['M'] - 3 * $uranus['M'])
                  + 10 * cos(2 * $jupiter['M'] - 2 * $saturn['M'])
                  + 9 * sin(4 * $jupiter['M'] - 9 * $saturn['M'])
                  - 8 * sin($saturn['M'] - 2 * $uranus['M'])
                  - 8 * cos(2 * $saturn['L'] + $saturn['M'])
                  + 8 * cos(2 * $saturn['L'] - $saturn['M'])
                  + 8 * cos($saturn['M'] - $uranus['M'])
                  - 8 * sin(2 * $saturn['L'] - $saturn['M'])
                  + 7 * sin(2 * $saturn['L'] + $saturn['M'])
                  - 7 * cos($jupiter['M'] - 2 * $saturn['M'])
                  - 7 * cos(2 * $saturn['M'])
                  - 6 * $T * sin(4 * $jupiter['M'] - 10 * $saturn['M'])
                  + 6 * $T * cos(4 * $jupiter['M'] - 10 * $saturn['M'])
                  + 6 * $T * sin(2 * $jupiter['M'] - 6 * $saturn['M'])
                  - 5 * sin(3 * $jupiter['M'] - 7 * $saturn['M'])
                  - 5 * cos(3 * $jupiter['M'] - 3 * $saturn['M'])
                  - 5 * cos(2 * $saturn['M'] - 2 * $uranus['M'])
                  + 5 * sin(3 * $jupiter['M'] - 4 * $saturn['M'])
                  + 5 * sin(2 * $jupiter['M'] - 7 * $saturn['M'])
                  + 4 * sin(3 * $jupiter['M'] - 3 * $saturn['M'])
                  + 4 * sin(3 * $jupiter['M'] - 5 * $saturn['M'])
                  + 4 * $T * cos($jupiter['M'] - 2 * $saturn['M'])
                  + 3 * $T * cos(2 * $jupiter['M'] - 4 * $saturn['M'])
                  + 3 * cos(2 * $jupiter['M'] - 6 * $saturn['M'] + 3 * $uranus['M'])
                  - 3 * $T * sin(2 * $saturn['L'])
                  + 3 * $T * cos(2 * $jupiter['M'] - 6 * $saturn['M'])
                  - 3 * $T * cos(2 * $saturn['L'])
                  + 3 * cos(3 * $jupiter['M'] - 7 * $saturn['M'])
                  + 3 * cos(4 * $jupiter['M'] - 9 * $saturn['M'])
                  + 3 * sin(3 * $jupiter['M'] - 6 * $saturn['M'])
                  + 3 * sin(2 * $jupiter['M'] - $saturn['M'])
                  + 3 * sin($jupiter['M'] - 4 * $saturn['M'])
                  + 2 * cos(3 * $saturn['M'] - 3 * $uranus['M'])
                  + 2 * $T * sin($jupiter['M'] - 2 * $saturn['M'])
                  + 2 * sin(4 * $saturn['M'])
                  - 2 * cos(3 * $jupiter['M'] - 4 * $saturn['M'])
                  - 2 * cos(2 * $jupiter['M'] - $saturn['M'])
                  - 2 * sin(2 * $jupiter['M'] - 7 * $saturn['M'] + 3 * $uranus['M'])
                  + 2 * cos($jupiter['M'] - 4 * $saturn['M'])
                  + 2 * cos(4 * $jupiter['M'] - 11 * $saturn['M'])
                  - 2 * sin($saturn['M'] - $uranus['M']);
$saturn['b'] = 8297 * sin($saturn['M'])
             - 3346 * cos($saturn['M'])
             + 462 * sin(2 * $saturn['M'])
             - 189 * cos(2 * $saturn['M'])
             + 185
             - 79 * $T * cos($saturn['M'])
             - 71 * cos(2 * $jupiter['M'] - 4 * $saturn['M'])
             + 46 * sin(2 * $jupiter['M'] - 6 * $saturn['M'])
             - 45 * cos(2 * $jupiter['M'] - 6 * $saturn['M'])
             + 29 * sin(3 * $saturn['M'])
             - 20 * cos(2 * $jupiter['M'] - 3 * $saturn['M'])
             + 18 * $T * sin($saturn['M'])
             - 14 * cos(2 * $jupiter['M'] - 5 * $saturn['M'])
             - 11 * cos(3 * $saturn['M'])
             - 10 * $T
             + 6 * sin($jupiter['M'] - 3 * $saturn['M'])
             + 8 * sin($jupiter['M'] - $saturn['M'])
             - 6 * sin(2 * $jupiter['M'] - 3 * $saturn['M'])
             + 5 * sin(2 * $jupiter['M'] - 7 * $saturn['M'])
             - 5 * cos(2 * $jupiter['M'] - 7 * $saturn['M'])
             + 4 * sin(2 * $jupiter['M'] - 5 * $saturn['M'])
             - 4 * $T * sin(2 * $saturn['M'])
             - 3 * cos($jupiter['M'] - $saturn['M'])
             + 3 * cos($jupiter['M'] - 3 * $saturn['M'])
             + 3 * $T * sin(2 * $jupiter['M'] - 4 * $saturn['M'])
             + 3 * sin($jupiter['M'] - 2 * $saturn['M'])
             + 2 * sin(4 * $saturn['M'])
             - 2 * cos(2 * $jupiter['M'] - 2 * $saturn['M']);
$saturn['r'] = 9.55774 - 0.53252 * cos($saturn['M'])
                       - 0.01878 * sin(2 * $jupiter['M'] - 4 * $saturn['M'])
                       - 0.01482 * cos(2 * $saturn['M'])
                       + 0.00817 * sin($jupiter['M'] - $saturn['M'])
                       - 0.00539 * cos($jupiter['M'] - 2 * $saturn['M'])
                       - 0.00524 * $T * sin($saturn['M'])
                       + 0.00349 * sin(2 * $jupiter['M'] - 5 * $saturn['M'])
                       + 0.00347 * sin(2 * $jupiter['M'] - 6 * $saturn['M'])
                       + 0.00328 * $T * cos($saturn['M'])
                       - 0.00225 * sin($saturn['M'])
                       + 0.00149 * cos(2 * $jupiter['M'] - 6 * $saturn['M'])
                       - 0.00126 * cos(2 * $jupiter['M'] - 2 * $saturn['M'])
                       + 0.00104 * cos($jupiter['M'] - $saturn['M'])
                       + 0.00101 * cos(2 * $jupiter['M'] - 5 * $saturn['M'])
                       + 0.00098 * cos($jupiter['M'] - 3 * $saturn['M'])
                       - 0.00073 * cos(2 * $jupiter['M'] - 3 * $saturn['M'])
                       - 0.00062 * cos(3 * $saturn['M'])
                       + 0.00042 * sin(2 * $saturn['M'] - 3 * $uranus['M'])
                       + 0.00041 * sin(2 * $jupiter['M'] - 2 * $saturn['M'])
                       - 0.0004 * sin($jupiter['M'] - 3 * $saturn['M'])
                       + 0.0004 * cos(2 * $jupiter['M'] - 4 * $saturn['M'])
                       - 0.00028 * $T
                       - 0.00023 * sin($jupiter['M'])
                       + 0.0002 * sin(2 * $jupiter['M'] - 7 * $saturn['M']);

$uranus['l'] = $uranus['L'] + 19397 * sin($uranus['M'])
                  + 570 * sin(2 * $uranus['M'])
                  - 536 * $T * cos($uranus['M'])
                  + 143 * sin($saturn['M'] - 2 * $uranus['M'])
                  - 110 * $T * sin($uranus['M'])
                  + 102 * sin($saturn['M'] - 3 * $uranus['M'])
                  + 76 * cos($saturn['M'] - 3 * $uranus['M'])
                  - 49 * sin($jupiter['M'] - $uranus['M'])
                  + 32 * $T * $T
                  - 30 * $T * cos(2 * $uranus['M'])
                  + 29 * sin(2 * $jupiter['M'] - 6 * $saturn['M'] + 6 * $uranus['M'])
                  + 29 * cos(2 * $uranus['M'] - 2 * $neptune['M'])
                  - 28 * cos($uranus['M'] - $neptune['M'])
                  + 23 * sin(3 * $uranus['M'])
                  - 21 * cos($jupiter['M'] - $uranus['M'])
                  + 20 * sin($uranus['M'] - $neptune['M'])
                  + 20 * cos($saturn['M'] - 2 * $uranus['M'])
                  - 19 * cos($saturn['M'] - $uranus['M'])
                  + 17 * sin(2 * $uranus['M'] - 3 * $neptune['M'])
                  + 14 * sin(3 * $uranus['M'] - 3 * $neptune['M'])
                  + 13 * sin($saturn['M'] - $uranus['M'])
                  - 12 * $T * $T * cos($uranus['M'])
                  - 12 * cos($uranus['M'])
                  + 10 * sin(2 * $uranus['M'] - 2 * $neptune['M'])
                  - 9 * sin(2 * $uranus['u'])
                  - 9 * $T * $T * sin($uranus['M'])
                  + 9 * cos(2 * $uranus['M'] - 3 * $neptune['M'])
                  + 8 * $T * cos($saturn['M'] - 2 * $uranus['M'])
                  + 7 * $T * cos($saturn['M'] - 3 * $uranus['M'])
                  - 7 * $T * sin($saturn['M'] - 3 * $uranus['M'])
                  + 7 * $T * sin(2 * $uranus['M'])
                  + 6 * sin(2 * $jupiter['M'] - 6 * $saturn['M'] + 2 * $uranus['M'])
                  + 6 * cos(2 * $jupiter['M'] - 6 * $saturn['M'] + 2 * $uranus['M'])
                  + 5 * sin($saturn['M'] - 4 * $uranus['M'])
                  - 4 * sin(3 * $uranus['M'] - 4 * $neptune['M'])
                  + 4 * cos(3 * $uranus['M'] - 3 * $neptune['M'])
                  - 3 * cos($neptune['M'])
                  - 2 * sin($neptune['M']);
$uranus['b'] = 2775 * sin($uranus['u'])
             + 131 * sin($uranus['M'] - $uranus['u'])
             + 130 * sin($uranus['M'] + $uranus['u']);
$uranus['r'] = 19.21216 - 0.90154 * cos($uranus['M'])
                        - 0.02488 * $T * sin($uranus['M'])
                        - 0.02121 * cos(2 * $uranus['M'])
                        - 0.00585 * cos($saturn['M'] - 2 * $uranus['M'])
                        - 0.00508 * $T * cos($uranus['M'])
                        - 0.00451 * cos($jupiter['M'] - $uranus['M'])
                        + 0.00336 * sin($saturn['M'] - $uranus['M'])
                        + 0.00198 * sin($jupiter['M'] - $uranus['M'])
                        + 0.00118 * cos($saturn['M'] - 3 * $uranus['M'])
                        + 0.00107 * sin($saturn['M'] - 2 * $uranus['M'])
                        - 0.00103 * $T * sin(2 * $uranus['M'])
                        - 0.00081 * cos(3 * $uranus['M'] - 3 * $neptune['M']);

$neptune['l'] = $neptune['L'] + 3523 * sin($neptune['M'])
                   - 50 * sin(2 * $neptune['u'])
                   - 43 * $T * cos($neptune['M'])
                   + 29 * sin($jupiter['M'] - $neptune['M'])
                   + 19 * sin(2 * $neptune['M'])
                   - 18 * cos($jupiter['M'] - $neptune['M'])
                   + 13 * cos($saturn['M'] - $neptune['M'])
                   + 13 * sin($saturn['M'] - $neptune['M'])
                   - 9 * sin(2 * $uranus['M'] - 3 * $neptune['M'])
                   + 9 * cos(2 * $uranus['M'] - 2 * $neptune['M'])
                   - 5 * cos(2 * $uranus['M'] - 3 * $neptune['M'])
                   - 4 * $T * sin($neptune['M'])
                   + 4 * cos($uranus['M'] - 2 * $neptune['M'])
                   + 4 * $T * $T * sin($neptune['M']);
$neptune['b'] = 6404 * sin($neptune['u'])
              + 55 * sin($neptune['M'] + $neptune['u'])
              + 55 * sin($neptune['M'] - $neptune['u'])
              - 33 * $T * sin($neptune['u']);
$neptune['r'] = 30.07175 - 0.25701 * cos($neptune['M'])
                         - 0.00787 * cos(2 * $uranus['L'] - $uranus['M'] - 2 * $neptune['L'])
                         + 0.00409 * cos($jupiter['M'] - $neptune['M'])
                         - 0.00314 * $T * sin($neptune['M']);
                         + 0.0025 * sin($jupiter['M'] - $neptune['M'])
                         - 0.00194 * sin($saturn['M'] - $neptune['M'])
                         + 0.00185 * cos($saturn['M'] - $neptune['M']);

$sun['lambda'] = $sun['L'] + 6910 * sin($sun['M'])
                     + 72 * sin(2 * $sun['M'])
                     - 17 * $T * sin($sun['M'])
                     - 7 * cos($sun['M'] - $jupiter['M'])
                     + 6 * sin($moon['L'] - $sun['L'])
                     + 5 * sin(4 * $sun['M'] - 8 * $mars['M'] + 3 * $jupiter['M'])
                     - 5 * cos(2 * $sun['M'] - 2 * $sun['M'])
                     - 4 * sin($sun['M'] - $venus['M'])
                     + 4 * cos(4 * $sun['M'] - 8 * $mars['M'] + 3 * $jupiter['M'])
                     + 3 * sin(2 * $sun['M'] - 2 * $venus['M'])
                     - 3 * sin($jupiter['M'])
                     - 3 * sin(2 * $sun['M'] - 2 * $jupiter['M']);
$sun['R'] = 1.00014 - 0.01675 * cos($sun['M'])
                    - 0.00014 * cos(2 * $sun['M']);

$moon['lambda'] = $moon['L'] + 22640 * sin($moon['M'])
                      - 4586 * sin($moon['M'] - 2 * $moon['e'])
                      + 2370 * sin(2 * $moon['e'])
                      + 769 * sin(2 * $moon['M'])
                      - 668 * sin($sun['M'])
                      - 412 * sin(2 * $moon['u'])
                      - 212 * sin(2 * $moon['M'] - 2 * $moon['e'])
                      - 206 * sin($moon['M'] - 2 * $moon['e'] + $sun['M'])
                      + 192 * sin($moon['M'] + 2 * $moon['e'])
                      + 165 * sin(2 * $moon['e'] - $sun['M'])
                      + 148 * sin($moon['M'] - $sun['M'])
                      - 125 * sin($moon['e'])
                      - 110 * sin($moon['M'] + $sun['M'])
                      - 55 * sin(2 * $moon['u'] - 2 * $moon['e'])
                      - 45 * sin($moon['M'] + 2 * $moon['u'])
                      + 40 * sin($moon['M'] - 2 * $moon['u'])
                      - 38 * sin($moon['M'] - 4 * $moon['e'])
                      + 36 * sin(3 * $moon['M'])
                      - 31 * sin(2 * $moon['M'] - 4 * $moon['e'])
                      + 28 * sin($moon['M'] - 2 * $moon['e'] - $sun['M'])
                      - 24 * sin(2 * $moon['e'] + $sun['M'])
                      + 19 * sin($moon['M'] - $moon['e'])
                      + 18 * sin($moon['e'] + $sun['M'])
                      + 15 * sin($moon['M'] + 2 * $moon['e'] - $sun['M'])
                      + 14 * sin(2 * $moon['M'] + 2 * $moon['e'])
                      + 14 * sin(4 * $moon['e'])
                      - 13 * sin(3 * $moon['M'] - 2 * $moon['e'])
                      - 11 * sin($moon['M'] + 16 * $sun['L'] - 18 * $venus['L'])
                      + 10 * sin(2 * $moon['M'] - $sun['M'])
                      + 9 * sin($moon['M'] - 2 * $moon['u'] - 2 * $moon['e'])
                      + 9 * cos($moon['M'] + 16 * $sun['L'] - 18 * $venus['L'])
                      - 9 * sin(2 * $moon['M'] - 2 * $moon['e'] + $sun['M'])
                      - 8 * ($moon['M'] - $moon['e'])
                      + 8 * sin(2 * $moon['e'] - 2 * $sun['M'])
                      - 8 * sin(2 * $sun['M'] + $sun['M'])
                      - 7 * sin(2 * $sun['M'])
                      - 7 * sin($moon['M'] - 2 * $moon['e'] + 2 * $sun['M'])
                      + 7 * sin($moon['o'])
                      - 6 * sin($moon['M'] - 2 * $moon['u'] + 2 * $moon['e'])
                      - 6 * sin(2 * $moon['u'] + 2 * $moon['e'])
                      - 4 * sin($moon['M'] - 4 * $moon['e'] + $sun['M'])
                      + 4 * $T * cos($moon['M'] + 16 * $sun['L'] - 18 * $venus['L'])
                      - 4 * sin(2 * $moon['M'] - 2 * $moon['u'])
                      + 4 * $T * sin($moon['M'] + 16 * $sun['L'] - 18 * $venus['L'])
                      + 3 * sin($moon['M'] - 3 * $moon['e'])
                      - 3 * sin($moon['M'] + 2 * $moon['e'] + $sun['M']);
                      - 3 * sin(2 * $moon['M'] - 4 * $moon['e'] + $sun['M'])
                      + 3 * ($moon['M'] - 2 * $sun['M'])
                      + 3 * sin($moon['M'] - 2 * $moon['e'] - 2 * $sun['M'])
                      - 2 * sin(2 * $moon['M'] - 2 * $moon['e'] - $sun['M'])
                      - 2 * sin(2 * $moon['u'] - 2 * $moon['e'] + $sun['M'])
                      + 2 * sin($moon['M'] + 4 * $moon['e'])
                      + 2 * sin(4 * $moon['M'])
                      + 2 * sin(4 * $moon['e'] - $sun['M'])
                      + 2 * sin(2 * $moon['M'] - $moon['e']);
$moon['beta'] = 18461 * sin($moon['u'])
              + 1010 * sin($moon['M'] + $moon['u'])
              + 1000 * sin($moon['M'] - $moon['u'])
              - 624 * sin($moon['u'] - 2 * $moon['e'])
              - 199 * sin($moon['M'] - $moon['u'] - 2 * $moon['e'])
              - 167 * sin($moon['M'] + $moon['u'] - 2 * $moon['e'])
              + 117 * sin($moon['u'] - 2 * $moon['e'])
              + 62 * sin(2 * $moon['M'] + $moon['u'])
              + 33 * sin($moon['M'] - $moon['u'] + 2 * $moon['e'])
              + 32 * sin(2 * $moon['M'] - $moon['u'])
              - 30 * sin($moon['u'] - 2 * $moon['e'] + $sun['M'])
              - 16 * sin(2 * $moon['M'] + $moon['u'] - 2 * $moon['e'])
              + 15 * sin($moon['M'] + $moon['u'] + 2 * $moon['e'])
              + 12 * sin($moon['u'] - 2 * $moon['e'] - $sun['M'])
              - 9 * sin($moon['M'] - $moon['u'] - 2 * $moon['e'] + $sun['M'])
              - 8 * sin($moon['u'] + $moon['o'])
              + 8 * sin($moon['u'] + 2 * $moon['e'] - $sun['M'])
              - 7 * sin($moon['M'] + $moon['u'] - 2 * $moon['e'] + $sun['M'])
              + 7 * sin($moon['M'] + $moon['u'] - $sun['M'])
              - 7 * sin($moon['M'] + $moon['u'] - 4 * $moon['e'])
              - 6 * sin($moon['u'] + $sun['M'])
              - 6 * sin(3 * $moon['u'])
              + 6 * sin($moon['M'] - $moon['u'] - $sun['M'])
              - 5 * sin($moon['u'] + $moon['e'])
              - 5 * sin($moon['M'] + $moon['u'] + $sun['M'])
              - 5 * sin($moon['M'] - $moon['u'] + $sun['M'])
              + 5 * sin($moon['u'] + $sun['M'])
              + 5 * sin($moon['u'] - $moon['e'])
              + 4 * sin(3 * $moon['M'] + $moon['u'])
              - 4 * sin($moon['u'] - 4 * $moon['e'])
              - 3 * sin($moon['M'] - $moon['u'] - 4 * $moon['e'])
              + 3 * sin($moon['M'] - 3 * $moon['u'])
              - 2 * sin(2 * $moon['M'] - $moon['u'] - 4 * $moon['e'])
              - 2 * sin(3 * $moon['u'] - 2 * $moon['e'])
              + 2 * sin(2 * $moon['M'] - $moon['u'] + 2 * $moon['e'])
              + 2 * sin($moon['M'] - $moon['u'] + 2 * $moon['e'] - $sun['M'])
              + 2 * sin(2 * $moon['M'] - $moon['u'] - 2 * $moon['e'])
              + 2 * sin(3 * $moon['M'] - $moon['u']);
$moon['delta'] = 60.36298 - 3.27746 * cos($moon['M'])
                          + 0.00398 * cos($moon['M'] - 2 * $moon['e'] - $sun['M'])
                          - 0.57994 * cos($moon['M'] - 2 * $moon['e'])
                          - 0.00366 * cos(3 * $moon['M'])
                          - 0.46357 * cos(2 * $moon['e'])
                          - 0.00295 * cos(2 * $moon['M'] - 4 * $moon['e'])
                          - 0.08904 * cos(2 * $moon['M'])
                          - 0.00263 * cos($moon['e'] + $sun['M'])
                          + 0.03865 * cos(2 * $moon['M'] - 2 * $moon['e'])
                          + 0.00249 * cos(3 * $moon['M'] - 2 * $moon['e'])
                          - 0.03237 * cos(2 * $moon['e'] - $sun['M'])
                          - 0.00221 * cos($moon['M'] + 2 * $moon['e'] - $sun['M'])
                          - 0.02688 * cos($moon['M'] + 2 * $moon['e'])
                          + 0.00185 * cos(2 * $moon['u'] - 2 * $moon['e'])
                          - 0.02358 * cos($moon['M'] - 2 * $moon['e'] + $sun['M'])
                          - 0.0203 * cos($moon['M'] - $sun['M'])
                          - 0.00161 * cos(2 * $moon['e'] - 2 * $sun['M'])
                          + 0.01719 * cos($moon['e'])
                          + 0.00147 * cos($moon['M'] + 2 * $moon['u'] - 2 * $moon['e'])
                          + 0.01671 * cos($moon['M'] + $sun['M'])
                          - 0.00142 * cos(4 * $moon['e'])
                          + 0.01247 * cos($moon['M'] - 2 * $moon['u'])
                          + 0.00139 * cos(2 * $moon['M'] - 2 * $moon['e'] + $sun['M'])
                          + 0.00704 * cos($sun['M'])
                          - 0.00118 * cos($moon['M'] - 4 * $moon['e'] + $sun['M'])
                          + 0.00529 * cos(2 * $moon['e'] + $sun['M'])
                          - 0.00116 * cos(2 * $moon['M'] - 2 * $moon['e'])
                          - 0.00524 * cos($moon['M'] - 4 * $moon['e'])
                          - 0.0011 * cos(2 * $moon['M'] - $sun['M']);

//$venus['l'] = normalizeAngle($venus['l']);
//$venus['b'] = normalizeAngle($venus['b']);
//$venus['r'] = normalizeAngle($venus['r']);

print_r($venus); echo '<br />';
//print_r($mars); echo '<br />';
//print_r($jupiter); echo '<br />';
//echo $venus['L'].'>'.$venus['b'].'>'.$venus['r'].'><br />';
//echo $mars['L'].'>'.($mars['b'] / 3600).'>'.$mars['r'].'><br />';
//echo ($jupiter['L'] * 57.2957795131).'>'.($jupiter['b'] / 3600).'>'.$jupiter['r'].'><br />';
?>