<?php
    header('Content-Type: application/json');
    switch($_SERVER['REQUEST_METHOD']){

        case 'POST':
            $resultado['mensaje'] = $_POST = json_decode(file_get_contents('php://input'), true);
            echo(json_encode($resultado));
        break;
        case 'GET':
            if(isset($_GET['id'])){
                $resultado['mensaje'] = "Parametro GET: ".$_GET['id'];
            }else{
                $resultado['mensaje'] = "Sin parametros";
            }
            echo json_encode($resultado);
        break;
        case 'PUT':
            $resultado['mensaje'] = "Editar el usuario con el id: ". $_GET['id'].
             "informacion a actualizar". $_PUT = file_get_contents('php://input');
            
            echo json_encode($resultado);
        break;
        
        case 'DELETE':
            $resultado['mensaje'] = "ELiminar el usuario con el id: ".$_GET['id'];
            echo json_encode($resultado);
        break;         

    }

?>