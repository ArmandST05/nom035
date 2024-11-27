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
                                
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
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
