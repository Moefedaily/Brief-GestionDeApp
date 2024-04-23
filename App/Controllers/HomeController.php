<?php

namespace Controllers;

use Repositories\ClassesRepository;
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

        $action = $_GET['action'] ?? 'view';

        $this->render('dashboard', [
            'section' => 'dashboard',
            'action' => $action,
            'role' => $_SESSION['role'],
        ]);
    }
        
    public function formEditClass($classId)
    {
        $classesController = new ClassesController();
        $class = $classesController->getClassForEditing($classId);

        if ($class) {
            $this->render('dashboard', [
                'section' => 'dashboard',
                'action' => 'edit_class',
                'class' => $class
            ]);
        } else {
            echo "Class not found.";
        }
    }

    public function page404()
    {
        header("HTTP/1.1 404 Not Found");
        $this->render('views/404.php');
    }

  
}