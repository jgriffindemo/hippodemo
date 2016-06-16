<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
  <link rel='stylesheet' type='text/css' href='resources/css/style.css'>
  <title>Create user?</title>
</head>
<body>
  <div id='main'>
    <div id='header'>
     <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
   </div>
   <div id='desc'>
    You aren't a member of QuickWeather. Would you like to join?
  </div>
  <div id='accept_or_cancel'>
    <form action='PHPActions/AddUser.php' method='POST'>
     <input name='no' type='submit' value='No, take me to the main menu.' class='ui_button' id='cancel_button'>
     <input name='yes' type='submit' value='Yes.' class='ui_button' id='accept_button'>
   </form>
 </div>
</div>
</body>
</html>
