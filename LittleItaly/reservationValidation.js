document.addEventListener("DOMContentLoaded", function() {
    // Close button event listener
    var closeButton = document.getElementById("closeButton");
    if (closeButton) {
        closeButton.addEventListener("click", function() {
            window.location = "/LittleItaly/index.html";
        });
    }

    // Form submission with Fetch API and validation
    var reservationForm = document.forms["reservationForm"];
    reservationForm.addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        // Collect form data
        const formData = new FormData(this);

        // Perform client-side validation
        const name = formData.get('reservation_name').trim();
        const date = formData.get('reservation_date').trim();
        const time = formData.get('reservation_time').trim();
        const guests = parseInt(formData.get('reservation_guests').trim());
        const contact = formData.get('contact_number').trim();
        const specialRequest = formData.get('special_request').trim();
        const phoneNumberPattern = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;

        if (!name || !date || !time || !guests || !contact) {
            alert("All fields must be filled out before submitting the form.");
            return;
        }

        if (guests > 20) {
            alert("For parties larger than 20 guests, please call the restaurant to make a reservation.");
            return;
        }

        if (!phoneNumberPattern.test(contact)) {
            alert("Please enter a valid contact number. Format: 123-456-7890, 123.456.7890, or (123) 456 7890");
            return;
        }

        // Parse reservation date and time
        const reservationDateTime = new Date(date + 'T' + time);
        reservationDateTime.setMinutes(reservationDateTime.getMinutes() - reservationDateTime.getTimezoneOffset());

        const currentDate = new Date();
        const currentDateString = currentDate.toISOString().split('T')[0];

        // Check if reservation date is in the past or not enough in advance
        if (date < currentDateString || (date === currentDateString && reservationDateTime <= new Date(currentDate.getTime() + 60 * 60 * 1000))) {
            alert("Reservations must be for a future date and at least one hour in advance.");
            return;
        }

        try {
            const response = await fetch('/LittleItaly/reservationDB.php', {
                method: 'POST',
                body: formData,
            });

            const result = await response.json();

            if (response.ok) {
                if (result.error) {
                    // Handle server-side errors (like guest limits, fully booked times)
                    alert(result.error);
                } else {
                    // Show success message with the submitted info in an alert
                    alert(
                        "Reservation submitted successfully!\n\n" +
                        "Reservation Details:\n" +
                        "Name: " + name + "\n" +
                        "Date: " + date + "\n" +
                        "Time: " + time + "\n" +
                        "Guests: " + guests + "\n" +
                        "Contact: " + contact + "\n" +
                        "Special Requests: " + (specialRequest ? specialRequest : "None")
                    );

                    reservationForm.reset();
                    location.reload();
                }
            } else {
                alert("There was an error submitting the reservation. Please try again later.");
            }
        } catch (error) {
            console.error("Error submitting the reservation form:", error);
            alert("There was an error submitting the reservation form. Please try again later.");
        }
    });
});
