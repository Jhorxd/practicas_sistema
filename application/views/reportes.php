<!-- Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<style>
/* 🌟 Estilo general dashboard */
body {
    background: linear-gradient(135deg, #e0f2fe, #dbeafe);
    font-family: 'Poppins', sans-serif;
    padding: 40px;
    min-height: 100vh;
}

/* Tarjetas principales */
.module-card {
    background-color: #fff;
    border-radius: 25px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.module-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.card-title {
    font-weight: 800;
    font-size: 2rem;
    color: #1e293b;
    margin-bottom: 20px;
}

.card-text {
    font-size: 1.1rem;
    color: #334155;
    margin-bottom: 25px;
}

/* Grid de estadísticas */
.stats-grid {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.stat-card {
    flex: 1;
    min-width: 180px;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 18px;
    padding: 30px 20px;
    text-align: center;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.stat-card h3 {
    font-size: 2.5rem;
    color: #111827;
    margin-bottom: 10px;
}

.stat-card p {
    font-size: 1.1rem;
    font-weight: 600;
    color: #374151;
}

/* Contenedor de gráficos */
.chart-container {
    display: flex;
    gap: 35px;
    flex-wrap: wrap;
    margin-top: 40px;
}

.chart-box {
    flex: 1;
    min-width: 400px;
    height: 400px;
    background: #f3f4f6;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s;
}

.chart-box:hover {
    transform: translateY(-5px);
}

        /* Botón de volver al dashboard */
.btn-dashboard {
    display: inline-block;
    padding: 10px 20px;
    background-color: #64748b; /* mismo color que btn-secondary */
    color: #fff;
    border-radius: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn-dashboard:hover {
    background-color: #475569; /* mismo hover que btn-secondary */
}


</style>

<div class="mb-3">
    <a href="<?= base_url('dashboard'); ?>" class="btn-dashboard">← Volver al Dashboard</a>
</div>
<br>
<Br></Br>

<div class="module-card">
    <h2 class="card-title">📊 Reportes del Proyecto</h2>
    <p class="card-text">Visualiza estadísticas, métricas y progreso del proyecto de forma clara e interactiva.</p>

    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, #fef3c7, #facc15);">
            <h3><?= $total_requerimientos ?></h3>
            <p>Total Requerimientos</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #bfdbfe, #3b82f6);">
            <h3><?= $pendientes ?></h3>
            <p>Pendientes</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #bbf7d0, #10b981);">
            <h3><?= $aprobados ?></h3>
            <p>Aprobados</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #fbcfe8, #ec4899);">
            <h3><?= $avance_promedio ?>%</h3>
            <p>Avance Promedio</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #fcd5d5, #ef4444);">
            <h3><?= $total_acciones ?></h3>
            <p>Acciones Registradas</p>
        </div>
    </div>

    <div class="chart-container">
        <div class="chart-box" id="chart_estado"></div>
        <div class="chart-box" id="chart_avance"></div>
    </div>
</div>

<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {
    // Pie Chart - Estado
    var dataEstado = google.visualization.arrayToDataTable([
        ['Estado', 'Cantidad'],
        ['Pendientes', <?= $pendientes ?>],
        ['Aprobados', <?= $aprobados ?>],
        ['Rechazados', <?= $rechazados ?>]
    ]);

    var optionsEstado = {
        title: 'Requerimientos por Estado',
        pieHole: 0.4,
        colors: ['#f59e0b', '#10b981', '#ef4444'],
        fontSize: 16,
        chartArea: {width: '85%', height: '80%'},
        legend: {position: 'bottom', alignment: 'center'}
    };

    var chart = new google.visualization.PieChart(document.getElementById('chart_estado'));
    chart.draw(dataEstado, optionsEstado);

    // Bar Chart - Avance Promedio
    var dataAvance = google.visualization.arrayToDataTable([
        ['Proyecto', 'Avance (%)'],
        ['Avance Promedio', <?= $avance_promedio ?>]
    ]);

    var optionsAvance = {
        title: 'Avance Promedio de Requerimientos',
        legend: { position: 'none' },
        colors: ['#3b82f6'],
        vAxis: { minValue: 0, maxValue: 100, format: 'percent' },
        fontSize: 14,
        chartArea: {width: '70%', height: '70%'}
    };

    var chart2 = new google.visualization.BarChart(document.getElementById('chart_avance'));
    chart2.draw(dataAvance, optionsAvance);
}
</script>
