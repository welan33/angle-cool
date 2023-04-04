<?php
session_start();
unset($_SESSION['total_cost']);
# en attente de à quoi ça sert concrètement -> voir messages discord
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: /php_exam");
    exit;
}

require_once "db_connection.php";

$mail = $password = "";
$mail_err = $password_err = 1;

if (($_SERVER['REQUEST_METHOD'] === 'POST')) {
     // on check l'email
     if(empty(trim($_POST["mail"]))){
        $mail_err = 0;
    } else{
        $mail = trim($_POST["mail"]);
    }
    
    // on check le mdp
    if(empty(trim($_POST["password"]))){
        $password_err = 0;
    } else{
        $password = trim($_POST["password"]);
    }
    
    //si c'est ok
    if($mail_err == 1 && $password_err == 1){
        $sql = "SELECT * FROM users WHERE mail = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            // comme on a vu, on lie les paramètres
            mysqli_stmt_bind_param($stmt, "s", $param_mail);
            $param_mail = $mail;
            // on tente d'exécuter
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                // on check le mail puis on teste le mdp
                if(mysqli_stmt_num_rows($stmt) == 1){  
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $email, $solde, $picture, $role);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // password ok donc nouvelle session
                            
                            // et on store des variables utiles qu'on pourra rappeler facilement comme l'id
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["email"] = $email;  
                            $_SESSION["solde"] = $solde;  
                            $_SESSION["picture"] = $picture;   
                            $_SESSION["role"] = $role;     
                            // on redirige
                            header("location: /php_exam");
                        } else{
                            // password faux -> voir pour faire un popup erreur
                            header("location: error");
                        }
                    }
                } else{
                    // username inconnue -> voir pour faire un popup erreur
                    header("location: error");
                }
            } else{
                header("location : error");
            }

            // on ferme la requête
            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($mysqli);
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
    <link href="./static/logstyle.css" rel="stylesheet" media="all" type="text/css">
    <link rel="shortcut icon" href="/assets/favicon.ico" />
</head>

<body>
    <a href="/php_exam">Retour accueil</a>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="register" method="POST">
                <h1>Créer votre compte</h1>
                <input type="text" placeholder="Nom" name="username" required />
                <input type="email" placeholder="Email" name="mail" required />
                <input type="password" placeholder="Mot de passe" name="password" required />
                <button>S'inscrire</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="login" method="POST">
                <h1>Connexion</h1>
                <input type="email" placeholder="Email" name="mail" required />
                <input type="password" placeholder="Mot de passe" name="password" required />
                <button>Connexion</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>L'Angle cool</h1>
                    <p>Pour se connecter c'est par ici</p>
                    <button class="ghost" id="signIn">Connexion</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>L'Angle cool</h1>
                    <p>Pour créer son compte c'est par là</p>
                    <button class="ghost" id="signUp">S'inscrire</button>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });
    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>

</html>