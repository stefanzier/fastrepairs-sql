<?php
  //connect to your database
	$conn = oci_connect('szier', 'Stefanz5', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
    echo "ERROR_OCI_CONNECT";
    exit;
	}

  // Values from acceptMachineForm
  $contract_id = isset($_POST['contractId']) ? $_POST['contractId'] : null;
  $name = isset($_POST['name']) ? $_POST['name'] : null;
  $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
  $model = isset($_POST['model']) ? $_POST['model'] : null;
  $price = isset($_POST['price']) ? $_POST['price'] : null;
  $year = isset($_POST['year']) ? $_POST['year'] : null;
  $machine_type = isset($_POST['machineType']) ? $_POST['machineType'] : null;
  $machine_table = ($machine_type == "Computer") ? "Computers" : "Printers";

  // Query DB to get item and machine counts
  $itemCountQueryString = "SELECT COUNT(*) AS count FROM RepairItems";
  $itemCountQuery = oci_parse($conn, $itemCountQueryString);
  $itemCountQueryResult = oci_execute($itemCountQuery);

  if (!$itemCountQueryResult) {
		$e = oci_error($itemCountQuery);
		echo "ItemCountQuery Error: {$e['message']}";
		exit;
	}

	$itemCount = 0;
	while (oci_fetch($itemCountQuery)) {
		$itemCount = oci_result($itemCountQuery, 'COUNT');
	}

	// Itema and machine id declarations
  $itemCount = $itemCount+1;
	$itemId = "item{$itemCount}";
	$machineId = "m{$itemCount}";
	$repairId = "job{$itemCount}";


  // Query DB to get item and machine counts
  $empCountQueryString = "SELECT COUNT(*) AS count FROM RepairPersons";
  $empCountQuery = oci_parse($conn, $empCountQueryString);
  $empCountQueryResult = oci_execute($empCountQuery);

  if (!$empCountQueryResult) {
	  $e = oci_error($empCountQuery);
      echo "empCountQuery Error: {$e['message']}";
	  exit;
	}

	$empCount = 0;
	while (oci_fetch($empCountQuery)) {
		$empCount = rand(1, oci_result($empCountQuery, 'COUNT'));
	}

    // Query DB SingleContract to get contractId type
	$serviceContractQueryString = "SELECT * FROM ServiceContracts WHERE contractId='{$contract_id}' AND SYSDATE BETWEEN startDate AND endDate";
	$serviceContractQuery = oci_parse($conn, $serviceContractQueryString);
	$serviceContractQueryResult = oci_execute($serviceContractQuery);

	$countSCs = 0;
	while (($row = oci_fetch_array($serviceContractQuery, OCI_BOTH)) != false) {
		$countSCs += 1;
	}
	if ($countSCs == 0) {
		echo "ERR404";
		exit;
	}

	// If the contractId is not empty then we need to set the $contract_type to Single or GROUP
	$contract_type = "NONE";
	$errorMessage  = "";
	if ($contract_id !== '') {
		// Query DB SingleContract to get contractId type
	  $contractTypeQueryString = "SELECT * FROM SingleContracts WHERE contractid='{$contract_id}'";
	  $contractTypeQuery = oci_parse($conn, $contractTypeQueryString);
	  $contractTypeQueryResult = oci_execute($contractTypeQuery);

	  if (oci_num_rows($contractTypeQuery) > 0) {
			$contract_type = "SINGLE";
		} else {
			// No SingleContract present for this ID - Check GroupContracts
			$contractTypeQueryString = "SELECT * FROM GroupContracts WHERE contractid='{$contract_id}'";
	       	$contractTypeQuery = oci_parse($conn, $contractTypeQueryString);
		    $contractTypeQueryResult = oci_execute($contractTypeQuery);
			if (oci_num_rows($contractTypeQuery) > 0) {
				$contract_type = "GROUP";
			}
		}
	} else {
		$contract_id = NULL;
  }


    // Insert into Customers
    $insertCustomerQueryString = "INSERT INTO Customers VALUES('{$name}', '{$phone}')";
	$insertCustomerQuery = oci_parse($conn, $insertCustomerQueryString);
	$insertCustomerQueryResult = oci_execute($insertCustomerQuery);


	if (!$insertCustomerQueryResult) {
		echo "There's already a customer with this phone/email in the database. Continuing...";
	}

    // Insert into RepairItems
    $insertRepairItemQueryString = "INSERT INTO RepairItems VALUES('{$itemId}', '{$machineId}', {$price}, {$year}, '{$contract_type}')";
	$insertRepairItemQuery = oci_parse($conn, $insertRepairItemQueryString);
	$insertRepairItemQueryResult = oci_execute($insertRepairItemQuery);

	if (!$insertRepairItemQueryResult) {
		$e = oci_error($insertRepairItemQuery);
		echo "InsertRepairItem Error: {$e['message']}";
		exit;
	}

  // Insert into Computers or Printers
  $insertMachineQueryString = "INSERT INTO {$machine_table} VALUES('{$itemId}')";
	$insertMachineQuery = oci_parse($conn, $insertMachineQueryString);
	$insertMachineQueryResult = oci_execute($insertMachineQuery);
	if (!$insertMachineQueryResult) {
		$e = oci_error($insertMachineQuery);
		echo "InsertMachine Error: {$e['message']}";
		exit;
	}

	echo $contract_id;
    echo $itemId;

  // Insert into RepairJobs
	$employeeNo = "emp00{$empCount}";
	$currentTime = time();
	$timestamp = date("Y-m-d H:i:s.00", $currentTime);

  $insertRepairJobQueryString = "INSERT INTO RepairJobs VALUES('{$repairId}', '{$employeeNo}', '{$phone}', '{$itemId}', '{$contract_id}', TIMESTAMP '${timestamp}', 'UNDER_REPAIR')";
	$insertRepairJobQuery = oci_parse($conn, $insertRepairJobQueryString);
	$insertRepairJobQueryResult = oci_execute($insertRepairJobQuery);
	if (!$insertRepairJobQueryResult) {
		$e = oci_error($insertRepairJobQuery);
		echo "InsertRepairJob Error: {$e['message']}\n\n";
		exit;
	}



	echo 'SUCCESS. INSERTED VALUES';
	OCILogoff($conn);
?>
