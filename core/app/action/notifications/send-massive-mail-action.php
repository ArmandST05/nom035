<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores de los filtros
    $departamento = $_POST['departamento'] ?? 'todos';
    $role = $_POST['role'] ?? 'allRole';

    // Construir la consulta dinámica
    $sql = "SELECT 
                nombre, 
                usuario, 
                clave, 
                correo, 
                telefono 
            FROM personal 
            WHERE 1=1";

    // Filtro por departamento
    if ($departamento !== 'todos') {
        $sql .= " AND departamento_id = $departamento";
    }

    // Filtro por rol
    if ($role !== 'allRole') {
        $sql .= " AND role_id = $role";
    }

    // Ejecutar consulta
    $query = Executor::doit($sql);
    $data = Model::many($query[0], new UserData());

    // Preparar los datos para DataTables
    $response = [];
    foreach ($data as $row) {
        $response[] = [
            'nombre' => $row->nombre,
            'usuario' => $row->usuario,
            'clave' => $row->clave,
            'correo' => $row->correo,
            'telefono' => $row->telefono
        ];
    }

    // Devolver en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
