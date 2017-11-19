<?php
$app = require __DIR__ . '/../bootstrap/app.php';
$data = array_merge($_POST, $_GET);
$controller = new \App\Controllers\IndexController();
$action = $data['action'];
//$users = $controller->searchUsers();
if (!$data['action']) {
    include 'users.php';
}

$results = $controller->handle($data);
if ($action === 'signup') {
    include 'list_users.php';
    return;
}
if ($action === 'addBook')
    include 'list_books.php';

if($action ==='searchBook'){
    include 'search_results.php';
}
?>