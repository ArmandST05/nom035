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

// Filtros personalizados
$department_filter = isset($requestData['department_filter']) ? $requestData['department_filter'] : '';
$custom_search = isset($requestData['custom_search']) ? $requestData['custom_search'] : '';
$custom_length = isset($requestData['length']) ? intval($requestData['length']) : 10;

// Consulta inicial
$sql = "SELECT personal.id, personal.nombre, personal.usuario, personal.clave, personal.correo, personal.telefono, 
        departamentos.nombre AS departamento
        FROM personal
        INNER JOIN departamentos ON personal.id_departamento = departamentos.idDepartamento";

// Iniciar condición
$where = [];

// Filtro de búsqueda personalizado
if (!empty($custom_search)) {
    $searchValue = mysqli_real_escape_string($conn, $custom_search);
    $where[] = "(personal.nombre LIKE '%$searchValue%')";
}

// Aplicar el filtro por departamento
if (!empty($department_filter)) {
    $where[] = "personal.id_departamento = " . mysqli_real_escape_string($conn, $department_filter);
}

// Agregar condiciones al SQL
if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Total de registros filtrados
$query = mysqli_query($conn, $sql);
$totalFiltered = mysqli_num_rows($query);

// Orden y límite
$orderColumn = isset($requestData['order'][0]['column']) ? $columns[$requestData['order'][0]['column']] : 'id';
$orderDirection = isset($requestData['order'][0]['dir']) ? $requestData['order'][0]['dir'] : 'ASC';
$start = isset($requestData['start']) ? intval($requestData['start']) : 0;

// Aplicar orden y límite
$sql .= " ORDER BY $orderColumn $orderDirection LIMIT $start, $custom_length";

// Depuración
error_log($sql);

// Ejecución de la consulta con paginación
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
    
    $buttons = '
    <style>
        #dropdownMenuButton' . $row["id"] . ' i {
            background-color: grey;
            color: black;
            border-radius: 50%;
            padding: 6px;
            font-size: 18px;
            display: inline-block;
            text-align: center;
            line-height: 18px;
        }

        .table .dropdown {
            position: relative; /* Cambiar a relative si quieres que se posicione dentro de la celda */
            text-align: center;
        }

        .dropdown-menu {
            position: absolute;
            z-index: 10000;
            display: none;
            width: 250px; /* Cambia este valor para ajustar el tamaño del menú */
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            left: auto;
            right: 0; /* Esto hará que el menú se despliegue hacia la izquierda */
        }

        .dropdown-toggle {
            background-color: transparent;
            border: none;
        }

        td, th {
            height: 60px;
            border: 1px solid grey;
        }
    </style>
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton' . $row["id"] . '">
            <i class="fa-solid fa-ellipsis" style="color: black;"></i>
        </button>

        <ul class="dropdown-menu" id="dropdownMenu' . $row["id"] . '" style="display: none; position: absolute;">
            <li><a class="dropdown-item" href="#" onclick="editPersonal(' . $row["id"] . ')">Editar</a></li>
            <li><a class="dropdown-item" href="#" onclick="deletePersonal(' . $row["id"] . ',`' . $row["nombre"] . '`)">Eliminar</a></li>
            <li><a class="dropdown-item" href="#" onclick="openAssignSurveyModal(' . $row["id"] . ')">Asignar Encuesta</a></li>
<<<<<<< HEAD
            <hr></hr>
                        <li><a class="dropdown-item" href="#" onclick="sendMail(' . $row["id"] . ')">Enviar credenciales por correo</a></li>
                        <li><a class="dropdown-item" href="#" onclick="sendWhatsapp(' . $row["id"] . ')">Enviar credenciales por Whatsapp</a></li>

=======
>>>>>>> 665fa235bde515d8a57f32f92c25602e88778721
        </ul>
    </div>
';

    $nestedData[] = $buttons;
    $data[] = $nestedData;
}

// Respuesta final
$response = array(
    "draw" => intval($requestData['draw']),
    "recordsTotal" => intval($totalFiltered),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
);

header('Content-Type: application/json');
echo json_encode($response);
?>
