// transactions.js - JavaScript for Transactions Page
document.addEventListener('DOMContentLoaded', () => {
    const transactionForm = document.getElementById('transaction-form');
    const transactionList = document.getElementById('transaction-list');
    const userId = sessionStorage.getItem('userId')

    async function fetchTransactions() {
        const response = await fetch(`http://localhost/Personal_Finanace_Tracker/Backend/public/getTransaction.php?user_id=${userId}`);
        const transactions = await response.json();

        transactionList.innerHTML = '';
        transactions.forEach(transaction => {
            const transactionItem = document.createElement('div');
            transactionItem.classList.add('transaction-item');
            transactionItem.innerHTML = `
                <span>${transaction.date} - ${transaction.category}</span>
                <span>${transaction.type === 'income' ? '+' : '-'}$${transaction.amount}</span>
            `;
            transactionList.appendChild(transactionItem);
        });
    }

    transactionForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const amount = document.getElementById('amount').value;
        const category = document.getElementById('category').value;
        const type = document.getElementById('type').value;
        const date = document.getElementById('date').value;

        const response = await fetch(`http://localhost/Personal_Finanace_Tracker/Backend/public/addTransaction.php?user_id=${userId}`, {
            method: 'POST',
            body: JSON.stringify({amount,category,type,date})
        });
        const data = await response.json();
        
        if (response.ok) {
            alert(data.message)
            transactionForm.reset();
            fetchTransactions();
        } else {
            console.log(data)
            alert("Failed to add transaction");
            }
        
    });

    fetchTransactions();
});
