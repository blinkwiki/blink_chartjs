<?php

// constants file
//include('inc/constants.php');

// For make the connections to the database
include('inc/conn.php');

// import the required CRUD functions
include_once('inc/crud.php');

// import CRUD support
include_once('inc/support.php');

// import chart support functions
include_once('inc/chart_functions.php');

?><html lang="en-US" style="height: 100%;">
<head>
<title>Display a Table with values from an API</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="Keywords" content="">
<meta name="Description" content="">
<!-- the scripts-->
<script src="js/main.js"></script>
<script src="js/Chart.js"></script>
<script src="js/chart_functions.js"></script>
<!-- the scripts-->
<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
    
<strong class="f18">Complete ChartJS</strong><br>

<?php
    $get_act = mysql_real_escape_string($_GET['a']);
    $get_act = (strlen($get_act) > 0) ? $get_act : 'r';
?>
    
<?php if ($get_act == 'c') { ?>
    
<?php //*********************************************CREATE ?>

    <?php include ('inc/c.php') ;?>
    
<?php //*********************************************CREATE ?>
    
<?php } else if ($get_act == 'r'){ ?>
    
<?php //*********************************************READ ?>
    
    <?php include ('inc/r.php') ;?>
    
<?php //*********************************************READ ?>

<?php } else if ($get_act == 'u') { ?>
    
<?php //*********************************************UPDATE ?>

    <?php include ('inc/u.php') ;?>
    
<?php //*********************************************UPDATE ?>
    
<?php } else if ($get_act == 'd'){ ?>
    
<?php //*********************************************DELETE ?>

    <?php include ('inc/d.php') ;?>
    
<?php //*********************************************DELETE ?>

<?php } ?>
    
    <hr>
    &copy; BlinkWIki
    
</body>
</html>