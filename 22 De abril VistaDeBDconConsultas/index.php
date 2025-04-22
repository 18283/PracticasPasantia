<?php
echo '<!DOCTYPE html>
<html>
<head>
    <title>Base de datos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .form-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        form {
            display: inline-block;
        }
        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #7a5c94;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #5a4370;
        }
    </style>
</head>
<body>
    <h2>Consultar Base de Datos</h2>
    <div class="form-container">
        <form action="FormRegistroDeMarcacion.php" method="post">
            <input type="submit" value="Marcaciones">
        </form>
        <form action="FormPersonalDeDepartamento.php" method="post">
            <input type="submit" value="Departamento">
        </form>
    </div>
</body>
</html>';
?>
