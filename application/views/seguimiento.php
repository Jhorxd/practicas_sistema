<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seguimiento de Requerimientos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>

        /* Botón de volver al dashboard */
.btn-dashboard {
    display: inline-block;
    padding: 10px 20px;
    background-color: #64748b; /* mismo color que btn-secondary */
    color: #fff;
    border-radius: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn-dashboard:hover {
    background-color: #475569; /* mismo hover que btn-secondary */
}

        body {
            background: linear-gradient(135deg, #f0f4f8, #dce3ec);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        h1.titulo-panel {
            color: #1e293b;
            text-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            font-size: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        table thead {
            background-color: #1e293b;
            color: #fff;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
        }

        table tbody tr {
            border-bottom: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        table tbody tr:hover {
            background-color: #f1f5f9;
            transform: translateX(3px);
        }

        a.btn-detalle {
            display: inline-block;
            background-color: #3b82f6;
            color: #fff;
            padding: 6px 12px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        a.btn-detalle:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            table th, table td {
                font-size: 0.85rem;
                padding: 10px;
            }

            h1.titulo-panel {
                font-size: 1.5rem;
            }
        }


        
    </style>
</head>
<body>
<br>
        <!-- Botón volver al dashboard -->
<div class="mb-3">
    <a href="<?= base_url('dashboard'); ?>" class="btn-dashboard">← Volver al Dashboard</a>
</div>

    <h1 class="titulo-panel">Seguimiento de Requerimientos</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Proyecto</th>
                <th>Estado</th>
                <th>Avance (%)</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($requerimientos as $r): ?>
            <tr>
                <td><?= $r->id_requerimiento ?></td>
                <td><?= $r->titulo ?></td>
                <td><?= $r->nombre_proyecto ?></td>
                <td><?= $r->estado ?></td>
                <td><?= $r->porcentaje_avance ?? 0 ?></td>
                <td><?= date("d/m/Y H:i", strtotime($r->fecha_registro)) ?></td>
                <td>
                    <a href="<?= base_url('seguimiento/detalle/'.$r->id_requerimiento) ?>" class="btn-detalle">Ver Detalle</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
