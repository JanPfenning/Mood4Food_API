<?php

class RecipeService implements Service
{

    public function request($join, $where, $having, $order, $limit, $offset){
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
            ($having != "" ? "having ".$having." " : "").
            ($order != "" ? "order by ".$order." " : "").
            "limit ".$limit." ".
            "offset ".$offset." "
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

}

?>