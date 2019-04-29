<?php
// -------------------------------------------------------------------
// - NAME:        autosubmit.php
// - AUTHOR:      Reto Stauffer
// - DATE:        2015-07-29
// -------------------------------------------------------------------
// - DESCRIPTION:
// - NOTE:        Needs the following forward rule to keep care of
//                the old auto-submit url (fehler/form.php) used
//                on the old prognose server.
//
//                # - This is the redirect for the users who
//                #   submitted theyr bets via script. The old
//                #   script name was "fehler/form.php".
//                #   However, no idea why this shit was called
//                #   'fehler', but that was it's name.
//                RewriteCond %{REQUEST_URI} ^/fehler/form.php [NC]
//                RewriteRule ^fehler/form.php(.*)$ /wp-content/plugins/wp-wetterturnier/user/autosubmit/autosubmit.php$1 [R=301,NE,P]
//
//                RewriteCond %{REQUEST_URI} ^/autosubmit/ [NC]
//                RewriteRule ^autosubmit/(.*)$ /wp-content/plugins/wp-wetterturnier/user/autosubmit/autosubmit.php$1 [R=301,NE,P]
//
// - ERRORS:      Returns (via function stop) an error log message
//                which will be interpreted by the autosubmit.py
//                script with which you can submit your bets.
//                error 11:     login not successful
//                error 12:     city cannot be found in database
//                error 13:     tournament is closed 
//                error 14:     at least one required input missing
//                              (e.g, user, password, ...)
// -------------------------------------------------------------------
// - EDITORIAL:   2015-07-29, RS: Created file on thinkreto.
// -------------------------------------------------------------------
// - L@ST MODIFIED: 2015-07-29 08:56 on prognose2.met.fu-berlin.de
// -------------------------------------------------------------------


// Development errors
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Including wp-config to have access to the WT plugin functions
// and wordpress database. 
require_once('../../../../../wp-config.php');
// require_once('../../generalclass.php');
function convert_tdate( $tdate, $fmt = "%Y-%m-%d" ) {
    return( date( $fmt, (int)$tdate*86400 ) );
    }

// Load betclass file if not yet loaded, initialize  WTbetclass
if ( ! defined("loaded_betclass") ) {
    require_once( sprintf("../../betclass.php") );
    define( "loaded_betclass", 1 );
}

$WTbetclass = new wetterturnier_betclass();
global $WTuser;

print("\n");
print("        ^        THIS IS JUST A REMINDER:\n");
print("       / \       If something does not work please double-check\n");
print("      / | \      that you are using https://www.wetterturnier.de\n");
print("     /  |  \     (not http://...) and, if you are sending data via\n");
print("    /   .   \    wget please do not forget the --no-check-certificate\n");
print("   -----------   option.\n\n\n");

// ------------------------------------------------------------------
// Parsing input data
// ------------------------------------------------------------------
$data = $WTbetclass->parse_parameters( $_REQUEST );

// First, set $admin to NULL and $is_admin to FALSE. If an admin submits a bet (eg. a MOS becaus of belated submission) this will be changed later on and passed into write_to_database(...,$adminuser=$admin); in the very end of this script
$admin = NULL;
$is_admin = FALSE;
// If we are no one, the placedby value does not get changed. For a belated MOS we gonna assign its "userID" later on.
$whoami = NULL;

// ------------------------------------------------------------------
// Checks if the user has delivered ALL necessary parameters for this
// town (depends on $cityObj->paramconfig) and all forecast days
// (depends on option 'wetterturnier_betdays'. 
// ------------------------------------------------------------------
list($data,$checkflag) = $WTbetclass->check_received_data( $data );

// The function should never return $checkflag=false for the autosubmit
// procedure (will exit internally). Anyway, if this happens: stop.
if ( ! $checkflag ) {
   print "OHOH checkflag false, but not stopped. Should never run into here. ";
   $WTbetclass->error(99);
}

// ------------------------------------------------------------------
// Parsing input data
// ------------------------------------------------------------------
$maxdays = $WTuser->options->wetterturnier_betdays;
$data = $WTbetclass->check_correct_values( $data, $maxdays );


// ------------------------------------------------------------------
// Shows parsed data and ignore messages
// ------------------------------------------------------------------
$WTbetclass->show_parsed_data( $data, $maxdays );

// ------------------------------------------------------------------
// Login and check if login was ok
// ------------------------------------------------------------------
print "\n"; $msg = "Try to login to wetterturnier now\n";
printf("%s\n",strtoupper($msg));
printf("%s\n",str_repeat("=",strlen($msg)));

$creds = array();
$creds['user_login']    = $data->user;
$creds['user_password'] = $data->password;
$creds['remember']      = false;
$user = wp_signon( $creds, false );
printf( "Hello, %s!\n", $user->data->user_login );

// Check if current user can place bets as admin, if tournament is actually closed already.
$is_admin = $user->allcaps["wetterturnier_admin"];

if ( $is_admin ) {
	print "Admin mode enabled\n";
	$admin = $user;
}

// ------------------------------------------------------------------
// If there were errors
// ------------------------------------------------------------------
if ( property_exists( $user, 'errors' ) ) { 
   foreach ( $user->errors as $key => $val ) {
      print "\n"; $msg = "Error message: ".$key;
      printf("%s\n",strtoupper($msg));
      printf("%s\n",str_repeat("-",strlen($msg)));
      // Content
      foreach( $val as $rec ) {
         printf("%s\n",strip_tags($rec));
      }
   }
   $WTbetclass->error(11);
}

printf("Login for user %s with password %s was successful\n\n",
       $user->data->user_login,str_repeat("*",strlen($data->password)));


// ------------------------------------------------------------------
// Loading next tournament date and check if the user is allowed
// to place its bets or not.
// ------------------------------------------------------------------
print "\n"; $msg = "Checking next tournament and check if open or closed";
printf("%s\n",strtoupper($msg));
printf("%s\n",str_repeat("=",strlen($msg)));

// If a tdate exists in $data use this torunament date instead of next_tournament(0,true)

// TODO: check (via database or otherwise) whether tdate was REALLY a tournament date...
// Plus maybe check whether user is automaton/MOS to only allow MOS to do such dirty little things...


if ( $is_admin && property_exists ( $data, 'tdate' ) ) {
	printf("You're pretty late dear my friend ;) Choosing %d as old tournament date\n\n", $data->tdate);

	// The handy function next_tournament() allows us to trick the 	system by inserting the custom tournament date for our belated submission :D


	$next = $WTuser->next_tournament($row_offset=0,$check_access=false,$dayoffset=$data->tdate);
        $whoami = $admin->ID;
        printf("You're ID is: %d", $whoami);

} else {
	$next = $WTuser->next_tournament(0,true);
	if ( $next->closed ) {
   		printf("WARNING: tournament closed. Cannot store your bet at the moment.");
   		$WTbetclass->error(13);
	} else {
   		printf("Note: tournament %s is open to take your bet ...\n", convert_tdate($data->tdate) );
	}
    }

// ------------------------------------------------------------------
// Write data to database 
// ------------------------------------------------------------------
print "\n"; $msg = "Write data to database";
printf("%s\n",strtoupper($msg));
printf("%s\n",str_repeat("=",strlen($msg)));


print_r($admin);
print("\n");
print("\n");
#print_r($next);
print("\n");
print("\n");
print_r($data);

$WTbetclass->write_to_database( $user, $next, $data, $checkflag, $verbose=true, $adminuser=$admin, $placedby=$whoami);


?>
