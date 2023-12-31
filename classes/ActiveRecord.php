<?php

namespace App;

class ActiveRecord {
    //Base de Datos 
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    //Errores
    protected static $errores = [];

    
        protected $id;
        protected $titulo;
        protected $precio;
        protected $imagen;
        protected $descripcion;
        protected $habitaciones;
        protected $wc;
        protected $estacionamiento;
        protected $creado;
        protected $vendedorId;
    
        // Propiedades específicas de la tabla vendedor
        protected $nombre;
        protected $apellido;
        protected $telefono;
    

    //Definir la conexion a la DB
    public static function setDB($database)
    {
        self::$db = $database;
    }

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y-m-d');
        $this->vendedorId = $args['vendedorId'] ?? 1;
    }

   // Registros - CRUD
   public function guardar() {
    if(!is_null($this->id)) {
        // actualizar
        $this->actualizar();
    } else {
        // Creando un nuevo registro
        $this->crear();
    }
}

    public function crear()  {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES ('";
        $query .= join("', '", array_values($atributos));
        $query .= "')";

        $resultado = self::$db->query($query);

       //Mensaje de exito
       if ($resultado) {
        header('Location: /bienesraices/admin?resultado=1');
        exit;
    }
    }

    public function actualizar() {

        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 
        echo $query;


        $resultado = self::$db->query($query);

        if($resultado) {
            // Redireccionar al usuario.
            header('Location: /bienesraices/admin?resultado=2');
        }
    }

    // Eliminar un registro
    public function eliminar() {
    // Eliminar la propiedad
    $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";

    $resultado = self::$db->query($query);

    if ($resultado) {
        $this->borrarImagen();
        header('location: /bienesraices/admin?resultado=3');
    }
}



    // Identificar y unir los atributos de DB
    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }

    // Subida de Archivos
    public function setImagen($imagen) {

        // Elimina la imagen previa
        if ( !is_null($this->id) ) {
            $this->borrarImagen();            
        }

        //Asignar el atributo de imagen el mombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    //Eliminar el archivo 
    public function borrarImagen() {
        // Comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);

        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }


    //Validacion
    public static function getErrores() {

        return static::$errores;
    }
    
    public function validar() {
        
        static::$errores =[];
        return static::$errores;
    }

    // Lista todas los registros

    public static function all() {
        // Escribir el Query
        $query = "SELECT * FROM " . static::$tabla ;
        
       $resultado = self::consulatarSQL($query);

       return $resultado;
    }

    // Obtine determinado numero de registro

    public static function get($cantidad) {
        // Escribir el Query
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
        
       $resultado = self::consulatarSQL($query);

       return $resultado;
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";

        $resultado = self::consulatarSQL($query);

        return array_shift($resultado);
    }

    public static function consulatarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // Liberar la memoria
        $resultado->free();

        // Retotnar los resultados
        return $array;


    }

    protected static function crearObjeto($registro) {
        $objeto  = new static();

        

        foreach($registro as $key => $value) {
            if( property_exists( $objeto, $key )) {
                $objeto->$key = $value;
            }
        } 

        return $objeto;
    }

    // Sincronoza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar( $args = [] ) {
        foreach($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value) ) {
                $this->$key = $value;
            }
        }
    }
}