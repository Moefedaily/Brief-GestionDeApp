<?php
namespace Repositories;

use DbConnexion\Db;
use Models\Courses;
use PDO;

class CoursesRepository
{
    private $db;
    private $courses;

    public function __construct()
    {
        $database = new Db();
        $this->db = $database->getDB();
        $this->courses = new Courses();

        require_once __DIR__ . '/../../config/database.php';
    }
    public function getAllCourses()
        {
            $query = "
                SELECT
                    c.class_name,
                    c.places_available,
                    co.course_date,
                    co.course_id
                FROM
                    gda_classes c
                INNER JOIN
                    gda_courses co ON c.class_id = co.class_id
            ";
        
            $stmt = $this->db->prepare($query);
            $stmt->execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        
/************************************************************************************** */

        public function getStudentCourses($userId)
{
    $query = "
        SELECT
            c.class_name,
            c.places_available,
            co.course_date,
            co.course_id
        FROM
            gda_classes c
        INNER JOIN
            gda_courses co ON c.class_id = co.class_id
        INNER JOIN
            gda_attendance a ON co.course_id = a.course_id
        WHERE
            a.user_id = :userId
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/************************************************************************************** */

    public function isAttendanceValidated($userId, $courseId)
{
    $query = "
        SELECT COUNT(*) AS count 
        FROM gda_attendance
        WHERE user_id = :userId AND course_id = :courseId
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['count'] > 0;
}

/************************************************************************************** */

public function generateRandomCode()
{
    return mt_rand(10000, 99999);
}

/************************************************************************************** */

public function updateCourseRandomCode($courseId, $randomCode)
{
    $query = "
        UPDATE gda_courses
        SET course_randomCode = :randomCode
        WHERE course_id = :courseId
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':randomCode', $randomCode, PDO::PARAM_INT);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();
}

/************************************************************************************** */

public function getCourseRandomCode($courseId)
{
    $query = "
        SELECT course_randomCode
        FROM gda_courses
        WHERE course_id = :courseId
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['course_randomCode'] : null;
}

/************************************************************************************** */

public function markTrainerAttendance($userId, $courseId)
{
    // check if the attendance record already exists
    $existingAttendance = $this->getAttendanceRecord($userId, $courseId);

    if ($existingAttendance) {
        // Update the record
        $query = "
            UPDATE gda_attendance
            SET attend_date = CURDATE(), attend_status = 'present'
            WHERE user_id = :userId AND course_id = :courseId
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Insert  new record
        $query = "
            INSERT INTO gda_attendance (user_id, course_id, attend_date, attend_status)
            VALUES (:userId, :courseId, CURDATE(), 'present')
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

/************************************************************************************** */

private function getAttendanceRecord($userId, $courseId)
{
    $query = "
        SELECT *
        FROM gda_attendance
        WHERE user_id = :userId AND course_id = :courseId
    ";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/************************************************************************************** */

public function getAttendanceStatus($userId, $courseId)
{
    $query = "
        SELECT attend_status
        FROM gda_attendance
        WHERE user_id = :userId AND course_id = :courseId
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['attend_status'] : null;
}

/************************************************************************************** */

public function getCourseById($courseId)
{
    $query = "
        SELECT *
        FROM gda_courses
        WHERE course_id = :courseId
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/************************************************************************************** */

// ...

public function markStudentAttendance($userId, $courseId)
{
    $existingAttendance = $this->getStudentAttendanceRecord($userId, $courseId);

    if ($existingAttendance) {
        $query = "
            UPDATE gda_attendance
            SET attend_date = CURDATE(), attend_status = 'present'
            WHERE user_id = :userId AND course_id = :courseId
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $query = "
            INSERT INTO gda_attendance (user_id, course_id, attend_date, attend_status)
            VALUES (:userId, :courseId, CURDATE(), 'present')
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

/************************************************************************************** */

public function markStudentLate($userId, $courseId)
{
    $existingAttendance = $this->getStudentAttendanceRecord($userId, $courseId);

    if ($existingAttendance) {
        $query = "
            UPDATE gda_attendance
            SET attend_date = CURDATE(), attend_status = 'late'
            WHERE user_id = :userId AND course_id = :courseId
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $query = "
            INSERT INTO gda_attendance (user_id, course_id, attend_date, attend_status)
            VALUES (:userId, :courseId, CURDATE(), 'late')
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
/************************************************************************************** */

private function getStudentAttendanceRecord($userId, $courseId)
{
    $query = "
        SELECT *
        FROM gda_attendance
        WHERE user_id = :userId AND course_id = :courseId
    ";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/************************************************************************************** */


public function getCoursesByClassId($classId)
{
    $query = "SELECT * FROM gda_courses WHERE class_id = :classId";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/************************************************************************************** */



public function createAttendanceRecord($userId, $courseId)
{
    $query = "INSERT INTO gda_attendance (user_id, course_id) VALUES (:userId, :courseId)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();
}
}