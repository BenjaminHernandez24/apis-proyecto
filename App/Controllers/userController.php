<?php
require_once __DIR__ . '/../Models/user.php';

class UserController {
    // Método para obtener todos los usuarios
    public function getAllUsers() {
        $userModel = new User(); 
        $users = $userModel->getAll();  // Obtener todos los usuarios de la base de datos

        if (empty($users)) {
            echo json_encode(["message" => "No hay usuarios"]);
            return;
        }

        echo json_encode($users);  
    }

    public function createUser($data) {
        $userModel = new User(); 
        
        // Validamos si todos los campos necesarios están presentes
        if (isset($data->name, $data->lastname, $data->email, $data->password, $data->rol)) {
            
            $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);  // Hasheamos la contraseña

            $userCreated = $userModel->create($data->name, $data->lastname, $data->email, $hashedPassword, $data->rol);
            
            if ($userCreated) {
                echo json_encode(["code" => 200,"success" => true, "message" => "Usuario creado con éxito"]);
            } else {
                echo json_encode(["error" => "Hubo un problema al crear el usuario"]);
            }
        } else {
            echo json_encode(["error" => "Faltan datos para crear el usuario"]);
        }
    }

    public function updateUser($data) {
        $userModel = new User(); 
        

        if (isset($data['name']) && isset($data['lastname']) && isset($data['email']) && isset($data['password'])) {
            
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);  // Hasheamos la contraseña

            $userCreated = $userModel->update($data['id'],$data['name'],$data['lastname'],$data['email'],$hashedPassword,$data['rol']);
            
            if ($userCreated) {
                echo json_encode(["code" => 200,"success" => true, "message" => "Usuario creado con éxito"]);
            } else {
                echo json_encode(["error" => "Hubo un problema al crear el usuario"]);
            }
        } else {
            echo json_encode(["error" => "Faltan datos para crear el usuario"]);
        }
    }

    public function deleteUser($id) {
        $userModel = new User(); 
        
        $userDeleted = $userModel->delete($id);
        
        if ($userDeleted) {
            echo json_encode(["code" => 200, "success" => true, "message" => "Usuario eliminado con éxito"]);
        } else {
            echo json_encode(["error" => "Hubo un problema al eliminar el usuario"]);
        }
    }
    

}
