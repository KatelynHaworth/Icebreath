<?php
class Database
{
	private $connection;
	private $error;
	
	public function open_connection($database)
	{
		if(!empty($this->connection))
			mysqli_close($this->connection);
			
		if($this->error !== FALSE)
			$this->error = FALSE;
			
		$config = get_global_config();
		$this->connection = mysqli_connect($config['mysql_server'], $config['mysql_user'], $config['mysql_password'], $database);
	}
	
	public function get_error()
	{		
		return $this->error;
	}
	
	public function query($query) 
	{
		if(!empty($this->connection))
		{
			$result = mysqli_query($this->connection, $query);
			
			if(mysqli_errno($this->connection))
				$error = mysqli_error($this->connection);
				
			return $result;
		}
		
		return false;
	}
	
	public function close_connection()
	{
		if(!empty($this->connection))
			mysqli_close($this->connection);
	}
}
?>