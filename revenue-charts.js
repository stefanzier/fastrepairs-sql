console.log(Chart);
var data = {
  labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
  datasets: [
    {
      label: "Revenue by month",
      backgroundColor: "rgba(255,99,132,0.2)",
      borderColor: "rgba(255,99,132,1)",
      borderWidth: 2,
      hoverBackgroundColor: "rgba(255,99,132,0.4)",
      hoverBorderColor: "rgba(255,99,132,1)",
      data: [65, 59, 20, 81, 56, 55, 40]
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
