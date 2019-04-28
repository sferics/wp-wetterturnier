<?php

#// Output as text/plain
require_once("../../../wp-config.php");
#require_once("rankingclass.php");

global $WTuser;

$date = 17536;
$cityObj = new wetterturnier_cityObject( 4 );
//$cityObj->show();


$city_array = array($cityObj);
$tdate = array( 11977, 15543 );
$tdate = 17550;
$rankingObj = new wetterturnier_rankingObject(); # $cityID, 17543 );
$rankingObj->set_cities( $cityObj );
$rankingObj->set_tdate( $tdate );
$rankingObj->prepare_ranking();
print $rankingObj->return_json();


?>
