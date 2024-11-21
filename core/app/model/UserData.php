<?php
class UserData {
	public static $tablename = "users";

	public function __construct(){
		$this->name = "";
		$this->lastname = "";
		$this->email = "";
		$this->username = "";
		$this->password = "";
		$this->is_active = "";
		$this->date_of_birth = "";
		$this->user_type = "";
		$this->departamento = ""; // Nuevo campo agregado
		$this->created_at = "NOW()";
	}

	public function getUserType(){ 
		return UserTypeData::getById($this->user_type); 
	}

	public function add(){
		$sql = "INSERT INTO ".self::$tablename." 
				(name, lastname, username, email, date_of_birth, password, user_type, departamento, is_active, created_at) 
				VALUES 
				(\"$this->name\", \"$this->lastname\", \"$this->username\", \"$this->email\", \"$this->date_of_birth\", \"$this->password\", \"$this->user_type\", \"$this->departamento\", \"$this->is_active\", $this->created_at)";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." 
				SET 
					name=\"$this->name\",
					lastname=\"$this->lastname\",
					email=\"$this->email\",
					username=\"$this->username\",
					password=\"$this->password\",
					is_active=\"$this->is_active\",
					date_of_birth=\"$this->date_of_birth\",
					user_type=\"$this->user_type\",
					departamento=\"$this->departamento\" 
				WHERE id=$this->id";
		Executor::doit($sql);
	}



	public function updatePassword(){
		$sql = "update ".self::$tablename." set password=\"$this->password\" where id = $this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	

	public static function getByMail($mail){
		$sql = "select * from ".self::$tablename." WHERE user_type != 'api' AND email = \"$mail\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function validateApi($username,$authenticationToken){
		$sql = "SELECT * FROM ".self::$tablename." 
		WHERE username = '$username' AND authentication_token = '$authenticationToken'
		LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename ." WHERE user_type != 'api' order by name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getByUserTypeStatus($userType,$status){
		$sql = "SELECT * FROM ".self::$tablename ." WHERE user_type != 'api' ";
		if($userType != 0){
			$sql .= " AND user_type = '$userType' ";
		}
		if($status != 'all'){
			$sql .= " AND is_active = '$status' ";
		}
		$sql .= " order by user_type,name ASC ";

		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}


	public static function getUnassigned(){
		//Obtiene los usuarios que no se han asignado a ningún médico.
		$sql = "select ".self::$tablename .".* from ".self::$tablename ." 
		left join medics on ".self::$tablename .".id = medics.user_id 
		WHERE user_type != 'api' AND medics.id is null 
		order by ".self::$tablename .".name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." WHERE user_type != 'api' AND name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function deleteById($id){
		$sql = "delete from ".self::$tablename." WHERE id=$id";
		Executor::doit($sql);
	}
	public function changeStatusById(){
		$sql = "update ".self::$tablename." set is_active=\"$this->status_id\" where id=$this->id";
		Executor::doit($sql);
	}
	public static function getLoggedIn(){
		//Obtener datos del usuario logueado en el sistema
		$id = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}



	public static function getLoggedInNurse() {
		$id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
		$sql = "SELECT *, user_type as role FROM users WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new UserData());
	}
	
	
}
?>