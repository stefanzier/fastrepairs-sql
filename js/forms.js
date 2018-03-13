$(window).on("load", function() {
  // Accept Machine for Repair Handler
  $("#acceptMachineForm").submit(function(e) {
    e.preventDefault();

    var machine = {
      contractId: $("input[name=contract_id]").val(),
      name: $("input[name=cust_name]").val(),
      phone: $("input[name=cust_phone]").val(),
      model: $("input[name=model]").val(),
      price: $("input[name=price]").val(),
      year: $("input[name=year]").val(),
      machineType: $("select[name=machine_type]").val()
    };

    db.receiveInfo("./../../php/acceptForRepair.php", machine, "POST", function(
      data
    ) {
      console.log("Data Returned: ", data);
    });
  });

  // Show all machines in update machines modal
  $("#updateMachines").on("click", function(e) {
    db.receiveInfo("./php/getMachines.php", {}, "GET", function(data) {
      if (data.length === 0) {
		alert("Uh oh! Please ensure that there are machines in RepairJobs table.");
	  }

		var machines = data.split("|");
		localStorage.setItem('machines', data);
		$("#machine-items-dialog").append($("<div class='machine-items'></div>"));
		for (var i = 0; i < machines.length; i++) {
			if (i == machines.length-1) break; // remove undefined row
			var m = machines[i].split(",");
			var itemId = m[0];
			var employeeNo = m[1];
			var phone = m[2];
			var machineId = m[3];
			var serviceContractId = m[4];
			var timeOfArrival = m[5];
			var status = m[6];
		

			// Find the machines table and append rows to it with the new information
			$(".machine-items").append($('<div class="machine-item-details">').append($('<ul>')
        		.append($('<li>'+machineId+'</li>')) 
        		.append($('<li>'+status+'</li>')) 
        		.append($('<li><a class="machine-details-link" href="#updateMachineDetailsModal">UPDATE STATUS('+itemId+')</a></li>')) 
       		))
		}

		 // Close existing update machines modal when user wants to update machine-details
 		$(".machine-details-link").on("click", function() {
			var text = $(this).text();
			var jobId = text.substring('UPDATE STATUS('.length, text.length).slice(0, -1);
			$(".machine-items").remove();
			var machines = localStorage.getItem('machines').split("|");
			
			for (var i = 0; i < machines.length; i++) {
				if (machines[i].includes(jobId)) {
					var m = machines[i].split(",");
					var employeeNo = m[1];
					var phone = m[2];
					var machineId = m[3];
					var serviceContractId = m[4];
					var timeOfArrival = m[5];
					var status = m[6];
					var billId = m[7];
					var timeIn = m[8];
					var timeOut = m[9];
					var cost = m[10];
					var hours = m[11];

					$("input[name=details_repair_id]").val(jobId);
					$("input[name=details_employee_no]").val(employeeNo);
					$("input[name=details_bill_id]").val(billId);
					$("input[name=details_phone]").val(phone);
					$("input[name=details_machine_id]").val(machineId);
					$("input[name=details_service_contract_id]").val(serviceContractId);
					$("input[name=details_time_of_arrival]").val(timeOfArrival);
					$("input[name=details_machine_status]").val(status);
					$("input[name=details_time_in]").val(timeIn);
					$("input[name=details_time_out]").val(timeOut);
					$("input[name=details_cost_of_parts]").val(cost);
					$("input[name=details_time_hours]").val(hours);
				}
			}
		 });
    });
  });


  // Update Machine Status Form Submission
  $("#machineDetailsForm").submit(function(e) {
    e.preventDefault();

    var repairJob = {
      details_repair_id: $("input[name=details_repair_id]").val(),
      details_employee_no: $("input[name=details_employee_no]").val(),
      details_bill_id: $("input[name=details_bill_id]").val(),
      details_phone: $("input[name=details_phone]").val(),
      details_machine_id: $("input[name=details_machine_id]").val(),
      details_machine_status: $("select[name=details_machine_status]").val(),
      details_service_contract_id: $("input[name=details_service_contract_id]").val(),
      details_time_in: $("input[name=details_time_in]").val(),
      details_time_out: $("input[name=details_time_out]").val(),
      details_cost_of_parts: $("input[name=details_cost_of_parts]").val(),
      details_time_hours: $("input[name=details_time_hours]").val()
    };

	console.log(repairJob);
    db.receiveInfo("./php/updateMachineStatus.php", repairJob, "POST", function(
      data
    ) {
      console.log("Data Returned: ", data);
    });
  });


  // Show repair jobs
  setTimeout(function() {
  if ($("#repair-jobs-list").length) {
	console.log(db);
    db.receiveInfo("./php/showRepairsJobs.php", {}, "GET", function(data) {
      if (data.length === 0) {
        alert("Uh oh! There are no repair jobs!");
	  }

	  var jobs = data.split("|");
	  console.log(jobs);
	  for (var i = 0; i < jobs.length; i++) {
		if (i === jobs.length-1) break;

        var j = jobs[i].split(",");
		var jobId = j[0];
		var empNo = j[1];
		var phone = j[2];
		var machineId = j[3];
		var serviceContractId = j[4];
		var timeOfArrival = j[5];

		$("#repair-jobs-list").append($('<div class="repair-job-details">').append($('<ul>')
        		.append($('<li>'+jobId+'</li>')) 
        		.append($('<li>'+empNo+'</li>'))
         		.append($('<li>'+phone+'</li>'))
        		.append($('<li>'+machineId+'</li>'))
        		.append($('<li>'+serviceContractId+'</li>'))
        		.append($('<li>'+timeOfArrival+'</li>'))
       		))
	  }
	});
  }
  }, 500);


});
