<?php
session_start();
if(!isset($_SESSION["role"]) || $_SESSION["role"] !== 1){
    header("location: /php_exam");
    exit;
}
require_once "db_connection.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Page d'administration</title>
  <!-- Import de Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- Import de jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Import de Bootstrap JS -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

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

  <!-- Onglets -->
  <ul class="nav nav-tabs">
			<li class="active"><a href="#users" data-toggle="tab">Utilisateurs</a></li>
			<li><a href="#articles" data-toggle="tab">Articles</a></li>
		</ul>

    <!-- Contenu des onglets -->
		<div class="tab-content">

  <!-- Onglet Utilisateurs -->
  <div class="tab-pane active" id="users">
      <!-- Tableau des utilisateurs -->
				<table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th>Solde</th>
            <th>Rôle</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // Requête SQL pour récupérer tous les utilisateurs
            $sql = "SELECT user_id, username, mail, solde, role FROM users WHERE role != 1";
            $result = $mysqli->query($sql);

            // Boucle pour afficher tous les utilisateurs dans le tableau
            while ($row = $result->fetch_assoc()) {
              ?>
              <tr>
              <td> <?php echo $row["user_id"] ?></td>
              <td> <?php echo $row["username"] ?></td>
              <td> <?php echo $row["mail"] ?></td>
              <td> <?php echo $row["solde"] ?></td>
              <td><?php echo $row["role"] ?></td>
              <td><button type="button" class="btn btn-primary" onclick="editUser(<?php echo $row['user_id'] ?>)">Modifier</button>             
              <td><button type="button" class="btn btn-danger" onclick="showDeleteUserModal1(<?php echo $row['user_id'] ?>)">Supprimer</button></td>
              </tr>
              <?php
              }
              ?>
        </tbody>
      </table>
  </div>

   <!-- Onglet Articles -->
   <div class="tab-pane" id="articles">
				<!-- Tableau des articles -->
				<table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Prix</th>
        <th>Date de publication</th>
        <th>Utilisateurs</th>
        <th>Catégories</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // Requête SQL pour récupérer tous les articles //join pour user et category
        $sql = "SELECT article_id, article.name, price, publish_date, users.username as username, category.name as category FROM article LEFT JOIN users ON article.user_id = users.user_id LEFT JOIN category ON article.category_id = category.category_id";
        $result = $mysqli->query($sql);

        // afficher les articles
        while ($row = $result->fetch_assoc()) {
          ?>
          <tr>
          <td><?php echo $row['article_id']?></td>
          <td><?php echo $row['name']?></td>
          <td><?php echo $row['price'] . "€"?></td>
          <td><?php echo $row['publish_date']?></td>
          <td><?php echo $row['username']?></td>
          <td><?php echo $row['category']?></td>
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
</div>

<!-- Popup de modification utilisateur -->
<div class="modal fade" id="modifierUtilisateur" tabindex="-1" role="dialog" aria-labelledby="modifierUtilisateurLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modifierUtilisateurLabel">Modifier l'utilisateur</h4>
      </div>
      <div class="modal-body">
        <!-- Formulaire de modification -->
        <form action="editUser" method="POST" id="editForm">
          <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez le nouveau nom de l'utilisateur">
          </div>
          <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Entrez le nouveau mail de l'utilisateur">
          </div>
          <div class="form-group">
            <label for="motdepasse">Mot de passe :</label>
            <input type="password" class="form-control" id="motdepasse" name="motdepasse" placeholder="Entrez le nouveau mot de passe de l'utilisateur">
          </div>
          <div class="form-group">
            <label for="solde">Solde :</label>
            <input type="text" class="form-control" id="solde" name="solde" placeholder="Entrez le nouveau solde de l'utilisateur">
          </div>
          <div class="form-group">
            <label for="role">Rôle de l'utilisateur :</label>
            <select class="form-control" id="role" name="role">
              <option value="0">Utilisateur</option>
              <option value="1">Administrateur</option>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            <button type="submit" class="btn btn-primary" onclick="document.getElementById('editForm').submit();">Enregistrer les modifications</button>
            <input type="hidden" name="user_id" id="userIdInput" value="">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>

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
</html>