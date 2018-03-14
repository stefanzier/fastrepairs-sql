$(document).ready(function() {
  $("#getRevenue").submit(function(e) {
    e.preventDefault();
    var dates = {
      date_1: $("input[name=date_1").val(),
      date_2: $("input[name=date_2").val()
    };

    console.log(dates);
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
      createGraph(axisTitle, title, Number(data));
    });
  });

  function createGraph(labels, label, data) {
    var data = {
      labels: [labels],
      datasets: [
        {
          label: label,
          backgroundColor: "rgba(255,99,132,0.2)",
          borderColor: "rgba(255,99,132,1)",
          borderWidth: 2,
          hoverBackgroundColor: "rgba(255,99,132,0.4)",
          hoverBorderColor: "rgba(255,99,132,1)",
          data: [data]
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
      options,
      data,
      type: "bar"
    });

    var labels = {
      apples: true,
      oranges: true
    };
  }

  createGraph("Please enter a date range", "Please enter a date range", 0);
});
