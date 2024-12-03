<?php

$encuestas = EncuestaData::getAll();
$departamentos = DepartamentoData::getAll();
$allPuestos = PuestoData::getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>puestos</title>
    <link rel="stylesheet" href="path_to_bootstrap.css"> <!-- Asegúrate de que la hoja de estilos de Bootstrap esté incluida -->
</head>
<body>
    <div class="container mt-4"> <!-- Asegúrate de usar la clase container para que todo quede dentro del contenedor de Bootstrap -->
        <div class="row">
            <div class="col-md-8">
                <h4>Puestos</h4>
                <p>
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Suscipit, aliquam? In consequuntur fugiat perspiciatis cum? Beatae, iure minima cumque velit soluta nisi tenetur, quis repellendus culpa accusamus ab eius necessitatibus est veritatis reiciendis debitis fugit quibusdam et quo doloribus ut expedita illum! Amet aut corporis harum eum, iste excepturi ipsa.
                </p>
            </div>
            <div class="col-md-4">
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-primary" onclick="openModalAddPuesto()">Nuevo Puesto</button>
                </div>
            </div>
        </div>

        <!-- Card with table -->
        <div class="card" style="width: 95%;  margin-top: 20px; ">
    <div class="card-body">
        <table id="lookup" class="table table-striped table-hover">
            <thead style="background-color: grey; color: white;">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Departamento</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- El cuerpo se gestionará dinámicamente por DataTables -->
            </tbody>
        </table>
    </div>
</div>

    <!-- Modal for adding new puesto -->
    <div class="modal fade" id="PuestoModal" tabindex="-1" role="dialog" aria-labelledby="PuestoModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Agregar Nuevo Puesto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se colocarán los campos del formulario -->
                    <form id="addPuesto" action="index.php?action=puesto/add" method="POST"> 
                        <div class="form-group">
                            <label for="roleName">Nombre del Puesto</label>
                            <input type="text" class="form-control" id="roleName" placeholder="Ingrese el nombre">
                        </div>
                        <div class="form-group">
                            <label for="roleDepartment">Departamento</label>
                            <select class="form-control" id="id_departamento" name="id_departamento" required>
                                <option value="">Seleccione un departamento</option>
                                <?php 
                                    if(!empty($departamentos)){
                                        foreach($departamentos as $departamento){
                                            echo "<option value='{$departamento->idDepartamento}'>{$departamento->nombre}</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="roleEncuesta">Encuesta</label>
                            <select class="form-control" name="id_encuesta" id="id_encuesta">
                                <option value="">Seleccione una encuesta</option>
                                <?php 
                                    if(!empty($encuestas)){
                                        foreach($encuestas as $encuesta){
                                            echo "<option value='{$encuesta->id}'>{$encuesta->descripcion}</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="savePersonalBtn" class="btn btn-primary" onclick="addPuesto()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
           $(document).ready(function () {
    var dataTable = $('#lookup').DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
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
            url: "./?action=puestos/get-all", // Ruta al endpoint
            type: "POST",
            error: function () {
                $(".lookup-error").html("");
                $("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se han encontrado datos.</th></tr></tbody>');
                $("#lookup_processing").css("display", "none");
            }
        },
        "responsive": true,
        "scrollX": true
    });
});

        function openModalAddPuesto() {
            $('#PuestoModal').modal('show');
        }

        var newPuestoId = null;

        function addPuesto() {
            if (!newPuestoId) {
                var puestoData = {
                    "roleName": $("#roleName").val(),
                    "id_departamento": $("#id_departamento").val(),
                    "id_encuesta": $("#id_encuesta").val()
                };
                console.log(puestoData);
                $.ajax({
                    url: "./?action=puestos/add",
                    type: 'POST',
                    data: puestoData,
                    success: function(response) {
                        newPuestoId = response;
                        alert("Puesto agregado correctamente");
                        window.location = "index.php?view=puestos/index";
                    },
                    error: function() {
                        alert("Algo salió mal");
                    }
                });
            } else {
                alert("Este personal ya existe.");
            }
        }
    </script>

</body>
</html>
