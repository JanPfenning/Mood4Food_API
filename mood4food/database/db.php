<?php

    switch($_SERVER['SERVER_NAME']){
        case "localhost":
            $conn = mysqli_connect(
                "localhost",
                "root",
                "",
                "mood4food"
            );
            break;
        default:
            $conn = mysqli_connect(
                "rdbms.strato.de",
                "dbu641369",
                "fa1laf2fel8#",
                "dbs1759467"
            );
            break;
    }
    mysqli_set_charset($conn, "utf8");

?>