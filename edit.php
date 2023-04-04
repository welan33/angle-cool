<?php
require_once "db_connection.php";

session_start();
if (isset($_POST['entry'])){
    $articleId=$_POST['entry'];
    $_SESSION['editId'] = $articleId;
}
unset($_SESSION['total_cost']);
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false || !isset($_SESSION["editId"])){
    header("location: /php_exam");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['name'])){
        $new_name=$_POST['name'];

        $sql = "UPDATE article SET name = ? WHERE article_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $new_name,$_SESSION['editId']);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['description'])){
        $new_description=$_POST['description'];

        $sql = "UPDATE article SET description = ? WHERE article_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $new_description,$_SESSION['editId']);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);
    }

}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['price'])){
        $new_price=$_POST['price'];

        $sql = "UPDATE article SET price = ? WHERE article_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $new_price,$_SESSION['editId']);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);
    }

}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['quantity'])){
        $new_quantity=$_POST['quantity'];

        $sql = "UPDATE stock SET nbr_article = ? WHERE article_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $new_quantity,$_SESSION['editId']);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);
    }

}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['image'])){
        $new_image=$_POST['image'];

        $sql = "UPDATE article SET image = ? WHERE article_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $new_image,$_SESSION['editId']);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="POST">
    <label for="name">Modifier le nom de l'article :</label>
    <input type="text" id="name" name="name" required>
    <button type="submit">Envoyer</button>
</form>
<form method="POST">
    <div class="control">
        <textarea placeholder="Modifier la description" name="description" required class="textarea" style="position:relative; width: 95%; height: 250px; resize: none;"></textarea>
        <button type="submit">Envoyer</button>
    </div>
</form>
<form method="POST">
    <input type="number" placeholder="Modifier le prix" name="price" required />
    <button type="submit">Envoyer</button>
</form>
<form method="POST">
    <input type="number" placeholder="Modifier la quantité" name="quantity" required />
    <button type="submit">Envoyer</button>
</form>
<form method="POST">
    <input type="url" placeholder="Modifier l'image" name="image">
    <button type="submit">Envoyer</button>
</form>
    <a href="/php_exam">Retour accueil</a>
</body>
</html>