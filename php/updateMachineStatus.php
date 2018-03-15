<?php
  //connect to your database
    $conn = oci_connect('szier', 'Stefanz5', '//dbserver.engr.scu.edu/db11g');
    if (!$conn) {
        echo "ERROR_OCI_CONNECT";
        exit;
    }
  // Values from updateMachineForm
  $repairId = isset($_POST['details_repair_id']) ? $_POST['details_repair_id'] : null;
  $employeeNo = isset($_POST['details_employee_no']) ? $_POST['details_employee_no'] : null;
  $phone = isset($_POST['details_phone']) ? $_POST['details_phone'] : null;
  $machineId = isset($_POST['details_machine_id']) ? $_POST['details_machine_id'] : null;
  $serviceContractId = isset($_POST['details_service_contract_id']) ? $_POST['details_service_contract_id'] : null;
  $timeOfArrival = isset($_POST['details_time_of_arrival']) ? $_POST['details_time_of_arrival'] : null;
  $machineStatus = isset($_POST['details_machine_status']) ? $_POST['details_machine_status'] : null;
  // Insert into RepairJobs
  $insertRepairJobQueryString = "UPDATE RepairJobs SET status = '{$machineStatus}' WHERE repairId = '{$repairId}'";
  $insertRepairJobQuery = oci_parse($conn, $insertRepairJobQueryString);
  $insertRepairJobQueryResult = oci_execute($insertRepairJobQuery);
    if (!$insertRepairJobQueryResult) {
        $e = oci_error($insertRepairJobQuery);
        echo "InsertRepairJob Insert Error: {$e['message']}";
        exit;
    }
  // Values from updateMachineForm
  $billId = isset($_POST['details_bill_id']) ? $_POST['details_bill_id'] : null;
  $timeIn = isset($_POST['details_time_in']) ? $_POST['details_time_in'] : null;

  $timeOut = isset($_POST['details_time_out']) ? $_POST['details_time_out'] : null;
  $cost = isset($_POST['details_cost_of_parts']) ? $_POST['details_cost_of_parts'] : null;
  $hours = isset($_POST['details_time_hours']) ? $_POST['details_time_hours'] : null;

  // Query DB SingleContract to get contractId type
  $countCBs = 0;
  $cbQueryString = "SELECT * FROM CustomerBills WHERE billId='{$billId}'";
  $cbQuery = oci_parse($conn, $cbQueryString);
  $cbQueryStringResult = oci_execute($cbQuery);
  $row = oci_fetch_array($cbQuery, OCI_BOTH);
  
  $countCBs = 0;
  if ($row != false) {
    $countCBs = 1;
  }

  $total = 0;
  if (!$serviceContractId) {
  echo 'hours and cost: ' . $hours . $cost;
	if ($hours && $cost) {
    	$total = $hours*20 + 50 + $cost;
	}
  }

  $timeOut = (!$timeOut) ? "SYSTIMESTAMP" : "'{$timeOut}'";
  if ($countCBs > 0) {
    // Update into CustomerBill
    $cbQueryString = "UPDATE CustomerBills SET timeIn = '{$timeIn}', timeOut = {$timeOut}, costOfParts = {$cost}, laborHours = {$hours}, total={$total} WHERE billId = '{$billId}'";	
    $cbQuery = oci_parse($conn, $cbQueryString);
    $cbQueryResult = oci_execute($cbQuery);
      if (!$cbQueryResult) {
          $e = oci_error($cbQuery);
          echo "CustomerBill update Error: {$e['message']}";
          exit;
      }
  } else {
    // Update into CustomerBill
	$cost = (!$cost) ? "NULL" : $cost;
	$hours = (!$hours) ? "NULL" : $hours;
    $cbQueryString = "INSERT INTO CustomerBills VALUES('{$billId}', '{$machineId}', '{$phone}', TIMESTAMP'{$timeIn}', $timeOut, '{$employeeNo}', $cost, $hours, $total)";
	echo $cbQueryString;    
	$cbQuery = oci_parse($conn, $cbQueryString);
    $cbQueryResult = oci_execute($cbQuery);
  }


    echo 'SUCCESS. INSERTED VALUES';
    OCILogoff($conn);
