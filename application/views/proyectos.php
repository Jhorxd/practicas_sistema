<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proyectos</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">📁 Proyectos</h2>

        <!-- Botón volver al dashboard -->
    <div class="mb-3">
        <a href="<?= base_url('dashboard'); ?>" class="btn btn-secondary">← Volver al Dashboard</a>
    </div>


    <!-- Botón agregar proyecto -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregar">Agregar Proyecto</button>

    <!-- Tabla de proyectos -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Fecha creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($proyectos)): ?>
                <?php foreach ($proyectos as $p): ?>
                    <tr>
                        <td><?= $p->id_proyecto; ?></td>
                        <td><?= $p->nombre_usuario; ?></td>
                        <td><?= $p->nombre_proyecto; ?></td>
                        <td><?= $p->descripcion; ?></td>
                        <td>
                            <select class="form-select cambiar-estado" data-id="<?= $p->id_proyecto; ?>">
                                <option value="en cotizacion" <?= $p->estado=='en cotizacion'?'selected':''; ?>>En cotización</option>
                                <option value="en desarrollo" <?= $p->estado=='en desarrollo'?'selected':''; ?>>En desarrollo</option>
                                <option value="finalizado" <?= $p->estado=='finalizado'?'selected':''; ?>>Finalizado</option>
                            </select>
                        </td>
                        <td><?= $p->fecha_creacion; ?></td>
                        <td>
                            <a href="<?= base_url('proyectos/eliminar/'.$p->id_proyecto); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Desea eliminar este proyecto?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No hay proyectos registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal para agregar proyecto -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('proyectos/guardar'); ?>" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAgregarLabel">Agregar Proyecto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label>Cliente:</label>
                  <select name="id_usuario" class="form-control" required>
                      <option value="">Seleccione un cliente</option>
                      <?php foreach($usuarios as $u): ?>
                          <option value="<?= $u->id_usuario; ?>"><?= $u->nombre; ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="mb-3">
                  <label>Nombre del proyecto:</label>
                  <input type="text" name="nombre_proyecto" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>Descripción:</label>
                  <textarea name="descripcion" class="form-control" rows="3"></textarea>
              </div>
              <div class="mb-3">
                  <label>Estado:</label>
                  <select name="estado" class="form-control">
                      <option value="en cotizacion" selected>En cotización</option>
                      <option value="en desarrollo">En desarrollo</option>
                      <option value="finalizado">Finalizado</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    $('.cambiar-estado').change(function(){
        var id = $(this).data('id');
        var estado = $(this).val();
        $.ajax({
            url: '<?= base_url("proyectos/actualizar_estado"); ?>',
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
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
