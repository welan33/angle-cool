<?php
require_once "db_connection.php";
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
    header("location: /php_exam");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['user_id'])){
        $user_id=$_POST['user_id'];
        if (isset($_POST['nom']) && $_POST['nom'] !== null){
            $new_name=$_POST['nom'];
            $sql = "UPDATE users SET username = ? WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $new_name, $user_id);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        }
        if (isset($_POST['email']) && $_POST['email'] !== null){
            $new_mail=$_POST['email'];
            $sql = "UPDATE users SET mail = ? WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $new_mail, $user_id);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        }
        if (isset($_POST['motdepasse']) && $_POST['motdepasse'] !== null){
            $new_password = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $new_password, $user_id);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        }
        if (isset($_POST['solde']) && $_POST['solde'] !== null){
            $new_solde=$_POST['solde'];
            $sql = "UPDATE users SET solde = ? WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "ii", $new_solde, $user_id);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        }
        if (isset($_POST['role']) && $_POST['role'] !== null){
            $new_role=$_POST['role'];
            $sql = "UPDATE users SET role = ? WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "ii", $new_role, $user_id);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        }

        /*$new_name=$_POST['nom'];
        $new_mail=$_POST['email'];
        $new_password = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
        $new_solde=$_POST['solde'];
        $new_role=$_POST['role'];
        $sql = "UPDATE users SET username = ?, password = ?, mail = ?, solde = ?, role = ? WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "sssii", $new_username, $new_password, $new_mail, $new_solde, $new_role, $user_id);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);*/

        header("location : /admin");
    }
} else {
    header("location : /php_exam");
    exit;
}
?>