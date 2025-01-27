<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificamos que el archivo y la empresa hayan sido enviados
    if (isset($_FILES['file']) && isset($_POST['empresa_id']) && !empty($_POST['empresa_id'])) {
        // Recoger el id de la empresa
        $empresa_id = $_POST['empresa_id'];  // Asegurarse que es un número entero
        
        // Verificar que el archivo sea válido (tipo .xlsx, .xls, .csv)
        $file = $_FILES['file'];
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (!in_array($fileType, ['xls', 'xlsx', 'csv'])) {
            echo "El archivo debe ser de tipo .xls, .xlsx o .csv.";
            exit;
        }

        // Instanciar la clase CargaData
        $cargaData = new CargaData();
        $cargaData->setEmpresaId($empresa_id);  // Asignamos el empresa_id

        // Subir el archivo
        $fileName = $file['name'];
        $fileTmpPath = $file['tmp_name'];

        // Procesamos el archivo (puedes usar una función para extraer los datos de Excel o CSV)
        // Aquí se hace la carga del archivo en la base de datos (guardando el archivo como contenido)
        $fileData = file_get_contents($_FILES['file']['tmp_name']);
        $cargaData->insertFileToDatabase($fileName, $fileType, $fileData);  // Guardar el archivo en la base de datos

        // Si es un archivo Excel, podemos procesarlo aquí
        if ($fileType == 'xls' || $fileType == 'xlsx') {
            // Aquí podrías usar PhpSpreadsheet para procesar el archivo y extraer los datos
            require_once 'vendor/autoload.php';  // Asegúrate de tener PhpSpreadsheet

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = [];

            // Leer el contenido del archivo Excel (suponiendo que las columnas están en el orden correcto)
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE);

                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                // Limpiar los datos antes de insertarlos
                $rowData = $cargaData->cleanData($rowData);

                // Aquí procesas los datos y los insertas en la base de datos
                if (count($rowData) === 5) {
                    // Extraemos los datos del archivo
                    list($nombre, $puesto, $departamento, $correo, $telefono) = $rowData;

                    // Generar automáticamente el usuario
                    $nombre = trim($nombre);  // Asegúrate que no haya espacios extra al inicio o final
                    $iniciales = strtoupper(substr($nombre, 0, 1));
                    $palabras = explode(' ', $nombre);
                    if (count($palabras) > 1) {
                        $iniciales .= strtoupper(substr($palabras[1], 0, 1));
                    }
                    $numeroAzar = rand(100000, 999999);
                    $usuario = 'u' . $iniciales . $numeroAzar;

                    // Generar automáticamente la clave
                    $longitudClave = rand(6, 8);
                    $clave = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $longitudClave);

                    // Obtener los IDs de puesto y departamento
                    $positions = $cargaData->getPositions();
                    $departments = $cargaData->getDepartments();

                    // Limpiar los valores de puesto y departamento antes de hacer la búsqueda
                    $puesto = strtoupper(trim($puesto));
                    $departamento = strtoupper(trim($departamento));

                    $id_puesto = $positions[$puesto] ?? null;
                    $id_departamento = $departments[$departamento] ?? null;

                    // Insertar el dato en la base de datos
                    $fecha_alta = date('Y-m-d H:i:s');
                    if ($id_puesto && $id_departamento) {
                        $cargaData->insertIntoDatabase($nombre, $id_puesto, $id_departamento, $correo, $telefono, $usuario, $clave, $fecha_alta);
                    } else {
                        // Si no encontramos el puesto o departamento, podemos hacer un debug con var_dump
                        var_dump($cargaData->insertIntoDatabase($nombre, $id_puesto, $id_departamento, $correo, $telefono, $usuario, $clave, $fecha_alta));
                    }
                }
            }

            echo "Archivo procesado y datos insertados correctamente.";
        } else {
            // Si es CSV, puedes procesarlo de manera similar
            // Aquí puedes implementar el procesamiento de CSV si lo prefieres
        }
    } else {
        echo "Error: No se seleccionó archivo o empresa.";
    }
} else {
    echo "Método no permitido.";
}

?>
