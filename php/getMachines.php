<?php
  //connect to your database
	$conn = oci_connect('szier', 'Stefanz5', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
    	echo "ERROR_OCI_CONNECT";
    	exit;
	}

  	// Query DB to get item and machine counts
 	$getMachinesQueryString = "SELECT * FROM RepairJobs";
 	$getMachinesQuery = oci_parse($conn, $getMachinesQueryString);
 	$getMachinesQueryResult = oci_execute($getMachinesQuery);
	
	$machinesString = "";
	$machineId;

	while (($row = oci_fetch_array($getMachinesQuery, OCI_BOTH)) != false) {
   	  $machinesString .= "{$row[0]},";
	  $machinesString .= "{$row[1]},";
	  $machinesString .= "{$row[2]},";
	  $machinesString .= "{$row[3]},";
	  $machineId = $row[3];
	  $machinesString .= "{$row[4]},";
	  $machinesString .= "{$row[5]},";
	  $machinesString .= "{$row[6]},";

  	  // Query DB to get item and machine counts
 	  $getCustomerBillQueryString = "SELECT * FROM CustomerBills WHERE machineId='{$machineId}'";
 	  $getCustomerBillQuery = oci_parse($conn, $getCustomerBillQueryString);
 	  $getCustomerBillQueryResult = oci_execute($getCustomerBillQuery);
  
	  while (oci_fetch($getCustomerBillQuery)) {
	   	  $billId = oci_result($getCustomerBillQuery, 'BILLID');
		  $timeIn = oci_result($getCustomerBillQuery, 'TIMEIN');
		  $timeOut = oci_result($getCustomerBillQuery, 'TIMEOUT');
		  $costOfParts = oci_result($getCustomerBillQuery, 'COSTOFPARTS');
		  $hours = oci_result($getCustomerBillQuery, 'LABORHOURS');
		  $total = oci_result($getCustomerBillQuery, 'TOTAL');

		  $machinesString .= "{$billId},";
		  $machinesString .= "{$timeIn},";
		  $machinesString .= "{$timeOut},";
		  $machinesString .= "{$costOfParts},";
		  $machinesString .= "{$hours},";
		  $machinesString .= "{$total}|";
	  }
	}

	echo $machinesString;
	OCILogoff($conn);
?>
