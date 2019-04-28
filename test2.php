<?php


require_once("../../../wp-config.php");


global $wpdb;
global $WTuser;

$cityObj = 

$cityObj = new wetterturnier_cityObject( 1 ) ;
$cityObj->show();

$tdate = array(17000,18000);
$tdate = array(10977,17522);


$a = $WTuser->get_ranking_data($cityObj,$tdate,$limit=false);
print_r($a);


////$sql = <<<SQL
////SELECT SQL_NO_CACHE usr.user_login AS user_login, usr.display_name AS display_name,
////x.cityID AS cityID, x.userID AS userID, SUM(x.played) AS played,
////SUM(x.points_d1) AS points_d1,
////SUM(x.points_d2) AS points_d2,
////SUM(x.points) AS points FROM (
////   SELECT dt.cityID, dt.userID, dt.tdate, CASE WHEN data.points IS NULL THEN 0 ELSE 1 END AS played,
////          COALESCE(data.points_d1, dead.points_d1) AS points_d1,
////          COALESCE(data.points_d2, dead.points_d2) AS points_d2,
////          COALESCE(data.points, dead.points) AS points FROM (
////              SELECT dateUsr.cityID, dateUsr.userID, dateDate.tdate FROM (
////                   SELECT cityID, userID FROM wp_wetterturnier_betstat
////                   WHERE tdate >= 17067 AND tdate <= 17531 AND (cityID=1) GROUP BY cityID, userID
////              ) AS dateUsr
////              CROSS JOIN (
////                   SELECT tdate FROM wp_wetterturnier_betstat WHERE tdate >= 17067 AND
////                   tdate <= 17531 AND (cityID=1) AND userID=1130 GROUP BY tdate
////              ) AS dateDate
////          ) AS dt
////          LEFT JOIN (
////              SELECT cityID, tdate, points_d1, points_d2, points FROM wp_wetterturnier_betstat
////              WHERE userID=1130 AND (cityID=1) AND tdate>=17067 AND tdate <=17531
////          ) AS dead ON dt.tdate=dead.tdate AND dt.cityID=dead.cityID
////          LEFT OUTER JOIN wp_wetterturnier_betstat AS data ON dt.cityID=data.cityID
////          AND dt.userID=data.userID AND dt.tdate=data.tdate
////) AS x LEFT OUTER JOIN wp_users AS usr ON usr.ID = x.userID
////GROUP BY x.userID ORDER BY points DESC, points_d1 DESC, points_d2 DESC;
////
////select QUERY_ID,SEQ,STATE,DURATION,CPU_USER,CPU_SYSTEM,CONTEXT_VOLUNTARY,SOURCE_LINE from information_schema.profiling ORDER BY QUERY_ID DESC LIMIT 10;
////SQL;
////
////print $sql;
////
////$wpdb->get_results($sql);
?>

