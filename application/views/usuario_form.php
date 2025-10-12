<!DOCTYPE html>
<html>

<link rel="stylesheet" href="<?php echo base_url('assets/css/usuario_form.css'); ?>">
<head>
    <title>Registrar Usuario</title>
</head>
<body>

    <div class="header">
        <h2>Registrar usuario</h2>

        <div class="container">
            <a href="<?php echo site_url('tickets/index'); ?>" class="btn-ver-tickets">Ver Tickets</a>
            <a href="<?php echo site_url('auth/logout'); ?>" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>
    
    <?php if ($this->session->flashdata('msg')): ?>
        <p style="color: green;"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    
    <form method="post" action="<?php echo base_url('usuario/guardar'); ?>">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>
    
    <label>Correo:</label><br>
    <input type="email" name="correo" required><br><br>
    
    <label>Contraseña:</label><br>
    <input type="password" name="contrasena" required><br><br>
    
    <label>Teléfono:</label><br>
    <input type="number" name="telefono"><br><br>
    
    <label>Rol:</label><br>
    <select name="rol" required>
        <option value="admin">Admin</option>
        <option value="soporte">Soporte</option>
        <option value="cliente">Cliente</option>
    </select><br><br>
    
    <label>Estado:</label><br>
    <select name="estado" required>
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
    </select><br><br>
    
    <button type="submit">Guardar</button>
</form>

</body>
</html>
