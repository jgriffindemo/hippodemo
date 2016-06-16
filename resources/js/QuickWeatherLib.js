function formatTemp(temp, units) {
  return temp+'&deg'+units;
}

function formatLocation(city, region, country)
{
  var components = [];

  if(city.length !== 0) {
    components.push(city);
  }
  if(region.length !== 0) {
    components.push(region);
  }
  if(country.length !== 0){
    components.push(country);
  }

  return components.join(', ');
}

function formatWind(direction, speed, units)
{
  return speed+' '+units+' '+direction;
}

function apiFailure(error) {
  $("weather_box").html('<h3>'+error+'</h3>')
}

/*


  Uses the SimpleWeather library to pull the 
  weather for an arbitrary location.


*/ 
function getWeather(location_string)
{
  $.simpleWeather({
    location: location_string,
    woeid: '',
    unit: 'f',
    success: function(weather){outputWeather(weather)},
    error: function(error) {apiFailure(error)}
  });
}

/*


  Same as above, but used to generate just the temperature 
  and condition icon for a preview.


*/
function getWeatherThumbnail(location_string, thumb_id)
{
  $.simpleWeather({
    location: location_string,
    woeid: '',
    unit: 'f',
    success: function(weather) {
      var output  = "<span class='thumb_ico_container'><span class='icon-"+weather.code+"''></span></span>";
      output += "<b>"+formatTemp(weather.temp, weather.units.temp)+"</b>";
      $('#weather_thumb_'+thumb_id).html(output);
    },
    error: function(error) {api_failure(error)}
  });
}