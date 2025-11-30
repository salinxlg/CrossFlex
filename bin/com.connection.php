<?php

    $host = "sv18.byethost18.org";
    $username = "devlabsc_write_store";
    $password = "Dv229011000$";

    function newConnection($dbname){

        global $host, $username, $password;
        $connection = new mysqli($host, $username, $password, $dbname);
        $connection -> set_charset('utf8');

        if($connection -> connect_error){return ['error' => $connection -> connect_error];}
      
        return $connection;

    }

?>