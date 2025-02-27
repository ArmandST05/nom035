<?php
$conn = Database::getCon();
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

// Verificar el tipo de usuario y obtener el ID del médico o administrador
$user = UserData::getLoggedIn();
$user_type = $user->user_type;

// DataTables request
$requestData = $_REQUEST;

// Columnas
$columns = array(
    0 => 'id',
    1 => 'nombre',
    2 => 'id_departamento',
    3 => 'usuario',
    4 => 'clave',
    5 => 'correo',
    6 => 'telefono'
);

// Filtros
// Filtros personalizados
$department_filter = isset($requestData['department_filter']) ? $requestData['department_filter'] : '';
$custom_search = isset($requestData['custom_search']) ? $requestData['custom_search'] : '';

$custom_length = isset($requestData['length']) ? intval($requestData['length']) : 10;
$start = isset($requestData['start']) ? intval($requestData['start']) : 0;

// Base de la consulta
$sql = "SELECT personal.id, personal.nombre, personal.usuario, personal.clave, personal.correo, personal.telefono, 
        departamentos.nombre AS departamento
        FROM personal
        INNER JOIN departamentos ON personal.id_departamento = departamentos.idDepartamento";

// Condiciones dinámicas
$where = [];
$params = [];
$types = "";

// Filtro de búsqueda personalizada
if (!empty($custom_search)) {
    $where[] = "(personal.nombre LIKE ? OR personal.usuario LIKE ? OR personal.correo LIKE ?)";
    $searchValue = "%{$custom_search}%";
    $params[] = $searchValue;
    $params[] = $searchValue;
    $params[] = $searchValue;
    $types .= "sss"; // Tipos de datos (s = string)
}

// Filtro por departamento
if (!empty($department_filter)) {
    $where[] = "personal.id_departamento = ?";
    $params[] = $department_filter;
    $types .= "i"; // i = integer
}

// Agregar condiciones a la consulta
if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Obtener el número total de registros filtrados
$totalFiltered = 0;
$count_sql = "SELECT COUNT(*) FROM personal INNER JOIN departamentos ON personal.id_departamento = departamentos.idDepartamento";
if (count($where) > 0) {
    $count_sql .= " WHERE " . implode(" AND ", $where);
}
$stmt = $conn->prepare($count_sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$stmt->bind_result($totalFiltered);
$stmt->fetch();
$stmt->close();

// Ordenamiento y paginación
$orderColumn = isset($requestData['order'][0]['column']) ? $columns[$requestData['order'][0]['column']] : 'id';
$orderDirection = isset($requestData['order'][0]['dir']) ? $requestData['order'][0]['dir'] : 'ASC';

$sql .= " ORDER BY $orderColumn $orderDirection LIMIT ?, ?";
$params[] = $start;
$params[] = $custom_length;
$types .= "ii"; // Agregar los tipos para el límite

// Ejecutar consulta con parámetros
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Construcción de la respuesta JSON
$data = [];
while ($row = $result->fetch_assoc()) {
    $nestedData = [];
    $nestedData[] = '<input type="checkbox" class="employee-checkbox" value="' . $row["id"] . '">';
    
    $nestedData[] = $row["nombre"];
    $nestedData[] = $row["departamento"];
    $nestedData[] = $row["usuario"];
    $nestedData[] = $row["clave"];
    $nestedData[] = $row["correo"];
    $nestedData[] = $row["telefono"];
    
    $data[] = $nestedData;
}

$stmt->close();

// Respuesta final
$response = [
    "draw" => intval($requestData['draw']),
    "recordsTotal" => $totalFiltered,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
?>
