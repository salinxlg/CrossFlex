<?php

    require 'com.connection.php';
    header("content-type: application/json");

    if(isset($_POST["action"]) && isset($_POST["database"]) && isset($_POST["table"]) && isset($_POST["condition"])){

        $action = $_POST["action"];
        $database = $_POST["database"];
        $table = $_POST["table"];
        $connection = newConnection($database);

        function get() {

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

                if (count($data) === 1) {
                    $data = $data[0];
                }

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

        function new_row() {
            global $connection;

            if (is_array($connection) && isset($connection['error'])) {
                echo json_encode([
                    "execute" => false,
                    "error" => $connection['error']
                ]);
                exit;
            }

            $dataJson = $_POST["data"] ?? '';
            $data = json_decode($dataJson, true);
            $table = $_POST["table"];
            $database = $_POST["database"];

            if (empty($data) || !is_array($data)) {
                echo json_encode([
                    "execute" => false,
                    "error" => "No se enviaron datos para crear un registro."
                ]);
                exit;
            }

            $columns = array_map(fn($col) => mysqli_real_escape_string($connection, $col), array_keys($data));
            $values  = array_map(fn($val) => "'" . mysqli_real_escape_string($connection, $val) . "'", array_values($data));

            $sql = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";

            try {
                $connection->query($sql);
                echo json_encode([
                    "execute" => true,
                    "insert_id" => $connection->insert_id
                ]);
            } catch (mysqli_sql_exception $e) {
                echo json_encode([
                    "execute" => false,
                    "error" => $e->getMessage(),
                    "sql" => $sql
                ]);
            }
        }

        function update_change() {

            global $connection;
            if (is_array($connection) && isset($connection['error'])) {
                echo json_encode([
                    "execute" => false,
                    "error" => $connection['error']
                ]);
                exit;
            }

            $dataJson = $_POST["data"] ?? '';
            $data = json_decode($dataJson, true);
            $table = $_POST["table"];
            $database = $_POST["database"];

            if (empty($data) || !is_array($data)) {
                echo json_encode([
                    "execute" => false,
                    "error" => "No se enviaron datos para actualizar."
                ]);
                exit;
            }

            $conditionJson = $_POST["condition"] ?? '';
            $conditions = json_decode($conditionJson, true);

            if (empty($conditions) || !is_array($conditions)) {
                echo json_encode([
                    "execute" => false,
                    "error" => "No condition provided for update"
                ]);
                exit;
            }

            $setClauses = [];
            foreach ($data as $column => $value) {
                $column = mysqli_real_escape_string($connection, $column);
                $value = mysqli_real_escape_string($connection, $value);
                $setClauses[] = "$column = '$value'";
            }
            $setStr = implode(", ", $setClauses);

            $whereClauses = [];
            foreach ($conditions as $cond) {
                $column = mysqli_real_escape_string($connection, $cond['column']);
                $value  = mysqli_real_escape_string($connection, $cond['value']);
                $operator = isset($cond['operator']) ? strtoupper($cond['operator']) : '=';
                $whereClauses[] = "$column $operator '$value'";
            }
            $whereStr = 'WHERE ' . implode(' AND ', $whereClauses);

            $sql = "UPDATE $table SET $setStr $whereStr";

            try {
                $connection->query($sql);
                echo json_encode([
                    "execute" => true,
                    "affected_rows" => $connection->affected_rows
                ]);
            } catch (mysqli_sql_exception $e) {
                echo json_encode([
                    "execute" => false,
                    "error" => $e->getMessage(),
                    "sql" => $sql
                ]);
            }
        }

        function remove() {
            global $connection;
            $table = $_POST["table"];
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
                echo json_encode([
                    "execute" => false,
                    "error" => "No se enviaron datos para eliminar."
                ]);
                exit;
            }

            $sql = "DELETE FROM $table $whereparam";

            try {
                $query = $connection->query($sql);
                echo json_encode([
                    "execute" => true,
                    "affected_rows" => $connection->affected_rows
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
            case 'new':
                new_row();
                break;
            case 'change': 
                update_change();
                break;
            case 'remove':
                remove();
                break;

        }

    }else{

        ReturnNow(["execute" => false, "error" => 400]);

    }
    

    function ReturnNow($object){

        echo json_encode($object, JSON_PRETTY_PRINT);

    }

    
?>