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
      maintainAspectRatio: false,
      scales: {
        yAxes: [
          {
            stacked: true,
            gridLines: {
              display: true,
              color: "rgba(255,99,132,0.2)"
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
  type: 'bar',
  data: {
    labels: ["Total", "Covered Total"],
    datasets: [{
      label: 'total',
      data: [d, d2],
          backgroundColor: "rgba(255,99,132,0.2)",
          borderColor: "rgba(255,99,132,1)",
          borderWidth: 2,
          hoverBackgroundColor: "rgba(255,99,132,0.4)",
          hoverBorderColor: "rgba(255,99,132,1)"
      }]
    }
  });

  }

  createGraph("Please enter a date range", "Please enter a date range", 0);
});
