<?php
// Habilitar la visualización de errores en PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_FILES);
    print_r($_POST);
    echo "</pre>";

    // Verificamos que el archivo y la empresa hayan sido enviados
    if (isset($_FILES['file']) && isset($_POST['empresa_id']) && !empty($_POST['empresa_id'])) {
        // Recoger el id de la empresa
        $empresa_id = (int) $_POST['empresa_id'];  // Convertir a entero para evitar errores

        // Verificar que el archivo sea válido (tipo .xlsx, .xls, .csv)
        $file = $_FILES['file'];
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileTmpPath = $file['tmp_name'];
        $fileName = $file['name'];

        echo "Procesando archivo: $fileName de tipo $fileType <br>";

        if (!in_array($fileType, ['xls', 'xlsx', 'csv'])) {
            die("Error: El archivo debe ser de tipo .xls, .xlsx o .csv.");
        }

        // Verificar que el archivo se ha subido correctamente
        if (!is_uploaded_file($fileTmpPath)) {
            die("Error: No se pudo cargar el archivo.");
        }

        // Instanciar la clase CargaData
        $cargaData = new CargaData();
        $cargaData->setEmpresaId($empresa_id);  // Asignamos el empresa_id

        // Leer el contenido del archivo
        $fileData = file_get_contents($fileTmpPath);
        if ($fileData === false) {
            die("Error: No se pudo leer el archivo.");
        }

        // Guardar el archivo en la base de datos
        $insertResult = $cargaData->insertFileToDatabase($fileName, $fileType, $fileData);
        

        echo "Archivo guardado en la base de datos. <br>";

        // Si es un archivo Excel, lo procesamos
        if ($fileType == 'xls' || $fileType == 'xlsx') {
            require_once 'vendor/autoload.php';  // Asegúrate de tener PhpSpreadsheet

            
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
                $sheet = $spreadsheet->getActiveSheet();
            
            echo "Archivo Excel cargado correctamente. <br>";

            // Leer el contenido del archivo Excel
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE);

                foreach ($cellIterator as $cell) {
                    $rowData[] = trim($cell->getValue());
                }

                // Limpiar los datos antes de insertarlos
                $rowData = $cargaData->cleanData($rowData);

                // Verificar que la fila tiene exactamente 5 valores
                if (count($rowData) === 5) {
                    list($nombre, $puesto, $departamento, $correo, $telefono) = $rowData;

                    // Generar automáticamente el usuario
                    $nombre = trim($nombre);
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

                    // Limpiar y buscar IDs de puesto y departamento
                    $puesto = strtoupper(trim($puesto));
                    $departamento = strtoupper(trim($departamento));

                    $id_puesto = $positions[$puesto] ?? null;
                    $id_departamento = $departments[$departamento] ?? null;

                    echo "Procesando: Nombre: $nombre, Puesto: $puesto ($id_puesto), Departamento: $departamento ($id_departamento), Usuario: $usuario, Clave: $clave <br>";

                    // Insertar en la base de datos si los IDs son válidos
                    if ($id_puesto && $id_departamento) {
                        $fecha_alta = date('Y-m-d H:i:s');
                        $insertSuccess = $cargaData->insertIntoDatabase($nombre, $id_puesto, $id_departamento, $correo, $telefono, $usuario, $clave, $fecha_alta);
                        
                        if (!$insertSuccess) {
                            echo "Error al insertar en la base de datos: ";
                            var_dump($cargaData->insertIntoDatabase($nombre, $id_puesto, $id_departamento, $correo, $telefono, $usuario, $clave, $fecha_alta));
                        }
                    } else {
                        echo "⚠️ Advertencia: No se encontró el puesto o departamento para: $nombre <br>";
                    }
                } else {
                    echo "⚠️ Advertencia: La fila no tiene 5 valores. Datos: ";
                    var_dump($rowData);
                }
            }

            echo "✅ Archivo procesado y datos insertados correctamente.";
        } else {
            echo "⚠️ Advertencia: Procesamiento de archivos CSV aún no implementado.";
        }
    } else {
        die("❌ Error: No se seleccionó archivo o empresa.");
    }
} else {
    die("❌ Error: Método no permitido.");
    var_dump($_FILES);
var_dump($_POST);

}
?>
