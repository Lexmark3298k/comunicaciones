<?php
require 'conexion.php'; // Archivo para conectarse a la base de datos

// Consulta para cantidad total de registros en c_ingresos
$total_ingresos_query = "SELECT COUNT(*) as total FROM c_ingresos";
$total_ingresos_result = $conn->query($total_ingresos_query);
$total_ingresos = $total_ingresos_result->fetch_assoc()['total'];

// Consulta para cantidad total de registros por estado en c_recepcion
$estados_query = "SELECT estado, COUNT(*) as total FROM c_recepcion GROUP BY estado";
$estados_result = $conn->query($estados_query);
$estados = $estados_result->fetch_all(MYSQLI_ASSOC);

// Consulta para cantidad de cédulas registradas por mes
$cedulas_por_mes_query = "SELECT DATE_FORMAT(fecha_recep, '%Y-%m') as mes, COUNT(*) as total FROM c_ingresos GROUP BY mes";
$cedulas_por_mes_result = $conn->query($cedulas_por_mes_query);
$cedulas_por_mes = $cedulas_por_mes_result->fetch_all(MYSQLI_ASSOC);

// Consulta para cantidad de registros por usuario
$usuarios_query = "SELECT usuarios.fullname, COUNT(*) as total FROM c_ingresos 
                   JOIN usuarios ON c_ingresos.id_usuario = usuarios.id 
                   GROUP BY c_ingresos.id_usuario";
$usuarios_result = $conn->query($usuarios_query);
$usuarios = $usuarios_result->fetch_all(MYSQLI_ASSOC);

// Consulta para total de registros anuales
$anual_query = "SELECT anio, COUNT(*) as total FROM c_ingresos GROUP BY anio";
$anual_result = $conn->query($anual_query);
$anual = $anual_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidado de Contadores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        .contenedor {
            width: 80%;
            max-width: 900px;
            margin: 40px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        h1 {
            font-size: 2.5rem;
        }

        h2 {
            font-size: 1.8rem;
            margin-top: 20px;
            color: #007bff;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.5;
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            font-size: 1.1rem;
            margin: 10px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border-left: 5px solid #007bff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        li strong {
            color: #007bff;
            font-weight: bold;
        }

        li:hover {
            background-color: #f1f1f1;
        }

        .footer {
            text-align: center;
            font-size: 0.9rem;
            color: #777;
            margin-top: 30px;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

    <div class="contenedor">
        <h1>Consolidado de Contadores</h1>

        <div class="total-registros">
            <h2>Total de Registros</h2>
            <p><strong><?= $total_ingresos ?></strong> registros en la tabla <code>c_ingresos</code>.</p>
        </div>

        <div class="registros-estado">
            <h2>Registros por Estado (c_recepcion)</h2>
            <ul>
                <?php foreach ($estados as $estado): ?>
                    <li>
                        <span><?= $estado['estado'] ?></span>
                        <span><strong><?= $estado['total'] ?></strong></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="registros-mes">
            <h2>Registros por Mes (c_ingresos)</h2>
            <ul>
                <?php foreach ($cedulas_por_mes as $mes): ?>
                    <li>
                        <span><?= $mes['mes'] ?></span>
                        <span><strong><?= $mes['total'] ?></strong></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="registros-usuario">
            <h2>Registros por Usuario</h2>
            <ul>
                <?php foreach ($usuarios as $usuario): ?>
                    <li>
                        <span><?= $usuario['fullname'] ?></span>
                        <span><strong><?= $usuario['total'] ?></strong></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="registros-anual">
            <h2>Registros por Año</h2>
            <ul>
                <?php foreach ($anual as $anio): ?>
                    <li>
                        <span><?= $anio['anio'] ?></span>
                        <span><strong><?= $anio['total'] ?></strong></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>Generado por <a href="#">gfloresr@pj.gob.pe</a></p>
    </div>

</body>
</html>
