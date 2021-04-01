<?php
    class DB extends mysqli{

        private $host = "localhost";
        private $user = "root";
        private $pass = "";
        private $db_name = "bluerose_db";

        public $cat1;
        public $cat2;
        public $cat3;
        public $method;
        public $id;
        public $datos;

        public function __construct($cat1, $cat2, $cat3, $method, $id=false, $datos=false){

            parent:: __construct($this -> host, $this->user, $this->pass, $this-> db_name);

            $this -> cat1 = $cat1;
            $this -> cat2 = $cat2;
            $this -> cat3 = $cat3;
            $this -> method = $method;
            $this -> id = $id;
            $this -> datos = json_decode($datos, true);

            switch($this -> method){
                case 'POST':
                   echo $this -> agregar();
                break;
                case 'GET':
                   echo $this -> obtener();
                break;
                case 'PUT':
                   echo $this -> editar();
                break;
                case 'DELETE':
                   echo $this -> eliminar();
                break;
            }            
        }

        private function agregar(){                
            $resul['mensaje'] = $this -> Query("INSERT INTO ".$this -> cat3." (id_img, descripcion_img, url_img, id_categoria1, id_categoria2) VALUES ('".uniqid()."', '".$this -> datos['descripcion_img']."', '".$this -> datos['url_img']."', ".$this -> datos['id_cat1'].", ".$this -> datos['id_cat2'].")");
            return json_encode($resul);
        }
        private function obtener(){
            if(!$this -> id){
                $resul = $this -> Query("SELECT id_img, descripcion_img, url_img, categoria1.nombre_cat1, categoria1.descripcion_cat1,categoria2.nombre_cat2, categoria2.descripcion_cat2 FROM ".$this -> cat3." INNER JOIN categoria1 ON ".$this -> cat3.".id_categoria1 = categoria1.id_cat1 INNER JOIN categoria2 ON ".$this -> cat3.".id_categoria2 = categoria2.id_cat2 WHERE categoria1.nombre_cat1 = '".$this -> cat1."' AND categoria2.nombre_cat2 = '".$this -> cat2."'");
            }else{
                $resul = $this -> Query("SELECT id_img, descripcion_img, url_img, categoria1.nombre_cat1, categoria1.descripcion_cat1,categoria2.nombre_cat2, categoria2.descripcion_cat2 FROM ".$this -> cat3." INNER JOIN categoria1 ON ".$this -> cat3.".id_categoria1 = categoria1.id_cat1 INNER JOIN categoria2 ON ".$this -> cat3.".id_categoria2 = categoria2.id_cat2 WHERE id_img = '".$this -> id."'");
            }
            $data = [];
            while($row = mysqli_fetch_array($resul)){
                $new_data['id_img'] = $row['id_img'];
                $new_data['descripcion_img'] = $row['descripcion_img'];
                $new_data['url_img'] = $row['url_img'];
                $new_data['nombre_cat1'] = $row['nombre_cat1'];
                $new_data['nombre_cat2'] = $row['nombre_cat2'];

                array_push($data, $new_data);
            }

            return json_encode($data);
        }
        private function editar(){
            $resul['mensaje'] = $this -> Query("UPDATE ".$this -> cat3." SET descripcion_img = '".$this -> datos['descripcion_img']."', url_img = '".$this -> datos['url_img']."', id_categoria1 = ".$this -> datos['id_cat1'].", id_categoria2 = ".$this -> datos['id_cat2']." WHERE id_img = '".$this -> id."'");
            return json_encode($resul);
        }
        private function eliminar(){
            $resul['mensaje'] = $this -> Query("DELETE FROM ".$this -> cat3." WHERE id_img = '".$this -> id."'");
            return json_encode($resul);
        }
    }
    



?>