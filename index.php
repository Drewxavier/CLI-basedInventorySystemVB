<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family:"Aeria black";
        }
    </style>
        
</head>
<body>

    <h1>Recommended Books</h1>

    <?php
    $books= ["Heroes of Olympus"=> "Mark of Athena", 
            "The Kane Chronicals"=> "The Sea of Serpent", 
            "Percy Jackson"=>"And the heros of Olympus" ];
     ?>
     <vl>
   <?php
        echo "<li>".$books["Heroes of Olympus"] ."<li>";
    ?>

    </vl>
</body>
</html>