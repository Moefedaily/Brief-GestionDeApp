<?php

use Controllers\UserController;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password</title>
</head>
<body class="bg-gray-100 ">
<div class="container mx-auto py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
         <h2 class="text-2xl font-semibold mb-6 text-center">Welcome</h2>
        <h3 class="text-2xl font-semibold mb-6 text-center">Another heading</h3>
        <form id="passwordForm" action="<?php echo UserController::generateUrl($email); ?>" method="POST">
            <div class="mb-4">
                <label for="password" class="block mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="confirm_password" class="block mb-2">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">Reset</button>
        </form>
    </div>
</div>

    <script src="<?php echo HOME_URL; ?>App/src/js/SetPassword.js"></script>
</body>
</html>
