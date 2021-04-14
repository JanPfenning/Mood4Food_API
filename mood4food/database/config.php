<?php

    include "../../env/env.php";

    switch($_SERVER['SERVER_NAME']){
        case "localhost":
            $conn = mysqli_connect(
                $dev_dbHost,
                $dev_dbUser,
                $dev_dbPassword,
                $dev_dbName
            );
            break;
        default:
            $conn = mysqli_connect(
                $dbHost,
                $dbUser,
                $dbPassword,
                $dbName
            );
            break;
    }
    mysqli_set_charset($conn, "utf8");

?>