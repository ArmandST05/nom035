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
.dropdown {
    position: absolute;
    display: inline-block;
}

.dropdown-menu {
    display: none; /* El menú está oculto por defecto */
    position: absolute;
    top: 0; /* Al nivel del botón */
    right: 100%; /* Se posicionará hacia la izquierda del botón */
    transform: translateX(-8px); /* Ajusta un pequeño margen para separarlo del botón */
    background-color: #fff;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    min-width: 160px;
    z-index: 1;
}

.dropdown-menu li {
    padding: 8px 12px;
    cursor: pointer;
}

.dropdown-menu li:hover {
    background-color: #ddd;
}
.custom-select-width {
    max-width: 300px; /* Cambia a lo que necesites */
    width: 100%; /* Para que se ajuste al contenedor */
    display: inline-block; /* Evita que se estire */
}

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
    <div class="form-group">
    <label for="filter-department">Filtrar por Departamento:</label>
    <select id="filter-department" class="form-control custom-select-width">
        <option value="">Todos los departamentos</option>
        <!-- Las opciones serán generadas dinámicamente desde el servidor -->
    </select>
</div>

    <div class="card" style="width: 95%;  margin-top: 20px; ">
    <div class="card-body">
        <table id="lookup" class="table table-striped table-hover">
            <thead style="background-color: grey; color: white;">
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
                <form id="editPersonalForm">
                    <div class="form-group">
                        <label for="editEmployeeName">Nombre del Personal</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="editEmployeeName" 
                            placeholder="Ingrese el nombre" 
                            value="<?php echo isset($personal->nombre) ? htmlspecialchars($personal->nombre) : ''; ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="editEmployeeEmail">Correo Electrónico</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="editEmployeeEmail" 
                            placeholder="Ingrese el correo" 
                            value="<?php echo isset($personal->correo) ? htmlspecialchars($personal->correo) : ''; ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="editEmployeeRole">Puesto</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="editEmployeeRole" 
                            placeholder="Ingrese el puesto" 
                            value="<?php echo isset($personal->puesto) ? htmlspecialchars($personal->puesto) : ''; ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="editEmployeeDepartment">Departamento</label>
                        <select class="form-control" id="editEmployeeDepartment">
                            <option value="">Seleccione un departamento</option>
                            <!-- Opciones dinámicas -->
                            <?php foreach ($departamentos as $departamento): ?>
                                <option 
                                    value="<?php echo $departamento->id; ?>" 
                                    <?php echo (isset($personal->id_departamento) && $personal->id_departamento == $departamento->id) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($departamento->nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editEmployeeDate">Fecha de Alta</label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="editEmployeeDate" 
                            value="<?php echo isset($personal->fecha_alta) ? htmlspecialchars($personal->fecha_alta) : ''; ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="editEmployeePhone">Teléfono</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="editEmployeePhone" 
                            placeholder="Ingrese el teléfono (Opcional)" 
                            value="<?php echo isset($personal->telefono) ? htmlspecialchars($personal->telefono) : ''; ?>"
                        >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="savePersonal()">Guardar Cambios</button>
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
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "ordering": false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./?action=personal/get-all", // json datasource
            type: "POST",
            data: function(d) {
                // Agregar el valor seleccionado del filtro al request
                d.department_filter = $('#filter-department').val();
            },
            error: function() {
                $(".lookup-error").html("");
                $("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se han encontrado datos.</th></tr></tbody>');
                $("#lookup_processing").css("display", "none");
            }
        },
        "responsive": true,
        "scrollX": true
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
    // Al cambiar el filtro, recargar DataTable
    $('#filter-department').change(function() {
        dataTable.ajax.reload();
    });
});

function editPersonal(personalId) {
    editingPersonalId = personalId;  // Guardamos el ID del personal a editar

    $.ajax({
        url: "./?action=personal/get&id=" + personalId, // Verifica que el ID se esté pasando correctamente
        type: 'GET',
        success: function(response) {
            console.log("Datos recibidos:", response); // Para depuración

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
            console.log(xhr.responseText);  
            alert("Hubo un error al cargar los datos del personal.");
        }
    });
}



// Función para actualizar el personal
function updatePersonal() {
    // Recopilamos los datos del formulario
    var updatedPersonalData = {
        "personalId": editingPersonalId,
        "employeeName": $('#editEmployeeName').val(),
        "employeeEmail": $('#editEmployeeEmail').val(),
        "employeeRole": $('#editEmployeeRole').val(),
        "employeeDepartment": $('#editEmployeeDepartment').val(),
        "employeeDate": $('#editEmployeeDate').val(),
        "employeePhone": $('#editEmployeePhone').val()
    };

    // Realizamos la solicitud AJAX para actualizar el personal
    $.ajax({
        url: "./?action=personal/update", // Cambia la URL al endpoint de actualización
        type: 'POST',
        data: updatedPersonalData,
        success: function(response) {
            if (response.success) {
                // Si la actualización es exitosa, mostramos un mensaje y actualizamos la lista
                alert("Personal actualizado correctamente.");
                window.location.reload();  // Recarga la página para reflejar los cambios
            } else {
                alert("Error al actualizar los datos del personal.");
            }
        },
        error: function() {
            alert("Hubo un error al actualizar los datos.");
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



    </script>
</body>
</html>