<?php

require '../../includes/app.php';
use App\Propiedad;
use App\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;


estaAutentificado();

// Validar la URL por ID válido
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if(!$id) {
    header('Location: /bienesraices/admin');
}

// Obtener los datos de la propiedad
$propiedad = Propiedad::find($id);

// Consultar para obtener los valores
$vendedores = Vendedor::all();

// Arreglo con mensajes de errores
$errores = Propiedad::getErrores();


// Ejecuta el código después de que el usuario envía el formulario
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Asignar los atributos
    $args = $_POST['propiedad'];

    // Sincronizar el objeto Propiedad con los valores del formulario
    $propiedad->sincronizar($args);

    //Validacion
    $errores = $propiedad->validar();

    // Subida de archivos 

    // Generar un nombre único
    $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

    if ($_FILES['propiedad']['tmp_name']['imagen']) {
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    } 

    // Revisar que el array de errores esté vacío
    if(empty($errores)) {
        // Almacenar la imagen
        if($_FILES['propiedad']['tmp_name']['imagen']) {
            $image->save(CARPETA_IMAGENES . $nombreImagen);
        }

        $propiedad->guardar();
    }
}
    incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Actualizar Propiedad</h1>
    <a href="/bienesraices/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <?php include '../../includes/templates/formulario_propiedades.php'; ?>

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
    </form>
</main>


<?php 
    incluirTemplate('footer');
?> 
