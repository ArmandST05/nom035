<?php
class PeriodoData{

    public static function createPeriod($name, $startDate, $endDate, $companyId) {
        $sql = "INSERT INTO periods (name, start_date, end_date, company_id) 
                VALUES ('$name', '$startDate', '$endDate', $companyId)";
        return Executor::doit($sql);
    }
    public static function assignSurveysToPeriod($periodId, $surveyIds) {
        foreach ($surveyIds as $surveyId) {
            $sql = "INSERT INTO period_surveys (period_id, survey_id) 
                    VALUES ($periodId, $surveyId)";
            Executor::doit($sql);
        }
        return true;
    }
    public static function assignPeriodToUsers($periodId, $companyId, $departmentId = null, $positionId = null) {
        // Obtener encuestas del periodo
        $surveys = "SELECT survey_id FROM period_surveys WHERE period_id = $periodId";
        $surveyQuery = Executor::doit($surveys);
        $surveyIds = mysqli_fetch_all($surveyQuery[0], MYSQLI_ASSOC);
    
        // Filtrar empleados
        $sql = "SELECT id FROM personal WHERE company_id = $companyId";
        if ($departmentId) {
            $sql .= " AND department_id = $departmentId";
        }
        if ($positionId) {
            $sql .= " AND position_id = $positionId";
        }
        $employeeQuery = Executor::doit($sql);
        $employeeIds = mysqli_fetch_all($employeeQuery[0], MYSQLI_ASSOC);
    
        // Asignar encuestas a empleados
        foreach ($employeeIds as $employee) {
            foreach ($surveyIds as $survey) {
                EncuestaData::assignToPersonal($employee['id'], [$survey['survey_id']]);
            }
        }
        return true;
    }
            
}