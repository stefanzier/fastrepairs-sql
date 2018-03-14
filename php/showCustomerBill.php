<?php

  //connect to your database
    $conn = oci_connect('szier', 'Stefanz5', '//dbserver.engr.scu.edu/db11g');
    if (!$conn) {
        echo "ERROR_OCI_CONNECT";
        exit;
    }

  // Get selected machinedId
  $machineId = isset($_POST['machineId']) ? $_POST['machineId'] : null;

  // Query CustomerBills for the passed machineId
  $billsQueryString = "SELECT * FROM CustomerBills WHERE machineId = '{$machineId}'";
  $billsQuery = oci_parse($conn, $billsQueryString);
  $billsQueryResult = oci_execute($billsQuery);
  if (!$billsQueryResult) {
      $e = oci_error($billsQuery);
      echo "CustomerBills Query Error: {$e['message']}";
      exit;
  }

  // Get CustomerBills values into variables
  //$costOfParts = oci_result($billsQuery, 'COSTOFPARTS');
  //echo $costOfParts;
  //$laborHours = oci_result($billsQuery, 'LABORHOURS');
  //$total = oci_result($billsQuery, 'TOTAL');
  //$phone = oci_result($billsQuery, 'PHONE');

  $costOfParts = 0;
  $laborHours = 0;
  $total = 0;
  $phone;
  $timeOut;
  while (($row = oci_fetch_array($billsQuery, OCI_BOTH)) != false) {
      $costOfParts = $row[7];
      $laborHours = $row[6];
      $total = $row[8];
      $phone = $row[2];
      $timeOut = $row[4];
  }

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
  $name = "";

  while (($row = oci_fetch_array($customerQuery, OCI_BOTH)) != false) {
      $name = $row[0];
  }
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
  $model = "";

  while (($row = oci_fetch_array($repairQuery, OCI_BOTH)) != false) {
      $model = $row[1];
  }

  // Query RepairJobs for Time of Arrival
  $jobsQueryString = "SELECT * FROM RepairLog WHERE machineId = '{$machineId}'";
  $jobsQuery = oci_parse($conn, $jobsQueryString);
  $jobsQueryResult = oci_execute($jobsQuery);
  if (!$jobsQueryResult) {
      $e = oci_error($jobsQuery);
      echo "CustomerBills Query Error: {$e['message']}";
      exit;
  }
  // Get Arrival value into variable
  $timeOfArrival;

  while (($row = oci_fetch_array($jobsQuery, OCI_BOTH)) != false) {
      $timeOfArrival = $row[5];
  }

  // Query Problem Report for all problem codes
  $probReportQueryString = "SELECT * FROM ProblemReports NATURAL JOIN Problems WHERE itemId = '{$machineId}'";
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

  while (($row = oci_fetch_array($probReportQuery, OCI_BOTH)) != false) {
      array_push($problemIds, $row[0]);
      array_push($problemDesc, $row[2]);
  }

  // Write a result string including all results for the query
  $resultString = "";
  $resultString =  $costOfParts . "," . $laborHours . "," . $total . "," . $phone . "," . $name . "," . $model . "," . $timeOfArrival . "," . $timeOut . "/";
  for ($i = 0; $i < count($problemIds); $i++) {
      $resultString = $resultString . $problemIds[$i] . "," . $problemDesc[$i] . "%";
  }

  //$resultString =  $costOfParts;
  echo $resultString;
  //echo 'SUCCESS. Customer Bills shown.';
  OCILogoff($conn);
