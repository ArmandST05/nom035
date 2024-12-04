<?php

$departamentos = DepartamentoData::getAll();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal</title>
    <link rel="stylesheet" href="path_to_bootstrap.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



</head>
<body>
<style>


</style>



<div class="container mt-4">
        <div class="row">
            <!-- Columna del texto -->
            <div class="col-md-8">
                <h4>Listado Personal</h4>
                <p>
                    Indicaciones: En este módulo podrá agregar, eliminar y editar el personal. 
                    Utilice la opción Carga Masiva para cargar al personal mediante un archivo de Microsoft Excel.
                    Recuerde que al agregar a un personal se le activarán las encuestas seleccionadas del puesto.
                    De igual manera tiene la opción de seleccionar las encuestas a contestar de manera independiente 
                    por personal en la opción Encuestas seleccionadas.
                </p>
            </div>
            <!-- Columna de los botones -->
            <div class="col-md-4">
                <div class="d-flex flex-column gap-2">
                    
                            <button type="button" class="btn btn-primary" onclick="openModalAddPersonal()">Agregar Personal</button>
                                         
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
                    <th>Opciones</th>
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
    <!-- Modal Nuevo Empleado -->
    <div class="modal fade" id="PersonalModal" tabindex="-1" role="dialog" aria-labelledby="PersonalModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Agregar Nuevo Personal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se colocarán los campos del formulario -->
                    <form id="addPersonal" action="index.php?action=personal/add" method="POST"> 
                        <div class="form-group">
                            <label for="employeeName">Nombre del Personal</label>
                            <input type="text" class="form-control" id="employeeName" placeholder="Ingrese el nombre">
                        </div>
                        <div class="form-group">
                            <label for="employeeEmail">Correo Electrónico</label>
                            <input type="email" class="form-control" id="employeeEmail" name="email" placeholder="Ingrese el correo" required>
                        </div>
                        <div class="form-group">
                            <label for="employeeRole">Puesto</label>
                            <input type="text" class="form-control" id="employeeRole" placeholder="Ingrese el puesto">
                        </div>
                        <div class="form-group">
                            <label for="employeeDepartment">Departamento</label>
                            <select class="form-control" id="employeeDepartment" name="id_departamento" required>
                                <option value="">Seleccione un departamento</option>
                                <?php 
                                    if(!empty ($departamentos)){
                                        foreach($departamentos as $departamento){
                                            echo "<option value='{$departamento->idDepartamento}'>{$departamento->nombre}</option>";

                                        }
                                    }

                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                        <label for="employeeDate">Fecha de Alta</label>
                        <input type="date" class="form-control" id="employeeDate" name="fecha_alta" required>
                    </div>
                    <div class="form-group">
                        <label for="employeePhone">Teléfono</label>
                        <input type="text" class="form-control" id="employeePhone" name="phone" placeholder="Ingrese el teléfono (Opcional)">
                    </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="savePersonalBtn" class="btn btn-primary" onclick="addPersonal()">Guardar</button>
                    </div>
            </div>
        </div>
    </div>   
    
   <!-- Modal Editar Personal -->
<div class="modal fade" id="EditPersonalModal" tabindex="-1" role="dialog" aria-labelledby="EditPersonalModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="EditPersonalModalTitle">Editar Personal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php 
            
            ?>
            <div class="modal-body">
            <form id="editPersonalForm" action="index.php?action=personal/update" method="POST">
    <div class="form-group">
        <label for="editEmployeeName">Nombre del Personal</label>
        <input type="text"  class="form-control"  id="editEmployeeName" placeholder="Ingrese el nombre">
    </div>
    <div class="form-group">
        <label for="editEmployeeEmail">Correo Electrónico</label>
        <input type="email" class="form-control" id="editEmployeeEmail"placeholder="Ingrese el correo">
    </div>
    <div class="form-group">
        <label for="editEmployeeRole">Puesto</label>
        <input type="text" class="form-control" id="editEmployeeRole" placeholder="Ingrese el puesto">
    </div>
    <div class="form-group">
        <label for="editEmployeeDepartment">Departamento</label>
        <select class="form-control" id="editEmployeeDepartment">
                            <option value="">Seleccione un departamento</option>
                            <!-- Opciones dinámicas -->
                            <?php foreach ($departamentos as $departamento): ?>
                                <option 
                                    value="<?php echo $departamento->idDepartamento; ?>">
                                    <?php echo htmlspecialchars($departamento->nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
    </div>
    <div class="form-group">
        <label for="editEmployeeDate">Fecha de Alta</label>
        <input type="date"  class="form-control" id="editEmployeeDate">
    </div>
    <div class="form-group">
        <label for="editEmployeePhone">Teléfono</label>
        <input type="text" class="form-control" id="editEmployeePhone" placeholder="Ingrese el teléfono (Opcional)">
    </div>
</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="updatePersonal()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    
    $(document).ready(function () {
    // Manejador para el botón de tres puntos
    $(document).on('click', '.dropdown-toggle', function (e) {
        e.preventDefault();
        const menuId = $(this).attr('id').replace('dropdownMenuButton', 'dropdownMenu');
        
        // Cierra otros menús abiertos
        $('.dropdown-menu').not(`#${menuId}`).hide();
        
        // Alterna el menú asociado
        $(`#${menuId}`).toggle();
    });

    // Cierra el menú si se hace clic fuera de él
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').hide();
        }
    });
});
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
            url: "./?action=personal/get-all",
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


function editPersonal(personalId) {
    editingPersonalId = personalId;  // Guardamos el ID del personal a editar

    $.ajax({
        url: "./?action=personal/get&id=" + personalId, // Verifica que el ID se esté pasando correctamente
        type: 'GET',
        success: function(response) {
            console.log("Datos recibidoooooos:", response); // Para depuración
                 // Asegúrate de que sea un objeto, si es un string JSON conviértelo
    if (typeof response === "string") {
        try {
            response = JSON.parse(response);
        } catch (e) {
            console.error("Error al parsear JSON:", e);
            alert("Error al procesar los datos.");
            return;
        }
    }
            if (response.error) {
                alert(response.error); // Si hay un error, muéstralo
            } else {
                // Asignar los valores recibidos a los campos del formulario
                $('#editEmployeeName').val(response.nombre);
                $('#editEmployeeEmail').val(response.correo);
                $('#editEmployeeRole').val(response.id_puesto);
                $('#editEmployeeDepartment').val(response.id_departamento);
                $('#editEmployeeDate').val(response.fecha_alta);
                $('#editEmployeePhone').val(response.telefono);

                // Mostrar el modal
                $('#EditPersonalModal').modal('show');
            }
        },
        error: function(xhr, status, error) {
            alert("Hubo un error al cargar los datos del personal.");
        }
    });
}


// Función para actualizar el personal
function updatePersonal() {
    // Recopilamos los datos del formulario
   var updatedPersonalData = {
    "id": editingPersonalId,
    "nombre": $('#editEmployeeName').val(),
    "correo": $('#editEmployeeEmail').val(),
    "id_puesto": $('#editEmployeeRole').val(),
    "id_departamento": $('#editEmployeeDepartment').val(),
    "fecha_alta": $('#editEmployeeDate').val(),
    "telefono": $('#editEmployeePhone').val()
};


    $.ajax({
    url: "./?action=personal/update",
    type: 'POST',
    data: updatedPersonalData,
    success: function(response) {
        try {
            if (typeof response === "string") {
                response = JSON.parse(response); // Asegúrate de que sea un objeto JSON
            }
            if (response.success) {
                alert("Personal actualizado correctamente.");
                window.location.reload(); // Recargar la página
            } else {
                alert("Error: " + (response.message || "Ocurrió un problema."));
            }
        } catch (error) {
            console.error("Error al procesar la respuesta del servidor:", error, response);
            alert("Hubo un error inesperado.");
        }
    },
    error: function(xhr, status, error) {
        alert("Error al comunicarse con el servidor.");
        console.error("AJAX error:", status, error);
    }
});

}


        function openModalAddPersonal() {
            // Configura el contenido dinámico del modal si es necesario.
            $('#PersonalModal').modal('show'); // Muestra el modal.
        }
        var newPersonalId = null;
        function addPersonal() {
    if (!newPersonalId) { // Verifica si el empleado no ha sido agregado
        // Prepara los datos del formulario
        var personalData = {
    "employeeName": $("#employeeName").val(),
    "employeeEmail": $("#employeeEmail").val(),
    "employeeRole": $("#employeeRole").val(),
    "employeeDepartment": $("#employeeDepartment").val(),
    "employeeDate": $("#employeeDate").val(),
    "employeePhone": $("#employeePhone").val()
};



        // Realiza la solicitud AJAX
        $.ajax({
            url: "./?action=personal/add", // Cambia a la URL correspondiente de tu backend
            type: 'POST',
            data: personalData,
            success: function(response) {
                // Asigna el ID del nuevo personal
                newPersonalId = response;

                // Muestra un mensaje de éxito y redirecciona
                alert("Personal agregado correctamente.");
                window.location = "index.php?view=personal/listado";
            },
            error: function() {
                alert("Ha ocurrido un error al almacenar los datos del personal.");
            }
        });
    } else {
        // Si el personal ya existe
        alert("Este personal ya existe.");
    }
}


function deletePersonal(puestoId, puestoName) {
    const swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: true
    });

    swalWithBootstrapButtons.fire({
        title: '¿Estás seguro de eliminar el puesto ' + puestoName + '?',
        text: "¡No podrás revertirlo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminarlo',
        cancelButtonText: '¡No, cancelarlo!',
        reverseButtons: true
    }).then((result) => {
        if (result.value === true) {
            // Redirigir a la acción de eliminación de puesto
            window.location.href = "index.php?action=personal/delete&id=" + puestoId;
        }
    });
}


    </script>
</body>
</html>