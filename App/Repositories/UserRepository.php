<?php
namespace Repositories;

use DbConnexion\Db;
use Models\User;
use PDO;

class UserRepository
{
    private $db;
    private $user;

    public function __construct()
    {
        $database = new Db;
        $this->db = $database->getDB();
        $this->user = new User();
        

        require_once __DIR__ . '../../../config/database.php';
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM gda_users WHERE email = ?");
        $stmt->execute([$email]);
        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($userData) {
            return new User($userData);
        }
        
        return null;
    }

    public function create(User $user)
    {
        $stmt = $this->db->prepare("INSERT INTO gda_users (first_name, last_name, email, password, activation, role_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getActivation(),
            $user->getRoleid()
        ]);
        
        return $this->db->lastInsertId();
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM gda_users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return null;
    }

    public function register($userData)
    {
        $query = "SELECT COUNT(*) FROM gda_users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            return false; // Email already exists
        }
        
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        $query = "INSERT INTO gda_users (first_name, last_name, email, password, activation, role_id) 
                  VALUES (:first_name, :last_name, :email, :password, :activation, :role_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':first_name', $userData['first_name']);
        $stmt->bindParam(':last_name', $userData['last_name']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindValue(':activation', 0); 
        $stmt->bindValue(':role_id', 0); 
        
        return $stmt->execute();
    }
    public function getUserById($userId)
    {
        $query = "SELECT * FROM gda_users WHERE user_id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            $user = new User();
            $user->setUserid($userData['user_id']);
            $user->setFirstName($userData['first_name']);
            $user->setLastName($userData['last_name']);
            $user->setEmail($userData['email']);
            $user->setRoleid($userData['role_id']);

            return $user;
        }

        return null;
    }

    public function findByActivationCode($activationCode)
{
    $query = "SELECT * FROM gda_users WHERE activation = :activationCode";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':activationCode', $activationCode);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData) {
        return new User($userData);
    }

    return null;
}
public function updatePass(User $user)
    {
        $stmt = $this->db->prepare("UPDATE gda_users SET password = ? WHERE user_id = ?");
        $stmt->execute([
            $user->getPassword(),
            $user->getUserId()
        ]);
    }
}
