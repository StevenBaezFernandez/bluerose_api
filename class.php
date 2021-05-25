<?php
    class DB extends mysqli{

        private $host = "localhost";
        private $db_user = "root";
        private $db_pass = "";
        private $db_name = "bluerose_db";

        public $session;
        public $user;
        public $pass;
        public $cat1;
        public $cat2;
        public $index_cat1;
        public $index_cat2;
        public $cat3;
        public $method;
        public $id;
        public $items;
        public $paquete;
        public $datos;

        public function __construct(
            $session = false,
            $user = false,
            $pass = false,
            $cat1 = false, 
            $cat2 = false, 
            $cat3 = false, 
            $method = false, 
            $paquete = false, 
            $id = false, 
            $items = false, 
            $datos = false){      
            
            parent:: __construct(
                $this -> host, 
                $this -> db_user, 
                $this -> db_pass, 
                $this -> db_name);

            $this -> session = $session;
            $this -> user = $user;
            $this -> pass = $pass;
            $this -> cat1 = $cat1;
            $this -> cat2 = $cat2;
            $this -> cat3 = $cat3;
            $this -> method = $method;
            $this -> paquete = $paquete;
            $this -> id = $id;
            $this -> items = $items;
            $this -> datos = json_decode($datos, true);

            $this -> obtener_index_cat();
            
            if($this -> session ){
                echo json_encode($this -> open_session());
            }else{
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
        }

        private function open_session(){
            $resul_login = $this -> Query("SELECT * FROM `login` WHERE user = '".$this -> user."' and pass = '".$this -> pass."'");
            
            if(mysqli_num_rows($resul_login)){
                return true;
            }else{
                return false;
            }

        }
        private function verif_session(){

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

        
        // galeria
        private function agregar_galeria(){
            $str =  file_get_contents('php://input');
            $filename = md5(time()).'.png';
            $path = 'images/'.$filename;
            
            if(file_put_contents($path,$str)){
                $resul['mensaje'] = $this -> Query("INSERT INTO galeria (id_img,url_img, id_categoria1, id_categoria2) VALUES ('".uniqid()."', 'http://localhost/bluerose_api/".$path."', ".$this -> index_cat1.", ".$this -> index_cat2.")");
                return json_encode($resul);                
            }            
        }
        private function obtener_galeria(){
            if(!$this -> id){
                $resul = $this -> Query("SELECT id_img, url_img, categoria1.nombre_cat1, categoria1.descripcion_cat1,categoria2.nombre_cat2, categoria2.descripcion_cat2 FROM ".$this -> cat3." INNER JOIN categoria1 ON ".$this -> cat3.".id_categoria1 = categoria1.id_cat1 INNER JOIN categoria2 ON ".$this -> cat3.".id_categoria2 = categoria2.id_cat2 WHERE categoria1.nombre_cat1 = '".$this -> cat1."' AND categoria2.nombre_cat2 = '".$this -> cat2."'");
            }else{
                $resul = $this -> Query("SELECT id_img, url_img, categoria1.nombre_cat1, categoria1.descripcion_cat1,categoria2.nombre_cat2, categoria2.descripcion_cat2 FROM ".$this -> cat3." INNER JOIN categoria1 ON ".$this -> cat3.".id_categoria1 = categoria1.id_cat1 INNER JOIN categoria2 ON ".$this -> cat3.".id_categoria2 = categoria2.id_cat2 WHERE id_img = '".$this -> id."'");
            }
            $data = [];
            while($row = mysqli_fetch_array($resul)){
                $new_data['id_img'] = $row['id_img'];
                $new_data['url_img'] = $row['url_img'];
                array_push($data, $new_data);
            }
            return json_encode($data);
        }
        private function editar_galeria(){
            $resul['mensaje'] = $this -> Query("UPDATE ".$this -> cat3." SET url_img = '".$this -> datos['url_img']."', id_categoria1 = ".$this -> datos['id_cat1'].", id_categoria2 = ".$this -> datos['id_cat2']." WHERE id_img = '".$this -> id."'");
            return json_encode($resul);
        }
        private function eliminar_galeria(){

            $resul_img = $this -> Query("SELECT id_img, url_img, categoria1.nombre_cat1, categoria1.descripcion_cat1,categoria2.nombre_cat2, categoria2.descripcion_cat2 FROM ".$this -> cat3." INNER JOIN categoria1 ON ".$this -> cat3.".id_categoria1 = categoria1.id_cat1 INNER JOIN categoria2 ON ".$this -> cat3.".id_categoria2 = categoria2.id_cat2 WHERE id_img = '".$this -> id."'");

            $row = mysqli_fetch_array($resul_img);
            $url = $row['url_img'];
            $porcion = explode("/", $url);
            $file_pointer = "./images/".$porcion[5];

            if (!unlink($file_pointer)) { 
                $resul['mensaje'] = "$file_pointer cannot be deleted due to an error"; 
            } 
            else { 
                $resul['mensaje'] = "$file_pointer has been deleted"; 
            } 

            $this -> Query("DELETE FROM ".$this -> cat3." WHERE id_img = '".$this -> id."'");
            return json_encode($resul);
        }

        // paquetes
        private function agregar_paquete(){
            $resul['mensaje'] = $this -> Query("INSERT INTO paquetes
            (nombre_paq, 
            id_categoria1, 
            id_categoria2) 
            VALUES ('".$this -> datos['nombre_paq']."',
            ".$this -> index_cat1.",
            ".$this -> index_cat2.")");
            return json_encode($resul);
        }
        private function obtener_paquete(){
            if($this -> id){
                $resul = $this -> Query("SELECT * FROM paquetes WHERE id_paq = ".$this -> id);
            }else{
                $resul = $this -> Query("SELECT * FROM paquetes INNER JOIN categoria1 ON paquetes.id_categoria1 = categoria1.id_cat1 INNER JOIN categoria2 ON paquetes.id_categoria2 = categoria2.id_cat2 WHERE categoria1.nombre_cat1 = '".$this -> cat1."' AND categoria2.nombre_cat2 = '".$this -> cat2."'");
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
            nombre_paq='".$this -> datos['nombre_paq']."'
            WHERE id_paq = ".$this -> id);
            return json_encode($resul);
        }
        private function eliminar_paquete(){
            $resul['mensaje'] = $this -> Query("DELETE FROM 
            paquetes_items 
            WHERE id_paq = ".$this -> id);

            $resul['mensaje'] = $this -> Query("DELETE FROM 
            paquetes 
            WHERE id_paq = ".$this -> id);

            return json_encode($resul);
        }

        // Items paquetes
        private function agregar_item_paq(){
            $resul['mensaje'] = $this -> Query("INSERT INTO paquetes_items(id_paq, nombre_item) VALUES(".$this -> paquete.", '".$this -> datos['item']."') ");
            return json_encode($resul);
        }
        private function obtener_item_paq(){
            $resul_items = $this -> Query("SELECT paquetes_items.id_item, paquetes_items.nombre_item, paquetes.nombre_paq
            FROM paquetes_items 
            inner join paquetes 
            ON paquetes_items.id_paq = paquetes.id_paq 
            WHERE paquetes.id_paq = '".$this -> paquete."'");
            $items_paq = [];
            $temp_items = [];
            while($row = mysqli_fetch_array($resul_items)){
                $items_paq['paquete'] = $row['nombre_paq'];
                $item['id'] = $row['id_item'];
                $item['nombre'] = $row['nombre_item'];
                array_push($temp_items, $item);
            }
            $items_paq['items'] = $temp_items;
            return json_encode($items_paq);
        }
        private function editar_item_paq(){
            $resul['mensaje'] = $this -> Query("UPDATE paquetes_items SET 
            id_paq=".$this -> datos['paq'].", 
            nombre_item= '".$this -> datos['item']."' 
            WHERE id_item = ".$this -> datos['id']);
            return json_encode($resul);            
        }
        private function eliminar_item_paq(){
            if($this -> items){
                $items = explode('/', $this -> items);
                foreach($items as $item){
                    $resul['mensaje'] = $this -> Query("DELETE FROM 
                    paquetes_items 
                    WHERE id_item = ". (int)$item);
                }
                return json_encode($this -> items);
            }else{
                $resul['mensaje'] = $this -> Query("DELETE FROM 
                paquetes_items 
                WHERE id_item = ".$this -> id);
                return json_encode($resul);
            }
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
            return json_encode($resul);
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