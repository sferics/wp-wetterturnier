<?php

// Output as text/plain
require_once("../../../wp-config.php");

global $WTuser;

$date = 17536;
$cityObj = new wetterturnier_cityObject( 4 );
$cityObj->show();


$city_array = array($cityObj);
#$tdate = array( 10977, 17555 );
$tdate = array( 11977, 15543 );
$tdate = 17543;
$ranking = $WTuser->get_ranking_data($city_array,$tdate,NULL);

print_r($ranking);

?>
