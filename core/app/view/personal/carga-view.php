<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir archivo Excel</title>
    <style>
       
        .card {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table tr {
            border-bottom: 1px solid #ddd;
        }
        .table td {
            padding: 10px;
            text-align: left;
        }
        .drop-zone {
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            color: #007bff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .drop-zone.dragover {
            background-color: #e9f5ff;
        }
        #response {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="card">
    <table class="table">
        <tr><td>1.- Crear archivo .XLSX o convertir archivo en excel a .XLSX .</td></tr>
        <tr><td>2.- El archivo debe de pesar menos de 2 MB.</td></tr>
        <tr><td>3.- Deben ser 5 encabezados y llamarse tal cual: <br> <strong>nombre</strong>, <strong>puesto</strong>,
        <strong>departamento</strong>, <strong>correo</strong>, <strong>tlefono</strong></td></tr>
        <tr><td>4.- Enseguida podrá descargar un ejemplo del formato de carga masiva. Sustituya los campos por los datos que desea cargar, 
                asegurese de no dejar campos en blanco y que no estén duplicados. Descargar ejemplo</td></tr>
    </table>

    <form id="uploadForm" enctype="multipart/form-data">
        <div class="drop-zone" id="dropZone">
            Arrastra y suelta tu archivo aquí o haz clic para seleccionarlo
            <input type="file" name="file" id="fileInput" accept=".xls,.xlsx,.csv" style="display: none;" required>
        </div>
    </form>

    <div id="response"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');

        // Abrir selector de archivo al hacer clic en el área de arrastre
        dropZone.addEventListener('click', () => fileInput.click());

        // Cambiar estilo al arrastrar un archivo sobre el área
        dropZone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        // Manejar el archivo soltado
        dropZone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropZone.classList.remove('dragover');
            const files = event.dataTransfer.files;
            if (files.length) {
                fileInput.files = files; // Asignar el archivo al input oculto
                handleFileUpload();
            }
        });

        // Manejar el envío del formulario
        $('#uploadForm').on('submit', function (event) {
            event.preventDefault();
            handleFileUpload();
        });

        function handleFileUpload() {
            const formData = new FormData($('#uploadForm')[0]);
            $.ajax({
                url: './?action=personal/carga',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#response').html(response);
                },
                error: function () {
                    $('#response').html('Error al subir el archivo.');
                }
            });
        }
    });
</script>

</body>
</html>
