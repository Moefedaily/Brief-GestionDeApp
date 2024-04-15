<?php

namespace Controllers;

use Services\Reponse;

class HomeController
{
    use Reponse;

    public function index():void
    {
        if (isset($_GET['erreur'])) {
            $erreur = htmlspecialchars($_GET['erreur']);
        } else {
            $erreur = '';
        }

        $this->render('home', ["erreur" => $erreur]);
    }

    public function showLoginForm()
    {
        $this->render('home', ['section' => 'menu', 'action' => 'login']);
    }

    public function showRegistrationForm()
    {
        $this->render('home', ['section' => 'menu', 'action' => 'register']);
    }
    public function showSetPasswordForm($email){

        $this->render('home', ['section' => 'menu', 'action' => 'SetPassword', 'email' => $email]);
    }
    
    public function logout()
    {

            session_destroy();
            header('location: ' . HOME_URL);
            die();
        }

        public function showDashboardView()
        {
            if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
                header('Location: ' . HOME_URL . 'login');
                exit;
            }
        
            $coursesController = new CoursesController();
            $data = $coursesController->getCourses();
        
            $this->render('dashboard', [
                'dashboardData' => $data,
                'section' => 'dashboard',
                'action' => 'view',
                'role' => $_SESSION['role'],
            ]);
        }
        

    public function page404()
    {
        header("HTTP/1.1 404 Not Found");
        $this->render('views/404.php');
    }

  
}