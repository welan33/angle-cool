<?php
require_once "db_connection.php";
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
    header("location: /php_exam");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    //delete users
    if (isset($_POST['user_id']) && $_POST['user_id'] != ""){
        $user_id = $_POST['user_id'];

        # Delete les traces de user dans Invoice
        $sql = "DELETE FROM invoice WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);

        # Delete les traces de user dans History
        $sql = "DELETE FROM history WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);

        # Delete les traces de user dans comment
        $sql = "DELETE FROM comments WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);

        # Delete les traces de user dans cart
        $sql = "DELETE FROM cart WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);

        # Delete les traces de user dans favory
        $sql = "DELETE FROM favory WHERE user_id = ?";
            if($stmt = mysqli_prepare($mysqli, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
            }
        mysqli_stmt_close($stmt);


        # Delete les traces de user dans article
        $main_sql = "SELECT article_id FROM article WHERE user_id = ".$user_id.";";
        $article_list = $mysqli->query($main_sql);
        foreach($article_list as $article){
                # Delete article from cart
                $delete_art= "DELETE FROM cart WHERE article_id = ?";
                if($delete_art = mysqli_prepare($mysqli, $delete_art)){
                    mysqli_stmt_bind_param($delete_art, "i", $article['article_id']);
                    mysqli_stmt_execute($delete_art);
                }
                mysqli_stmt_close($delete_art);

                # Delete article from stock
                $delete_art= "DELETE FROM stock WHERE article_id = ?";
                if($delete_art = mysqli_prepare($mysqli, $delete_art)){
                    mysqli_stmt_bind_param($delete_art, "i", $article['article_id']);
                    mysqli_stmt_execute($delete_art);
                }
                mysqli_stmt_close($delete_art);

                # Delete article from favory
                $delete_art= "DELETE FROM favory WHERE article_id = ?";
                if($delete_art = mysqli_prepare($mysqli, $delete_art)){
                    mysqli_stmt_bind_param($delete_art, "i", $article['article_id']);
                    mysqli_stmt_execute($delete_art);
                }
                mysqli_stmt_close($delete_art);

                # Delete article from comments
                $delete_art= "DELETE FROM comments WHERE article_id = ?";
                if($delete_art = mysqli_prepare($mysqli, $delete_art)){
                    mysqli_stmt_bind_param($delete_art, "i", $article['article_id']);
                    mysqli_stmt_execute($delete_art);
                }
                mysqli_stmt_close($delete_art);

                # Delete article from history
                $delete_art= "DELETE FROM history WHERE article_id = ?";
                if($delete_art = mysqli_prepare($mysqli, $delete_art)){
                    mysqli_stmt_bind_param($delete_art, "i", $article['article_id']);
                    mysqli_stmt_execute($delete_art);
                    mysqli_stmt_close($delete_art);

                }

                # Delete article from article
                $delete_art= "DELETE FROM article WHERE article_id = ?";
                if($delete_art = mysqli_prepare($mysqli, $delete_art)){
                    mysqli_stmt_bind_param($delete_art, "i", $article['article_id']);
                    mysqli_stmt_execute($delete_art);
                    mysqli_stmt_close($delete_art);
                }
            }

        # Delete user from users
        $sql = "DELETE FROM users WHERE user_id = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        // header("location : /php_exam");
        // exit;
    } else if (isset($_POST['article_id'])){
        $article_id = $_POST['article_id'];

        echo $article_id;
        $delete_art= "DELETE FROM cart WHERE article_id = ?";
        if($delete_art = mysqli_prepare($mysqli, $delete_art)){
            mysqli_stmt_bind_param($delete_art, "i", $article_id);
            mysqli_stmt_execute($delete_art);
        }
        mysqli_stmt_close($delete_art);

        # Delete article from stock
        $delete_art= "DELETE FROM stock WHERE article_id = ?";
        if($delete_art = mysqli_prepare($mysqli, $delete_art)){
            mysqli_stmt_bind_param($delete_art, "i", $article_id);
            mysqli_stmt_execute($delete_art);
        }
        mysqli_stmt_close($delete_art);

        # Delete article from favory
        $delete_art= "DELETE FROM favory WHERE article_id = ?";
        if($delete_art = mysqli_prepare($mysqli, $delete_art)){
            mysqli_stmt_bind_param($delete_art, "i", $article['article_id']);
            mysqli_stmt_execute($delete_art);
        }
        mysqli_stmt_close($delete_art);

        # Delete article from comments
        $delete_art= "DELETE FROM comments WHERE article_id = ?";
        if($delete_art = mysqli_prepare($mysqli, $delete_art)){
            mysqli_stmt_bind_param($delete_art, "i", $article_id);
            mysqli_stmt_execute($delete_art);
        }
        mysqli_stmt_close($delete_art);

        # Delete article from history
        $delete_art= "DELETE FROM history WHERE article_id = ?";
        if($delete_art = mysqli_prepare($mysqli, $delete_art)){
            mysqli_stmt_bind_param($delete_art, "i", $article_id);
            mysqli_stmt_execute($delete_art);
            mysqli_stmt_close($delete_art);
        }
        
        # Delete article from article
        $delete_art= "DELETE FROM article WHERE article_id = ?";
        if($delete_art = mysqli_prepare($mysqli, $delete_art)){
            mysqli_stmt_bind_param($delete_art, "i", $article_id);
            mysqli_stmt_execute($delete_art);
            mysqli_stmt_close($delete_art);
        }

        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
} else {
    header("Location : /php_exam");
    exit;
}

?>