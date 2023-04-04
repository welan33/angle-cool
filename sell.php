<?php

session_start();
unset($_SESSION['total_cost']);
require_once "db_connection.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
    header("location: /php_exam");
    exit;
}

if ( ($_SERVER['REQUEST_METHOD'] === 'POST') ) {
    //vérif donnée et save dans bd
    $article_id = 0;
    $name =  $_REQUEST['name'];
    $description = mysqli_real_escape_string($mysqli, $_REQUEST['description']);
    $price = $_REQUEST['price'];
    $user_id = $_SESSION['id'];
    $nbr_article = $_REQUEST['quantity'];
    if ($_REQUEST['image'] != null){
        $image = $_REQUEST['image'];
    }else{
        $image = "./assets/no_product.png";
    }
    $category_id = $_REQUEST['category'];
    $user_id = $userId;
    $stock_id = 0;

    // Performing insert query execution
    // $sql = "INSERT INTO article VALUES ('$article_id','$name','$description','$price', DATE( NOW() ),'$user_id','$image','$category_id')";
    $date = date('Y-m-d');
    $sql = "INSERT INTO article (name, description, price, publish_date, user_id, image, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    if($stmt = mysqli_prepare($mysqli, $sql)){
        mysqli_stmt_bind_param($stmt, "ssisisi", $name, $description, $price, $date, $_SESSION['id'], $image,$category_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    $getId_sql = "SELECT article_id FROM article WHERE name = \"".$name."\" AND description = \"".$description."\" AND user_id = ".$_SESSION['id']." AND image = \"".$image."\";";
    $current_article = $mysqli->query($getId_sql);
    foreach($current_article as $article){
        $article_id = $article['article_id'];
    }

    $sql2 = "INSERT INTO stock VALUES ('$stock_id', '$article_id', '$nbr_article')";
    $mysqli->query($sql2);

    header("location: /php_exam");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vente produit</title>
    <link href="./static/logstyle.css" rel="stylesheet" media="all" type="text/css">
    <link rel="shortcut icon" href="/assets/favicon.ico" />
</head>

<body>

<div class="field">
    <form action="sell" class="form" method="POST">
    <label class="label">Mettez en vente votre produit !</label>
    <input type="text" placeholder="Nom de l'article en vente" name="name" required />
        <div class="control">
            <textarea placeholder="Ecrire votre comment ici" name="description" required class="textarea" style="position:relative; width: 95%; height: 250px; resize: none;"></textarea>
        </div>
    <input type="number" placeholder="Prix" name="price" required />
    <input type="number" placeholder="Quantitée" name="quantity" required />
    <input type="url" placeholder="inserer l'url de votre image" name="image">
    <select class="select" name="category" id="category-select" required>
        <option value="">--Catégories--</option>
        <?php
        $cat = "SELECT * FROM category";
        $catName = $mysqli->query($cat);
        foreach ($catName as $name){
            echo "<option value=" . $name['category_id'] . ">" . $name['name'] . "</option>";
        }
        ?>
    </select>
    <div class="field">
        <div class="control">
            <button type="submit" class="button">Confirmer</button>
        </div>
    </div>
    </form>
</div>
<a href="/php_exam">Retour accueil</a>
</body>
</html>