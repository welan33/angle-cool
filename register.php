<?php
require_once "db_connection.php";

$username = $password = $mail = "";
$username_err = $password_err = $mail_err = 0;

if ( ($_SERVER['REQUEST_METHOD'] === 'POST') ) {

    // Validate mail
    if(empty(trim($_POST["mail"]))){
        $mail_err = 1;
    } else{
        // On prépare ce qu'on appelle un 'statement' (une déclaration)
        $sql = "SELECT user_id FROM users WHERE mail = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            // On y associes des variables en paramètres
            mysqli_stmt_bind_param($stmt, "s", $param_mail); //le 's' correspond à string car ici on a le mail
            // Set parameters
            $param_mail = trim($_POST["mail"]);
            // On tente d'éxécuter la requête avec le mail en paramètre
            if(mysqli_stmt_execute($stmt)){
                //on stock le résultat
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){ //si on a un retour c'est que le mail est déjà dans la bdd
                    $mail_err = 1;
                } else{
                    $mail = trim($_POST["mail"]); //sinon on le garde du form pour ensuite le save dans la bdd
                }
            } else{
                echo "Il y a eu une erreur, réessayez plus tard";
            }
            // Il faut fermer les statements !
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = 1;     
    } else{
        $password = trim($_POST["password"]); //on stock le mot de passe du form
    }

    //valider le nom
    if(empty(trim($_POST["username"]))){
         $username_err = 1;
    } else{
        $sql = "SELECT user_id FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = 1;
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Il y a eu une erreur, réessayez plus tard";
            }
            mysqli_stmt_close($stmt);
        }
    }

    //divers
    $solde = 0;
    $picture = null;
    $role = 0;
    $user_id;

    if($username_err != 1 && $password_err != 1 && $mail_err != 1){
        
        // On prépare la requête
        $sql = "INSERT INTO users (username, password, mail, solde, picture, role) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($mysqli, $sql)){
            
            mysqli_stmt_bind_param($stmt, "sssisi", $param_username, $param_password, $param_mail, $solde, $picture, $role); //"sss.. par rapport au paramètres"
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // on hash, on testera avec password_verify
            $param_mail = $mail;

            if(mysqli_stmt_execute($stmt)){
                session_start();
                $sql = "SELECT user_id FROM users WHERE username = \"".$param_username."\" AND mail = \"".$param_mail."\";";
                $user_requested = $mysqli->query($sql);
                foreach($user_requested as $single_user){
                    $_SESSION["id"] = $single_user['user_id'];
                }
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $param_username;
                $_SESSION["email"] = $param_mail;  
                $_SESSION["solde"] = $solde;  
                $_SESSION["picture"] = $picture;   
                $_SESSION["role"] = $role; 
                header('Location: /php_exam');
            }else{
                echo "Il y a eu une erreur, réessayez plus tard";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($mysqli);
    exit;

} elseif ( ($_SERVER['REQUEST_METHOD'] === 'GET') ) {
    header('Location: /php_exam');
    exit;
}
?>
