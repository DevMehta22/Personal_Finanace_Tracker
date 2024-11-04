document.addEventListener('DOMContentLoaded', async () => {
    await fetchSummary();
});

async function fetchSummary() {
    const summaryDiv = document.getElementById('summary');
    const userId = sessionStorage.getItem('userId');

    const response = await fetch(`http://localhost:8000/public/monthly_summary.php?user_id=${userId}`);
    const summaryData = await response.json();

    summaryDiv.innerHTML = `
        <div>
            <h3>Starting Balance</h3>
            <p>$${summaryData.starting_balance}</p>
        </div>
        <div>
            <h3>Total Income</h3>
            <p>$${summaryData.total_income}</p>
        </div>
        <div>
            <h3>Total Expenses</h3>
            <p>$${summaryData.total_expense}</p>
        </div>
        <div>
            <h3>Current Balance</h3>
            <p>$${summaryData.current_balance}</p>
        </div>
    `;
}

async function setStartingBalance() {
    const startingBalance = document.getElementById("startingBalance").value;
    const userId = sessionStorage.getItem("userId");

    try {
        const response = await fetch(`http://localhost:8000/public/setStartingBalance.php?user_id=${userId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ starting_balance: startingBalance })
        });
        const result = await response.json();
        console.log(result);
        const message = document.getElementById("balanceMessage");

        if (response.ok) {
            message.innerText = "Starting balance set successfully!";
            message.style.color = "green";
            await fetchSummary(); // Refresh summary data without page reload
        } else {
            message.innerText = "Failed to set starting balance.";
            message.style.color = "red";
        }
    } catch (error) {
        console.error("Error setting starting balance:", error);
    }
}
