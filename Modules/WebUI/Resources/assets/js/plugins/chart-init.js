var ctx = document.getElementById("lineChart").getContext("2d");
var gradient = ctx.createLinearGradient(0, 0, 0, 400);


var myChart = new Chart(ctx, {
  type: "line", // Sử dụng biểu đồ line và thiết lập vùng fill
  data: {
    labels: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "	Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
    datasets: [
      {
        data: [
          42, 45, 70, 65, 140, 130, 145, 145, 160, 135, 140, 130, 135, 140, 250,
        ],
        backgroundColor: gradient, // Sử dụng gradient cho vùng màu
        borderColor: "#073B3A", // Màu của đường viền
        borderWidth: 2,
        fill: true, // Đổ màu bên dưới đường
        tension: 0.4, // Tạo đường cong mềm mại cho biểu đồ
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: false, 
      },
    },
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  },
});