<?php
  //connect to your database
	$conn = oci_connect('szier', 'Stefanz5', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
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
  $insertRepairJobQueryString = "UPDATE RepairJobs SET machineStatus = '{$machineStatus}' WHERE repairId = '{$repairId}')";
  $insertRepairJobQuery = oci_parse($conn, $insertRepairJobQueryString);
  $insertRepairJobQueryResult = oci_execute($insertRepairJobQuery);

	if (!$insertRepairJobQueryResult) {
		$e = oci_error($insertRepairJobQuery);
		echo "InsertRepairJob Insert Error: {$e['message']}";
		exit;
	}

  // Values from updateMachineForm
  $billId = isset($_POST['details_bill_id']) ? $_POST['details_bill_id'] : null;
  $timeIn = isset($_POST['time_in']) ? $_POST['time_in'] : null;
  $timeOut = isset($_POST['time_out']) ? $_POST['time_out'] : null;
  $cost = isset($_POST['cost']) ? $_POST['cost'] : null;
  $hours = isset($_POST['hours']) ? $_POST['hours'] : null;

  // Insert into CustomerBill
  $insertBillQueryString = "UPDATE CustomerBills SET timeIn = '{$time_in}', timeOut = '{$time_out}', cost = '{$cost}', hours = '{$hours}' WHERE billId = '{$billId}')";
  $insertBillQuery = oci_parse($conn, $insertBillQueryString);
  $insertBillQueryResult = oci_execute($insertBillQuery);

	if (!$insertBillQueryResult) {
		$e = oci_error($insertBillQuery);
		echo "CustomerBill Insert Error: {$e['message']}";
		exit;
	}

	echo 'SUCCESS. INSERTED VALUES';
	OCILogoff($conn);
?>
