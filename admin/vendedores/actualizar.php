<?php

require '../../includes/app.php';

use App\Vendedor;

estaAutentificado();

// Validar que se un ID válido

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location /bienesraices/admin');
}

// Obtener el areglo del vendedor
$vendedor = Vendedor::find($id);


//Arreglo con mensaje de erroes
$errores = Vendedor::getErrores();

// Ejecutar el código de que el usuario envia el formualrio 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Asignar lo valoeres
    $args = $_POST['vendedor'];

    //Sincronizar objeto en memoria con lo que el usuroario escribió
    $vendedor->sincronizar($args);

    //Validación
    $errores = $vendedor->validar();

    if (empty($errores)) {
        $vendedor->guardar();
    }

    

}

incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Actualizar Vendedor(a)</h1>
    <a href="/bienesraices/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" >

        <?php include '../../includes/templates/formulario_vendedores.php'; ?>
        <input type="submit" value="Guardar Cambios " class="boton boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>