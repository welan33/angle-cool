<?php
$request = $_SERVER['REQUEST_URI'];
//echo "Route.php" . " " . $request;
$temp = explode('?',$request);
if (count($temp) == 2){
    $request = $temp[0];
}
switch ($request) {
    case '/php_exam':
        require __DIR__ . '\index.php';
        break;
    case '/php_exam/test':
        require __DIR__ . '\testIndex.php';
        break;
    case '/php_exam/login':
        require __DIR__ . '\login.php';
        break;
    case '/php_exam/register':
        require __DIR__ . '\register.php';
        break;
    case '/php_exam/logout':
        require __DIR__ . '\logout.php';
        break;
    case '/php_exam/sell':
        require __DIR__ . '\sell.php';
        break;
    case '/php_exam/detail':
        require __DIR__ . '\detail.php';
        break;
    case '/php_exam/cart':
        require __DIR__ . '\cart.php';
        break;
    case '/php_exam/cart/validate':
        require __DIR__ . '\cart_validate.php';
        break;
    case '/php_exam/edit':
        require __DIR__ . '\edit.php';
        break;
    case '/php_exam/admin':
        require __DIR__ . '\admin.php';
        break;
    case '/php_exam/account':
        require __DIR__ . '\account.php';
        break;
    case '/php_exam/admin':
        require __DIR__ . '\admin.php';
        break;
    case '/php_exam/delete':
        require __DIR__ . '\delete.php';
        break;
    case '/php_exam/editUser':
        require __DIR__ . '\edit_user.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '\404.php';
        break;
}
exit;
