<?php
session_start();
require_once "db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
        header("location: /php_exam/login");
        exit;
    }
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
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L'Angle Cool</title>
    <!-- Inclure les fichiers CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="static\style.css">
  </head>

  <body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Bouton de la barre de navigation -->
        <div class="navbar-header">
          <!-- Logo de la navbar -->
          <a class="navbar-brand" href="/php_exam">L'Angle Cool</a>
        </div>

        <!-- Eléments de la navbar -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <!-- Bouton de recherche de produits -->
            <li>
              <form class="navbar-form navbar-left" role="search" action="recherche" method="POST">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Recherche" name="search">
                </div>
                <button type="submit" class="btn btn-default">Rechercher</button>
              </form>
              <button class="navbar-form navbar-left btn btn-default" href="filter">
                    <span></span> Filtrer
                </button>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <!-- Bouton de connexion / profil -->
            <?php if(!isset($_SESSION['loggedin'])): ?>
                <li>
                <a href="login">
                    <span class="glyphicon glyphicon-user"></span> Connexion
                </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="account">
                        <span class="glyphicon glyphicon-user"></span> Profil
                    </a>
                </li>
                <?php if($_SESSION['role'] == 1): ?>
                <!-- Bouton d'administration -->
                    <li>
                        <a href="admin">
                            <span class="glyphicon glyphicon-cog"></span> Admin
                        </a>
                      </li>
                    <?php endif; ?>
                <!-- Bouton de panier -->
                <li>
                    <a href="cart">
                        <span class="glyphicon glyphicon-shopping-cart"></span> Panier
                     </a>
                </li>
                <!-- Bouton de mise en vente -->
                <li>
                    <a href="sell">
                        <span class="glyphicon glyphicon-upload"></span> Mettre en vente
                    </a>
                </li>
                <li>
                    <a href="logout">
                        <span class="glyphicon glyphicon-user"></span> Se déconnecter
                    </a>
                </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Le contenu de la page va ici -->
   <!-- partial:index.partial.html -->

<?php
$sql = "SELECT article.article_id as article_id, article.name as art_name, category.name as cat_name, price, image, description FROM article JOIN category ON category.category_id = article.category_id JOIN stock ON stock.article_id = article.article_id WHERE nbr_article > 0 ORDER BY publish_date DESC,article_id DESC;";
$article_list = $mysqli->query($sql);
echo "<h1>Liste des produits publiés :</h1>";
?>
<div id="products" class="row list-group">
<?php
foreach($article_list as $article){
    ?>
    <div class="item  col-xs-4 col-md-3">
        <div class="thumbnail">
            <img class="group list-group-image h-100" src="<?php echo $article['image'] ?>" alt="" />
          <div class="category">
            <h5 class="category-name"><?php echo $article['cat_name'] ?></h5>
          </div>
            <div class="caption">
                <h4 class="group inner list-group-item-heading">
                <?php echo $article['art_name'] ?></h4>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <p class="lead">
                        <?php echo $article['price']." $" ?></p>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-details" href="detail?item=<?php echo $article['article_id']?>">Details</a>
                        <?php
                        $sql = "SELECT * FROM cart WHERE user_id = ? AND article_id = ?";
                        if($stmt = mysqli_prepare($mysqli, $sql)){
                            mysqli_stmt_bind_param($stmt, "ss", $_SESSION['id'],$article['article_id']);
                            if(mysqli_stmt_execute($stmt)){
                                mysqli_stmt_store_result($stmt);
                                if(mysqli_stmt_num_rows($stmt) == 0){
                        ?>
                        <form class="btn-group" method="post">
                            <input type="hidden" name="add_article_cart" value="<?php echo $article['article_id'] ?>">
                            <button type="submit" class="btn btn-success">Ajouter au Panier</button>
                        </form>
                        <?php
                                }else{
                        ?>
                        <form class="btn-group" method="post">
                            <input type="hidden" name="rmv_article_cart" value="<?php echo $article['article_id'] ?>">
                            <button type="submit" class="btn btn-failed">Enlever du Panier</button>
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
        </div>
    </div>
<?php
}
?>
</div>
</div>
</body>
</html>