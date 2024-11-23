<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal</title>

</head>
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
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            Launch demo modal
                            </button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-secondary">Exportar excel</button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-success">Carga masiva</button>
                        </li>
                    </ul>                    
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Nuvo Empleado-->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


    <script>
        const abrirModal = document.querySelector("#nuevoPersonal");
        const cerrarModal = document.querySelector("#cerrarPersonal")

        const modal =document.querySelector("#modalPersonal")

        abrirModal.addEventListener('click', ()=>{
            modal.showModal();
        })
        cerrarModal.addEventListener('click', ()=>{
            modal.close();

        })
    </script>

</body>
</html>
