<?php
require_once __DIR__ . '/../Config/database.php';


class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /*Función para buscar si el email ingresado existe en la base de datos*/
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email and status = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*Función para enviar todo los usuarios */
    public function getAll() {
        $query = "SELECT users.id,users.name,users.last_name,users.email,roles.name_role FROM users as users inner join roles as roles on users.id_role = roles.id where users.status = 1";

        $result = $this->conn->query($query);
    
        $users = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row; 
        }

        return $users;
    }

    public function create($name, $lastname, $email, $password, $rol) {
        // Preparar la consulta para insertar el nuevo usuario
        $query = "INSERT INTO users (name, last_name, email, password, id_role) VALUES (:name, :lastname, :email, :password, :rol)";
        
        // Preparar la declaración con PDO
        $stmt = $this->conn->prepare($query);
    
        // Asignar valores a los parámetros
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':rol', $rol);
    
        // Ejecutar la declaración
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update($id,$name, $lastname, $email, $password, $rol) {
        // Preparar la consulta para insertar el nuevo usuario
        $query = "UPDATE users SET name = :name, last_name = :lastname, email = :email, password = :password, id_role = :rol WHERE id = :id";
        
        // Preparar la declaración con PDO
        $stmt = $this->conn->prepare($query);
    
        // Asignar valores a los parámetros
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':id', $id);
    
        // Ejecutar la declaración
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("UPDATE users SET status = 0 WHERE id = :id");
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
    
}
