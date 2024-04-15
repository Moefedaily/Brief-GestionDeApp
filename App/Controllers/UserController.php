<?php
namespace Controllers;

use Exception;
use Models\User;
use Repositories\ClassesRepository;
use Repositories\CoursesRepository;
use Repositories\UserRepository;
use Services\Reponse;

class UserController
{
    private $userRepository;
    private $classesRepository;

    use Reponse;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->classesRepository = new ClassesRepository();
    }

    public function login(User $user)
    {
        $email = $user->getEmail();
        $password = $user->getPassword();

        $existingUser = $this->userRepository->findByEmail($email);

        if ($existingUser && $existingUser->verifyPassword($password)) {
            $roleUser = $existingUser->getRoleid();
            $_SESSION['user_id'] = $existingUser->getUserid();
            $_SESSION['role'] = $roleUser;

            return [
                'status' => 'success',
                'role' => $roleUser
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Identifiants incorrects'
            ];
        }
    }
    public function register(User $user, $classId)
    {
        try {
            $existingUser = $this->userRepository->findByEmail($user->getEmail());
            if ($existingUser) {
                error_log("User already exists with email: " . $user->getEmail());
                return [
                    'success' => false,
                    'error' => 'User with this email already exists'
                ];
            }
    
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));

            $user->setActivation(0);
            $user->setRoleid(0);
    
            error_log("First name: " . $user->getFirstName());
            error_log("Last name: " . $user->getLastName());
            error_log("Email: " . $user->getEmail());
    
            $userId = $this->userRepository->create($user);
            error_log("New user created with ID: " . $userId);
    
            $this->enrollStudentInClass($userId, $classId);
            error_log("Student enrolled in courses of class ID: " . $classId);
    
            $passwordSetupUrl = $this->generateUrl($user->getEmail());
            $this->sendWelcomeEmail($user->getEmail(), $user->getFirstName(), $user->getLastName(), $passwordSetupUrl);            // Return the JSON response
            return [
                'success' => true,
                'user_id' => $userId
            ];
        } catch (Exception $e) {
            error_log("Error in UserController::register(): " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'An error occurred while registering the user.'
            ];
        }
    }

    public static function generateUrl($email)
{
    return BASE_URL.'SetPassword/email/'.urlencode($email);
}
    private function enrollStudentInClass($userId, $classId)
    {
        try {
            $coursesRepository = new CoursesRepository();
            $courses = $coursesRepository->getCoursesByClassId($classId);

            foreach ($courses as $course) {
                $coursesRepository->createAttendanceRecord($userId, $course['course_id']);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function sendWelcomeEmail($to, $firstName, $lastName, $passwordSetupUrl)
    {
        $subject = 'Welcome to Our Application';
        $message = "Bonjour " . $firstName . " " . $lastName . ",\n\n"
            . "Confirmation de votre inscription :\n\n"
            . "Merci de vous être inscrit à notre application.\n\n"
            . "Pour définir votre mot de passe, veuillez cliquer sur le lien suivant :\n"
            . $passwordSetupUrl . "\n\n"
            . "Cordialement,\n"
            . "L'équipe d'informatique";
    
        $headers = 'From: noreply@your-domain.com' . "\r\n" .
            'Reply-To: noreply@your-domain.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
    
        if (mail($to, $subject, $message, $headers)) {
            error_log("Welcome email sent to: " . $to);
        } else {
            error_log("Failed to send welcome email to: " . $to);
        }
    }

    public function setPassword($email)
{
    try {
        error_log("Attempting to set password for email: " . $email);

        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            error_log("User found for email: " . $email);

            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            error_log("Password: " . $password);
            error_log("confirm_password: " . $confirmPassword);

            if ($password === $confirmPassword) {
                error_log("Passwords match");

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $user->setPassword($hashedPassword);

                try {
                    error_log("Attempting to update password for user: " . $email);
                    $this->userRepository->updatePass($user);
                    error_log("Password updated successfully for user: " . $email);

                    return ['success' => true, 'message' => 'Password set successfully'];
                } catch (Exception $e) {
                    error_log('Error in UserController::setPassword() when updating password: ' . $e->getMessage());
                    error_log('Error details: ' . $e->getTraceAsString());
                    return ['success' => false, 'error' => 'An error occurred while setting the password'];
                }
            } else {
                error_log("Passwords do not match for user: " . $email);
                return ['success' => false, 'error' => 'Passwords do not match'];
            }
        } else {
            error_log("User not found for email: " . $email);
            return ['success' => false, 'error' => 'Invalid email or password already set'];
        }
    } catch (Exception $e) {
        error_log('Error in UserController::setPassword(): ' . $e->getMessage());
        error_log('Error details: ' . $e->getTraceAsString());
        return ['success' => false, 'error' => 'An error occurred while setting the password'];
    }
}

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /cours/Brief-GestionDeApp/login');
        exit();
    }

}