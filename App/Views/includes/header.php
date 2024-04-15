<?php
use Repositories\UserRepository;

$loggedIn = isset($_SESSION['user_id']);
if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $UserRepo = new UserRepository();
    $user = $UserRepo->getUserById($userId);
    if ($user) {
        $userName =  $user->getLastName();
    } else {
        $userName = '';
    }
} else {
    $userName = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <nav>
                <ul class="flex space-x-4">
                    <?php if ($loggedIn): ?>
                        <li><a href="<?php echo HOME_URL; ?>" class="hover:text-gray-300">Home</a></li>
                        <li><a href="<?php echo HOME_URL; ?>dashboard" class="hover:text-gray-300">Dashboard</a></li>
                        <li><a href="<?php echo HOME_URL; ?>logout" class="hover:text-gray-300">Logout</a></li>
                        <?php else: ?>
                        <li><a href="<?php echo HOME_URL; ?>login" class="hover:text-gray-300">Login</a></li>
                        <li><a href="<?php echo HOME_URL; ?>register" class="hover:text-gray-300">Register</a></li>                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container mx-auto py-8">