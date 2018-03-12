<?php
	
  //connect to your database
	$conn = oci_connect('szier', 'Stefanz5', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
    echo "ERROR_OCI_CONNECT";
    exit;
	}
  // Query DB for repair jobs that are under repair
  $repairJobsQueryString = "SELECT * FROM RepairJobs WHERE status = 'UNDER_REPAIR'";
  $repairJobsQuery = oci_parse($conn, $repairJobsQueryString);
  $repairJobsQueryResult = oci_execute($repairJobsQuery);
  if (!$repairJobsQueryResult) {
		$e = oci_error($repairJobsQuery);
		echo "RepairJobsQuery Error: {$e['message']}";
		exit;
  }
  
  // Write a result string including all results for the query
  $resultString = "";
  while (oci_fetch($repairJobsQuery)) {
	$repairId = oci_result($repairJobsQuery, 'REPAIRID');
	$employeeNo = oci_result($repairJobsQuery, 'EMPLOYEENO');
	$phone = oci_result($repairJobsQuery, 'PHONE');
	$machineId = oci_result($repairJobsQuery, 'MACHINEID');
	$serviceContractId = oci_result($repairJobsQuery, 'SERVICECONTRACTID');
	$timeOfArrival = oci_result($repairJobsQuery, 'TIMEOFARRIVAL');
	$resultString =  $resultString . $repairId . "," . $employeeNo . "," . $phone . "," . $machineId . "," . $serviceContractId . "," . $timeOfArrival . "|";
  }
  echo $resultString;
  OCILogoff($conn);
?>
