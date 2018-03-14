<?php
  //connect to your database
	$conn = oci_connect('szier', 'Stefanz5', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
    	echo "ERROR_OCI_CONNECT";
    	exit;
	}

  	// Query DB to get item and machine counts
 	$getMachinesQueryString = "SELECT * FROM RepairLog";
 	$getMachinesQuery = oci_parse($conn, $getMachinesQueryString);
 	$getMachinesQueryResult = oci_execute($getMachinesQuery);
	
	$machinesString = "";
	$machineId;

	while (($row = oci_fetch_array($getMachinesQuery, OCI_BOTH)) != false) {
	  $machineId = $row[3];

	  $machinesString .= $machineId . "|";
	}

	echo $machinesString;
	OCILogoff($conn);
?>
