$(document).ready(function() {
  $("#getRevenue").submit(function(e) {
    e.preventDefault();
    var dates = {
      date_1: $("input[name=date_1").val(),
      date_2: $("input[name=date_2").val()
    };

    console.log(dates);
	console.log(db);
    db.receiveInfo("./php/getRevenue.php", dates, "POST", function(data) {
      if (data.length === 0) {
        alert("Uh oh! Nothing found with this date range.");
      }

      var title = `Range: ${$("input[name=date_1").val()}-${$(
        "input[name=date_2"
      ).val()}`;
      var axisTitle = `${$("input[name=date_1").val()}-${$(
        "input[name=date_2"
      ).val()}`;

	
	  var data = data.split("|");
	  var total = data[0];
	  var coveredTotal = data[1];
      createGraph(axisTitle, title, Number(total), Number(coveredTotal));
    });
  });

  function createGraph(labels, label, d, d2) {
    var data = {
      datasets: [
        {
          backgroundColor: "rgba(255,99,132,0.2)",
          borderColor: "rgba(255,99,132,1)",
          borderWidth: 2,
          hoverBackgroundColor: "rgba(255,99,132,0.4)",
          hoverBorderColor: "rgba(255,99,132,1)"
        }
      ]
    };

    var options = {
      scales: {
        yAxes: [
          {
            gridLines: {
              display: true,
              color: "rgba(255,99,132,0.2)",
            },
	    ticks: {
		beginAtZero: true
	    }
          }
        ],
        xAxes: [
          {
            gridLines: {
              display: false
            }
          }
        ]
      }
    };

  var myChart = new Chart("chart", {
  options,
  type: 'bar',
  data: {
    labels: ["Totals"],
    datasets: [{
      label: 'Unconvered Total',
          backgroundColor: "rgba(255,99,132,0.2)",
          borderColor: "rgba(255,99,132,1)",
          borderWidth: 1,
          hoverBackgroundColor: "rgba(255,99,132,0.4)",
          hoverBorderColor: "rgba(255,99,132,1)",
	  data: [d]
      }, {
      label: 'Covered',
          backgroundColor: "rgba(255,99,132,0.2)",
          borderColor: "rgba(255,99,132,1)",
          borderWidth: 1,
          hoverBackgroundColor: "rgba(255,99,132,0.4)",
          hoverBorderColor: "rgba(255,99,132,1)",
	  data: [d2]
      }]
    }
  });

  }

  createGraph("Please enter a date range", "Please enter a date range", 0, 0);
});
