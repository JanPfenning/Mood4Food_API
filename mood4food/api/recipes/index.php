<?php

    require "service.php";

    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            $result = "";

            if(isset($_GET["recipeId"])){
                $recipeId = $_GET["recipeId"];

                $result = getRecipes("recipeId = '".$recipeId."'", 1, 0)[0];
            }else{
                $limit = 50;
                $offset = 0;
                $where = "";
                $join = "";
                $order = "";
                if(isset($_GET["limit"])){
                    $limit = $_GET["limit"];
                }
                if(isset($_GET["offset"])){
                    $offset = $_GET["offset"];
                }
                if(isset($_GET["search"])){
                    $search = $_GET["search"];
                    $where .= ($where == "" ? "" : "and ")."lower(title) like '%".strtolower($search)."%'";
                }
                if(isset($_GET["ingredients"])){
                    $ingredients = explode(",", str_replace(" ", "", $_GET["ingredients"]));
                    if(count($ingredients) > 0){
                        $join = "inner join ".
                            "ingredients as i ".
                            "on i.recipeId = r.recipeId";
                        $where .= ($where == "" ? "(" : "and (");
                        foreach($ingredients as $ingredient){
                            $where .= (substr($where, -1) == "(" ? "" : "or ")."lower(i.name) like '%".strtolower($ingredient)."%' ";
                        }
                        $where .= ") ";
                    }
                }

                $result = getRecipes($join, $where, $limit, $offset, $order);
            }

            header("Content-type: application/json; charset=utf-8");
            http_response_code(200);
            echo json_encode($result);
            break;
        default:
            http_response_code(400);
            echo "This service does not support the used request method.";
            break;
    }

?>