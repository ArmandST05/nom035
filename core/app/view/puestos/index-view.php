<?php

$encuestas = EncuestaData::getAll();
$departamentos = DepartamentoData::getAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>puestos</title>
</head>
<body>
    <div class="containet mt-4">
        <div class="row">
            <div class="col-md-8">
                <h4>Puestos</h4>
                <p>
                    Lorem ipsum dolor sit amet consectetur, adipisicing
                     elit. Suscipit, aliquam? In consequuntur fugiat
                      perspiciatis cum? Beatae, iure minima cumque 
                      velit soluta nisi tenetur, quis repellendus culpa accusamus ab eius necessitatibus est veritatis reiciendis debitis fugit quibusdam et quo doloribus ut expedita illum!
                     Amet aut corporis harum eum, iste excepturi ipsa.
                </p>
            </div>
            <div class="col-md-4">
                <div class="d-flex felx-column gap-2">
                <button type="button" class="btn btn-primary" onclick="openModalAddPuesto()">Nuevo Puesto</button>

                </div>
            </div>
        </div>
    </div>



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
                                    if(!empty ($departamentos)){
                                        foreach($departamentos as $departamento){
                                            echo "<option value='{$departamento->idDepartamento}'>{$departamento->nombre}</option>";

                                        }
                                    }

                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="roleEncuesta">
                                <select  class="form-control" name="id_encuesta" id="id_encuesta">
                                    <option value="">Seleccione una encuesta</option>
                                    <?php 
                                        if(!empty($encuestas)){
                                            foreach($encuestas as $encuesta){
                                                echo "<option value'{$encuesta->id}'>{$encuesta->descripcion}</option>";
                                            }
                                        }
                                    
                                    ?>
                                    
                                </select>
                            </label>
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
</body>
<script>
     function openModalAddPuesto() {
            // Configura el contenido dinámico del modal si es necesario.
            $('#PuestoModal').modal('show'); // Muestra el modal.
        }


        var newPuestoId = null;
        function addPuesto(){
            

            if(!newPuestoId){
                var PuestoData = {
                    "roleName": $("#roleName").val(),
                    "roleDepartment": $("id_departamento").val(),
                    "roleEncuesta": $("id_encuestas").val()
                };
            
            $.ajax({
                url: "./?action=puestos/add",
                type: 'POST',
                data: puestoData,
                success: function(response){
                    newPuestoId = response;


                    alert("Puesto agregado correctamente")
                    windoq.location = "inde.php?view=puesto/index";
                },
                error: function(){
                    alert("Algo salio mal")
                }
            })
          }else{
            alert("Este personal ya existe.");
          }
             

        }
</script>
</html>