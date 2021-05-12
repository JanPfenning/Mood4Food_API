<?php

    require "service.php";
    require "../proxy.php";

    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":

            $limit = 50;
            $offset = 0;
            if(isset($_GET["limit"])){
                $limit = $_GET["limit"];
            }
            if(isset($_GET["offset"])){
                $offset = $_GET["offset"];
            }

            header("Content-type: application/json; charset=utf-8");
            http_response_code(200);

            echo json_encode(getDistinctMaterials("", $limit, $offset));

            break;
        default:
            http_response_code(400);
            echo "This service does not support the used request method.";
            break;
    }

?>