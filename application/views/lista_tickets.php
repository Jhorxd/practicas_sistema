<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Tickets</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/lista_tickets.css'); ?>">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container" style="display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 20px;">
        <a href="<?php echo site_url('auth/logout'); ?>" class="btn-logout">Cerrar Sesión</a>
        <a href="<?php echo site_url('usuario/index'); ?>" class="btn-logout">Registrar usuario</a>
    </div>

    <h2>Lista de Tickets</h2>

<form method="GET" action="">
    <label for="filtro-prioridad">Filtrar por prioridad:</label>
    <select id="filtro-prioridad" name="prioridad" onchange="this.form.submit()">
        <option value="" <?php if(empty($_GET['prioridad'])) echo 'selected'; ?>>Todas</option>
        <option value="alta" <?php if(isset($_GET['prioridad']) && $_GET['prioridad'] === 'alta') echo 'selected'; ?>>Alta</option>
        <option value="media" <?php if(isset($_GET['prioridad']) && $_GET['prioridad'] === 'media') echo 'selected'; ?>>Media</option>
        <option value="baja" <?php if(isset($_GET['prioridad']) && $_GET['prioridad'] === 'baja') echo 'selected'; ?>>Baja</option>
    </select>

    <label for="filtro-cliente">Filtrar por cliente:</label>
    <select id="filtro-cliente" name="cliente" onchange="this.form.submit()">
        <option value="" <?php if(empty($_GET['cliente'])) echo 'selected'; ?>>Todos los clientes</option>
        <?php foreach ($clientes as $cliente): ?>
            <option value="<?php echo htmlspecialchars($cliente->id_usuario); ?>"
                <?php if(isset($_GET['cliente']) && $_GET['cliente'] == $cliente->id_usuario) echo 'selected'; ?>>
                <?php echo htmlspecialchars($cliente->nombre); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<p style="font-weight: bold; margin-top: 20px;">
    Total de tickets: <?php echo count($tickets); ?>
</p>

<p style="font-weight: bold; margin-top: 10px;">
    Tickets notificados: <?php echo count(array_filter($tickets, function($t) { return $t->notificado == 1; })); ?>
</p>
<table>
    <br>
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Título</th>
            <th>Prioridad</th>
            <th>Estado</th>
            <th>Creado en</th>
            <th>Acciones</th>
            <th>Notificado</th>
            <th>Cerrado en</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?php echo $ticket->id_ticket; ?></td>
                <td><?php echo htmlspecialchars($ticket->nombre_usuario); ?></td>
                <td><?php echo htmlspecialchars($ticket->titulo); ?></td>
                <td>
                    <select class="cambiar-prioridad" data-id="<?php echo $ticket->id_ticket; ?>">
                        <option value="" <?php echo empty($ticket->prioridad) ? 'selected' : ''; ?>>Seleccione la prioridad</option>
                        <option value="alta" <?php echo $ticket->prioridad === 'alta' ? 'selected' : ''; ?>>Alta</option>
                        <option value="media" <?php echo $ticket->prioridad === 'media' ? 'selected' : ''; ?>>Media</option>
                        <option value="baja" <?php echo $ticket->prioridad === 'baja' ? 'selected' : ''; ?>>Baja</option>
                    </select>
                </td>
                <td>
                    <select class="cambiar-estado" data-id="<?php echo $ticket->id_ticket; ?>">
                        <option value="abierto" <?php echo $ticket->estado === 'abierto' ? 'selected' : ''; ?>>Abierto</option>
                        <option value="cerrado" <?php echo $ticket->estado === 'cerrado' ? 'selected' : ''; ?>>Cerrado</option>
                    </select>
                </td>
                <td><?php echo $ticket->creado_en; ?></td>
                <td>
                    <a href="javascript:void(0);" class="btn-ver" data-id="<?php echo $ticket->id_ticket; ?>">Ver</a> |
                    <a href="#" class="btn-responder" data-id="<?php echo $ticket->id_ticket ?>">Responder</a>
                </td>
                <td>
    <?php if ($ticket->notificado): ?>
        <span style="color: green; font-weight: bold;">Sí</span>
    <?php else: ?>
        <span style="color: red; font-weight: bold;">No</span>
    <?php endif; ?>
</td>
<td><?php echo $ticket->cerrado_en ?? '---'; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Funcionalidad botón Ver
            $('.btn-ver').on('click', function () {
                const ticketId = $(this).data('id');

                $.ajax({
                    url: '<?php echo site_url('tickets/ajax_evidencias'); ?>/' + ticketId,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
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
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo cargar la evidencia.', 'error');
                    }
                });
            });


            $('.cambiar-estado').on('change', function () {
    const id_ticket = $(this).data('id');
    const nuevo_estado = $(this).val();

    $.ajax({
        url: '<?php echo site_url('tickets/actualizar_estado'); ?>',
        method: 'POST',
        data: {
            id_ticket: id_ticket,
            estado: nuevo_estado
        },
        success: function (response) {
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: 'Estado actualizado correctamente',
        timer: 2500, // duración aumentada a 2.5 segundos
        showConfirmButton: false
    });

            // Espera 2.5 segundos antes de recargar
            setTimeout(function () {
                location.reload();
            }, 2500);
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al actualizar el estado',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
});



            $('.btn-responder').on('click', function(e) {
    e.preventDefault();
    const anuncioId = $(this).data('id');

    Swal.fire({
        title: 'Responder al Ticket',
        input: 'textarea',
        inputLabel: 'Mensaje',
        inputPlaceholder: 'Escribe tu mensaje aquí...',
        inputAttributes: {
            'aria-label': 'Mensaje'
        },
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        preConfirm: (mensaje) => {
            if (!mensaje) {
                Swal.showValidationMessage('Por favor ingresa un mensaje');
                return false;
            }
            return mensaje;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const mensaje = encodeURIComponent(result.value);

            // Paso 1: Obtener ID usuario y teléfono vía AJAX usando anuncioId
            $.ajax({
                url: '<?php echo site_url('Tickets/get_usuario_telefono'); ?>/' + anuncioId,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.telefono) {
                        // Paso 2: Abrir WhatsApp con mensaje y teléfono
                        const telefono = data.telefono.replace(/\D/g, ''); // limpiar caracteres no numéricos
                        const urlWsp = `https://wa.me/${telefono}?text=${mensaje}`;
                        window.open(urlWsp, '_blank');

                        // Marcar ticket como notificado
                        $.ajax({
                            url: '<?php echo site_url('tickets/marcar_como_notificado'); ?>/' + anuncioId,
                            method: 'POST',
                            success: function (response) {
                                // Opcional: mostrar mensaje
                                console.log('Ticket marcado como notificado');
                                // Recargar la página para actualizar visualmente
                                location.reload();
                            },
                            error: function () {
                                console.error('Error al marcar el ticket como notificado');
                            }
                        });

                    } else {
                        Swal.fire('Error', 'No se encontró el teléfono del usuario.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo obtener la información del usuario.', 'error');
                }
            });
        }
    });
});

            // Funcionalidad para cambiar prioridad
            $('.cambiar-prioridad').on('change', function () {
                const id_ticket = $(this).data('id');
                const nueva_prioridad = $(this).val();

                $.ajax({
                    url: '<?php echo site_url('tickets/actualizar_prioridad'); ?>',
                    method: 'POST',
                    data: {
                        id_ticket: id_ticket,
                        prioridad: nueva_prioridad
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Prioridad actualizada correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error al actualizar la prioridad',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
