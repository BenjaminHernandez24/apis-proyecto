<?php
require_once __DIR__ . '/../Config/database.php';


class Publications {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /*Función para enviar todo los usuarios */
    public function getAll() {
        $query = "SELECT pub.id,pub.title,pub.description,pub.create_at,user.name,rol.name_role FROM publicaciones as pub inner join users as user on pub.user = user.id inner join roles as rol on user.id_role = rol.id where pub.status = 1";

        $result = $this->conn->query($query);
    
        $publications = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $publications[] = $row; 
        }

        return $publications;
    }

    public function create($title, $description, $user) {
        // Preparar la consulta para insertar el nuevo usuario
        $query = "INSERT INTO publicaciones (title, description, user) VALUES (:title, :description, :user)";
        
        // Preparar la declaración con PDO
        $stmt = $this->conn->prepare($query);
    
        // Asignar valores a los parámetros
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':user', $user);
    
        // Ejecutar la declaración
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update($id, $title, $description) {
        // Preparamos la consulta para actualizar
        $stmt = $this->conn->prepare("UPDATE publicaciones SET title = :title, description= :description WHERE id = :id");
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function deactivate($id) {
        // Preparamos la consulta para actualizar el campo "status" a 0
        $stmt = $this->conn->prepare("UPDATE publicaciones SET status = 0 WHERE id = :id");
        $stmt->bindParam(":id", $id);
        
        // Ejecutamos la consulta y devolvemos el resultado
        return $stmt->execute();
    }
    
    
}
