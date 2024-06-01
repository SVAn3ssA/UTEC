
function toggleInputs() {
    const tipoReporte = document.getElementById('tipo_reporte').value;
    const fechaRango = document.getElementById('fecha_rango');
    const anioInput = document.getElementById('anio_input');
    const cicloInput = document.getElementById('ciclo_input');
    const diaInput = document.getElementById('dia_input');

    // Clear all input values
    document.getElementById('desde').value = '';
    document.getElementById('hasta').value = '';
    document.getElementById('anio').value = '';
    document.getElementById('numero_laboratorio').value = '';
    document.getElementById('ciclo1').checked = false;
    document.getElementById('ciclo2').checked = false;
    document.getElementById('dia').value = '';

    // Hide all input sections
    fechaRango.style.display = 'none';
    anioInput.style.display = 'none';
    cicloInput.style.display = 'none';
    diaInput.style.display = 'none';

    // Show relevant input sections based on tipo_reporte
    if (tipoReporte === 'rango') {
        fechaRango.style.display = 'block';
    } else if (tipoReporte === 'anio') {
        anioInput.style.display = 'block';
    } else if (tipoReporte === 'ciclo') {
        anioInput.style.display = 'block';
        cicloInput.style.display = 'block';
    } else if (tipoReporte === 'dia') {
        diaInput.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    toggleInputs(); // Ensure correct inputs are shown on page load
});
