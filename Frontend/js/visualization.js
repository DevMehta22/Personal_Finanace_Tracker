document.addEventListener('DOMContentLoaded', async () => {
    const userId = sessionStorage.getItem('userId');
    
    try {
        const response = await fetch(`http://localhost:8000/public/dataVisualization.php?user_id=${userId}`);
        const summaryData = await response.json();
        
        // Prepare data for the chart
        const labels = summaryData.summary.map(data => `Month ${data.month}`);
        const incomeData = summaryData.summary.map(data => data.total_income);
        const expenseData = summaryData.summary.map(data => data.total_expense);

        // Render the chart
        const ctx = document.getElementById('transactionChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Income',
                        data: incomeData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: expenseData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount ($)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error("Error loading data:", error);
    }
});
