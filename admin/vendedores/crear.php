<?php

require '../../includes/app.php';

use App\Vendedor;

estaAutentificado();

$vendedor = new Vendedor;

//Arreglo con mensaje de erroes
$errores = Vendedor::getErrores();

// Ejecutar el código de que el usuario envia el formualrio 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Crear una nueva instacia 
    $vendedor = new Vendedor($_POST['vendedor']);

    // validar que no haya campos vacios
   $errores = $vendedor->validar();

   // No hay errores
   if (empty($errores)) {
    $vendedor->guardar();
   }


}

incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Registrar Vendedor(a)</h1>
    <a href="/bienesraices/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" action="/bienesraices/admin/vendedores/crear.php" >

        <?php include '../../includes/templates/formulario_vendedores.php'; ?>
        <input type="submit" value="Registrar Vendedor" class="boton boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>