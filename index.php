<!DOCTYPE html>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
  <link rel='stylesheet' type='text/css' href='resources/css/style.css'>
  <title>QuickWeather</title>
</head>
<body>
  <div id='main'>
    <div id='header'>
     <h1>Welcome to QuickWeather</h1>
   </div>
   <div id='desc'>
     Enter a username below to get started.
   </div>
   <div id='log_in'>
    <form action='PHPActions/CheckUser.php' method='POST'>
     <input name='username' type='text' class='ui_input' id='username_box'>
     <input name='submit' type='submit' id='submit_button' class='ui_button' value='Continue'>
   </form>
   </div>
 </div>
</body>
</html>
