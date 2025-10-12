<!DOCTYPE html>
<html>

<head>
    <title>Registrar Ticket</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/registro_ticket.css'); ?>">

    <!-- Incluir SweetAlert desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="header">
        <h2>Registrar Ticket</h2>

        <div class="container">
            <a href="<?php echo site_url('tickets/mis_tickets'); ?>" class="btn-ver-tickets">Ver mis Tickets</a>
            <a href="<?php echo site_url('auth/logout'); ?>" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>

    <?php echo form_open_multipart('tickets/registrar', ['id' => 'formRegistrarTicket']); ?>

    <label>Título:</label>
    <input type="text" name="titulo" required>

    <label>Descripción:</label>
    <textarea name="descripcion" rows="5" required></textarea>

    <label>Evidencia (opcional):</label>
    <input type="file" name="evidencia">

    <button type="submit">Registrar Ticket</button>

    <?php echo form_close(); ?>

    <?php if ($this->session->flashdata('ticket_registrado')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Ticket registrado!',
                text: 'Tu ticket ha sido registrado correctamente.',
                confirmButtonText: 'Aceptar'
            });
        </script>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formRegistrarTicket');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Evita el envío inmediato

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres registrar este ticket?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    </script>

</body>

</html>
