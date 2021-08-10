<?php
    header("Content-Type: application/json");
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");


    if( isset($_GET['cat1'], $_GET['cat2'], $_GET['cat3']) ){
        if(!isset($_GET['id'])){
            $id = false;
        }else{
            $id = $_GET['id'];
        }
        if(!isset($_GET['items']) || $_GET['items'] == 'false'){
            $items = false;
        }else{
            $items = $_GET['items'];
        }

        if(!isset($_GET['paquete']) || !$_GET['paquete']){
            $paquete = false;
        }else{
            $paquete = $_GET['paquete'];
        }

        require_once 'class.php';
        $prueba = new DB(
            false,
            false,
            false,
            $_GET['cat1'], 
            $_GET['cat2'], 
            $_GET['cat3'], 
            $_SERVER['REQUEST_METHOD'],
            $paquete, 
            $id, 
            $items,
            file_get_contents('php://input')
        );

    }elseif( isset($_GET['session']) ){
        session_start();
        if( isset($_GET['user'], $_GET['pass']) ){
            $user = $_GET['user'];
            $pass = $_GET['pass'];
        }else{
            $user = false;
            $pass = false;
        }
        require_once 'class.php';
        $prueba = new DB(
            $_GET['session'],
            $user,
            $pass,
            false, 
            false, 
            false, 
            $_SERVER['REQUEST_METHOD'],
            false, 
            false, 
            false,
            file_get_contents('php://input'));
        // session_destroy();


    }
?>