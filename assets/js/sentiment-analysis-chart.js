// chart.js

document.addEventListener('DOMContentLoaded', function () {
    // Function to fetch sentiment data from the server
    async function fetchSentimentData() {
      try {
        const response = await fetch('https://xclusiveautospa.site/assets/php/chart-sentiment-analysis.php');
        const data = await response.json();
        return data;
      } catch (error) {
        console.error('Error fetching sentiment data:', error);
        throw error;
      }
    }
  
    // Function to update the pie chart with the fetched data
    async function updatePieChart() {
      try {
        // Fetch sentiment data from the server
        const sentimentData = await fetchSentimentData();
  
        // Extract labels and data from the fetched JSON
        const labels = sentimentData.map(entry => entry.sentiment);
        const data = sentimentData.map(entry => entry.count);
  
        // Define custom colors for each sentiment
        const colors = {
            'positive': '#4CAF50',  // Green
            'negative': '#FF5252',  // Dark Red
            'neutral': '#FFC107',   // Amber
         
        };
  
        // Map the colors based on sentiments
        const backgroundColor = labels.map(sentiment => colors[sentiment]);
  
        // Get the pie chart canvas
        var ctx = document.getElementById('sentiment-chart').getContext('2d');
  
        // Create a new pie chart
        var pieChart = new Chart(ctx, {
          type: 'pie',
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
              text: 'Sentiment Distribution',
              fontColor: 'black'
            }
            // Add additional options here if needed
          }
        });
      } catch (error) {
        console.error('Error updating pie chart:', error);
      }
    }
  
    // Call the updatePieChart function to fetch and update sentiment data
    updatePieChart();
  });
  