<?php
	
  //connect to your database
	$conn = oci_connect('szier', 'Stefanz5', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
    echo "ERROR_OCI_CONNECT";
    exit;
	}
	
  // Get selected machinedId
  $machineId = isset($_POST['machineId']) ? $_POST['machineId'] : null;;

  // Query CustomerBills for the passed machineId
  $billsQueryString = "SELECT * FROM CustomerBills WHERE machineId = '{$machineId}')";
  $billsQuery = oci_parse($conn, $billsQueryString);
  $billsQueryResult = oci_execute($billsQuery);
  if (!$billsQueryResult) {
		$e = oci_error($billsQuery);
		echo "CustomerBills Query Error: {$e['message']}";
		exit;
  }  
  // Get CustomerBills values into variables
  $costOfParts = oci_result($billsQuery, 'costOfParts');
  $laborHours = oci_result($billsQuery, 'laborHours');
  $total = oci_result($billsQuery, 'total');
  $phone = oci_result($billsQuery, 'phone');

  // Query Customers for name using phone
  $customerQueryString = "SELECT * FROM Customers WHERE phone = '{$phone}'";
  $customerQuery = oci_parse($conn, $customerQueryString);
  $customerQueryResult = oci_execute($customerQuery);
  if (!$customerQueryResult) {
		$e = oci_error($customerQuery);
		echo "Customers Query Error: {$e['message']}";
		exit;
  }
  // Get Customer name value into variable
  $name = oci_result($customerQuery, 'name');

  // Query RepairItem for Model
  $repairQueryString = "SELECT * FROM RepairItems WHERE itemId = '{$machineId}'";
  $repairQuery = oci_parse($conn, $repairQueryString);
  $repairQueryResult = oci_execute($repairQuery);
  if (!$repairQueryResult) {
		$e = oci_error($repairQuery);
		echo "RepairItem Query Error: {$e['message']}";
		exit;
  }
  // Get model value into variable
  $model = oci_result($repairQuery, 'model');

  // Query RepairJobs for Time of Arrival
  $jobsQueryString = "SELECT * FROM RepairJosb WHERE machineId = '{$machineId}'";
  $jobsQuery = oci_parse($conn, $jobsQueryString);
  $jobsQueryResult = oci_execute($jobsQuery);
  if (!$jobsQueryResult) {
		$e = oci_error($jobsQuery);
		echo "CustomerBills Query Error: {$e['message']}";
		exit;
  }
  // Get Arrival value into variable
  $ = oci_result($jobsQuery, 'TIMEOFARRIVAL');

  // Query Problem Report for all problem codes
  $probReportQueryString = "SELECT * FROM ProblemReport NATURAL JOIN Problems WHERE itemId = '{$machineId}'";
  $probReportQuery = oci_parse($conn, $probReportQueryString);
  $probReportQueryResult = oci_execute($probReportQuery);
  if (!$probReportQueryResult) {
		$e = oci_error($probReportQuery);
		echo "Problem Report Query Error: {$e['message']}";
		exit;
  }
  // Get problem id's into variable
  $problemIds = array();
  $problemDesc = array();
  while (oci_fetch($probReportQuery)) {
	array_push($problemIds, oci_result($probReportQuery, 'CODE'));
    array_push($problemDesc, oci_result($probReportQuery, 'DESC'));
  }

  // Write a result string including all results for the query
  $resultString = "";
  $resultString =  $costOfParts . "|" . $laborHours . "," . $total . "," . $phone . "," . $name . "," . $model . "," . $timeOfArrival . ",";
  for($i = 0; $i < count($problemIds); $i++){
    $resultString = $resultString . $problemIds[$i] . "," . $problemDesc[$i] . "/";
  }              

  echo $resultString;

  echo 'SUCCESS. Customer Bills shown.';
  OCILogoff($conn);

?>
