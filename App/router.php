<?php

use Controllers\ClassesController;
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
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }
        elseif ($routeComposee[1] === 'edit_class' && isset($routeComposee[2])) {
            $classId = $routeComposee[2];
           /* if ($method === 'GET') {
                $homeController = new HomeController();
                $homeController->formEditClass($classId);
            } */
            if ($method === 'POST') {
                $classesController = new ClassesController();
                $classesController->updateClass($classId, $_POST);
            }
        }

         elseif ($routeComposee[1] === 'delete_class' && isset($routeComposee[2])) {
            $classId = $routeComposee[2];
            $classesController = new ClassesController();
            $classesController->deleteClass($classId);
        }
        elseif ($routeComposee[1] === 'class' && isset($routeComposee[2])&& $routeComposee[3] === 'students') {
            $classId = $routeComposee[2];
            $classesController = new ClassesController();
            $classesController->getClassStudents($classId);
        }
        elseif ($routeComposee[1] === 'delete_student' && isset($routeComposee[2])) {
            $userId = $routeComposee[2];
            if ($method === 'DELETE') {
                $UserController = new UserController();
                $response = $UserController->deleteStudent($userId);
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }

        elseif ($routeComposee[1] === 'edit_student' && isset($routeComposee[2])) {
            $userId = $routeComposee[2];
            if ($method === 'POST') {
                $UserController = new UserController();
                $response = $UserController->editStudent($userId);
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }
        
        break;
        case 'SetPassword':
            if (isset($routeComposee[1]) && $routeComposee[1] === 'email' && isset($routeComposee[2])) {
                $email = urldecode($routeComposee[2]);
                if ($method === 'GET') {
                    $homeController = new HomeController();
                    $homeController->showSetPasswordForm($email);
                } elseif ($method === 'POST') {
                    $userController = new UserController();
                    $response = $userController->setPassword($email);
                    header('Content-Type:application/json');
                    echo json_encode($response);
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

        case HOME_URL . 'register-student':

           if ($method === 'POST') {
                $formData = $_POST;
                $classId = $formData['class_id'];
                unset($formData['class_id']);
                $user = new User($formData);
                $userController = new UserController();
                $response = $userController->register($user, $classId);
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
            
        case HOME_URL . 'dashboard/create_class':
            if ($method === 'POST') {
                $classesController = new ClassesController();
                $classesController->createClass($_POST);
            }
            break;
        
        case HOME_URL . 'logout':
            $userController = new UserController();
            $userController->logout();
            break;

            default:
            error_log("Unknown route: " . $route);
            http_response_code(404); 
            break;
}