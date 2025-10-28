<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cotizaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/cotizacion.css'); ?>">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>

<div class="container mt-5 fade-in">
    <h2 class="text-center mb-4">💼 Gestión de Cotizaciones</h2>

        <!-- Botón volver al dashboard -->
    <div class="mb-3">
        <a href="<?= base_url('dashboard'); ?>" class="btn btn-secondary">← Volver al Dashboard</a>
    </div>

    <div class="card p-4 shadow-sm">
        <table id="tablaProyectos" class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Nombre del Proyecto</th>
                    <th>Fecha de Registro</th>
                    <th>Total Cotizado (S/)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($proyectos)): ?>
                    <?php foreach ($proyectos as $i => $p): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1; ?></td>
                            <td><?= $p->nombre_proyecto; ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($p->fecha_creacion)); ?></td>
                            <td class="text-end"><?= number_format($p->monto_total  ?? 0, 2); ?></td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm btnCotizar"
                                        data-id="<?= $p->id_proyecto; ?>"
                                        data-nombre="<?= htmlspecialchars($p->nombre_proyecto); ?>">
                                    💰 Cotizar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No hay proyectos registrados</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL DE COTIZACIÓN -->
<div class="modal fade" id="modalCotizar" tabindex="-1" aria-labelledby="modalCotizarLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <form id="formCotizacionModal" method="post" action="<?= base_url('cotizaciones/guardar'); ?>">
        <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="modalCotizarLabel">💰 Cotización del Proyecto</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id_proyecto" id="idProyectoModal">
            <h5 id="nombreProyectoModal" class="text-center mb-4"></h5>

            <!-- Selección de moneda -->
            <div class="mb-3 text-end">
                <label class="form-label fw-bold me-2">Moneda:</label>
                <select class="form-select d-inline-block w-auto" name="moneda" id="moneda" required>
                    <option value="PEN">Soles (S/)</option>
                    <option value="USD">Dólares ($)</option>
                </select>
            </div>

            <!-- Tabla de requerimientos -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="tablaRequerimientos">
                    <thead class="table-secondary text-center">
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th style="width: 100px;">Cantidad</th>
                            <th style="width: 150px;">Precio Unitario (S/)</th>
                            <th>Subtotal (S/)</th>
                        </tr>
                    </thead>
                    <tbody id="listaRequerimientos">
                        <tr><td colspan="6" class="text-center text-muted">Seleccione un proyecto para ver sus requerimientos</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Total -->
            <div class="text-end mt-3">
                <h4>Total: S/ <span id="totalModal">0.00</span></h4>
            </div>

            <!-- Observaciones -->
            <div class="mt-3">
                <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                <textarea class="form-control" name="observaciones" id="observaciones" rows="3"></textarea>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">💾 Guardar Cotización</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {

    $('.btnCotizar').on('click', function() {
        const idProyecto = $(this).data('id');
        const nombre = $(this).data('nombre');

        console.log("🟦 Clic en cotizar proyecto:", idProyecto, nombre);

        $('#idProyectoModal').val(idProyecto);
        $('#nombreProyectoModal').text('Proyecto: ' + nombre);
        $('#listaRequerimientos').html('<tr><td colspan="6" class="text-center text-muted">Cargando...</td></tr>');
        $('#totalModal').text('0.00');

        $.ajax({
            url: '<?= base_url("cotizaciones/obtener_requerimientos"); ?>/' + idProyecto,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log("✅ Requerimientos recibidos:", data);
                if (data.length > 0) {
                    let filas = '';
                    let total = 0;

                    data.forEach((req, index) => {
                        const precio = req.precio_unitario || 0;
                        const subtotal = parseFloat(precio).toFixed(2);
                        total += parseFloat(subtotal);

                        filas += `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td>${req.titulo}</td>
                                <td>${req.descripcion}</td>
                                <td class="text-center">1</td> <!-- cantidad fija -->
                                <td>
                                    <input type="number" 
                                           name="detalles[${index}][precio_unitario]" 
                                           class="form-control precio text-center" 
                                           step="0.01" 
                                           min="0" 
                                           value="${precio}">
                                </td>
                                <td class="subtotal text-center">${subtotal}</td>
                                <input type="hidden" name="detalles[${index}][id_requerimiento]" value="${req.id_requerimiento}">
                                <input type="hidden" name="detalles[${index}][descripcion]" value="${req.descripcion}">
                            </tr>
                        `;
                    });

                    $('#listaRequerimientos').html(filas);
                    $('#totalModal').text(total.toFixed(2));
                } else {
                    $('#listaRequerimientos').html('<tr><td colspan="6" class="text-center text-muted">No hay requerimientos para este proyecto</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error("❌ Error AJAX:", {xhr, status, error});
                $('#listaRequerimientos').html('<tr><td colspan="6" class="text-center text-danger">Error al cargar requerimientos</td></tr>');
            }
        });

        $('#modalCotizar').modal('show');
    });

    // Calcular total al cambiar precios
    $(document).on('input', '.precio', function() {
        let total = 0;
        $('#tablaRequerimientos tbody tr').each(function() {
            const precio = parseFloat($(this).find('.precio').val()) || 0;
            const subtotal = precio; // subtotal = precio porque cantidad = 1
            $(this).find('.subtotal').text(subtotal.toFixed(2));
            total += subtotal;
        });
        $('#totalModal').text(total.toFixed(2));
    });

});

</script>
</body>
</html>
