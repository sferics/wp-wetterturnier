

// Initialize demo table
jQuery(document).on('ready',function() {

   // Copy jQuery to $
   $ = jQuery


   $.fn.show_ranking = function(ajaxurl, input) {

      // Element where we have to store the data to
      var elem = $(this)

      // Setting defaults
      var defaults = {hidebuttons: false}
      $.each(defaults, function(key, val) {
          if ( ! input.hasOwnProperty(key) ) { input[key] = val; }
      });

      // Check if file exists. If it exists, just display.
      // If not existing, start Rscript to create the image.
      // Ajaxing the calculation miniscript
      input["action"] = "ranking_ajax"
      $.ajax({
         url: ajaxurl, dataType: 'json', type: 'post', data: input,
         success: function( results, hxr, settings ) {

             if ( results.error != undefined ) {
                $(elem).html("<div class=\"wetterturnier-info error\">" +
                             results.error + "</div>");
             } else {
                $(elem).html( "<div class=\"wetterturnier-info\">Data loaded, waiting for js to display it.</div>" );
                data = results;
             }
             display_ranking($(elem), results, input);
         },
         error: function( hxr, ajaxOptions, thrownError ) {
            //$error = e; console.log('errorlog'); console.log(e);
            $(this).html("Problems loading ranking data.<br><br>\n" +
                hxr.responseText + "\n" + thrownError );
            data = false
         }

      });

      function statusbar( rel, width = 200 ) {

          var relwidth = parseInt(width * rel);
          var percent = parseInt(rel*1000)/10.;
          if ( rel > 0.5 ) {
             var html = "<span class=\"ranking-statusbar\" style=\"width: 100%;\">"
                      + "  <span style=\"width: " + rel * 100 + "%;\">" + percent + "%&nbsp;</span>"
                      + "</span>";
          } else {
             var html = "<span class=\"ranking-statusbar\" style=\"width: 100%;\">"
                      + "  <span style=\"width: " + rel * 100 + "%;\"></span>&nbsp;" + percent + "%"
                      + "</span>";
          }
          return html;
      }

      // This one was my original, currently unused.
      function colorize_trend_11cols( trend ) {
          // Creates a color id btw, 0 and 10 to address color.
          // Trend divided by 3, so every three the color changes.
          colidx = Math.min( Math.max( -5, Math.round(trend/3) ), 5 ) + 5
          var cols = ["#6695EB","#81A1E7","#98AEE3","#ADBBDF","#C1C7DA",
                      "#D4D4D4","#D7C4B9","#D7B49C","#D6A47E","#D3955B","#CF862B"];
          var sign = ( trend > 0 ) ? "+" : "";
          return "<span style=\"color: " + cols[10-colidx] + ";\">" + sign + trend + "</span>";
      }

      // Same as above, but with less colors.
      function colorize_trend( trend ) {
          // Creates a color id btw, 0 and 4 to address color.
          // Trend divided by 3, so every three the color changes.
          colidx = Math.min( Math.max( -1, Math.round(trend/10) ), 1 ) + 1
          if ( trend == 0 ) { var col = "#8D8D8D" } else if ( trend < 0 ) { var col = "#ff6616"; } else { col = "#668fcc"; }
          var sign = ( trend > 0 ) ? "+" : "";
          return "<span style=\"color: " + col + ";\">" + sign + trend + "</span>";
      }

      function display_ranking( e, data, input ) {

          // Clear content of the div
          $(e).empty();

          // Append new table
          $(e).append("<table class=\"wttable-show-ranking wttable-show small ranking-weekend default\"></table>")
          $(e).find("table").append("<thead><tr></tr></thead><tbody></tbody>")

          var head = $(e).find("table thead tr")
          var body = $(e).find("table tbody")
          $( head ).append("<th class=\"rank\">"+data.dict.rank+"</th>");
          if ( data.meta.has_trends ) {
              $( head ).append("<th class=\"trend\">"+data.dict.trend+"</th>");
          }
          // Only show number of played games if begin/end date differ
          if ( data.meta.ntournaments > 1 ) { $( head ).append("<th class=\"played\">"+data.dict.played+"</th>"); }
          $( head ).append("<th class=\"user\">"+data.dict.user+"</th>")
                   .append("<th class=\"points difference\">"+data.dict.difference+"</th>")
                   .append("<th class=\"points\">"+data.dict.points+"</th>")
                   .append("<th class=\"statusbar\"></th>");

          counter = 0;
          if ( typeof(input.limit) == undefined | typeof(input.limit) === "boolean" ) {
              input.limit = data.data.length;
          }
          $.each( data.data, function(idx,rec) {

             // If input.type === "seasoncities": colorize the guys who have
             // not played all games.
             if ( input.type === "seasoncities" && rec.played_now < data.meta.ntournaments )
             { tdclass = " partial-participation"; } else { tdclass = ""; }

             // Append new table row and select the new html element (variable tr)
             $( body ).append("<tr class=\"" + rec.userclass + tdclass + "\"></tr>")
             var tr = $( body ).find("tr").last()

             // Appending data ...
             $(tr).append("<td class=\"rank "+rec.userclass+"\">"+rec.rank_now+"</td>");
             if ( data.meta.has_trends ) {
                 $( tr ).append("<td class=\"trend\">"+colorize_trend(rec.trend)+"</td>");
             }
             // Only show number of played games if begin/end date differ
             if ( data.meta.ntournaments > 1 ) { $(tr).append("<td class=\"played\">"+rec.played_now+"/"+data.meta.ntournaments+"</td>"); }
             $(tr).append("<td>" +
                          (( rec.detail_button != undefined && data.meta.ntournaments === 1 ) ? rec.detail_button : "") + 
                          (( rec.edit_button != undefined ) ? rec.edit_button : "") +
                          rec.profile_link + "</td>")
                       .append("<td class=\"points difference\">"+rec.points_diff+"</td>")
                       .append("<td class=\"points\">"+rec.points_now+"</td>")
                       .append("<td class=\"statusbar\">"+statusbar(rec.points_relative)+"</td>");

              // Increase loop counter
              counter++;
              if ( counter >= input.limit ) { return false; }

          });

          // Short information
          $(e).append(data.dict.points_max + " <b>" + data.meta.points_max + "</b>.");



      };

    };

});
