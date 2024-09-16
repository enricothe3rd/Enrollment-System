<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Payment</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AeTnJCEfQ0MJolcWHGQSC8kwaMioTs_jWRC1mOJ05nqsy2zJe7ou1LvYQ88-EMm1vIIjImwRKvULNCT-&currency=PHP"></script>
</head>
<body>
    <label for="amount">Enter amount:</label>
    <input type="number" id="amount" placeholder="Amount in PHP" step="0.01" min="0" required>
    <div id="paypal-button-container">Payment</div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                const amount = document.getElementById('amount').value;

                if (!amount || amount <= 0) {
                    alert('Please enter a valid amount.');
                    return;
                }

                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: amount // Custom amount from the input field
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Redirect to receipt page with transaction details
                    window.location.href = `receipt.php?amount=${encodeURIComponent(details.purchase_units[0].amount.value)}&transaction_id=${encodeURIComponent(data.orderID)}`;
                });
            },
            onError: function(err) {
                console.error('PayPal error:', err);
                // Handle errors here
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
