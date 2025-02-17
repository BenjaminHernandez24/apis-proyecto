<?php
require_once __DIR__ . '/../Models/user.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {
    private $secretKey = "aV3ryL0ngAndC0mpl3xSecreTKey-12345!@#";

    /*Función para hacer el logueo*/
    public function login($email, $password) {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        
        if (!$user || !password_verify($password, $user['password'])) {
            echo json_encode(["error" => "Usuario o contraseña incorrectos"]);
            return;
        }


        $payload = [
            "email" => $user['email'],
            "exp" => time() + (60 * 60)
        ];

        $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
        echo json_encode(["token" => $jwt, "id_user" => $user['id'], "rol" => $user['id_role']]);
    }
}
