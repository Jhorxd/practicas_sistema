<style>
/* 🌟 Estilo general moderno y llamativo */
body {
    background: linear-gradient(135deg, #e0f7fa, #e1bee7);
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    padding: 20px;
    color: #1f2937;
}

/* Tarjetas */
.module-card {
    background-color: #ffffff;
    border-radius: 25px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    transition: all 0.4s ease;
}

.module-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

/* Títulos */
.card-title {
    font-weight: 800;
    font-size: 1.6rem;
    color: #000000ff;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Texto */
.card-text {
    font-size: 1rem;
    color: #334155;
    margin-bottom: 12px;
}

/* Botones */
.btn {
    border-radius: 15px;
    padding: 12px 22px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.btn-primary {
    background: linear-gradient(45deg, #3b82f6, #3b82f6);
    color: #fff;
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    transform: scale(1.05);
}

.btn-secondary {
    background: linear-gradient(45deg, #6b7280, #9ca3af);
    color: #fff;
    border: none;
}

.btn-secondary:hover {
    background: linear-gradient(45deg, #4b5563, #6b7280);
    transform: scale(1.05);
}

.btn-danger {
    background: linear-gradient(45deg, #ef4444, #f87171);
    color: #fff;
    border: none;
}

.btn-danger:hover {
    background: linear-gradient(45deg, #b91c1c, #ef4444);
    transform: scale(1.05);
}

/* Inputs */
.form-control {
    padding: 10px 15px;
    border-radius: 15px;
    border: 1px solid #cbd5e1;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 8px rgba(59,130,246,0.3);
    outline: none;
}

/* Tabla */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    border-radius: 12px;
    overflow: hidden;
}

table th, table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
}

table thead tr {
    background: linear-gradient(90deg, #3b82f6, #3b82f6);
    color: #fff;
}

table tbody tr:hover {
    background-color: #f3f4f6;
    transition: all 0.2s ease;
}

/* Lista de adjuntos */
ul {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

ul li a img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

ul li a img:hover {
    transform: scale(1.1);
    border-color: #3b82f6;
}

/* Mini tarjetas de estadísticas */
.stats-card {
    flex: 1;
    min-width: 150px;
    background: linear-gradient(135deg, #3b82f6, #3b82f6);
    color: #fff;
    padding: 20px;
    border-radius: 18px;
    text-align: center;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 12px 25px rgba(0,0,0,0.2);
}

.stats-card h3 {
    font-size: 2rem;
    margin-bottom: 5px;
}

.stats-card p {
    font-size: 1rem;
    margin: 0;
}

/* Botón volver */
.mb-3 a {
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    display: inline-block;
    padding: 10px 18px;
    border-radius: 15px;
    background: linear-gradient(45deg, #64748b, #1e293b);
    color: #fff;
    transition: all 0.3s ease;
}

.mb-3 a:hover {
    background: linear-gradient(45deg, #475569, #334155);
    transform: scale(1.05);
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
