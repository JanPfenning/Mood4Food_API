<?php

    function getRecipes($join, $where, $limit, $offset, $order){
        require "../../database/config.php";
        require "../ingredients/service.php";
        require "../materials/service.php";
        $array = [];
        $recipes = mysqli_query($conn, 
            "select * ".
            "from recipes as r ".
            $join." ".
            ($where != "" ? "where ".$where." " : "").
            ($join != "" ? "group by r.recipeId " : "").
            "limit ".$limit." ".
            "offset ".$offset." ".
            ($order != "" ? "order by ".$order." " : "")
        );
        while($row = mysqli_fetch_array($recipes)){
            $recipe = [];
            foreach(array_keys($row) as $key){
                if(!is_numeric($key)){
                    $recipe[$key] = $row[$key];
                }
            }
            $recipe["ingredients"] = getIngredients("recipeId = '".$recipe["recipeId"]."'", 100, 0);
            $recipe["materials"] = getMaterials("recipeId = '".$recipe["recipeId"]."'", 100, 0);
            array_push($array, $recipe);
        }
        return $array;
    }

?>