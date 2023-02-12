<?php

	function getUserData($user_id, $company_id, $from_date = '2019-03-01', $to_date = '2019-04-30'){
		require_once('config.php');	
		$resp = array();
		$resp['Failure'] = '';
		//tables to consider
		$tables = array('ledgers','daybook_details','trial_balance_ledger','trial_balance_accounts','oc_user_company','OC_user');
		
		// Create connection
		$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

		// Check connection
		if ($conn->connect_error) {
			$resp['Failure'] = "Connection failed: " . $conn->connect_error;
		} 

		if(false === $conn)	{
			$resp['Failure'] = "couldn't connect to database";
		}
		
		if(!mysqli_select_db($conn, DB_DATABASE)) {
			$resp['Failure'] = "Couldn't select Database ".DB_DATABASE;
		}
			
		$dir = 'User_'.$user_id;
		  
		if(!is_dir($dir)) {
			if(!mkdir($dir, 0775)) {
				$resp['Failure'] = "Couldn't create directory ".$dir; //would probably affect all databases
			}
		}
		
		foreach($tables as $table) {
			
			$getColumns = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema='".DB_DATABASE."' AND table_name='".$table."'";
			$result = $conn->query($getColumns);
			$insertQry = 'insert into '.$table;
			$columns = ' (';
			
			$columnHeader = array();
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					$columns .= $row['COLUMN_NAME'].', ';
					$columnHeader[] = $row['COLUMN_NAME'];
				}
			}
			
			$columns = rtrim($columns, ', ');
			$insertQry .= $columns.') values ';
			
			if($table == 'OC_user'){
				$getColumnsValues = "select * from ".$table." where user_id='".$user_id."' and date(date_added) between '".$from_date."' and '".$to_date."'";
			} else {
				$getColumnsValues = "select * from ".$table." where company_id='".$company_id."'";
			}
			
			$result = $conn->query($getColumnsValues);	
			$vals = '';
			if($result) {
				if ($result->num_rows > 0) {					
					while($row = $result->fetch_assoc()) {
						$vals .= '(';
						$i = 0;
						while($i < count($columnHeader)){
							$vals .= "'".$row[$columnHeader[$i]]."', ";
							$i++;
						}
						$vals = rtrim($vals, ', ');
						$vals .= '), ';
					}
				} else {
					$vals .= '(0 results)';
				}
				$vals = rtrim($vals, ', ');
				$insertQry .= $vals;
			}
			//Write data to file
			$file_name = $dir.'/'.strtoupper($table).'_'.date('Y-m-d_H-i-s',time()).'.csv';
			$fp = fopen($file_name, 'w');
			fwrite($fp, $insertQry);
			fclose($fp);	
		}
		
		//zip it up
		$zip = new ZipArchive();
		$zip_file = 'company_id_'.date('Y-m-d_H-i-s',time()).'.zip';
		if(file_exists($zip_file)) {
			unlink($zip_file);
		}
		
		if(true !== $zip -> open($zip_file, ZipArchive::CREATE)) {
			$resp['Failure'] = "Couldn't create zip archive ".$zip_file;//would probably affect other exports, quit script
		}
		
		$files = glob($dir.'/*.csv');
		
		foreach ($files as $file) {
			$zip -> addFile($file, str_replace($dir . DIRECTORY_SEPARATOR, '', $file));
		}
		$zip->close();
		
		foreach ($files as $file) {
			unlink(realpath($file));	
		}
		rmdir($dir);
		if($resp['Failure'] == ''){
			$resp['Success'] = 'Data exported and Zip file created';
			unset($resp['Failure']);
		}
		return json_encode($resp);
	}
	
	if(function_exists($_GET['export'])){
		$user_id = $_GET['user_id'];
		$company_id = $_GET['company_id'];
		$from_date = $_GET['from_date'];
		$to_date = $_GET['to_date'];
		echo getUserData($user_id, $company_id, $from_date, $to_date);
	}
	
	
	
	
	
?>