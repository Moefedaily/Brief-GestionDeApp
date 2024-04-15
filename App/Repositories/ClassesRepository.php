<?php
namespace Repositories;

use DbConnexion\Db;
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
    public function getAllClasses()
{
    $query = "
        SELECT class_id, class_name
        FROM gda_classes
    ";

    $stmt = $this->db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}