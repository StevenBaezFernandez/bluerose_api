<?php
    header("Content-Type: application/json");
    if( isset($_GET['cat1'], $_GET['cat2'], $_GET['cat3']) ){
        if(!isset($_GET['id'])){
            $id = false;
        }else{
            $id = $_GET['id'];
        }

        require_once 'class.php';
        $prueba = new DB(
            $_GET['cat1'], 
            $_GET['cat2'], 
            $_GET['cat3'], 
            $_SERVER['REQUEST_METHOD'], 
            $id, 
            file_get_contents('php://input')
        );

    }




?>