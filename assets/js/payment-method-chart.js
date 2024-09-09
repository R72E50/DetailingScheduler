document.addEventListener('DOMContentLoaded', function () {
  // Function to fetch payment method data from the server
  async function fetchPaymentData() {
    try {
      const response = await fetch('https://xclusiveautospa.site/assets/php/chart-payment.php');
      const data = await response.json();
      return data;
    } catch (error) {
      console.error('Error fetching payment data:', error);
      throw error;
    }
  }

  // Function to update the doughnut chart with the fetched data
  async function updateDoughnutChart() {
    try {
      // Fetch payment method data from the server
      const paymentData = await fetchPaymentData();

      // Extract labels and data from the fetched JSON
      const labels = paymentData.map(entry => entry.method);
      const data = paymentData.map(entry => entry.count);

      // Define custom colors for each payment method
      const colors = {
        'Cash': '#FF6384',     // Red
        'Paypal': '#36A2EB',   // Blue
        'Nextpay': '#FFCE56',  // Yellow
        // Add more colors for other payment methods if needed
      };

      // Map the colors based on payment methods
      const backgroundColor = labels.map(method => colors[method]);

      // Get the doughnut chart canvas
      var ctx = document.getElementById('payment-method').getContext('2d');

      // Create a new doughnut chart
      var doughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            data: data,
            backgroundColor: backgroundColor
          }]
        },
        options: {
          legend: {
            display: false,
            position: 'right',
            labels: {
              fontColor: 'black'
            }
          },
          title: {
            display: true,
            text: 'Payment Method Distribution',
            fontColor: 'black'
          }
          // Add additional options here if needed
        }
      });
    } catch (error) {
      console.error('Error updating doughnut chart:', error);
    }
  }

  // Call the updateDoughnutChart function to fetch and update payment data
  updateDoughnutChart();
});
