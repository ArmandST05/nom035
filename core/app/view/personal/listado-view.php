<?php 

$departamentos = DepartamentoData::getAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal</title>
    
    <style>
        ul {
            list-style-type: none;
        }
        ul li {
            margin-top: 5px;
        }
        #nuevoPersonal {
            background-color: #0016cc;
        }
    </style>
</head>
<body>
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
                    <ul>
                        <li>
                            <button type="button" class="btn btn-primary" onclick="openModalAddPersonal()">Agregar Personal</button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-secondary">Exportar Excel</button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-success">Carga Masiva</button>
                        </li>
                    </ul>                    
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
                    <form id="addPersonalForm" action="../../action/personal/add-action.php" method="POST"> 
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
                                            echo "<option value='{$departamento->id}'>{$departamento->name}</option>";

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
                        <input type="text" class="form-control" id="employeePhone" name="phone" placeholder="Ingrese el teléfono">
                    </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="savePersonalBtn" class="btn btn-primary">Guardar</button>                </div>
            </div>
        </div>
    </div>
                                
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function openModalAddPersonal() {
            // Configura el contenido dinámico del modal si es necesario.
            $('#PersonalModal').modal('show'); // Muestra el modal.
        }

        $(document).ready(function() {
    // Enviar el formulario al hacer clic en "Guardar"
    $('#savePersonalBtn').on('click', function() {
        var formData = $('#addPersonalForm').serialize();  // Obtiene los datos del formulario

        // Realizar la solicitud AJAX
        $.ajax({
            url: $('#addPersonalForm').attr('action'),  // Usa la acción definida en el formulario
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    alert('Personal agregado correctamente');
                    $('#PersonalModal').modal('hide');  // Cierra el modal
                    $('#addPersonalForm')[0].reset();  // Resetea el formulario
                } else {
                    alert('Hubo un error al agregar al personal');
                }
            },
            error: function() {
                alert('Error en la solicitud AJAX');
            }
        });
    });
});


    </script>
</body>
</html>
