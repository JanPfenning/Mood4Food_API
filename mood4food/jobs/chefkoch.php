<?php

    require_once "../database/db.php";
    require_once "../libs/simple_html_dom/simple_html_dom.php";

    $c = 100;
    if(isset($_GET["c"])){
        $c = $_GET["c"];
    }

    $i = 0;
    while($i < $c){

        $html = file_get_html("https://www.chefkoch.de/rezepte/was-koche-ich-heute/");
        foreach($html->find(".card__link") as $link) {

            // Insert recipes

            echo $link->href."<br/>";
            $recipe = file_get_html($link->href);

            $title = preg_replace("/\s+/", " ", $recipe->find("h1")[0]->plaintext);
            $description = preg_replace("/\s+/", " ", $recipe->find("#recipeGuide")[0]->parent()->find("div.ds-box")[0]->plaintext);
            $imageUri = $recipe->find("img.i-amphtml-fill-content")[0]->src;

            $insertRec = mysqli_query($conn, 
                "insert into recipes ".
                "(title, description, imageUri)".
                "values ('".$title."', '".$description."', '".$imageUri."')"
            );
            if($insertRec){
                echo $title."<br>";
                echo $description."<br>";
                echo $imageUri."<p>";
                $recipeId = mysqli_insert_id($conn);
        
                $tables = $recipe->find("table.ingredients");
                foreach($tables as $table){
                    $ingredients = $table->find("tbody")[0]->find("tr");
                    foreach($ingredients as $ingredient){
            
                        $ingName = preg_replace("/\s+/", " ", $ingredient->find("td.td-right")[0]->find("span")[0]->plaintext);
                        $amount = $ingredient->find("td.td-left")[0]->find("span");
                        $ingAmount = "";
                        if(count($amount) > 0){
                            $ingAmount = preg_replace("/\s+/", " ", $amount[0]->plaintext);
                        }
            
                        $insertIng = mysqli_query($conn, 
                            "insert into ingredients ".
                            "(name, amount, recipeId)".
                            "values ('".$ingName."', '".$ingAmount."', '".$recipeId."')"
                        );
                        if($insertIng){
                            echo $ingAmount."<br>";
                            echo $ingName."<p>";
                        }

                    }
                }
                
                $mfinder = strtolower($description);
                $materials = [];
                $aMaterials = [
                    "Messer",
                    "Topf",
                    "Sieb",
                    "Backblech",
                    "Mixer",
                    "Rührgerät",
                    "Reibe",
                    "Pfanne"
                ];
                foreach($aMaterials as $material){
                    if(strpos($mfinder, strtolower($material)) !== false){

                        $insertMat = mysqli_query($conn, 
                            "insert into materials ".
                            "(name, recipeId)".
                            "values ('".$material."', '".$recipeId."')"
                        );
                        if($insertMat){
                            echo $material."<p>";
                        }

                    }
                }

                echo "Done ".$i."/".$c."<hr>";

                $i++;
            }

        }
    }

?>