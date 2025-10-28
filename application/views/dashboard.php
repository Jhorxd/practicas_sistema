<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3">
    <div class="container-fluid px-4">
        <a class="navbar-brand fs-5 fw-bold" href="#">💻 Sistema XP - Panel Principal</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text text-white me-3 fw-semibold">
                👋 <?= $this->session->userdata('nombre'); ?>
            </span>
            <a href="<?= base_url('auth/logout'); ?>" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-5 fw-bold titulo-panel">📊 Panel de Gestión de Sistemas ERP</h2>

    <div class="row g-4 justify-content-center">
        <!-- Módulo de Requerimientos -->
        <div class="col-md-4 col-lg-3">
            <div class="card text-center shadow-lg module-card border-0">
                <div class="card-body">
                    <div class="icono-modulo bg-primary text-white mb-3">
                        🧾
                    </div>
                    <h5 class="card-title">Requerimientos</h5>
                    <p class="card-text">Registra y gestiona solicitudes del área de sistemas.</p>
                    <a href="<?= base_url('requerimientos'); ?>" class="btn btn-primary btn-lg">Ir al módulo</a>
                </div>
            </div>
        </div>

        <!-- Módulo de Cotizaciones -->
        <div class="col-md-4 col-lg-3">
            <div class="card text-center shadow-lg module-card border-0">
                <div class="card-body">
                    <div class="icono-modulo bg-warning text-white mb-3">
                        💰
                    </div>
                    <h5 class="card-title">Cotizaciones</h5>
                    <p class="card-text">Genera cotizaciones personalizadas para requerimientos.</p>
                    <a href="<?= base_url('cotizaciones'); ?>" class="btn btn-warning btn-lg text-white">Ir al módulo</a>
                </div>
            </div>
        </div>

        <!-- Módulo de Seguimiento -->
        <div class="col-md-4 col-lg-3">
            <div class="card text-center shadow-lg module-card border-0">
                <div class="card-body">
                    <div class="icono-modulo bg-success text-white mb-3">
                        📈
                    </div>
                    <h5 class="card-title">Seguimiento</h5>
                    <p class="card-text">Monitorea el estado y avance de los requerimientos.</p>
                    <a href="<?= base_url('seguimiento'); ?>" class="btn btn-success btn-lg">Ir al módulo</a>
                </div>
            </div>
        </div>

        <!-- Módulo de Proyectos -->
        <div class="col-md-4 col-lg-3">
            <div class="card text-center shadow-lg module-card border-0">
                <div class="card-body">
                    <div class="icono-modulo bg-info text-white mb-3">
                        📁
                    </div>
                    <h5 class="card-title">Proyectos</h5>
                    <p class="card-text">Administra los proyectos y fases de desarrollo XP.</p>
                    <a href="<?= base_url('proyectos'); ?>" class="btn btn-info btn-lg text-white">Ir al módulo</a>
                </div>
            </div>
        </div>

        <!-- Módulo de Reportes -->
        <div class="col-md-4 col-lg-3">
            <div class="card text-center shadow-lg module-card border-0">
                <div class="card-body">
                    <div class="icono-modulo bg-secondary text-white mb-3">
                        📊
                    </div>
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text">Visualiza estadísticas, métricas y progreso del proyecto.</p>
                    <a href="<?= base_url('reportes'); ?>" class="btn btn-secondary btn-lg">Ir al módulo</a>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer mt-5 py-3 text-center text-muted">
    <small>© 2025 Sistema XP - Desarrollado por practicante UCV</small>
</footer>

</body>
</html>
