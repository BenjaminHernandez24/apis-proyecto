<?php
require_once __DIR__ . '/../Models/publication.php';

class PublicationController {

    public function getAllPublication() {
        $publicationModel = new Publications(); 
        $publications = $publicationModel->getAll(); 

        if (empty($publications)) {
            echo json_encode(["message" => "No hay publicaciones"]);
            return;
        }

        echo json_encode($publications);  
    }

    public function createPublication($data) {
        $publicationModel = new Publications();  
        
        if (isset($data->title, $data->description, $data->user)) {

            $publicationCreated = $publicationModel->create($data->title, $data->description, $data->user);
            
            if ($publicationCreated) {
                echo json_encode(["code" => 200,"success" => true, "message" => "Publicación creada con éxito"]);
            } else {
                echo json_encode(["error" => "Hubo un problema al crear la publicación"]);
            }
        } else {
            echo json_encode(["error" => "Faltan datos para crear la publicación"]);
        }
    }

    public function updatePublication($id, $title, $description) {
        $publicationModel = new Publications();  
        
        $publicationDeleted = $publicationModel->update($id, $title, $description);
        
        if ($publicationDeleted) {
            echo json_encode(["code" => 200, "success" => true, "message" => "publicacion modificado con éxito"]);
        } else {
            echo json_encode(["error" => "Hubo un problema al modificar la publicacion"]);
        }
    }

    public function deactivatePublication($id) {
        $publicationModel = new Publications();  
        
        $publicationDeleted = $publicationModel->deactivate($id);
        
        if ($publicationDeleted) {
            echo json_encode(["code" => 200, "success" => true, "message" => "publicacion eliminado con éxito"]);
        } else {
            echo json_encode(["error" => "Hubo un problema al eliminar la publicacion"]);
        }
    }
    

}
