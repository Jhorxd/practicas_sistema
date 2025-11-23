<style>
/* 🌿 Estilo general */
body {
    background: linear-gradient(135deg, #f0f4f8, #dce3ec);
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    padding: 20px;
}

.module-card {
    background-color: #fff;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.module-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
}

.card-title {
    font-weight: 700;
    font-size: 1.4rem;
    color: #1e293b;
    margin-bottom: 10px;
}

.card-text {
    font-size: 0.95rem;
    color: #334155;
    margin-bottom: 8px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th, table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

table thead tr {
    background-color: #f3f4f6;
}

.btn {
    border-radius: 12px;
    padding: 10px 18px;
    font-weight: 500;
    cursor: pointer;
}

.btn-primary {
    background-color: #3b82f6;
    color: #fff;
    border: none;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: #64748b;
    color: #fff;
    border: none;
}

.btn-secondary:hover {
    background-color: #475569;
}

.btn-danger {
    background-color: #ef4444;
    color: #fff;
    border: none;
}

.btn-danger:hover {
    background-color: #dc2626;
}

.form-control {
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    font-size: 0.95rem;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar flashdata con SweetAlert
    <?php if($swal = $this->session->flashdata('swal')): ?>
        Swal.fire({
            icon: '<?= $swal['icon'] ?>',
            title: '<?= $swal['title'] ?>',
            text: '<?= $swal['text'] ?>',
            confirmButtonColor: '#3b82f6'
        });
    <?php endif; ?>

    // Confirmación SweetAlert para eliminar historial
    document.querySelectorAll('.eliminar-historial').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();
            Swal.fire({
                title: '¿Desea eliminar este historial?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if(result.isConfirmed){
                    form.submit();
                }
            });
        });
    });
});
</script>

        <!-- Botón volver al dashboard -->
    <div class="mb-3">
        <a href="<?= base_url('seguimiento'); ?>" class="btn btn-secondary">← Volver a Seguimientos</a>
    </div>

    <br>

<div class="module-card">
    <h2 class="card-title"><?= $requerimiento->titulo ?></h2>
    <p class="card-text"><strong>Proyecto:</strong> <?= $requerimiento->nombre_proyecto ?></p>
    <p class="card-text"><strong>Estado:</strong> <?= ucfirst($requerimiento->estado) ?> | <strong>Avance:</strong> <?= $requerimiento->porcentaje_avance ?>%</p>
    <p class="card-text"><strong>Descripción:</strong> <?= $requerimiento->descripcion ?></p>
</div>

<div class="module-card">
    <h3 class="card-title">Historial de cambios</h3>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($historial as $h): ?>
            <tr>
                <td><?= $h->fecha ?></td>
                <td><?= $h->usuario_nombre ?></td>
                <td><?= $h->accion ?></td>
                <td>
                    <form action="<?= base_url('seguimiento/eliminar_historial') ?>" method="POST" class="eliminar-historial">
                        <input type="hidden" name="id_historial" value="<?= $h->id_historial ?>">
                        <input type="hidden" name="id_requerimiento" value="<?= $requerimiento->id_requerimiento ?>">
                        <button type="submit" class="btn btn-danger" style="padding:5px 10px; font-size:0.85rem;">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="module-card">
    <h3 class="card-title">Adjuntos</h3>
    <ul style="list-style:none; padding-left:0; display:flex; flex-wrap: wrap; gap:10px;">
        <?php foreach($adjuntos as $a): 
            $ext = strtolower(pathinfo($a->nombre_archivo, PATHINFO_EXTENSION));
            $es_imagen = in_array($ext, ['jpg','jpeg','png','gif']);
        ?>
            <li>
                <?php if($es_imagen): ?>
                    <a href="<?= base_url($a->ruta) ?>" target="_blank">
                        <img src="<?= base_url($a->ruta) ?>" alt="<?= $a->nombre_archivo ?>" 
                             style="width:80px; height:80px; object-fit:cover; border-radius:8px; border:1px solid #ccc;">
                    </a>
                <?php else: ?>
                    <a href="<?= base_url($a->ruta) ?>" target="_blank"><?= $a->nombre_archivo ?></a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="module-card">
    <h3 class="card-title">Actualizar requerimiento</h3>
    <form action="<?= base_url('seguimiento/actualizar_estado') ?>" method="POST" style="display:flex; flex-direction: column; gap:10px;">
        <input type="hidden" name="id_requerimiento" value="<?= $requerimiento->id_requerimiento ?>">
        <label>Estado:</label>
        <select name="estado" class="form-control">
            <option value="pendiente" <?= $requerimiento->estado=='pendiente'?'selected':'' ?>>Pendiente</option>
            <option value="aprobado" <?= $requerimiento->estado=='aprobado'?'selected':'' ?>>Aprobado</option>
            <option value="rechazado" <?= $requerimiento->estado=='rechazado'?'selected':'' ?>>Rechazado</option>
        </select>

        <label>Avance (%):</label>
        <input type="number" name="porcentaje_avance" value="<?= $requerimiento->porcentaje_avance ?>" max="100" class="form-control">

        <label>Acción:</label>
        <input type="text" name="accion" placeholder="Descripción de la acción" class="form-control">

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>

<div class="module-card">
    <h3 class="card-title">Subir adjunto</h3>
    <form action="<?= base_url('seguimiento/subir_adjunto') ?>" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction: column; gap:10px;">
        <input type="hidden" name="id_requerimiento" value="<?= $requerimiento->id_requerimiento ?>">
        <input type="file" name="archivo" class="form-control">
        <button type="submit" class="btn btn-secondary">Subir</button>
    </form>
</div>
