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
                $having = "";
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
                $likeorder = "";
                if(isset($_GET["like"])){
                    $ingredients = explode(",", str_replace(" ", "", $_GET["like"]));
                    if(count($ingredients) > 0){
                        $join = "left join ".
                            "ingredients as i ".
                            "on i.recipeId = r.recipeId";
                        $where .= ($where == "" ? "(" : "and (");
                        foreach($ingredients as $ingredient){
                            $where .= (substr($where, -1) == "(" ? "" : "or ")."lower(i.name) like '%".strtolower($ingredient)."%' ";
                            $likeorder .= ($likeorder == "" ? "" : "+ ")."if(lower(group_concat(i.name)) like '%".strtolower($ingredient)."%', 1, 0) ";
                        }
                        $where .= ") ";

                        if(isset($_GET["dislike"])){
                            $ingredients = explode(",", str_replace(" ", "", $_GET["dislike"]));
                            if(count($ingredients) > 0){
                                $join = "left join ".
                                    "ingredients as i ".
                                    "on i.recipeId = r.recipeId";
                                foreach($ingredients as $ingredient){
                                    $likeorder .= ($likeorder == "" ? "" : "+ ")."if(lower(group_concat(i.name)) not like '%".strtolower($ingredient)."%', 2, 0) ";
                                }
                            }
                        }
                    }
                }

                

                if($likeorder != ""){
                    $order .= ($order == "" ? "" : ", ")."(".$likeorder.") desc";
                }

                $result = getRecipes($join, $where, $having, $order, $limit, $offset);
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