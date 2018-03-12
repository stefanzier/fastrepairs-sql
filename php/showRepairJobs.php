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
	$repairId = oci_result($repairJobsQuery, 'repairId');
	$employeeNo = oci_result($repairJobsQuery, 'employeeNo');
	$phone = oci_result($repairJobsQuery, 'phone');
	$machineId = oci_result($repairJobsQuery, 'machineId');
	$serviceContractId = oci_result($repairJobsQuery, 'serviceContractId');
	$timeOfArrival = oci_result($repairJobsQuery, 'timeOfArrival');

	$resultString =  $resultString . "|" . $repairId . "," . $employeeNo . "," . $phone . "," . $machineId . "," . 					     $serviceContractId . "," . $timeOfArrival;
  }

  echo $resultString;

  echo 'SUCCESS. Repair Jobs shown.';
  OCILogoff($conn);

?>
