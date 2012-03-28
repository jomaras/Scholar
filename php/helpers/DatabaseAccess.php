<?php
class DatabaseAccess
{
	private $_databaseUrl;
	private $_username;
	private $_password;
	private $_tableName;
	private $_connection;
	
	public function __construct($databaseUrl, $tableName, $username, $password)
	{
		$this->_databaseUrl = $databaseUrl;
		$this->_username = $username;
		$this->_password = $password;
		$this->_tableName = $tableName;
	}
	
	public static function ExQuery($query)
	{
		$dbAccess = new DatabaseAccess("localhost", "admin", "admin", "passAdmin1");
		
		return $dbAccess->ExecuteQuery($query);
	}
	
	public function ExecuteQuery($query)
	{
		$connection = mysql_connect($this->_databaseUrl, $this->_username, $this->_password);
		
		if ($connection)
		{
			$database = mysql_select_db($this->_tableName, $connection);
			
			if($database)
			{
				mysql_query('SET character_set_results=utf8');
				mysql_query('SET character_set_client=utf8');
				mysql_query('SET names utf8');     										
		 		
				$queryResponse = mysql_query($query);

                echo(mysql_error($connection));
				
				$resultItems = array();
									    	
		   		while(is_resource($queryResponse) && $item = mysql_fetch_row($queryResponse))
		   		{ 
		   			$resultItems[] = $item; 
		   		}
		   		
			   	return  $resultItems;
			}
		}
	}
}