<?php
session_start();
unset($_SESSION['total_cost']);
require_once "db_connection.php";

if(!isset($_SESSION["loggedin"])|| $_SESSION["loggedin"] === false){
    header("location: /php_exam");
    exit;
}
// if (isset($_POST[$noms_boutons[$i]['incrementer']])) {
//     $valeurs[$i]++;
//   }

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
    <link rel="stylesheet" href="static\cart.css">
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
<div class="container">

<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $sql = "SELECT article.article_id as art_id, article.name as art_name, description, price, image, quantities, category.name as cat_name FROM cart JOIN article ON article.article_id = cart.article_id JOIN category ON category.category_id = article.category_id WHERE cart.user_id = \"".$_SESSION['id']."\";";
    $article_list = $mysqli->query($sql);
    $mega_total = 0;
    $arbitre=false;
    ?>
<h1>Panier d'achat</h1>
<table>
		<thead>
			<tr>
				<th>Produit</th>
				<th>Prix unitaire</th>
				<th>Quantité</th>
				<th>Prix total</th>
			</tr>
		</thead>
<?php
    foreach($article_list as $article){
        $arbitre=true;
        $total = $article['price'] * $article['quantities'];
        $mega_total = $mega_total + $total;
        ?>

		<tbody>
			<tr>
				<td><?php echo $article['art_name'] ?></td>
				<td><?php echo $article['price'] ?> €</td>
				<td class="quantity">
                <form method ="post"><button name="decrement" type="submit" value ="<?php echo $article['art_id'] ?>">-</button></form>
					<text type="text" value=""><?php echo $article['quantities'] ?></text>
					<form method ="post"><button name="increment" type="submit" value ="<?php echo $article['art_id'] ?>">+</button></form>
				</td>
				<td><?php echo $total ?> €</td>
			</tr>
		</tbody>
        
    <?php    
}
?>
<tfoot>
	<tr>
<?php
    if ($arbitre){
        echo "<td colspan=\"3\" class=\"total\">Total : </td> <td class=\"total\">".$mega_total." €</td>";
        ?>
        </tr>
    </tfoot>
</table>
    <?php
        if ($_SESSION['solde'] < $mega_total){
            echo "<text class=\"checkout\">Désolé, vous n'avez pas assez d'argent =(</text>";
        }else{
            $_SESSION['total_cost'] = $mega_total;
            echo "<a href=\"cart/validate\" class=\"checkout\"> Valider le panier</a>";
        }
    }  
?>
<?php
}else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['increment'])) {
        $add_articleId= $_POST['increment'];
        $sql = "SELECT quantities,nbr_article FROM cart JOIN stock ON stock.article_id = cart.article_id WHERE user_id = \"".$_SESSION['id']."\" AND cart.article_id = \"".$add_articleId."\";";
        $article_list = $mysqli->query($sql);
        $quant = 0;
        $sum = 0;
        foreach($article_list as $article){
            $quant = $article['quantities'];
            $sum = $article['nbr_article'];
        }
        if ($quant < $sum){
            $quant = $quant + 1;
            $update_sql = "UPDATE cart SET quantities = ? WHERE user_id = ? AND article_id = ?";
            if($stmt = mysqli_prepare($mysqli, $update_sql)){
                mysqli_stmt_bind_param($stmt, "iss", $quant,$_SESSION['id'],$add_articleId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        } //else{
        //     $_SESSION['error_message'] = "Stock insufisant"; // à voir si c'est une bonne idée
        // }
        header("Location: /php_exam/cart");
    }else if (isset($_POST['decrement'])) {
        $minus_articleId= $_POST['decrement'];
        $sql = "SELECT quantities FROM cart WHERE user_id = \"".$_SESSION['id']."\" AND article_id = \"".$minus_articleId."\";";
        $article_list = $mysqli->query($sql);
        $quant = 0;
        foreach($article_list as $article){
            $quant = $article['quantities'];
            $sum = $article['nbr_article'];
        }
        if ($quant > 1){
            $quant = $quant - 1;
            $update_sql = "UPDATE cart SET quantities = ? WHERE user_id = ? AND article_id = ?";
            if($stmt = mysqli_prepare($mysqli, $update_sql)){
                mysqli_stmt_bind_param($stmt, "iss", $quant,$_SESSION['id'],$minus_articleId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("Location: /php_exam/cart");
            }
        }else{
            $update_sql = "DELETE FROM cart WHERE user_id = ? AND article_id = ?";
            if($stmt = mysqli_prepare($mysqli, $update_sql)){
                mysqli_stmt_bind_param($stmt, "ss",$_SESSION['id'],$minus_articleId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("Location: /php_exam/cart");
            }
        }
    }
}
?>
</body>
</html>