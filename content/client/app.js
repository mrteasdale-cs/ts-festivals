// NOT MY CODE - I USED PAYPALS CODE INTEGRATOR IN ORDER TO SIMULATE PAYPAL PAYMENTS
function initPayPalButton(currentCost) {

    // Get total cost of trip to pass to paypal
    var tr = document.getElementById("total_cost_cell");
    var td = tr.getElementsByTagName("td");
    var total = parseFloat(td[0].innerText);

    paypal.Buttons({
        style: {
            shape: 'pill',
            color: 'gold',
            layout: 'vertical',
            label: 'paypal',
        },

        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{"amount":{"currency_code":"USD","value":total}}]
            });
        },

        onApprove: function(data, actions) {
            return actions.order.capture().then(function(orderData) {

                // Full available details
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));

                // Show a success message within this page, for example:
                const element = document.getElementById('paypal-button-container');

                // Success message
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../email_confirmation.php?event='TEST'");
                element.innerHTML = '<h3>Thank you for your payment! Please check your email.</h3>';


            });
        },

        onError: function(err) {
            console.log(err);
        }
    }).render('#paypal-button-container');
}
initPayPalButton();