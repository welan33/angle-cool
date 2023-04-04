<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false || !isset($_SESSION["total_cost"])){
    header("location: /php_exam");
    exit;
}

// unset($_SESSION['counter']); Permet de supprimer une variable 
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
    <h1>Page validation du panier</h1>

<?php
require_once "db_connection.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $adress= $_POST['adress'];
    $ville= $_POST['city'];
    $code_postal= $_POST['postal_code'];
    $sql = "SELECT quantities,nbr_article,name,price,cart.article_id as art_id FROM cart JOIN stock ON stock.article_id = cart.article_id JOIN article ON cart.article_id = article.article_id WHERE cart.user_id = \"".$_SESSION['id']."\";";
    $article_list = $mysqli->query($sql);
    $isValid = true;
    foreach($article_list as $article){
        if ($article['nbr_article'] < $article['quantities']){
            echo "<h2>Stock insuffisant pour".$article['name']." x ".$article['quantities']."</h2>";
            $new_quantities = $article['nbr_article'];
            if ($new_quantities == 0){
                $update_sql = "DELETE FROM cart WHERE user_id = ? AND article_id = ?";
                if($stmt = mysqli_prepare($mysqli, $update_sql)){
                    mysqli_stmt_bind_param($stmt, "ss",$_SESSION['id'],$article['art_id']);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
            }else{
                $update_sql = "UPDATE cart SET quantities = ? WHERE user_id = ? AND article_id = ?";
                if($stmt = mysqli_prepare($mysqli, $update_sql)){
                    mysqli_stmt_bind_param($stmt, "iss", $new_quantities,$_SESSION['id'],$article['art_id']);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
            }
            $isValid = false;
        }
    }
    if ($isValid){
        $date = date('Y-m-d');
        $sql = "INSERT INTO invoice (user_id, buy_date, total, adress, city, postal_code) VALUES (?, ?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            mysqli_stmt_bind_param($stmt, "ssisss", $_SESSION['id'], $date, $_SESSION['total_cost'], $adress, $ville, $code_postal);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        $sql = "SELECT invoice_id FROM invoice WHERE user_id = \"".$_SESSION['id']."\" AND buy_date = \"".$date."\" AND total = \"".$_SESSION['total_cost']."\";";
        $current_invoiceId = $mysqli->query($sql);
        $invoiceId = 0;
        foreach($current_invoiceId as $invoice){
            $invoiceId = $invoice['invoice_id'];
        }

        # Liste de tout les articles présent dans le panier
        $sql = "SELECT cart.article_id as article_id,quantities,nbr_article FROM cart JOIN stock ON stock.article_id = cart.article_id WHERE user_id = \"".$_SESSION['id']."\"";
        $article_list = $mysqli->query($sql);
        foreach($article_list as $articleId){

            # Ajout des articles dans la table history
            $insert_sql = "INSERT INTO history (user_id,article_id,invoice_id,quantities) VALUES (?,?,?,?)";
            if($stmt = mysqli_prepare($mysqli, $insert_sql)){
                mysqli_stmt_bind_param($stmt, "iiii", $_SESSION['id'], $articleId['article_id'], $invoiceId,$articleId['quantities']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

            # Mise à jour du stock
            $new_stock = $articleId['nbr_article'] - $articleId['quantities'];
            $insert_sql = "UPDATE stock SET nbr_article = ? WHERE article_id = ?";
            if($stmt = mysqli_prepare($mysqli, $insert_sql)){
                mysqli_stmt_bind_param($stmt, "ii", $new_stock, $articleId['article_id']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

            # Supprimer le panier
            $delete_sql = "DELETE FROM cart WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $delete_sql)){
                mysqli_stmt_bind_param($stmt, "i",$_SESSION['id']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

        }

        # Mise à jour du solde
        $insert_sql = "UPDATE users SET solde = ? WHERE user_id = ?";
        $new_solde = $_SESSION['solde'] - $_SESSION["total_cost"];
        if($stmt = mysqli_prepare($mysqli, $insert_sql)){
            mysqli_stmt_bind_param($stmt, "ii", $new_solde, $_SESSION['id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['solde'] = $new_solde;
        }
        
        echo "<h2>Commande validé, la facture est disponible sur la page de votre profil</h2>";
        unset($_SESSION['total_cost']);
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    ?>
    <form method="POST" action="validate">
      <div>
        <label for="adress">Adresse :</label>
        <input type="text" id="adress" name="adress" required>
      </div>
      <div>
        <label for="city">Ville :</label>
        <input type="text" id="city" name="city" required>
      </div>
      <div>
        <label for="postal_code">Code postal :</label>
        <input type="text" id="postal_code" name="postal_code" required>
      </div>
      <button type="submit">Valider le panier</button>
    </form>
<?php
}
?>
    <a href="/php_exam">Retour accueil</a>
</body>
</html>