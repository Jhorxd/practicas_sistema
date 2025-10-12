<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tickets</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/cliente_tickets.css'); ?>">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <!-- Botón para registrar nuevo ticket -->
    <br>
    <div class="container" style="display: flex; justify-content: flex-end; gap: 10px;">
        <a href="<?php echo site_url('tickets/nuevo'); ?>" class="btn-registrar">Registrar nuevo ticket</a>
        <a href="<?php echo site_url('auth/logout'); ?>" class="btn-logout">Cerrar Sesión</a>
    </div>

    <h2>Mis Tickets</h2>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?php echo $ticket->titulo; ?></td>
                    <td><?php echo ucfirst($ticket->estado); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($ticket->creado_en)); ?></td>
                    <td>
                        <?php if (strtolower($ticket->estado) === 'abierto'): ?>
                            <a href="javascript:void(0);" class="btn-ver" data-id="<?php echo $ticket->id_ticket; ?>">
                                Ver
                            </a>
                            <a href="javascript:void(0);" class="btn-eliminar" data-id="<?php echo $ticket->id_ticket; ?>">
                                Eliminar
                            </a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <style>
        .btn-ver {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            margin-right: 5px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-ver:hover {
            background-color: #388e3c;
        }
        .btn-eliminar {
            background-color: #d33;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-eliminar:hover {
            background-color: #a52727;
        }
    </style>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Botón Eliminar
        const deleteButtons = document.querySelectorAll('.btn-eliminar');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const ticketId = this.getAttribute('data-id');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Este ticket se eliminará permanentemente.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?php echo site_url('tickets/eliminar/'); ?>" + ticketId;
                    }
                });
            });
        });

        // Botón Ver Evidencia y Detalles
        const viewButtons = document.querySelectorAll('.btn-ver');
        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                const ticketId = this.getAttribute('data-id');
                fetch('<?php echo site_url('tickets/ajax_evidencias/'); ?>' + ticketId)
                    .then(response => response.json())
                    .then(data => {
                        const evidencias = data.evidencias || [];
                        const descripcion = data.descripcion || 'Sin descripción disponible';

                        if (evidencias.length === 0) {
                            // Sin evidencias, mostrar modal con botón Ver detalles
                            Swal.fire({
                                title: 'Sin evidencia',
                                text: 'No hay evidencia para este ticket.',
                                icon: 'info',
                                showCancelButton: true,
                                confirmButtonText: 'Cerrar',
                                cancelButtonText: 'Ver detalles',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.cancel) {
                                    Swal.fire({
                                        title: 'Descripción del ticket',
                                        html: `<p style="white-space: pre-wrap;">${descripcion}</p>`,
                                        icon: 'info',
                                        confirmButtonText: 'Cerrar'
                                    });
                                }
                            });
                            return;
                        }

                        // Hay evidencias, mostrar modal con evidencias y botón Ver detalles
                        let html = '';
                        evidencias.forEach(ev => {
                            const ext = ev.nombre_archivo.split('.').pop().toLowerCase();
                            const url = '<?php echo base_url(); ?>' + ev.ruta_archivo;
                            if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                                html += `<div style="margin-bottom:10px"><img src="${url}" alt="${ev.nombre_archivo}" style="max-width:100%;max-height:200px;"/></div>`;
                            } else if (['mp4','mov','avi','mkv','webm'].includes(ext)) {
                                html += `<div style="margin-bottom:10px"><video src="${url}" controls style="max-width:100%;max-height:200px;"></video></div>`;
                            } else if (['pdf'].includes(ext)) {
                                html += `<div style="margin-bottom:10px"><a href="${url}" target="_blank">Ver PDF: ${ev.nombre_archivo}</a></div>`;
                            } else if (['docx','zip'].includes(ext)) {
                                html += `<div style="margin-bottom:10px"><a href="${url}" target="_blank" download>Descargar: ${ev.nombre_archivo}</a></div>`;
                            } else {
                                html += `<div style="margin-bottom:10px"><a href="${url}" target="_blank" download>${ev.nombre_archivo}</a></div>`;
                            }
                        });

                        Swal.fire({
                            title: 'Evidencias',
                            html: html,
                            width: 600,
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonText: 'Cerrar',
                            cancelButtonText: 'Ver detalles',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.cancel) {
                                Swal.fire({
                                    title: 'Descripción del ticket',
                                    html: `<p style="white-space: pre-wrap;">${descripcion}</p>`,
                                    icon: 'info',
                                    confirmButtonText: 'Cerrar'
                                });
                            }
                        });
                    })
                    .catch(() => {
                        Swal.fire('Error', 'No se pudo cargar la evidencia.', 'error');
                    });
            });
        });
    });
    </script>
</body>
</html>
