<?php

require 'vendor/autoload.php';  // Asegúrate de incluir esto

use PhpOffice\PhpSpreadsheet\IOFactory;


class CargaData {
    private $db;

    public function __construct() {
        $this->db = Database::getCon(); // Usamos tu método estático para obtener la conexión
    }

    public function uploadFile($file) {
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
    
        if (!in_array($fileType, ['xls', 'xlsx', 'csv'])) {
            return "El archivo debe ser de tipo .xls, .xlsx o .csv.";
        }
    
        // Leer el archivo como binario
        $fileData = file_get_contents($file['tmp_name']); // Obtiene el contenido binario del archivo
    
        // Guardar el archivo en la base de datos
        $this->saveFileToDatabase($file['name'], $fileType, $fileData);
    
        // Procesar el archivo para extraer los datos
        return $this->processFile($file['tmp_name'], $fileType);
    }
    
    private function saveFileToDatabase($fileName, $fileType, $fileData) {
        $stmt = $this->db->prepare("INSERT INTO archivos (nombre_archivo, tipo_archivo, contenido) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fileName, $fileType, $fileData);
    
        if (!$stmt->execute()) {
            echo "Error al guardar el archivo: " . $stmt->error;
        }
    
        $stmt->close();
    }
        

    private function processFile($filePath, $fileType) {
        if ($fileType == 'csv') {
            return $this->processCSV($filePath);
        } else {
            return $this->processExcel($filePath);
        }
    }

    private function processCSV($filePath) {
        $file = fopen($filePath, 'r');
        $firstLine = true;
    
        while (($data = fgetcsv($file, 1000, ",")) !== false) {
            if ($firstLine) {
                $firstLine = false; // Saltamos la primera línea si contiene encabezados
                continue;
            }
    
            $this->saveToDatabase($data); // Guardamos los datos extraídos del CSV en la base de datos
        }
    
        fclose($file);
        return "Archivo CSV procesado y datos almacenados correctamente.";
    }
    
    private function processExcel($filePath) {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
    
        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Saltamos la primera fila si contiene encabezados
            $this->saveToDatabase($row); // Guardamos los datos extraídos del Excel en la base de datos
        }
    
        return "Archivo Excel procesado y datos almacenados correctamente.";
    }
    
    public function saveToDatabase($data) {
        // Limpiar los datos eliminando los espacios sobrantes
        $data = $this->cleanData($data);
        
        // Ajustar los índices a las columnas de tu base de datos
        $col1 = $data[0];  // nombre
        $col2 = $data[1];  // puesto (texto)
        $col3 = $data[2];  // departamento (texto)
        $col4 = $data[3];  // correo
        $col5 = $data[4];  // teléfono
        
        // Obtener los IDs de departamentos y puestos de la base de datos
        $departments = $this->getDepartments();
        $positions = $this->getPositions();
    
        // Buscar el ID del departamento
        $id_departamento = isset($departments[$col3]) ? $departments[$col3] : null;
    
        // Buscar el ID del puesto
        $id_puesto = isset($positions[$col2]) ? $positions[$col2] : null;
    
        // Verificar que se hayan encontrado los IDs antes de continuar
        if ($id_departamento === null || $id_puesto === null) {
            return "Error: No se pudo encontrar el departamento o el puesto para los valores proporcionados.";
        }
    
        // Generar automáticamente el usuario
        $nombre = trim($col1);
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
    
        // Obtener la fecha actual
        $fecha_alta = date("Y-m-d"); // Formato de fecha: AAAA-MM-DD
    
        // Construir la consulta SQL con los IDs obtenidos
        $sql = "INSERT INTO personal (nombre, id_puesto, id_departamento, correo, telefono, usuario, clave, fecha_alta) 
                VALUES (\"$col1\", \"$id_puesto\", \"$id_departamento\", \"$col4\", \"$col5\", \"$usuario\", \"$clave\", \"$fecha_alta\")";
        
        // Ejecutar la consulta
        return Executor::doit($sql);
    }
    
    private function getPositions() {
        // Ejecutamos la consulta directamente
        $sql = "SELECT id, nombre FROM puestos";
        $con = Database::getCon();
        
        // Ejecutamos la consulta y recorremos el resultado en el mismo paso
        $positions = [];
        if ($query = $con->query($sql)) {
            while ($row = $query->fetch_assoc()) {
                // Convertir el nombre del puesto a mayúsculas
                $positions[strtoupper($row['nombre'])] = $row['id'];
            }
        }
        
        // Retornamos el array con los puestos
        return $positions;
    }
    private function getDepartments() {
        // Ejecutamos la consulta directamente
        $sql = "SELECT idDepartamento, nombre FROM departamentos";
        $con = Database::getCon();
        
        // Ejecutamos la consulta y recorremos el resultado en el mismo paso
        $departments = [];
        if ($query = $con->query($sql)) {
            while ($row = $query->fetch_assoc()) {
                // Convertir el nombre del departamento a mayúsculas
                $departments[strtoupper($row['nombre'])] = $row['idDepartamento'];
            }
        }
        
        // Retornamos el array con los departamentos
        return $departments;
    }
    
    
    
    
    private function cleanData($data) {
        // Elimina los espacios al principio y al final de cada valor
        return array_map(function($value) {
            // Eliminar los espacios antes y después
            $value = trim($value);
            
            // Reemplazar los espacios múltiples en medio de la cadena con un solo espacio
            $value = preg_replace('/\s+/', ' ', $value);
            
            return $value;
        }, $data);
    }
    
}
?>