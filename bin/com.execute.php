<?php

    require 'com.connection.php';
    header("content-type: application/json");

    if(isset($_POST["action"]) && isset($_POST["database"]) && isset($_POST["table"]) && isset($_POST["condition"])){

        $action = $_POST["action"];
        $database = $_POST["database"];
        $table = $_POST["table"];
        $connection = newConnection($database);

        function get(){

            global $connection;

            if (is_array($connection) && isset($connection['error'])) {
                echo json_encode([
                    "execute" => false,
                    "error" => $connection['error']
                ]);
                exit;
            }

            $conditionJson = $_POST["condition"];
            $conditions = json_decode($conditionJson, true);

            $whereparam = '';
            if (!empty($conditions) && is_array($conditions)) {
                $clauses = [];
                foreach ($conditions as $cond) {
                    $column = mysqli_real_escape_string($connection, $cond['column']);
                    $value  = mysqli_real_escape_string($connection, $cond['value']);
                    $operator = isset($cond['operator']) ? strtoupper($cond['operator']) : '=';
                    $clauses[] = "$column $operator '$value'";
                }
                $whereparam = 'WHERE ' . implode(' AND ', $clauses);
            } else {
                $whereparam = 'WHERE 1';
            }

            $table = $_POST["table"];
            $limit = $_POST["limit"] ?? '';
            $order = $_POST["order"] ?? '';
            $fields = $_POST["columns"] ?? '*';

            $sql = "SELECT $fields FROM $table $whereparam $order $limit";

            try {

                $query = $connection->query($sql);
                $data = $query->fetch_all(MYSQLI_ASSOC);

                echo json_encode([
                    "execute" => true,
                    "result" => $data
            ]);

            } catch (mysqli_sql_exception $e) {

                echo json_encode([
                    "execute" => false,
                    "error" => $e->getMessage(),
                    "sql" => $sql 
                ]);
            }
        }


        switch($action){

            case 'get':
                get();
                break;

        }

    }else{

        ReturnNow(["execute" => false, "code" => 400]);

    }
    

    function ReturnNow($object){

        echo json_encode($object, JSON_PRETTY_PRINT);

    }

    
?>