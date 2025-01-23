<?php

$cantidadEmpleados = EmpresaData::getCantidades();

$empresas = EmpresaData::getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresas</title>
    <link rel="stylesheet" href="path_to_bootstrap.css"> <!-- Asegúrate de que la hoja de estilos de Bootstrap esté incluida -->
</head>
<body>
    <div class="container mt-4"> <!-- Asegúrate de usar la clase container para que todo quede dentro del contenedor de Bootstrap -->
        <div class="row">
            <div class="col-md-8">
                <h4>Empresas</h4>
                <p>
                Indicaciones: En este módulo podrá agregar, editar y eliminar razones sociales. Y podrá administrar a sus empleados depende a la razón social que seleccione.

            </p>
            </div>
            <div class="col-md-4">
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-primary" onclick="openModalAddPuesto()">Nueva razón socal</button>
                </div>
            </div>
        </div>

        <!-- Card with table -->
        <div class="card mt-4" style="width: 90%; margin: auto; margin-top: 20px;"> <!-- Agregar mt-4 para dar un margen superior -->
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead style="background-color: grey; color: white;">
                        <tr>
                            <th>#</th>
                            <th>Nombre de la razón social</th>
                            <th>Cantidad de personal</th>
                            <th>Comentarios</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if(!empty($empresas)){
                                $index= 1;
                                foreach ($empresas as $empresa) {
                                    echo "<tr>";
                                    echo "<td>{$index}</td>";
                                    echo "<td>{$empresa->nombre}</td>";
                                    echo "<td>{$empresa->id_cantidad}</td>";
                                    echo "<td>{$empresa->comentarios}</td>";
                                    echo "<td>
                                            <button class='btn btn-primary btn-sm' onclick='editPersonal({$empresa->id})'>Editar</button>
                                            <button class='btn btn-danger btn-sm' onclick='deletePersonal({$empresa->id})'>Eliminar</button>
                                          </td>";
                                    echo "</tr>";
                                    $index++;
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal for adding new puesto -->
    <div class="modal fade" id="PuestoModal" tabindex="-1" role="dialog" aria-labelledby="PuestoModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Agregar razón social</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se colocarán los campos del formulario -->
                    <form id="addPuesto" action="index.php?action=empresas/add" method="POST"> 
                        <div class="form-group">
                            <label for="razonNombre">Nombre de la razón social</label>
                            <input type="text" class="form-control" name="id_nombre" id="id_nombre" placeholder="Razón social">
                        </div>
                        <div class="form-group">
                            <label for="razonComentarios">Comentarios: </label>
                            <textarea name="id_comentarios" id="id_comentarios" class="form-control" placeholder="Comentarios o descripción de la razón social" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="razonCantidad">Número de empleados</label>
                            <select class="form-control" name="id_cantidad" id="id_cantidad">
                                <option value="">Seleccione una cantidad</option>
                                <?php 
                                    if(!empty($cantidadEmpleados)){
                                        foreach($cantidadEmpleados as $cantidad){
                                            echo "<option value='{$cantidad->id}'>{$cantidad->descripcion}</option>";
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
        function openModalAddPuesto() {
            $('#PuestoModal').modal('show');
        }

        var newEmpresaId = null;

        function addPuesto() {
            if (!newEmpresaId) {
                var empresaData = {
                    "id_nombre": $("#id_nombre").val(),
                    "id_comentarios": $("#id_comentarios").val(),
                    "id_cantidad": $("#id_cantidad").val()
                };
                
                $.ajax({
                    url: "./?action=empresas/add",
                    type: 'POST',
                    data: empresaData,
                    success: function(response) {
                        newEmpresaId = response;
                        alert("Puesto agregado correctamente");
                        window.location = "index.php?view=empresas/index";
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
