<?php
class NotificationData
{
	public static $tablename = "notifications";
	public static $tablenameModules = "notification_modules";
	public static $tablenameStatus = "notification_status";
	public static $tablenameDirections = "notification_directions";
	public static $tablenameTypes = "notification_types";
	public static $tablenameSmsPurchases = "sms_purchases";
	public static $tablenameDefaultMessages = "default_messages";

	public $patient_id;
	public $reservation_id;
	public $type_id;
	public $direction_id;
	public $status_id;
	public $module_id;
	public $receptor;
	public $message;

	//Tipos de notificaciones: CORREO ELECTRÓNICO (1), SMS (2)
	public function __construct()
	{
		$this->patient_id = "";
		$this->reservation_id = "";
		$this->type_id = "";
		$this->direction_id = "";
		$this->status_id = "";
		$this->module_id = "";
		$this->receptor = "";
		$this->message = "";
	}

	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (patient_id,reservation_id,type_id,direction_id,status_id,module_id,receptor,message) ";
		$sql .= "value (\"$this->patient_id\",\"$this->reservation_id\",\"$this->type_id\",\"$this->direction_id\",$this->status_id,\"$this->module_id\",\"$this->receptor\",\"$this->message\")";
		Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new NotificationData());
	}

	public static function getAllByType($typeId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE type_id = '$typeId' ORDER BY date_at ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new NotificationData());
	}
	
}
