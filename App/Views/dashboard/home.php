<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="flex flex-col items-center gap-5 bg-white">
        <nav class="flex items-center px-3 py-2 w-full bg-gray-100">
            <!-- Navigation content -->
        </nav>

        <div class="w-2/3 mx-auto">
            <ul class="relative flex flex-wrap -mb-px text-sm font-medium text-center" data-tabs="tabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <a href="#" class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 active" data-tabs-target="#Welcome" role="tab" aria-controls="Welcome" aria-selected="true">Home</a>
                </li>
                <?php if ($_SESSION['role'] !== 1): ?>
                <li class="mr-2" role="presentation">
                    <a href="#" class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300" data-tabs-target="#classes" role="tab" aria-controls="classes" aria-selected="false">Classes</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <div id="Welcome" class="tab-content active flex flex-col items-start gap-10 w-11/12 p-0">
            <div id="courseCards" class="flex flex-col items-start gap-5 w-11/12"></div>
        </div>

        <div id="classes" class="tab-content flex flex-col items-start gap-10 w-11/12 p-0">
            <div id="classesContainer" class="flex flex-col items-start gap-5 w-11/12"></div>
            
        </div>
        <div id="classDetailsContainer" class="hidden">
        <div id="classGeneralInfo" class=" mb-4"></div>
    <ul class="relative flex flex-wrap -mb-px text-sm font-medium text-center" data-tabs="tabs" role="tablist">
        <li class="mr-2" role="presentation">
            <a href="#" class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 active" data-tab-target="studentInfo" role="tab" aria-controls="studentInfo" aria-selected="true">Student Information</a>
        </li>
        <li class="mr-2" role="presentation">
            <a href="#" class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300" data-tab-target="attendanceRecords" role="tab" aria-controls="attendanceRecords" aria-selected="false">Attendance Records</a>
        </li>
    </ul>
    <div id="studentInfo" class="tab-content">
    </div>
    <div id="attendanceRecords" class="tab-content" style="display: none;">
    </div>
</div>
        <div id="randomCodeContainer" class="hidden flex justify-center items-center fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50">
            <div class="bg-white p-8 rounded shadow-lg">
                <div class="text-2xl font-bold mb-4">Random Code</div>
                <div id="randomCode" class="text-4xl font-bold"></div>
                <button id="closeRandomCodeButton" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>

    <script>
        const tabLinks = document.querySelectorAll('[data-tabs-target]');
        const tabContents = document.querySelectorAll('.tab-content');

        function showTab(event, tabTarget) {
            event.preventDefault();

            tabLinks.forEach(link => link.classList.remove('active', 'border-blue-600'));
            tabContents.forEach(content => content.classList.remove('active'));

            const targetLink = document.querySelector(`[data-tabs-target="${tabTarget}"]`);
            targetLink.classList.add('active', 'border-blue-600');

            const targetContent = document.querySelector(tabTarget);
            targetContent.classList.add('active');
        }

        tabLinks.forEach(link => {
            link.addEventListener('click', event => showTab(event, link.getAttribute('data-tabs-target')));
        });
    </script>
    
</body>
<script src="<?php echo HOME_URL; ?>App/src/js/script.js"></script>
</html>