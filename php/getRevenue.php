<?php
  //connect to your database
    $conn = oci_connect('szier', 'Stefanz5', '//dbserver.engr.scu.edu/db11g');
    if (!$conn) {
        echo "ERROR_OCI_CONNECT";
        exit;
    }

    $date_1 = isset($_POST['date_1']) ? date('Y-m-d H:i:s.00', strtotime($_POST['date_1'])) : null;
    $date_2 = isset($_POST['date_2']) ? date('Y-m-d H:i:s.00', strtotime($_POST['date_2'])) : null;

    // Query DB to get item and machine counts
    $getMachineIDsQueryString = "SELECT * FROM RepairLog";
    $getMachineIDsQuery = oci_parse($conn, $getMachineIDsQueryString);
    $getMachineIDsQueryResult = oci_execute($getMachineIDsQuery);

    $machineIDs = array();
    while (($row = oci_fetch_array($getMachineIDsQuery, OCI_BOTH)) != false) {
        array_push($machineIDs, $row[3]);
    }


    $getCustomerBillQueryString = "SELECT * FROM CustomerBills WHERE timeOut BETWEEN TIMESTAMP'{$date_1}' AND TIMESTAMP'{$date_2}'";
    $getCustomerBillQuery = oci_parse($conn, $getCustomerBillQueryString);
    $getCustomerBillQueryResult = oci_execute($getCustomerBillQuery);

    $total = 0;
    while (($row = oci_fetch_array($getCustomerBillQuery, OCI_BOTH)) != false) {
        $currMachineID = $row[1];
        $currTotal = $row[8];
        foreach ($machineIDs as &$mID) {
            if ($currMachineID == $mID) {
                $total += (float)$currTotal;
            }
        }
    }


    echo $total;

    OCILogoff($conn);
