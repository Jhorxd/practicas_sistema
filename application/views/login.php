<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/login.css'); ?>">
    <title>Login</title>
</head>
<body>

    <!-- Imagen del logo -->
    <img src="<?php echo base_url('assets/img/logo1.png'); ?>" alt="Logo" class="logo-login">

    <h2>Iniciar sesión</h2>

    <?php if ($this->session->flashdata('error')): ?>
        <p style="color:red;"><?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <form method="post" action="<?php echo site_url('auth/login'); ?>">
        <label>Correo:</label><br>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="contraseña" required><br><br>

        <button type="submit">Iniciar sesión</button>
    </form>

</body>
</html>
