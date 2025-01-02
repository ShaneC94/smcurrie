document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("orderform");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission

        const formData = new FormData(form);

        fetch("orderDB.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Display success alert with order details
                alert(
                    "Order placed successfully!\n\n" +
                    "Order Name: " + data.orderDetails.orderName + "\n" +
                    "Email Address: " + data.orderDetails.emailAddress + "\n" +
                    "Phone Number: " + data.orderDetails.phoneNumber + "\n" +
                    "Your Order: " + data.orderDetails.selectedFoods + "\n" +
                    "Special Requests: " + (data.orderDetails.specialRequest ? data.orderDetails.specialRequest : "None") + "\n" +
                    "Total Price: $" + data.orderDetails.totalPrice
                );

                // Clear form and refresh page
                form.reset();
                document.getElementById("totalPrice").innerText = ''; // Clear displayed total price
                location.reload(); // Reload the page
            } else {
                alert("Error: " + (data.error || "Something went wrong. Please try again."));
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Something went wrong, please try again.");
        });
    });

    const foodItems = document.querySelectorAll('.foodItem');
    const quantities = document.querySelectorAll('.quantity');

    // Update quantity when checkbox is clicked
    foodItems.forEach(item => {
        item.addEventListener('click', function () {
            const quantityInput = item.parentElement.querySelector('.quantity');
            if (item.checked) {
                if (quantityInput.value <= 0) {
                    quantityInput.value = 1; // Default to 1 if it's not already set
                }
            } else {
                quantityInput.value = 0; // Uncheck means no items ordered
            }
            calculateTotal(); // Recalculate total price
        });
    });

    // Update checkbox and total when quantity is changed
    quantities.forEach(qty => {
        qty.addEventListener('change', function () {
            const checkbox = qty.parentElement.querySelector('.foodItem');
            if (qty.value > 0) {
                checkbox.checked = true; // Check the box when quantity is more than 0
            } else {
                checkbox.checked = false; // Uncheck the box if quantity is 0
            }
            calculateTotal(); // Recalculate total price
        });
    });
});

// Calculates and updates the total price
function calculateTotal() {
    let totalPrice = 0;
    const itemPrices = {
        "Pizza Margherita": 18.99,
        "Pizza Siciliana": 22.99,
        "Pizza Viennese": 22.99,
        "Pizza Capricciosa": 24.99,
    };

    const foodItems = document.querySelectorAll(".foodItem");
    foodItems.forEach(item => {
        const foodName = item.value;
        const quantity = item.parentElement.querySelector('.quantity').value;
        if (item.checked && itemPrices[foodName] && quantity > 0) {
            totalPrice += itemPrices[foodName] * quantity;
        }
    });

    document.getElementById("totalPrice").innerText = `Total Price: $${totalPrice.toFixed(2)}`;
    document.getElementById("totalPriceInput").value = totalPrice.toFixed(2);
}
