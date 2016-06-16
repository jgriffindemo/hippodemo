/*
*
*
*
*
*  QuickWeather.js
*
*  This is a Javascript library for the QuickWeather app that 
*  handles retrieving the weather from the Yahoo! Weather API 
*  (via the SimpleWeather wrapper), as well as DOM manipulation 
*  to a fluid UI.
*
*
*
*
*/

/*



  Both generateSearchBox() and outputWeather() 
  (along with the helper functions) allow us to toggle between 
  searching a location, and viewing the weather for that location, 
  in a dynamic fashion.



*/

function generateSearchBox()
 {
  /* begin HTML block */
  html  = "<div id='search_form_container'>";
  html += "<h3>Search for your city:</h3>";
  html += "<form id='search_form'>";
  html += "<input type='text' name='location_search' id='location_search' class='ui_input' placeholder='City, Region, Lat/Long'>";
  html += "<input type='submit' id='get_weather_button' value='Get Weather' class='ui_button'>";
  html +=  "</form></div>";
  /* end HTML block */

  $("#weather_box").html(html);

  /* Set the weather API trigger on the search box. */
    $('#search_form').submit(function(e){
      e.preventDefault();
      var form_data = $('#search_form').serializeArray().reduce(function(obj, item){
        obj[item.name] = item.value;
        return obj;
      },{});;
      getWeather(form_data['location_search']);
    });

}

function outputWeather(weather)
{
  /* begin HTML block */
  html  =  "<h2><span id='icon_box' class='icon-"+weather.code+"''></span> "+formatTemp(weather.temp, weather.units.temp)+"</h2>";
  html += "<div id='result_location'>"+formatLocation(weather.city, weather.region, weather.country)+"</div>";
  html += "<div id='result_weather_desc'>"+weather.currently+"</div>";
  html += "<div id='result_weather_info'>Wind: "+formatWind(weather.wind.direction, weather.wind.speed, weather.units.speed)+"</div>";
  html += "<div id='result_wind_chill'>Wind Chill: <b>"+ formatTemp(weather.wind.chill, weather.units.temp)+"</b></div>";
  html += "<div id='buttons'><input type='button' class='ui_button' id='return_button' value='Return to Search'>";
  html += "<form name='save' action='PHPActions/ToggleLocationSave.php' method='POST'>";
  html += "<input type='hidden' name='city' value='"+weather.city+"'>";
  html += "<input type='hidden' name='region' value='"+weather.region+"'>";
  html += "<input type='hidden' name='country' value='"+weather.country+"'>";
  html += "<input type='submit' name='submit' value='Save Location' class='ui_save_button_default' id='save_button'></form></div>";
  /* end HTML block */
  $("#weather_box").html(html);

  /* 
    Determine if the location exists in the user's 
    saved locations, and set the appearance of the 'Save Location' 
    button accordingly.
  */
  setDefaultSaveButtonState(weather.city, weather.region, weather.country);

  /* Set up ability to toggle back to the search form. */
  $('#return_button').click(function(){
    generateSearchBox();
  });


  /* Force the 'Save Location' button to POST asynchronously. */
  $('form[name=save]').submit(function(event){
    event.preventDefault(); /* stop default form action */
    $.ajax({
      type: 'POST',
      dataType: 'json',
      cache: false,
      url: 'PHPActions/ToggleLocationSave.php',
      data: $(this).serialize(),
      success: function(response){
        var action;
        /* 
           Set up the toggle on the 'Save Location' button that changes based
           on whether the location is stored or not.
        */
        $.each(response, function(index, value){
          if(index === 'action') {
            action = value;
          }
          else {
            alert ("Invalid or missing JSON response from server.");
          }
        });

        if(action === 'save') {
          $('#save_button').attr('class', 'ui_save_button_saved');
        }
        else {
          $('#save_button').attr('class', 'ui_save_button_default');
        }

        /* Now update the list of locations. */
        pullSavedLocations();
      }
    });
  });

  /* 
    Update all the sidebar locations each 
    time we pull in a new location. 
  */
  pullSavedLocations();
}


function setDefaultSaveButtonState(city, region, country)
{
 $.ajax({
  type: 'POST',
  dataType: 'json',
  cache: false,
  url: 'PHPActions/CheckLocation.php',
  data: {'city': city, 'region': region, 'country': country},
  success: function(response){
    var saved;
    $.each(response, function(index, value){
      if(index === 'response') {
        saved = value;
      }
      else {
        alert ("Invalid or missing JSON response from server.");
      }
    });
    if(saved === true) {
      $('#save_button').attr('class', 'ui_save_button_saved');
    }
  }
 });
}



/*


  pullSavedLocations() and formatSavedLocations() 
  are used to pull and display the user's saved 
  locations on the fly.


*/

function pullSavedLocations()
{
  $('#locations_menu').html('');
  $.getJSON("PHPActions/PullSavedLocations.php",
    function(data) {
      $.each(data, function(index, string){
        $('#locations_menu').append(formatSavedLocation(string, index));
        $('#locations_menu #'+index).click(function(){getWeather(string)});
        /* Set up the delete button. */
        $('#locations_menu #del_button_'+index).click(function(){
          $.ajax({
            type: 'POST',
            dataType: 'text',
            cache: false,
            url: 'PHPActions/DeleteLocation.php',
            data: {'location_string': string},
            success: function(response){
              pullSavedLocations();
              $('#save_button').attr('class', 'ui_save_button_default');
            }
          });
        });
  })});
}

function formatSavedLocation(location_string, id)
{
  var output  = "<li><a href='#' class='delete_icon' id='del_button_"+id+"'></a>";
  output += "<a href='#' id='"+id+"'>"+location_string+"</a>";
  output += "<span id='weather_thumb_"+id+"'>"+getWeatherThumbnail(location_string, id)+"</span></li>";
  return output;
}