// Wrap your code in a DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function () {
  // Function to update the chart with the fetched data
  async function updateChart() {
    try {
      // Fetch data from the server
      const response = await fetch('https://xclusiveautospa.site/assets/php/chart-bookings.php');
      const newData = await response.json();

      // Extract labels and data from the fetched JSON
      const labels = newData.map(entry => entry.status);
      const data = newData.map(entry => entry.count);

      const colors = [
        '#FFCE56', // Yellow for Pending
        '#36A2EB', // Blue for Confirmed
        '#FF6384', // Red for Cancelled
        '#4CAF50', // Green for Completed
        '#FF5733', // Orange for Declined
        '#9B59B6', // Purple for Waitlisted
        '#3498DB'  // Another shade of blue for Refund
      ];

      // Update the chart data
      myChart.data.labels = labels;
      myChart.data.datasets[0].data = data;
      myChart.data.datasets[0].backgroundColor = colors;

      // Update the chart
      myChart.update();
    } catch (error) {
      console.error('Error updating chart:', error);
    }
  }

  // Initial chart setup
  const chartData = {
    labels: [], // Will be populated with status values
    data: [],   // Will be populated with count values
  };

  const myChart = new Chart(document.getElementById("booking-chart"), {
    type: "doughnut",
    data: {
      labels: chartData.labels,
      datasets: [
        {
          label: "Bookings",
          data: chartData.data,
        },
      ],
    },
    options: {
      borderWidth: 10,
      borderRadius: 2,
      hoverBorderWidth: 0,
      plugins: {
        legend: {
          display: true,
          position: 'right',
        },
      },
    },
  });

  // Call the updateChart function to fetch and update data
  updateChart();
});
