<?php

use Controllers\CoursesController;
use Controllers\UserController;
use Controllers\HomeController;
use Models\User;
use Services\Routing;

$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$routeComposee = Routing::routeComposee($route);


switch ($routeComposee[0]) {
    case 'dashboard':
        if ($routeComposee[1] === 'validateAttnd' && $routeComposee[2] !== null) {
            if ($method === 'POST') {
                $courseId = $routeComposee[2];
                $CoursesController = new CoursesController();
                $response = $CoursesController->validateTrainerAttendance($courseId);
                header('Content-Type: application/json');
                echo json_encode($response);
            }  break;
        } elseif ($routeComposee[1] === 'validStudAttnd' && isset($routeComposee[2])) {
            if ($method === 'POST') {
                $courseId = $routeComposee[2];
                $CoursesController = new CoursesController();
                $response = $CoursesController->validateStudentAttendance($courseId);
            }
        }
        break;

        }

        


switch ($route) {
    case HOME_URL:
        $homeController = new HomeController();
        $homeController->index();
        break;

    case HOME_URL . 'login':
        if ($method === 'GET') {
            $homeController = new HomeController();
            $homeController->showLoginForm();
        } elseif ($method === 'POST') {
            $user = new User($_POST);
            $userController = new UserController();
            $response = $userController->login($user);
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        break;

    case HOME_URL . 'register':

          if ($method === 'GET') {
            $homeController = new HomeController();
            $homeController->showRegistrationForm();
        }  elseif ($method === 'POST') {
            $formData = $_POST;
            $classId = $formData['class_id'];
            unset($formData['class_id']);
            $user = new User($formData);
            $userController = new UserController();
            $response = $userController->register($user, $classId);
            echo json_encode($response);
            exit;
        }
        break;
    case HOME_URL .'setPassword':
        if ($method === 'GET') {
            $homeController = new HomeController();
            $homeController->showSetPasswordForm();
        } elseif ($method === 'POST') {
            $userController = new UserController();
            $response = $userController->setPassword();
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        break;

    case HOME_URL . 'dashboard':
        $homeController = new HomeController();
        $response = $homeController->showDashboardView();
        break;

    case HOME_URL . 'dashboard/data':
        $CoursesController = new CoursesController();
        $CoursesController->getDashboardData();
        break;


     
        
    case HOME_URL . 'logout':
        $userController = new UserController();
        $userController->logout();
        break;

        default:
        error_log("Unknown route: " . $route);
        http_response_code(404); // Not Found
        break;
}