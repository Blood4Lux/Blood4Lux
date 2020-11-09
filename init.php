<?php
    $host = "localhost"; //on same server we keep localhost
    $user = "id15291122_apmproject";  //username of the database
    $pass = "Password1#12";   //password of the database
    $db = "id15291122_blood4lux";  //name of database
    
    $con = mysqli_connect($host,$user,$pass,$db);
    
    if($con){
        //echo "Connected to Database";
    }else{
        //echo "Failed to connect ".mysqli_connect_error();
    }
?>