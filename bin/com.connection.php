<?php

    $host = "";
    $username = "";
    $password = "";

    function newConnection($dbname){

    global $host, $username, $password;

    try {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $connection = new mysqli($host, $username, $password, $dbname);
        $connection->set_charset('utf8');

        return $connection;

    } catch (mysqli_sql_exception $e) {

        return [
            'error' => $e->getMessage()
        ];
    }
}


?>