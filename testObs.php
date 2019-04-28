<?php

require_once("../../../wp-config.php");

global $wpdb;
global $WTuser;

////$WTuser->getobservations_ajax( 11120 );

$stnObj = new wetterturnier_stationObject( 11120, "wmo" );
// Create new object
$from = 1516024800;
$obj = new wetterturnier_latestobsObject( $stnObj, $from );
// Show archive table
///$obj->show();
print_r($obj->get_json( false ));

?>

