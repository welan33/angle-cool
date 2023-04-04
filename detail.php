<?php
require_once "db_connection.php";

session_start();
unset($_SESSION['total_cost']);

$articleId = 0;
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if (isset($_GET['item'])){
        $articleId=$_GET['item'];
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['add_article_cart'])){
        $articleId=$_POST['add_article_cart'];
        $sql = "INSERT INTO cart (user_id, article_id, quantities) VALUES (?, ?, ?)";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            $quantities= 1;
            mysqli_stmt_bind_param($stmt, "iii", $_SESSION['id'], $articleId, $quantities);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }else if (isset($_POST['rmv_article_cart'])){
        $articleId=$_POST['rmv_article_cart'];
        $sql = "DELETE FROM cart WHERE user_id = ? AND article_id = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            mysqli_stmt_bind_param($stmt, "ii", $_SESSION['id'], $articleId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

    }else if (isset($_POST['comment'])){
        $comment_id = 0;
        $comment=$_POST['comment'];
        $articleId=$_POST['create_comment'];
        $note=$_POST['note'];
        $sql = "INSERT INTO comments (comment_id, description, notes, user_id, article_id) VALUES (?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            mysqli_stmt_bind_param($stmt, "isiii", $comment_id, $comment, $note, $_SESSION['id'], $articleId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

    }else if (isset($_POST['add_to_fav'])){
        $articleId=$_POST['add_to_fav'];
        $favory_id = 0;
        $date = date('Y-m-d');
        $sql = "INSERT INTO favory (favory_id, user_id, article_id, add_date) VALUES (?, ?, ?, ?)";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            mysqli_stmt_bind_param($stmt, "iiis", $favory_id, $_SESSION['id'], $articleId, $date);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
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
    <link rel="stylesheet" href="static\detail.css">
</head>
<body>
    <h1>Page détail produit</h1>
    <a href="/php_exam">Retour accueil</a>
    <?php
    $sql = "SELECT article_id, article.name as aName, description, price, publish_date, username, image, category.name AS cName FROM article LEFT JOIN users ON users.user_id = article.user_id LEFT JOIN category ON category.category_id = article.category_id WHERE article_id = $articleId";
    $details = $mysqli->query($sql);
    foreach ($details as $detail){
    ?>
  <div class="wrapper">
    <div class="product-img">
      <img src="<?php echo $detail['image'] ?>" height="420" width="327">
    </div>
    <div class="product-info">
      <div class="product-text">
        <h1><?php echo $detail['aName'] ?></h1>
        <h2>de <?php echo $detail['username'] . " le " . $detail['publish_date'] ?></h2>
        <p><?php echo $detail['description'] ?></p>
      </div>
      <div class="product-price-btn">
        <p><span><?php echo $detail['price'] ?></span>€</p>
        <?php
        $sql = "SELECT * FROM cart WHERE user_id = ? AND article_id = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $_SESSION['id'],$articleId);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 0){
        ?>
        <form method="post">
            <input type="hidden" name="add_article_cart" value="<?php echo $articleId ?>">
            <button type="submit">Ajouter au panier</button>
        </form>
        <?php
                }else{
        ?>
        <form method="post">
            <input type="hidden" name="rmv_article_cart" value="<?php echo $articleId ?>">
            <button type="submit">Enlever du Panier</button>
        </form>
        <?php
                }
                mysqli_stmt_close($stmt);
            }
        }
        ?>
      </div>
    </div>
  </div>
    <form class="btn-group" method="post">
        <input type="hidden" name="add_to_fav" value="<?php echo $detail['article_id'] ?>">
        <button type="submit" class="btn btn-success">Ajouter au favori</button>
    </form>

<?php
}
echo '<h1>Liste des commentaires</h1>';
$comment_sql = "SELECT description, notes, comments.user_id, username FROM comments JOIN users ON users.user_id = comments.user_id WHERE article_id = ".$articleId.";";
$comments = $mysqli->query($comment_sql);
foreach($comments as $comment){
    echo "<p> De ".$comment['username']." : " .$comment['description']."</p></br>";
    echo "<p> Note : ".$comment['notes']."/10</p>";
}

$sql = "SELECT * FROM history WHERE user_id = ? AND article_id = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $_SESSION['id'],$articleId);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 0){
?>
<button type="submit" class="btn btn-failed">Il faut d'abord acheter le produit</button>
<?php
                }else{
?>
<form class="btn-group" method="post">
    <textarea placeholder="Modifier la description" name="comment" class="textarea" style="position:relative; width: 95%; height: 250px; resize: none;"></textarea>
    <input type="hidden" name="create_comment" value="<?php echo $articleId ?>">
    <input type="number" name="note" value="1" min="0" max="10">
    <button type="submit">Ajouter commentaire</button>
</form>

<?php
                }
            }
        }
?>
</main>
</body>
</html>