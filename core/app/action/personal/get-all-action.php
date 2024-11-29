<?php
$conn = Database::getCon();
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

// Verificar el tipo de usuario y obtener el ID del médico o administrador
$user = UserData::getLoggedIn();
$user_type = $user->user_type;

// DataTables request
$requestData = $_REQUEST;

// Declaración de columnas
$columns = array(
    0 => 'id',
    1 => 'nombre',
    2 => 'id_departamento',
    3 => 'usuario',
    4 => 'clave',
    5 => 'correo',
    6 => 'telefono'
);

// Filtrar por departamento si el filtro existe
$department_filter = isset($requestData['department_filter']) ? $requestData['department_filter'] : '';

// Consulta inicial
$sql = "SELECT personal.id, personal.nombre, personal.usuario, personal.clave, personal.correo, personal.telefono, 
        departamentos.nombre AS departamento
        FROM personal
        INNER JOIN departamentos ON personal.id_departamento = departamentos.idDepartamento";

// Filtro de búsqueda
if (!empty($requestData['search']['value'])) {
    $searchValue = mysqli_real_escape_string($conn, $requestData['search']['value']);
    $sql .= " WHERE (personal.nombre LIKE '%$searchValue%' 
                OR personal.usuario LIKE '%$searchValue%' 
                OR personal.correo LIKE '%$searchValue%' 
                OR departamentos.nombre LIKE '%$searchValue%')";
}

// Aplicar el filtro por departamento si se ha seleccionado uno
if (!empty($department_filter)) {
    $sql .= " AND personal.id_departamento = " . mysqli_real_escape_string($conn, $department_filter);
}

// Total de registros
$query = mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);

// Orden y límite
$orderColumn = $columns[$requestData['order'][0]['column']];
$orderDirection = $requestData['order'][0]['dir'];
$start = $requestData['start'];
$length = $requestData['length'];

$sql .= " ORDER BY $orderColumn $orderDirection LIMIT $start, $length";
$query = mysqli_query($conn, $sql);

// Construcción de la respuesta JSON
$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $nestedData = array();
    $nestedData[] = $row["id"];
    $nestedData[] = $row["nombre"];
    $nestedData[] = $row["departamento"];
    $nestedData[] = $row["usuario"];
    $nestedData[] = $row["clave"];
    $nestedData[] = $row["correo"];
    $nestedData[] = $row["telefono"];
    
    // Generación de menú desplegable según el tipo de usuario
    // Generación de menú desplegable
    $buttons = '
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton' . $row["id"] . '">
            <i class="bi bi-three-dots"></i> <!-- Icono de tres puntos -->
        </button>
        <ul class="dropdown-menu" id="dropdownMenu' . $row["id"] . '" style="display: none;">
            <li><a class="dropdown-item" href="index.php?view=personal/edit&id=' . $row["id"] . '">Editar</a></li>
            <li><a class="dropdown-item" href="#" onclick="deletePersonal(' . $row["id"] . ',`' . $row["nombre"] . '`)">Eliminar</a></li>
        </ul>
    </div>
';


    $nestedData[] = $buttons;
    $data[] = $nestedData;
}

$response = array(
    "draw" => intval($requestData['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalData),
    "data" => $data
);

header('Content-Type: application/json');
echo json_encode($response);
?>
