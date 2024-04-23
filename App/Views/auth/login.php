<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/047f41f5d9.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:max-w-md">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Login</h2>
            <form id="loginForm" class="space-y-6" action="login" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="relative">
                        <input id="email" name="email" type="email" required
                            class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
                        <span
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"><i
                                class="fas fa-envelope"></i></span>
                    </div>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required
                            class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-400 focus:ring-opacity-50">
                        <span
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"><i
                                class="fas fa-lock"></i></span>
                    </div>
                </div>
                <div class="champVideConnexion text-red-700">
                </div>

                <div>
                    <button type="submit"
                        class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-semibold text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Login
                    </button>
                </div>
            </form>
            <p class="mt-6 text-center text-sm text-gray-600">
                Not a member? <a href="register"
                    class="font-medium text-indigo-600 hover:text-indigo-500">Register Now</a>
            </p>
        </div>
    </div>
    <script src="App/src/js/login.js"></script>
</body>

</html>
