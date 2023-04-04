<?php

session_start();
unset($_SESSION['total_cost']);

require_once "db_connection.php";

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
    <link rel="stylesheet" href="static\style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- Import de jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Import de Bootstrap JS -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
    <!-- Onglets -->
<ul class="nav nav-tabs">
	<li class="active"><a href="#users" data-toggle="tab">Profil</a></li>
	<li><a href="#articles" data-toggle="tab">Articles</a></li>
    <li><a href="#factures" data-toggle="tab">Factures</a></li>
</ul>

<!-- Contenu des onglets -->
<div class="tab-content">

<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if (isset($_GET['usr'])){
        $user_from_url=$_GET['usr'];
        
        $sql = "SELECT * FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            // comme on a vu, on lie les paramètres
            mysqli_stmt_bind_param($stmt, "s", $user_from_url);
            // on tente d'exécuter
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                // on check le mail puis on teste le mdp
                if(mysqli_stmt_num_rows($stmt) == 1){  
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $email, $solde, $picture, $role);
                    if(mysqli_stmt_fetch($stmt)){
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
        echo "<h1>Nom utilisateur : </h1>\n\r<h2>".$user_from_url."</h2></br>";
        echo "<h1>Mail : </h1>\n\r<h2>".$email."</h2></br>";
        echo "<h1>Liste des Factures :</h1>";
        # Liste des articles publié par ce user
        $sql = "SELECT name,price FROM article WHERE user_id = \"".$id."\";";
        $article_list = $mysqli->query($sql);
        echo "<h1>Liste des produits publiés :</h1>";
        foreach($article_list as $article){
            echo "<h3>".$article['name']."</h3>\n\r<h4>Price : ".$article['price']."</h4></br>";
        }

    } elseif (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true) { // isset($_COOKIE["token"])){
        ?>
    <!-- Onglet Utilisateurs -->
  <div class="tab-pane active" id="users">
    <?php
        echo "<h1>Nom utilisateur : </h1>\n\r<h2>".$_SESSION['username']."</h2></br>";
        echo "<form method=\"POST\" action=\"account\">
            <label for=\"username\">Modifier votre nom d'utilisateur :</label>
            <input type=\"text\" id=\"username\" name=\"username\" required>
            <button type=\"submit\">Envoyer</button>
        </form>";
        echo "<h1>Mail : </h1>\n\r<h2>".$_SESSION['email']."</h2></br>";
        echo "<form method=\"POST\" action=\"account\">
            <label for=\"email\">Modifier email :</label>
            <input type=\"email\" id=\"email\" name=\"email\" required>
            <button type=\"submit\">Envoyer</button>
        </form>";
        echo "<h1>Solde : </h1>\n\r<h2>".$_SESSION['solde']." €</h2></br>";
        echo "<form method=\"POST\" action=\"account\">
            <label for=\"numero\">Modifier votre solde :</label>
            <input type=\"number\" id=\"numero\" name=\"solde\" value =".$_SESSION['solde']." required>
            <button type=\"submit\">Envoyer</button>
        </form>";
        echo "<h1>Mot de passe : </h1>";
        echo "<form method=\"POST\" action=\"account\">
            <label for=\"change_passwd\">Modifier mot de passe :</label>
            <input type=\"password\" id=\"change_passwd\" name=\"new_password\" required>
            <button type=\"submit\">Envoyer</button>
        </form>";
        ?>
    </div>

        <!-- Onglet Articles -->
   <div class="tab-pane" id="articles">
	<!-- Tableau des articles -->
	<table class="table table-striped">
    <thead>
      <tr>
        <th>Titre</th>
        <th>Prix</th>
        <th>Date de publication</th>
        <th>Catégorie</th>
        <th>Quantité</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // Requête SQL pour récupérer tous les articles //join pour user et category
        $sql = "SELECT article.article_id, article.name, price, publish_date, category.name as category, stock.nbr_article as quantity FROM article LEFT JOIN category ON article.category_id = category.category_id LEFT JOIN stock ON article.article_id = stock.article_id WHERE article.user_id = ".$_SESSION['id'];
        $result = $mysqli->query($sql);

        // afficher les articles
        while ($row = $result->fetch_assoc()) {
          ?>
          <tr>
          <td><?php echo $row['name']?></td>
          <td><?php echo $row['price'] . "€"?></td>
          <td><?php echo $row['publish_date']?></td>
          <td><?php echo $row['category']?></td>
          <td><?php echo $row['quantity']?></td>
          <td>
          <form method=POST action=edit>
                <input type="hidden" name="entry" value="<?php $row['article_id']?>">
                <button type="submit" class="btn btn-primary" >Modifier</button>
            </form>
          <td>
          <button type="button" class="btn btn-danger" onclick="showDeleteUserModal2(<?php echo $row['article_id'] ?>)">Supprimer</button></td>
          </td>
          </tr>
          <?php
          }
        ?>
    </tbody>
  </table>
    </div>
    <div class="tab-pane" id="factures">
        <?php
        # Liste des factures
        echo "<h1>Liste des Factures :</h1>";
        $main_sql = "SELECT invoice_id FROM invoice WHERE user_id = ".$_SESSION['id']." ORDER BY invoice_id ASC";
        $invoiceId_list = $mysqli->query($main_sql);
        $index = 1;
        foreach($invoiceId_list as $invoiceId){
            $sql = "SELECT name,price,quantities FROM history JOIN article ON article.article_id = history.article_id WHERE invoice_id = \"".$invoiceId['invoice_id']."\";";
            $invoice_article_list = $mysqli->query($sql);
            echo "<h2> Facture n°".$index." :";
            $sum = 0;
            foreach($invoice_article_list as $article){
                $temp = $article['quantities'] * $article['price'];
                $sum = $sum + $temp;
                echo "<h3>".$article['name']." x ".$article['quantities']."</h3>\n\r<h4>Prix unitaire : ".$article['price']." , total produit = ".$temp."</h4></br>";
            }
            echo "<h3> Total de la facture = ".$sum."€</h3>";
            $index = $index + 1;
        }
        ?>
</div>
</div>


<?php
    }else{
        header('Location: /php_exam');
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    # Cas ou on change le solde
    if(isset($_POST['solde'])){
        $new_solde = $_POST['solde'];
        if ($new_solde != $_SESSION['solde']){
            $sql = "UPDATE users SET solde = ? WHERE username = ? AND mail = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "iss", $new_solde,$_SESSION['username'],$_SESSION['email']);
                if(mysqli_stmt_execute($stmt)){
                    $_SESSION['solde'] = $new_solde;
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
    # Cas ou on change l'email
    if(isset($_POST['email'])){
        $new_email = $_POST['email'];

        # Check pour savoir si l'email existe déjà ou pas
        $sql = "SELECT user_id FROM users WHERE mail = '".$new_email."'";
        $mail_already_exist = 1;
        if($stmt = mysqli_prepare($mysqli, $sql)){
            // mysqli_stmt_bind_param($stmt, "s", $new_mail); // Obligé de ne pas passer par cette fonction, car ça déconne avec les adress mails
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 0){ //si on a un retour c'est que le mail est déjà dans la bdd
                    $mail_already_exist = 0;
                }
            }
            mysqli_stmt_close($stmt);
        }
        # Si l'email n'existe pas, alors on effecture le changement
        if ($mail_already_exist == 0){
            $sql = "UPDATE users SET mail = ? WHERE username = ? AND mail = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "sss", $new_email,$_SESSION['username'],$_SESSION['email']);
                if(mysqli_stmt_execute($stmt)){
                    $_SESSION['email'] = $new_email;
                }
            }
        }
    }

    # Cas ou on change l'username
    if(isset($_POST['username'])){
        $new_user = $_POST['username'];

        $sql = "SELECT user_id FROM users WHERE username = ?";
        $user_already_exist = 1;
        if($stmt = mysqli_prepare($mysqli, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $new_user);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 0){
                    $user_already_exist = 0;
                }
            }
            mysqli_stmt_close($stmt);
        }

        if ($user_already_exist == 0){
            $sql = "UPDATE users SET username = ? WHERE username = ? AND mail = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "sss", $new_user,$_SESSION['username'],$_SESSION['email']);
                if(mysqli_stmt_execute($stmt)){
                    $_SESSION['username'] = $new_user;
                }
            }
        }
    }
    # Cas ou on change le password
    if(isset($_POST['new_password'])){
        $new_password = password_hash($_POST['new_password'],PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password = ? WHERE username = ? AND mail = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $new_password,$_SESSION['username'],$_SESSION['email']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    header("Location: /php_exam/account");
}
?>

<!-- Popup de confirmation de suppression -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Confirmation de suppression</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer cet élément ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="document.getElementById('deleteForm').submit();">Supprimer</button>
        <form id="deleteForm" action="delete" method="post" style="display: none;">
          <input type="hidden" name="user_id" id="deleteUserIdInput" value="">
          <input type="hidden" name="article_id" id="deleteArticleIdInput" value="">
        </form>
      </div>
    </div>
  </div>
</div>

</div>

<script>
function showDeleteUserModal1(userId) {
  document.getElementById('deleteUserIdInput').value = userId;
  $('#confirmationModal').modal('show');
}
function showDeleteUserModal2(articleId) {
  document.getElementById('deleteArticleIdInput').value = articleId;
  $('#confirmationModal').modal('show');
}
function editUser(userId) {
  document.getElementById('userIdInput').value = userId;
  $('#modifierUtilisateur').modal('show');
}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>