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

  // Accept Machine for Repair Handler
  $("#updateMachinesModal").on("click", function(e) {
    db.receiveInfo("./php/getMachines.php", {}, "GET", function(data) {
      console.log("Data Returned: ", data);
    });
  });
});
