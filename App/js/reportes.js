function toggleInputs() {
    const tipoReporte = document.getElementById('tipo_reporte').value;
    const otraLista = document.getElementById('otra_lista');
    const fechaRango = document.getElementById('fecha_rango');
    const anioInput = document.getElementById('anio_input');
    const cicloInput = document.getElementById('ciclo_input');
    const diaInput = document.getElementById('dia_input');
    const listaGeneral = document.getElementById('lista_general');
    const numeroLaboratorioGroup = document.getElementById('numero_laboratorio_group');


    document.getElementById('desde').value = '';
    document.getElementById('hasta').value = '';
    document.getElementById('anio').value = '';
    document.getElementById('ciclo1').checked = false;
    document.getElementById('ciclo2').checked = false;
    document.getElementById('dia').value = '';

    fechaRango.style.display = 'none';
    anioInput.style.display = 'none';
    cicloInput.style.display = 'none';
    diaInput.style.display = 'none';
    listaGeneral.style.display = 'none';
    numeroLaboratorioGroup.style.display = 'block'; // Por defecto, mostrar el campo de número de laboratorio

    if (tipoReporte === 'rango') {
        fechaRango.style.display = 'block';
    } else if (tipoReporte === 'anio') {
        anioInput.style.display = 'block';
    } else if (tipoReporte === 'ciclo') {
        anioInput.style.display = 'block';
        cicloInput.style.display = 'block';
    } else if (tipoReporte === 'dia') {
        diaInput.style.display = 'block';
    } else if (tipoReporte === 'general') {
        listaGeneral.style.display = 'block';
        numeroLaboratorioGroup.style.display = 'none'; // Ocultar el campo de número de laboratorio cuando se selecciona "General"
        toggleOtraLista();
    }
}

function toggleOtraLista() {
    const otraLista = document.getElementById('otra_lista').value;
    const fechaRango = document.getElementById('fecha_rango');
    const anioInput = document.getElementById('anio_input');
    const cicloInput = document.getElementById('ciclo_input');
    const diaInput = document.getElementById('dia_input');

    document.getElementById('desde').value = '';
    document.getElementById('hasta').value = '';
    document.getElementById('anio').value = '';
    document.getElementById('ciclo1').checked = false;
    document.getElementById('ciclo2').checked = false;
    document.getElementById('dia').value = '';

    // Ocultar todos los campos por defecto
    fechaRango.style.display = 'none';
    anioInput.style.display = 'none';
    cicloInput.style.display = 'none';
    diaInput.style.display = 'none';

    // Mostrar el campo correspondiente según la selección
    if (otraLista === 'rango') {
        fechaRango.style.display = 'block';
    } else if (otraLista === 'anio') {
        anioInput.style.display = 'block';
    } else if (otraLista === 'ciclo') {
        // Si se selecciona "Ciclo", se muestra tanto el campo de año como el de ciclo
        anioInput.style.display = 'block';
        cicloInput.style.display = 'block';
    } else if (otraLista === 'dia') {
        diaInput.style.display = 'block';
    }
}




document.addEventListener('DOMContentLoaded', function () {
    toggleInputs();
});

