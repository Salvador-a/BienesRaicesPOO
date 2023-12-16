<?php
    //incluye el header
    require 'includes/app.php';
    $db = conectarDB();

    
    // Autentificar el usuario
    
    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // var_dump($_POST);

        $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) );        
        $password =mysqli_real_escape_string($db, $_POST['password']);

        if (!$email) {
            $errores[] = 'El email es obligatorio o no es valido';
        }

        if (!$password) {
            $errores[] = 'El Password es obligatorio o no es valido';
        }

        if(empty($errores)) {
            // Revisar si el usuario existe.
            $query = "SELECT * FROM usuarios WHERE email = '{$email}' ";

            $resultado = mysqli_query($db, $query);

            if ($resultado->num_rows) {
                //Revisar si el password es corecto
                $usuario = mysqli_fetch_assoc($resultado);
               
                // Verificar si el password es correto o no
                $auth = password_verify($password, $usuario['password']);

                if ($auth) {
                    //El usuario esta autentifiado
                    session_start();
                    
                    // Llenar el arreglo de la sesión
                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;

                    header('location: /bienesraices/admin');


                } else {
                    $errores[] = 'El pasword es incorrecto';
                }
                
                
            } else {
                $errores[] = "El Usuario no exite";
            }
            
        }
    }

    
    incluirTemplate('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sección</h1>
        
        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>

            </div>
        <?php endforeach; ?>

        <form method="POST" class="formulario" action="">
            <fieldset>
                <legend>Email Y Password</legend>           

                <label for="email">E-mail:</label>
                <input type="email" name="email" placeholder="Tu Email" id="email" >

                <label for="password">Password:</label>
                <input type="password" name="password" placeholder="Tu Password" id="password" >
            </fieldset>

        <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer');
?>