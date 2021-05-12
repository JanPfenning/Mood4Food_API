<?php

    function getMaterials($where, $limit, $offset){
        require "../../database/config.php";

        $array = [];
        $materials = mysqli_query($conn,
            "select * ".
            "from materials ".
            ($where != "" ? "where ".$where." " : "").
            "limit ".$limit." ".
            "offset ".$offset." "
        );
        while($row = mysqli_fetch_array($materials)){
            $material = [];
            foreach(array_keys($row) as $key){
                if(!is_numeric($key)){
                    $material[$key] = $row[$key];
                }
            }
            array_push($array, $material);
        }
        return $array;
    }

    function getDistinctMaterials($where, $limit, $offset){
        require "../../database/config.php";

        $array = [];
        $materials = mysqli_query($conn,
            "select distinct name ".
            "from materials ".
            ($where != "" ? "where ".$where." " : "").
            "limit ".$limit." ".
            "offset ".$offset." "
        );
        while($row = mysqli_fetch_array($materials)){
            $material = [];
            foreach(array_keys($row) as $key){
                if(!is_numeric($key)){
                    $material[$key] = $row[$key];
                }
            }
            array_push($array, $material);
        }
        return $array;
    }

?>