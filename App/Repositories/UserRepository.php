<?php
namespace Repositories;

use DbConnexion\Db;
use Models\User;
use PDO;
use PDOException;

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
        try {
            $stmt = $this->db->prepare("
                INSERT INTO gda_users (first_name, last_name, password, email, activation, role_id)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
    
            $stmt->execute([
                $user->getFirstName(),
                $user->getLastName(),
                $user->getPassword(),
                $user->getEmail(),
                $user->getActivation(),
                $user->getRoleid()
            ]);
    
            $userId = $this->db->lastInsertId();
            error_log("User created with ID: " . $userId);
    
            return $userId;
        } catch (PDOException $e) {
            error_log("Error in UserRepository::create(): " . $e->getMessage());
            throw $e;
        }
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

            error_log("userREPO:: getUserById : " .print_r($user,true));

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

    public function addUserInClass($userId, $classId)
    {
        $stmt = $this->db->prepare("INSERT INTO gda_user_class (user_id, class_id) VALUES (?, ?)");
        $stmt->execute([$userId, $classId]);
    }

public function update(User $user)
{
    $stmt = $this->db->prepare("
        UPDATE gda_users SET 
            first_name = ?,
            last_name = ?,
            email = ?,
            activation = ?,
            role_id = ?
        WHERE user_id = ?
    ");

    $stmt->execute([
        $user->getFirstName(),
        $user->getLastName(),
        $user->getEmail(),
        $user->getActivation(),
        $user->getRoleid(),
        $user->getUserId()
    ]);
}


public function delete($userId)
{
    $sql = "DELETE FROM gda_attendance WHERE user_id = :userId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $sql = "DELETE FROM gda_user_class WHERE user_id = :userId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $user = $this->getUserById($userId);
    error_log("user delete function : " . print_r($user, true));

    $sql = "DELETE FROM gda_users WHERE user_id = :userId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    return $stmt->execute();
}

}

