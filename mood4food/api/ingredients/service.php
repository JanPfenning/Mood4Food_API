<?php

    function getIngredients($where, $limit, $offset){
        require "../../database/db.php";

        $array = [];
        $ingredients = mysqli_query($conn, 
            "select * ".
            "from ingredients ".
            ($where != "" ? "where ".$where." " : "").
            "limit ".$limit." ".
            "offset ".$offset." "
        );
        while($row = mysqli_fetch_array($ingredients)){
            $ingredient = [];
            foreach(array_keys($row) as $key){
                if(!is_numeric($key)){
                    $ingredient[$key] = $row[$key];
                }
            }
            array_push($array, $ingredient);
        }
        return $array;
    }

    function getDistinctIngredients($where, $limit, $offset){
        require "../../database/db.php";

        $array = [];
        $ingredients = mysqli_query($conn, 
            "select distinct name ".
            "from ingredients ".
            ($where != "" ? "where ".$where." " : "").
            "limit ".$limit." ".
            "offset ".$offset." "
        );
        while($row = mysqli_fetch_array($ingredients)){
            $ingredient = [];
            foreach(array_keys($row) as $key){
                if(!is_numeric($key)){
                    $ingredient[$key] = $row[$key];
                }
            }
            array_push($array, $ingredient);
        }
        return $array;
    }

?>