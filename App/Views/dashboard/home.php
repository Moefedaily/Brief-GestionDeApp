<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="flex flex-col items-center gap-5 bg-white">
        <nav class="flex items-center px-3 py-2 w-full bg-gray-100">
            <div class="flex items-start px-4 py-1.5">
                <div class="flex justify-center items-start">
                    <div class="text-2xl font-normal text-black opacity-90">SIMPLON</div>
                </div>
            </div>
            <div class="flex items-center flex-grow">
                <ul class="flex justify-end items-center flex-grow">
                    <li class="flex justify-center items-center">
                        <div class="flex items-center px-4 py-2 gap-1">
                            <div class="flex items-center gap-2">
                                <div class="flex justify-center items-start">
                                    <div class="text-base font-bold text-black">Accueil</div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <form class="flex items-center gap-2 hidden"></form>
            </div>
        </nav>

        <div class="flex flex-col items-start gap-10 w-11/12 p-0">
            <div class="flex flex-col items-start gap-3.5 w-full">
                <div class="flex flex-col items-start gap-3.5">
                    <div class="text-2xl font-normal text-black">Cours du jour</div>
                </div>
            </div>

            <div id="courseCards" class="flex flex-col items-start gap-5 w-11/12"></div>
         </div>
         <div id="dashboardContainer"></div>
         <div id="randomCodeContainer" class="hidden flex justify-center items-center fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50">
    <div class="bg-white p-8 rounded shadow-lg">
        <div class="text-2xl font-bold mb-4">Random Code</div>
        <div id="randomCode" class="text-4xl font-bold"></div>
        <button id="closeRandomCodeButton" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Close</button>
    </div>
</div>
    </div>
</body>
<script src="/cours/Brief-GestionDeApp/App/src/js/script.js"></script>
</html>