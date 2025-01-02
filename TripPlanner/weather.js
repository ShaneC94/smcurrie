// Function to fetch and display weather at start and end locations
function displayWeatherAtLocations(startCoord, endCoord, duration) {
    const weatherInfo = document.getElementById('weather-info');
    weatherInfo.innerHTML = ''; // Clear previous weather information

    // Get the address entered by the user to display it correctly
    const startAddress = document.getElementById("start").value.trim();
    const endAddress = document.getElementById("end").value.trim();

    // Validate input addresses and coordinates
    if (!startAddress || !endAddress || !startCoord || !endCoord) {
        weatherInfo.innerHTML = `<p>Error: Invalid input. Please enter valid start and end locations.</p>`;
        console.error("Invalid coordinates or addresses.");
        return;
    }

    // Display current weather at start location
    getWeatherAtPoint(startCoord[1], startCoord[0], startAddress, 0, true);

    // Display forecasted weather at end location based on estimated travel time
    const forecastedTimeOffset = Math.round(duration / 3600); // Convert travel time from seconds to hours
    getWeatherAtPoint(endCoord[1], endCoord[0], endAddress, forecastedTimeOffset, false);

    // Fetch and store weather for start and end locations in the database
    const user_id = getUserId();
    // if (user_id && user_id !== "null") {
    //     console.log("Storing weather data with the following parameters:");
    //     console.log("User ID:", user_id);
    //     console.log("Start Coordinates:", startCoord);
    //     console.log("End Coordinates:", endCoord);
    //     console.log("Duration (hours):", forecastedTimeOffset);
    //     fetchAndStoreWeather(user_id, startCoord, endCoord, duration);
    // } else {
    //     console.warn("User ID not available or invalid. Weather data will not be stored.");
    // }
}

// Fetch weather data for a specific latitude and longitude
async function getWeatherAtPoint(lat, lon, displayAddress, offsetHours = 0, isStartLocation = false) {
    const weatherUrl = `https://api.weatherapi.com/v1/forecast.json?key=
                        &q=${lat},${lon}&hours=48`; // Enter your weather_api key after key

    try {
        const response = await fetch(weatherUrl);
        if (!response.ok) throw new Error(`Weather API responded with status ${response.status}`);
        
        const data = await response.json();

        if (!data || !data.current || !data.forecast || !data.forecast.forecastday[0]) {
            throw new Error("Incomplete weather data received from API.");
        }

        const weatherInfo = document.getElementById('weather-info');
        const current = data.current;
        const labelDisplay = `Weather at ${displayAddress}`;

        // Display current weather for the start location
        if (isStartLocation) {
            weatherInfo.innerHTML += `
                <h3>${labelDisplay}</h3>
                <p><strong>Current Temperature:</strong> ${current.temp_c} &#8451;</p>
                <p><strong>Current Condition:</strong> ${current.condition.text}</p>
                <img src="https:${current.condition.icon}" alt="${current.condition.text}" />
            `;
        } else {
            const targetTime = new Date();
            targetTime.setHours(targetTime.getHours() + offsetHours);

            const closestForecastHour = findClosestForecastHour(data.forecast.forecastday, targetTime);

            if (!closestForecastHour) {
                throw new Error(`No forecast available for approximately ${offsetHours} hours later at this location.`);
            }

            // Display forecasted weather
            weatherInfo.innerHTML += `
                <h3>${labelDisplay}</h3>
                <p><strong>Current Temperature:</strong> ${current.temp_c} &#8451;</p>
                <p><strong>Current Condition:</strong> ${current.condition.text}</p>
                <img src="https:${current.condition.icon}" alt="${current.condition.text}" />
                
                <p><strong>Forecasted Temperature (${offsetHours} hours later):</strong> ${closestForecastHour.temp_c} &#8451;</p>
                <p><strong>Forecasted Condition (${offsetHours} hours later):</strong> ${closestForecastHour.condition.text}</p>
                <img src="https:${closestForecastHour.condition.icon}" alt="${closestForecastHour.condition.text}" />
            `;
        }
    } catch (error) {
        // console.error('Error fetching weather data:', error.message);
        document.getElementById('weather-info').innerHTML += `<p>Error fetching weather data for ${displayAddress}: ${error.message}</p>`;
    }
}

// Helper function to find the closest forecast hour to the target time
function findClosestForecastHour(forecastDays, targetTime) {
    let closestHour = null;
    let smallestDifference = Infinity;

    forecastDays.forEach(day => {
        day.hour.forEach(hourData => {
            const forecastTime = new Date(hourData.time);
            const timeDifference = Math.abs(forecastTime - targetTime);

            if (timeDifference < smallestDifference) {
                smallestDifference = timeDifference;
                closestHour = hourData;
            }
        });
    });

    return closestHour;
}

// Fetch and store weather data in the database
async function fetchAndStoreWeather(user_id, startCoord, endCoord, duration) {
    const url = `weather_api.php`;
    const payload = {
        user_id: user_id,
        start_lat: startCoord[1],
        start_lon: startCoord[0],
        end_lat: endCoord[1],
        end_lon: endCoord[0],
        duration: Math.round(duration / 3600),
    };

    // console.log("Sending payload to weather_api.php:", payload);

    // try {
    //     const response = await fetch(url, {
    //         method: 'POST',
    //         headers: { 'Content-Type': 'application/json' },
    //         body: JSON.stringify(payload),
    //     });

    //     const data = await response.json();
    //     console.log("Server Response:", data);

    //     if (data.status === "success") {
    //         console.log("Weather data stored successfully:", data.data);
    //     } else {
    //         console.error("Error storing weather data:", data.message);
    //     }
    // } catch (error) {
    //     console.error("Error in fetchAndStoreWeather:", error.message);
    // }
}

// Helper function to retrieve the logged-in user ID
function getUserId() {
    const userIdMeta = document.querySelector('meta[name="user_id"]');
    const userId = userIdMeta ? userIdMeta.getAttribute('content') : null;
    // console.log("Retrieved User ID:", userId);
    return userId;
}
