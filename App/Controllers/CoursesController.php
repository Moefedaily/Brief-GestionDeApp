<?php
namespace Controllers;

use DateTime;
use DateTimeZone;
use Exception;
use Repositories\ClassesRepository;
use Repositories\CoursesRepository;

class CoursesController
{
    private $coursesRepository;

    public function __construct()
    {
        $this->coursesRepository = new CoursesRepository();
    }


    /************************* Get Courses ***************************/
    public function getCourses()
    {
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'];
    
        if ($userRole === 1) {
            $courses = $this->coursesRepository->getStudentCourses($userId);
            // student
        } elseif ($userRole === 2) {
            $courses = $this->coursesRepository->getTrainerCourses($userId);
            // trainer
        } elseif ($userRole === 3) {
            $courses = $this->coursesRepository->getAllCourses();
            // responsible
        }
    

        $attendanceData = array_map(function ($course) use ($userId) {
            $isAttendanceValidated = $this->coursesRepository->isAttendanceValidated($userId, $course['course_id']);
            $attendanceStatus = $this->coursesRepository->getAttendanceStatus($userId, $course['course_id']);
            $randomCode = $this->coursesRepository->getCourseRandomCode($course['course_id']);
    
            return [
                'course_id' => $course['course_id'],
                'isAttendanceValidated' => $isAttendanceValidated,
                'presence' => $attendanceStatus ? $attendanceStatus['presence'] : null,
                'delay' => $attendanceStatus ? $attendanceStatus['delay'] : null,
                'randomCode' => $randomCode,
            ];
        }, $courses);
    
        $classesRepository = new ClassesRepository();
        $classes = $classesRepository->getAllClasses();
        
        $data = [
            'courses' => $courses,
            'attendanceData' => $attendanceData,
            'userRole' => $userRole,
            'classes' => $classes
        ];
    
        return $data;
    }


    public function checkAllStudentsAttended($courseId)
{
    try {
        $allAttended = $this->coursesRepository->checkAllStudentsAttended($courseId);
        $response = [
            'allAttended' => $allAttended
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } catch (Exception $e) {
        error_log('Error in checkAllStudentsAttended: ' . $e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
        exit;
    }
}
    /************************  Dashboard Data *********************************/

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

    /************************ Validate Trainer Attendance **********************************/

    public function validateTrainerAttendance($courseId)
    {

        error_log("Validating attendance for course ID: " . $courseId);
        try {
            $userId = $_SESSION['user_id'];
            $userRole = $_SESSION['role'];
    
            if ($userRole !== 2) {
                return ['error' => 'Not ALLOWED TO BE HERE'];
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

    /************************  Validate Student Attendance *********************************/

    public function validateStudentAttendance($courseId)
{
    try {
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role']; 

        if ($userRole !== 1) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $submittedCode = $data['attendanceCode'];

        error_log("Controller -> Submitted code: " . $submittedCode);

        $randomCode = $this->coursesRepository->getCourseRandomCode($courseId);

        error_log("Controller -> Random code: " . $randomCode);

        if ((string)$submittedCode !== (string)$randomCode) {
            error_log("Comparison result: " . ((string)$submittedCode !== (string)$randomCode));
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid attendance code']);
            exit;
        }


        // to redo the caluclation
        $course = $this->coursesRepository->getCourseById($courseId);
        error_log('Course log: ' . print_r($course, true));

        $courseStartTime = DateTime::createFromFormat('H:i:s', $course['course_start_time']);
        error_log('course Time '.$course['course_start_time']);

        $currentDateTime = new DateTime('now', new DateTimeZone('Europe/Paris')); 

        error_log('curren Time '.print_r($currentDateTime,true));
        
        $timeDifference = $currentDateTime->getTimestamp() - $courseStartTime->getTimestamp();
        error_log('Time diffrence '.$timeDifference);


        
        // time to minutes
        $minutesDifference = $timeDifference / 60;

        error_log('Time Minures '.$minutesDifference);
        
        if ($minutesDifference <= 15) {
            $this->coursesRepository->markStudentAttendance($userId, $courseId, false);
            $response = [
                'status' => 'success',
                'message' => 'Attendance marked as present'
            ];
        } else {
            $this->coursesRepository->markStudentAttendance($userId, $courseId, true);
            $response = [
                'status' => 'success',
                'message' => 'Attendance marked as late'
            ];
        }
        //End Of the Calculation!!!! 

        header('Content-Type: application/json');
        echo json_encode($response);
        error_log('Response data: ' . print_r($response, true));
        exit;
    } catch (Exception $e) {
        error_log('Error in validateStudentAttendance: ' . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal server error']);
        exit;
    }
}
}