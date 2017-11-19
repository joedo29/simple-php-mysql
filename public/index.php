<?php
$app = require __DIR__ . '/../bootstrap/app.php';
$data = array_merge($_POST, $_GET);
$controller = new \App\Controllers\IndexController();
$action = $data['action'];
//$users = $controller->searchUsers();
if (!$data['action']) {
    include 'users.php';
}
//$data = ['title' => 'huhed', 'action' => 'search']
$results = $controller->handle($data);
if ($action === 'signup') {
    include 'list_users.php';
    return;
}
if (in_array($action, ['addBook', 'search']))
    include 'list_books.php';

?>