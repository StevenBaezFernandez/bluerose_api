<?php
    class DB extends mysqli{

        private $host = "localhost";
        private $user = "root";
        private $pass = "";
        private $db_name = "bluerose_db";

        public $cat1;
        public $cat2;
        public $index_cat1;
        public $index_cat2;
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

            $this -> obtener_index_cat();
            
            switch($this -> method){
                case 'POST':
                   echo( $this -> agregar());
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
            switch($this ->cat3){
                case 'galeria':
                    return $this -> agregar_galeria();                
                break;
                case 'items-paquetes':
                    return $this -> agregar_item_paq();
                break;  
                case 'paquetes':
                    return $this -> agregar_paquete();
                break; 
                case 'proveedores':
                    return $this -> agregar_proveedores();
                break;
            }
        }
        private function obtener(){            
            switch($this -> cat3){                
                case 'galeria':
                   return $this -> obtener_galeria();         
                break;
                case 'items-paquetes':
                    return $this -> obtener_item_paq();
                break;
                case 'paquetes':
                    return $this -> obtener_paquete();
                break; 
                case 'proveedores':
                    return $this -> obtener_proveedores();
                break;
            }
            
        }
        private function editar(){
            switch($this -> cat3){                
                case 'galeria':
                   return $this -> editar_galeria();         
                break;
                case 'items-paquetes':
                    return $this -> editar_item_paq();
                break;
                case 'paquetes':
                    return $this -> editar_paquete();
                break; 
                case 'proveedores':
                    return $this -> editar_proveedores();
                break;
            }
        }
        private function eliminar(){
            switch($this -> cat3){                
                case 'galeria':
                   return $this -> eliminar_galeria();         
                break;
                case 'items-paquetes':
                    return $this -> eliminar_item_paq();
                break;
                case 'paquetes':
                    return $this -> eliminar_paquete();
                break; 
                case 'proveedores':
                    return $this -> eliminar_proveedores();
                break;
            }
        }

        private function obtener_index_cat(){
            $resul_cat1 = $this -> Query("SELECT id_cat1 FROM categoria1 WHERE nombre_cat1 = '".$this -> cat1."'");
            $resul_cat2 = $this -> Query("SELECT id_cat2 FROM categoria2 WHERE nombre_cat2 = '".$this -> cat2."'");
            while($row = mysqli_fetch_array($resul_cat1)){
                $this -> index_cat1 = $row['id_cat1'];
            }
            while($row = mysqli_fetch_array($resul_cat2)){
                $this -> index_cat2 = $row['id_cat2'];
            }
        }


        // galeria
        private function agregar_galeria(){
            $resul['mensaje'] = $this -> Query(
                "INSERT INTO ".$this -> cat3." (
                    id_img,
                    descripcion_img,
                    url_img,
                    id_categoria1,
                    id_categoria2
                )
                VALUES ('".uniqid()."',
                '".$this -> datos['descripcion_img']."',
                '".$this -> datos['url_img']."',
                ".$this -> datos['id_cat1'].",
                ".$this -> datos['id_cat2'].")"
                );
            return json_encode($resul);
        }
        private function obtener_galeria(){
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
                array_push($data, $new_data);
            }
            return json_encode($data);
        }
        private function editar_galeria(){
            $resul['mensaje'] = $this -> Query("UPDATE ".$this -> cat3." SET descripcion_img = '".$this -> datos['descripcion_img']."', url_img = '".$this -> datos['url_img']."', id_categoria1 = ".$this -> datos['id_cat1'].", id_categoria2 = ".$this -> datos['id_cat2']." WHERE id_img = '".$this -> id."'");
            return json_encode($resul);
        }
        private function eliminar_galeria(){
            $resul['mensaje'] = $this -> Query("DELETE FROM ".$this -> cat3." WHERE id_img = '".$this -> id."'");
            return json_encode($resul);
        }

        // paquetes
        private function agregar_paquete(){
            $resul['mensaje'] = $this ->Query("INSERT INTO paquetes
            (nombre_paq, 
            descripcion_paq, 
            id_categoria1, 
            id_categoria2) 
            VALUES ('".$this -> datos['nombre_paq']."',
            '".$this -> datos['descripcion_paq']."',
            ".$this -> datos['id_cat1'].",
            ".$this -> datos['id_cat2'].")");
            return json_encode($resul);
        }
        private function obtener_paquete(){
            if($this -> id){
                $resul = $this -> Query("SELECT * FROM paquetes WHERE id_paq = ".$this -> id);
            }else{
                $resul = $this -> Query("SELECT * FROM paquetes");
            }
            $data = [];
            while($row = mysqli_fetch_array($resul)){
                $new_data['id_paq'] = $row['id_paq'];
                $new_data['nombre_paq'] = $row['nombre_paq'];
                $new_data['id_categoria1'] = $row['id_categoria1'];
                $new_data['id_categoria2'] = $row['id_categoria2'];

                array_push($data, $new_data);
            }
            return json_encode($data);
        }
        private function editar_paquete(){
            $resul['mensaje'] = $this -> Query("UPDATE paquetes SET 
            nombre_paq='".$this -> datos['nombre_paq']."',
            descripcion_paq='".$this -> datos['descripcion_paq']."',
            id_categoria1=".$this -> datos['id_cat1'].",
            id_categoria2=".$this -> datos['id_cat2']." 
            WHERE id_paq = ".$this -> id);
            return json_encode($resul);
        }
        private function eliminar_paquete(){
            $resul['mensaje'] = ("DELETE FROM paquetes WHERE id_paq = ".$this -> id);
            return json_encode($resul);
        }

        // Items paquetes
        private function agregar_item_paq(){
            $data_insert = "";
            foreach($this -> datos['paquete'] as $paquete){
                $data_insert .= "(".$this -> datos['id_paq'].",'.$paquete[nombre].'),";
            }
            $resul['mensaje'] = $this -> Query("INSERT INTO paquetes_items(id_paq, nombre_item) VALUES ".trim($data_insert, ","));
            return json_encode($resul);
        }
        private function obtener_item_paq(){
            $paquetes = [];
            if(!$this -> id){
            $resul_paq = $this -> Query("SELECT nombre_paq FROM paquetes 
            INNER JOIN categoria1 
            ON paquetes.id_categoria1 = categoria1.id_cat1 
            INNER JOIN categoria2 
            ON paquetes.id_categoria2 = categoria2.id_cat2 
            WHERE categoria1.nombre_cat1 = '".$this -> cat1."' AND categoria2.nombre_cat2 = '".$this -> cat2."'");
            }else{
                $resul_paq = $this -> Query("SELECT nombre_paq FROM paquetes WHERE id_paq = ".$this -> id);
            }
            while($row_paq = mysqli_fetch_array($resul_paq)){
                $nombre_paq = $row_paq['nombre_paq'];
                $resul_items = $this -> Query("SELECT paquetes_items.id_item, paquetes_items.nombre_item 
                FROM `paquetes_items` 
                inner join paquetes 
                ON paquetes_items.id_paq = paquetes.id_paq 
                WHERE paquetes.nombre_paq = '".$nombre_paq."'");
                $paquete = [];
                while($row = mysqli_fetch_array($resul_items)){
                    $paquete['nombre_paq'] = $nombre_paq;
                    $paquete['items'][] = $row['nombre_item'];
                }
                if(count($paquete) !== 0){
                    array_push($paquetes, $paquete);
                }
                unset($paquete);
            }
            return json_encode($paquetes);
        }
        private function editar_item_paq(){
            $resul['mensaje'] = $this -> Query("UPDATE paquetes_items SET 
            id_paq=".$this -> datos['id_paq'].", 
            nombre_item= '".$this -> datos['nombre']."' 
            WHERE id_item = ".$this -> datos['id_item']);
            return json_encode($resul);            
        }
        private function eliminar_item_paq(){
            $resul['mensaje'] = $this -> Query("DELETE FROM 
            paquetes_items 
            WHERE id_item = ".$this -> id);
            return json_encode($resul);
        }
        // proveedores
        private function agregar_proveedores(){
            
            $resul['mensaje'] = $this -> Query("INSERT INTO proveedores
            (nombre_prov, 
            apellido_prov, 
            telefono_prov, 
            direccion_prov, 
            correo_prov, 
            id_categoria1, 
            id_categoria2) 
            VALUES('".$this -> datos['nombre']."',
            '".$this -> datos['apellido']."',
            '".$this -> datos['telefono']."',
            '".$this -> datos['direccion']."',
            '".$this -> datos['correo']."',
            ".$this -> index_cat1.",
            ".$this -> index_cat2.")"
            );  
            return $this -> index_cat1;
        }
        private function obtener_proveedores(){
            if(!$this -> id){
                $resul = $this -> Query("SELECT id_prov, nombre_prov, apellido_prov, telefono_prov, direccion_prov, correo_prov FROM proveedores INNER JOIN categoria1 ON proveedores.id_categoria1 = categoria1.id_cat1 INNER JOIN categoria2 ON proveedores.id_categoria2 = categoria2.id_cat2 WHERE categoria1.nombre_cat1 = '".$this -> cat1."' AND categoria2.nombre_cat2 = '".$this -> cat2."'");
            }else{
                $resul = $this -> Query("SELECT id_prov, nombre_prov, apellido_prov, telefono_prov, direccion_prov, correo_prov FROM proveedores WHERE id_prov = ".$this -> id);
            }
            $data = [];
            while($row = mysqli_fetch_array($resul)){
                $new_data['id_prov'] = $row['id_prov'];
                $new_data['nombre_prov'] = $row['nombre_prov'];
                $new_data['apellido_prov'] = $row['apellido_prov'];
                $new_data['telefono_prov'] = $row['telefono_prov'];
                $new_data['direccion_prov'] = $row['direccion_prov'];
                $new_data['apellido_prov'] = $row['apellido_prov'];
                $new_data['correo_prov'] = $row['correo_prov'];
                array_push($data, $new_data);
            }
            return json_encode($data);
        }
        private function editar_proveedores(){
            $resul['mensaje'] = $this -> Query("UPDATE proveedores SET 
            nombre_prov='".$this -> datos['nombre']."',
            apellido_prov='".$this -> datos['apellido']."',
            telefono_prov='".$this -> datos['telefono']."',
            direccion_prov='".$this -> datos['direccion']."',
            correo_prov='".$this -> datos['correo']."'
            WHERE id_prov = ".$this -> id);

            return json_encode($resul);
        }
        private function eliminar_proveedores(){
            $resul['mensaje'] = $this -> Query("DELETE FROM proveedores WHERE id_prov = ".$this -> id);
            return json_encode($resul);
        }

        
    } 



?>