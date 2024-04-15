<?php
namespace Controllers;

use Exception;
use Repositories\CoursesRepository;

class CoursesController
{
    private $coursesRepository;

    public function __construct()
    {
        $this->coursesRepository = new CoursesRepository();
    }
    public function getCourses()
    {
        
        $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['role'];

    if ($userRole === 0) {
        $courses = $this->coursesRepository->getStudentCourses($userId);
    } elseif($userRole === 1) {
        $courses = $this->coursesRepository->getAllCourses();
    }
        $attendanceData = array_map(function ($course) use ($userId) {
            $isAttendanceValidated = $this->coursesRepository->isAttendanceValidated($userId, $course['course_id']);
            $attendanceStatus = $this->coursesRepository->getAttendanceStatus($userId, $course['course_id']);
            $randomCode = $this->coursesRepository->getCourseRandomCode($course['course_id']);
            return [
                'course_id' => $course['course_id'],
                'isAttendanceValidated' => $isAttendanceValidated,
                'attendanceStatus' => $attendanceStatus,
                'randomCode' => $randomCode,
            ];
        }, $courses);
    
        $data = [
            'courses' => $courses,
            'attendanceData' => $attendanceData,
            'userRole' => $userRole,
        ];
        return $data;
    }
    public function getDashboardData()
{
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        return ['error' => 'Unauthorized'];
    }

    $data = $this->getCourses();

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
    
    public function validateTrainerAttendance($courseId)
    {

        error_log("Validating attendance for course ID: " . $courseId);
        try {
            $userId = $_SESSION['user_id'];
            $userRole = $_SESSION['role'];
    
            if ($userRole !== 1) {
                return ['error' => 'Unauthorized'];
            }
    
            $randomCode = $this->coursesRepository->generateRandomCode();

            
            $this->coursesRepository->updateCourseRandomCode($courseId, $randomCode);
    
            $this->coursesRepository->markTrainerAttendance($userId, $courseId);
    
            $response = [
                'status' => 'success',
                'randomCode' => $randomCode
            ];
    
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } catch (Exception $e) {
            error_log('Error in validate/Attendance: ' . $e->getMessage());
            return ['error' => 'server error'];
        }
    }

    public function validateStudentAttendance($courseId)
{
    try {
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role']; 

        if ($userRole !== 0) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $submittedCode = $data['attendanceCode'] ?? null;

        error_log("Controller->Submitted code: " . $submittedCode);

        $randomCode = $this->coursesRepository->getCourseRandomCode($courseId);

        error_log("Controller->Random code: " . $randomCode);

        if ((string)$submittedCode !== (string)$randomCode) {
            error_log("Comparison result: " . ((string)$submittedCode !== (string)$randomCode));
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid attendance code']);
            exit;
        }

        $course = $this->coursesRepository->getCourseById($courseId);
        $courseStartTime = $course['course_startTime'];
        $currentTime = date('H:i:s');
        $timeDifference = strtotime($currentTime) - strtotime($courseStartTime);

        if ($timeDifference <= 15 * 60) {
            $this->coursesRepository->markStudentAttendance($userId, $courseId);
            $response = [
                'status' => 'success',
                'message' => 'Attendance marked'
            ];
        } else {
            $this->coursesRepository->markStudentLate($userId, $courseId);
            $response = [
                'status' => 'success',
                'message' => 'Attendance marked as late'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } catch (Exception $e) {
        error_log('Error in validateStudentAttendance: ' . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal server error']);
        exit;
    }
}
}