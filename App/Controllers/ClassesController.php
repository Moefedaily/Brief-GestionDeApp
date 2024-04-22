<?php
namespace Controllers;

use Repositories\ClassesRepository;

class ClassesController
{
    private $classesRepository;

    public function __construct()
    {
        $this->classesRepository = new ClassesRepository();
    }

    public function getClasses()
    {
        $classes = $this->classesRepository->getAllClasses();
        header('Content-Type: application/json');
        echo json_encode($classes);
        exit;
    }

    public function getClassForEditing($classId)
    {
        return $this->classesRepository->getClassById($classId);
    }
    
    public function updateClass($classId, $formData)
    {
        $formData['class_id'] = $classId;
        $result = $this->classesRepository->updateClass($formData);
    
        $response = [
            'success' => $result,
            'message' => $result ? 'Class updated successfully.' : 'Failed to update the class.'
        ];
    
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function createClass($formData)
{
    $result = $this->classesRepository->createClass($formData);

    $response = [
        'success' => $result,
        'message' => $result ? 'Class created successfully.' : 'Failed to create the class.'
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

    
    public function deleteClass($classId)
    {
        $result = $this->classesRepository->deleteClass($classId);

        $response = [
            'success' => $result,
            'message' => $result ? 'Class Deleted successfully.' : 'Failed to delete the class.'
        ];
    
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }


    public function getClassStudents($classId)
{
    $students = $this->classesRepository->getStudentsForClass($classId);
    $attendance = $this->classesRepository->getAttendanceForClass($classId);
    $classData = $this->classesRepository->getClassData($classId); 

    $data = [
        'students' => $students,
        'attendance' => $attendance,
        'classData' => $classData
    ];


    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
}