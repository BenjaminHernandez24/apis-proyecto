<?php
use Firebase\JWT\JWT;

class TokenHelper {
    private static $secretKey = "aV3ryL0ngAndC0mpl3xSecreTKey-12345!@#";

    // Validar el token JWT
    public static function validateToken($token) {
        try {

            $decoded = JWT::decode($token, self::$secretKey, array('HS256'));

            // Verificar si el token ha expirado
            if (isset($decoded->exp) && time() > $decoded->exp) {
                return ["error" => "Token ha expirado"];
            }

            return $decoded;

        } catch (Exception $e) {
            // Si ocurre un error durante la decodificación, el token es inválido
            return ["error" => "Token inválido"];
        }
    }
}
