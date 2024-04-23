<?php
namespace Repositories;

use DbConnexion\Db;
use Models\Classes;
use PDO;

class ClassesRepository
{
    private $db;

    public function __construct()
    {
        $database = new Db();
        $this->db = $database->getDB();

        require_once __DIR__ . '/../../config/database.php';
    }

     /************************* All Classes ****************************/

    public function getAllClasses()
    {
        
        $query = "
            SELECT *
            FROM gda_classes
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /************************* Delete Class ****************************/


    public function deleteClass($classId)
    {
        $sql = "DELETE FROM gda_courses WHERE class_id = :classId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
        $stmt->execute();
    
        $sql = "DELETE FROM gda_classes WHERE class_id = :classId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
        return $stmt->execute();
    }


    /************************* get class by id ****************************/

    public function getClassById($classId)
    {
        $sql = "SELECT * FROM gda_classes WHERE class_id = :classId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Classes($row);
        }

        return null;
    }

    /************************* Update Class ****************************/


    public function updateClass($classData)
{
    $classId = $classData['class_id'];
    $className = $classData['class_name'];
    $startDate = $classData['class_start_date'];
    $endDate = $classData['class_end_date'];
    $availablePlaces = $classData['places_available'];

    $sql = "UPDATE gda_classes SET
                class_name = :className,
                class_start_date = :startDate,
                class_end_date = :endDate,
                places_available = :availablePlaces
            WHERE class_id = :classId";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':className', $className, PDO::PARAM_STR);
    $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
    $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
    $stmt->bindParam(':availablePlaces', $availablePlaces, PDO::PARAM_INT);
    $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);

    return $stmt->execute();
}

public function createClass($classData)
{
    $className = $classData['class_name'];
    $startDate = $classData['start_date'];
    $endDate = $classData['end_date'];
    $availablePlaces = $classData['places_available'];

    $sql = "INSERT INTO gda_classes (class_name, class_start_date, class_end_date, places_available)
            VALUES (:className, :startDate, :endDate, :availablePlaces)";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':className', $className, PDO::PARAM_STR);
    $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
    $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
    $stmt->bindParam(':availablePlaces', $availablePlaces, PDO::PARAM_INT);

    return $stmt->execute();
}

public function getStudentsForClass($classId)
    {
        $sql = "SELECT u.user_id, u.first_name, u.last_name, u.email, r.role_name
                FROM gda_users u
                JOIN gda_user_class uc ON u.user_id = uc.user_id
                JOIN gda_roles r ON u.role_id = r.role_id
                WHERE uc.class_id = :classId AND r.role_name = 'Apprenant'";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function getAttendanceForClass($classId)
{
    $sql = "SELECT u.user_id, u.first_name, u.last_name, a.course_id, a.presence, a.delay
            FROM gda_users u
            JOIN gda_roles r ON u.role_id = r.role_id
            JOIN gda_user_class uc ON u.user_id = uc.user_id
            JOIN gda_attendance a ON u.user_id = a.user_id
            JOIN gda_courses c ON a.course_id = c.course_id
            WHERE uc.class_id = :classId AND c.class_id = :classId AND r.role_name = 'Apprenant' AND (a.presence IS NOT NULL OR a.delay IS NOT NULL)";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getClassData($classId)
{
    $sql = "SELECT c.class_id, c.class_name, c.class_start_date, c.class_end_date
            FROM gda_classes c
            WHERE c.class_id = :classId";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return $row;
    }

    return null;
}
}