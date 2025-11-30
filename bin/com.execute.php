<?php

    require 'com.connection.php';
    header("content-type: application/json");

    if(isset($_POST["action"]) && isset($_POST["data"]) && isset($_POST["condition"])){



    }else{



    }
    
        ReturnNow(["state" => 200, "functiona?" => "simon"]);


    function ReturnNow($object){

        echo json_encode($object, JSON_PRETTY_PRINT);

    }

    
?>