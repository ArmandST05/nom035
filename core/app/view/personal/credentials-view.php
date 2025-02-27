<?php

$departamentos = DepartamentoData::getAll();
$encuestas = EncuestaData::getAll();


?>



<div class="container mt-4">
        <div class="row">
            <!-- Columna del texto -->
            <div class="col-md-8">
                <h4>Listado Personal</h4>
                <p>
                    Indicaciones: Este modulo es para seleccionar al personal al que se le enviaran las credenciales
                    por correo o por whatsapp.
                </p>
            </div>
            <!-- Columna de los botones -->
            <div class="col-md-4">
                <div class="d-flex flex-column gap-2">
                    
                    <button type="button" class="btn btn-primary" onclick="sendMassiveMail()">Enviar por correo</button>
                    <button type="button" class="btn btn-primary" onclick="sendMassiveWhatsapp()">Enviar por WhatsApp</button>
       
                </div>
            </div>
        </div>
</div>
<div class="row mb-4">
    <!-- Filtro por Departamento -->
    <div class="col-md-4">
        <div class="form-group">
            <label for="filter-department">Filtrar por Departamento:</label>
        <select id="filter-department" class="form-control custom-select-width">
            <option value="">Todos los departamentos</option> <!-- Opción por defecto -->
        </select>

        </div>
    </div>
    <!-- Campo de Búsqueda -->
    <div class="col-md-4">
        <div class="form-group">
            <label for="custom-search">Buscar:</label>
            <input type="text" id="custom-search" class="form-control" placeholder="Escribe para buscar...">
        </div>
    </div>

    <!-- Selección de Cantidad de Resultados -->
    <div class="col-md-4">
        <div class="form-group">
            <label for="custom-length">Mostrar registros:</label>
            <select id="custom-length" class="form-control">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>
</div>


    <div class="card" style="width: 100%;  margin-top: 20px">
    <div class="card-body">
        <table id="lookup" class="table table-striped table-hover">
            <thead style="background-color: #484848; color: white; border-radius: 5px;">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Departamento / Puesto</th>
                    <th>Usuario</th>
                    <th>Clave</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    
                </tr>
            </thead>
            <tbody>
                <!-- El cuerpo se gestionará dinámicamente por DataTables -->
            </tbody>
        </table>
    </div>
</div>


    </div>
</div>


<script>
function getSelectedUsers() {
    let selectedUsers = [];
    $(".employee-checkbox:checked").each(function() {
        selectedUsers.push($(this).val()); // Agregar el ID del usuario seleccionado
    });

    if (selectedUsers.length === 0) {
        alert("Por favor, selecciona al menos un usuario.");
        return null;
    }
    return selectedUsers;
    console.log(selectedUsers)
}

function sendMassiveMail() {
    let selectedUsers = getSelectedUsers();
    if (!selectedUsers) return; // Si no hay usuarios seleccionados, detener la ejecución

    $.ajax({
        url: './?action=notifications/send-massive-mail',
        method: 'POST',
        data: JSON.stringify({ users: selectedUsers }),
        contentType: 'application/json',
        dataType: 'json',
        success: function (response) {
            console.log(response.message);
        },
        error: function (xhr) {
            console.log("Error en la solicitud: " + xhr.status + " " + xhr.responseText);
        }
    });
}


$(document).ready(function() {
    
    var dataTable = $('#lookup').DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "ordering": false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./?action=personal/get-all-credentials",
            type: "POST",
            data: function(d) {
                d.department_filter = $('#filter-department').val();
                d.custom_search = $('#custom-search').val();
                d.length = $('#custom-length').val();
            },
            dataSrc: function(json) {
                return json.data;  // Asegúrate de que data es lo que DataTable espera
            },
            error: function(xhr, error, code) {
            }
        },
        "responsive": true,
        "scrollX": true,
        "dom": '<"datatable-content"t><"datatable-footer"ip>', // Coloca la paginación e información en la parte inferior
    });

    $.ajax({
    url: './?action=departamentos/get-all', // Endpoint para obtener todos los departamentos
    method: 'GET',
    success: function(data) {
        var departmentSelect = $('#filter-department');
        data.forEach(function(department) {
            departmentSelect.append('<option value="' + department.id + '">' + department.nombre + '</option>');
        });
    }
});

    // Recargar DataTable al cambiar el filtro
    $('#filter-department').change(function() {
        dataTable.ajax.reload();
    });

    // Funcionalidad para la búsqueda
    $('#custom-search').on('keyup', function () {
        dataTable.ajax.reload();
    });

    // Funcionalidad para la selección de longitud
    $('#custom-length').change(function() {
        dataTable.ajax.reload();
    });
});





    </script>
