<?php
// Incluir los controladores
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';
require_once __DIR__ . '/../app/Controllers/PublicationController.php';
require_once __DIR__ . '/../app/Helpers/TokenHelper.php'; 


$requestMethod = $_SERVER["REQUEST_METHOD"];
$data = json_decode(file_get_contents("php://input")); // Cargar los datos JSON enviados en la solicitud

// Instanciar controladores
$authController = new AuthController();
$userController = new UserController();
$publicationController = new PublicationController();

// Obtener el token desde el encabezado de la solicitud (Authorization)
$headers = apache_request_headers();
$token = isset($headers['Authorization']) ? $headers['Authorization'] : null; // El token se pasa como Authorization en el header

// Verificar el token solo si la acción no es 'login'
if (!(isset($data->action) && $data->action === 'login')) {
    if ($token) {
        $isValidToken = TokenHelper::validateToken($token);  // Suponiendo que tienes una función para validar el token

        if (!$isValidToken) {
            echo json_encode(["error" => "Token inválido"]);
            exit;
        }
    } else {
        echo json_encode(["error" => "Token no proporcionado"]);
        exit;
    }
}

switch ($requestMethod) {
    case 'POST':
        // Acción específica del POST
        if (isset($data->action)) {
            $action = $data->action;
        } else {
            echo json_encode(["error" => "Acción no especificada"]);
            exit;
        }

        switch ($action) {
            case 'login':
                // Validar los datos para login (No requiere token)
                if (isset($data->email) && isset($data->password)) {
                    $authController->login($data->email, $data->password);
                } else {
                    echo json_encode(["error" => "Faltan datos para login"]);
                }
                break;

            case 'create_user':
                // Crear el usuario
                if (isset($data->name) && isset($data->lastname) && isset($data->email) && isset($data->password)) {
                    $userController->createUser($data);
                } else {
                    echo json_encode(["error" => "Faltan datos para crear el usuario"]);
                }
                break;

            case 'create_publication':
                    if (isset($data->title) && isset($data->description) && isset($data->user)) {
                        $publicationController->createPublication($data);
                    } else {
                        echo json_encode(["error" => "Faltan datos para crear el usuario"]);
                    }
                    break;

            default:
                echo json_encode(["error" => "Acción no válida"]);
                break;
        }
        break;

    case 'GET':
        // Acción específica del GET
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            switch ($action) {
                case 'users':
                    $userController->getAllUsers();
                    break;
                case 'publications':
                    $publicationController->getAllPublication();
                    break;
                default:
                    echo json_encode(["error" => "Acción no válida"]);
                    break;
            }
        } else {
            echo json_encode(["error" => "Acción no especificada"]);
        }
        break;

    case 'PUT':
        // Asegúrate de que el action y el id estén en el cuerpo de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'deactivate_publication':
                    if (isset($data['id'])) {
                        // Llama a la función para desactivar la publicación
                        $publicationController->deactivatePublication($data['id']);
                    } else {
                        echo json_encode(["error" => "ID no proporcionado"]);
                    }
                    break;

                case 'delete_user':
                        if (isset($data['id'])) {
                            $userController->deleteUser($data['id']);
                        } else {
                            echo json_encode(["error" => "ID no proporcionado"]);
                        }
                        break;
                
                case 'update_user':
                    if (isset($data['name']) && isset($data['lastname']) && isset($data['email']) && isset($data['password']) && isset($data['rol']) ) {
                        // Llama a la función para actualizar la publicación
                        $userController->updateUser($data);
                    } else {
                        echo json_encode(["error" => "ID o nuevos datos no proporcionados"]);
                    }
                    break;
                    
                case 'update_publication':
                    if (isset($data['id']) && isset($data['title']) && isset($data['description'])) {
                        // Llama a la función para actualizar la publicación
                        $publicationController->updatePublication($data['id'], $data['title'], $data['description']);
                    } else {
                        echo json_encode(["error" => "ID o nuevos datos no proporcionados"]);
                    }
                    break;
            }
        } else {
            echo json_encode(["error" => "Acción no especificada"]);
        }
        break;        

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>
