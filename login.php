<?php 
session_start();
$username = '';
$password = '';
$msj = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);

    if ($username == '' || $password == '') {
        $msj = 'Debes completar los campos.';
    } else {
        //verificar usuario y pass contra la BD
        require_once 'controladores/C_Usuarios.php';
        $objCont = new C_Usuarios();
        $id_Usuario=$objCont->validarUsuario(array('usuario'=>$username, 'pass'=>$password));

        if($id_Usuario!='') {
            $_SESSION['login'] = $username;
            // Saltar a esta página
            header('Location: index.php');
        } else {
            unset($_SESSION['login']);
            unset($_SESSION['id_Usuario']);
            $msj = 'No es correcto.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        .msj {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }

        .elementoFormCentrado {
            text-align: center;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form action="login.php" method="post">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username">

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>">

            <span id="msj" class="msj"><?php echo $msj; ?></span>
            <div class="elementoFormCentrado">
                <button type="submit" id="aceptar">Aceptar</button>
            </div>
        </form>
    </div>
</body>
</html>
