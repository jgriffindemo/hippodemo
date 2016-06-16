<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
  <link rel='stylesheet' type='text/css' href='resources/css/style.css'>
  <link rel='stylesheet' type='text/css' href='resources/css/icons.css'>
  <link rel='stylesheet' type='text/css' href='resources/css/jquery.mCustomScrollbar.css'>
  <script src='resources/js/jquery-2.1.4.min.js'></script>
  <script src='resources/js/jquery.simpleWeather.min.js'></script>
  <script src='resources/js/jquery.mCustomScrollbar.concat.min.js'></script>
  <script src='resources/js/QuickWeatherLib.js'></script>
  <script src='resources/js/QuickWeather.js'></script>
  <script type='text/javascript'>
      $(document).ready(function(){
        generateSearchBox();
        pullSavedLocations();
      });

      /* Set the scrollbar on the locations.*/
      $(window).load(function(){
       $("#locations_box").mCustomScrollbar({
        axis: "y",
        scrollbarPosition: "inside",
        theme: "inset-dark"
      });
     });
  </script>
  <title><?php echo htmlspecialchars($_SESSION['username']); ?>'s QuickWeather</title>
</head>
<body>
  <div id='main'>
    <div id='return_link'>
      <a href='index.php'>&laquo; return to main</a>
    </div>
    <div id='weather_main'>
     <div id='weather_box'>
     </div>
     <div id='locations_box' class='default_skin'>
      <h3><u>Saved Locations</u></h3>
      <ul id='locations_menu'>
      </ul>
    </div>
  </div>
</div>
</body>
</html>
