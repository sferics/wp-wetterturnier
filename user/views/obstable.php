<?php
// ------------------------------------------------------------------
/// @file user/views/obstable.php
/// @author Reto Stauffer
/// @date 16 June 2017
/// @brief Frontent page to display the latest observations in table
///   form.
/// @details Based on the station definition or the wetterturnier
///   this page displays the latest observations in a table format.
///   This view was mainly used during development to see whether
///   we got the required observations or whether there is someting
///   wrong with the backend and/or observations are missing.
///   The file contains some css/jQuer functions as well.
// ------------------------------------------------------------------

global $WTuser;

// Access only for logged in users
if ( $WTuser->access_denied() ) { return; }

/// Loading active city, see @ref wetterturnier_generalclass::get_current_cityObj
$cityObj = $WTuser->get_current_cityObj();

// Including the needed jquery script
$WTuser->include_js_script("wetterturnier.obstable");

// Get custom table styling
$wttable_style = get_user_option("wt_wttable_style");
$wttable_style = (is_bool($wttable_style) ? "" : $wttable_style);
?>

<script>
jQuery(document).on('ready',function() {
   (function($){

      // Function to refresh the data table
      function loadDataTable( title, statnr, days ) {
         ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
         var statnr = $("input.active.obs-table-station").attr("statnr")
         var title  = $("input.active.obs-table-station").val()
         var days   = $("input.active.obs-table-days").attr("days")
console.log( statnr+'  '+title+'  '+days )
         var style = "<?php print $wttable_style; ?>"
         $('#obs-table').show_obstable({ajaxurl:ajaxurl,style:style,title:title,statnr:statnr,days:days});
      }

      // Initialize the data
      $("input[type='button'].obs-table-station").first().addClass("active")
      $("input[type='button'].obs-table-days").first().addClass("active")
      loadDataTable( )

      // Adding func. to select station/days
      $(document).on("click","input[type='button'].obs-table-station",function() {
         $("input[type='button'].obs-table-station").removeClass("active")
         $(this).addClass("active")
         loadDataTable( ) 
      })
      $(document).on("click","input[type='button'].obs-table-days",function() {
         $("input[type='button'].obs-table-days").removeClass("active")
         $(this).addClass("active")
         loadDataTable( ) 
      })

      // Makes lines highlightable
      $(document).on("click","table.wetterturnier-obstable tr",function($) {
         $ = jQuery
         var trclass = $(this).attr('row')
         var classname = "highlighted";
         if ( $(this).hasClass( classname ) ) {
            $("table.wetterturnier-obstable tr[row='"+trclass+"']").removeClass( classname )
         } else {
            $("table.wetterturnier-obstable tr[row='"+trclass+"']").addClass( classname )
         }
      });

   })(jQuery);
});
</script>

<style>
table.wetterturnier-obstable            { width: auto; }
table.wetterturnier-obstable tr td.null { color: #B2B2B2; }
table.wetterturnier-obstable tr td      { white-space: nowrap; }
input[type='button'].obs-table-station  { margin-right: 10px; }
input[type='button'].obs-table-days     { margin-right: 10px; }
input[type='button'].active             { background-color: #41a62a;     }
div#obs-table                           { margin-top: 20px; }
table.wetterturnier-obstable tr td          { background-color: transparent !important; }
table.wetterturnier-obstable tr:nth-child(odd) { background-color: #eef0f2; } 
table.wetterturnier-obstable tr.highlighted { background-color: #ffe4a8; }
table.wetterturnier-obstable tr.highlighted:nth-child(odd) { background-color: #ffd270; }
.wetterturnier-obstable th, .wetterturnier-obstable td { max-width: 100px; }
#wetterturnier-obstable-nav {
    margin-bottom: 1em; 
}
#wetterturnier-obstable-nav > ul {
    list-style: none;
    position: relative;
}
#wetterturnier-obstable-nav > ul > li {
    float: left; min-width: 100px;
}
#wetterturnier-obstable-nav > .preset > h3 {
   font-size: 1em; float: left;
   padding-right: 1em; line-height: 1.5em;
}
#wetterturnier-obstable-nav > .preset > ul {
   list-style: none;
   position: relative;
}
#wetterturnier-obstable-nav > .preset > ul li {
   float: left; padding: 0 1em 0 0;
   cursor: pointer;
}
#wetterturnier-obstable-nav > .preset > ul li:hover {
    color: #ff6600;
}
</style>

<?php
foreach( $cityObj->stations() as $stnObj ) {
   printf("<input type=\"button\" class=\"obs-table-station\" statnr=\"%d\" value=\"[%d] %s\"></input>",
           $stnObj->get('wmo'),$stnObj->get('wmo'),$stnObj->get('name'));
}
for ( $i=2; $i<=9; $i++ ) {
   printf("<input type=\"button\" class=\"obs-table-days\" days=\"%d\" value=\"%d d\"></input>",
            $i,$i);
}
?>
<div id='obs-table'></div>

