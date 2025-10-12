<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Requerimientos</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">🧾 Requerimientos</h2>

    <!-- Botón volver al dashboard -->
    <div class="mb-3">
        <a href="<?= base_url('dashboard'); ?>" class="btn btn-secondary">← Volver al Dashboard</a>
    </div>

    <!-- Filtro por proyecto -->
    <div class="mb-3">
        <label>Filtrar por proyecto:</label>
        <select id="filtroProyecto" class="form-select">
            <option value="">Todos los proyectos</option>
            <?php foreach($proyectos as $p): ?>
                <option value="<?= $p->id_proyecto; ?>"><?= $p->nombre_proyecto; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Botón para agregar nuevo requerimiento -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregar">Agregar Requerimiento</button>

    <!-- Tabla de requerimientos -->
    <table class="table table-bordered table-striped" id="tablaRequerimientos">
        <thead>
            <tr>
                <th>#</th>
                <th>Proyecto</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Fecha registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($requerimientos)): ?>
                <?php foreach ($requerimientos as $req): ?>
                    <tr data-proyecto="<?= $req->id_proyecto; ?>">
                        <td><?= $req->id_requerimiento; ?></td>
                        <td><?= $req->nombre_proyecto; ?></td>
                        <td><?= $req->titulo; ?></td>
                        <td><?= $req->descripcion; ?></td>
                        <td><?= ucfirst($req->prioridad); ?></td>
                        <td>
                            <select class="form-select cambiar-estado" data-id="<?= $req->id_requerimiento; ?>">
                                <option value="pendiente" <?= $req->estado=='pendiente'?'selected':''; ?>>Pendiente</option>
                                <option value="aprobado" <?= $req->estado=='aprobado'?'selected':''; ?>>Aprobado</option>
                                <option value="rechazado" <?= $req->estado=='rechazado'?'selected':''; ?>>Rechazado</option>
                            </select>
                        </td>
                        <td><?= $req->fecha_registro; ?></td>
                        <td>
                            <a href="<?= base_url('requerimientos/eliminar/'.$req->id_requerimiento); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Desea eliminar este requerimiento?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No hay requerimientos registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal para agregar requerimiento -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('requerimientos/guardar'); ?>" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAgregarLabel">Agregar Requerimiento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label>Proyecto:</label>
                  <select name="id_proyecto" class="form-control" required>
                      <option value="">Seleccione un proyecto</option>
                      <?php foreach($proyectos as $p): ?>
                          <option value="<?= $p->id_proyecto; ?>"><?= $p->nombre_proyecto; ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="mb-3">
                  <label>Título:</label>
                  <input type="text" name="titulo" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>Descripción:</label>
                  <textarea name="descripcion" class="form-control" rows="3"></textarea>
              </div>
              <div class="mb-3">
                  <label>Prioridad:</label>
                  <select name="prioridad" class="form-control">
                      <option value="baja">Baja</option>
                      <option value="media" selected>Media</option>
                      <option value="alta">Alta</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Guardar</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    // Cambiar estado de requerimiento
    $('.cambiar-estado').change(function(){
        var id = $(this).data('id');
        var estado = $(this).val();
        $.ajax({
            url: '<?= base_url("requerimientos/actualizar_estado"); ?>',
            type: 'POST',
            data: {id:id, estado:estado},
            success: function(res){
                alert('Estado actualizado correctamente');
            },
            error: function(){
                alert('Error al actualizar estado');
            }
        });
    });

    // Filtrar requerimientos por proyecto
    $('#filtroProyecto').change(function(){
        var proyecto = $(this).val();
        $('#tablaRequerimientos tbody tr').each(function(){
            if(proyecto === "" || $(this).data('proyecto') == proyecto){
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
