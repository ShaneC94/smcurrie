// Mapbox API key for authentication with Mapbox services
mapboxgl.accessToken = ''; // Enter your Mapbox API

// Initialize the Mapbox map, setting default view to Toronto with a zoom level of 8
var map = new mapboxgl.Map({
    container: 'map', // HTML element ID where the map will be rendered
    style: 'mapbox://styles/mapbox/streets-v11', // Map style using Mapbox's street view
    center: [-79.38, 43.65], // Coordinates for the default map center (Toronto)
    zoom: 8 // Zoom level (8 is a mid-level zoom to see city details)
});

// Add map navigation controls (zoom and rotate) to the map for better user interaction
map.addControl(new mapboxgl.NavigationControl());

// Resize the map when the window is resized to ensure it fits properly
window.addEventListener('resize', function() {
    map.resize();
});

// Event listener for the form submission
document.getElementById("trip-planner").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent default form submission (page reload)

    // Get the start and end location values from the input fields
    const start = document.getElementById("start").value;
    const end = document.getElementById("end").value;

    if (start && end) {
        getRoute(start, end); // Call the function to fetch and display the route
    } else {
        alert("Please enter both start and end locations."); // Prompt the user if fields are empty
    }
});

// Fetch the geographic coordinates for a given location using Mapbox's Geocoding API
function getCoordinates(location) {
    const geocodeUrl = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(location)}.json?access_token=${mapboxgl.accessToken}`;

    // Fetch the coordinates as a promise
    return fetch(geocodeUrl)
        .then(response => response.json())
        .then(data => {
            if (data.features.length > 0) {
                return data.features[0].geometry.coordinates; // Return [lng, lat] coordinates
            } else {
                throw new Error("Location not found."); // Error handling for no results
            }
        });
}

// Fetch the route between the start and end coordinates using Mapbox Directions API
function getRoute(start, end) {
    // Fetch coordinates for both start and end locations
    Promise.all([getCoordinates(start), getCoordinates(end)])
        .then(coords => {
            const startCoords = coords[0];
            const endCoords = coords[1];

            // Fetch the driving route using Mapbox Directions API
            const directionsUrl = `https://api.mapbox.com/directions/v5/mapbox/driving/${startCoords[0]},${startCoords[1]};${endCoords[0]},${endCoords[1]}?geometries=geojson&access_token=${mapboxgl.accessToken}`;

            return fetch(directionsUrl);
        })
        .then(response => response.json())
        .then(data => {
            if (data.routes.length === 0) {
                throw new Error('No routes found');
            }

            const route = data.routes[0].geometry;
            const totalDistance = data.routes[0].distance;
            const duration = data.routes[0].duration;

            // Convert distance to kilometers and calculate estimated duration
            const distanceKm = (totalDistance / 1000).toFixed(2);
            const durationHours = Math.floor(duration / 3600);
            const durationMinutes = Math.floor((duration % 3600) / 60);

            // Display trip details (distance and time)
            document.getElementById('trip-details').innerHTML = `
                <h3>Trip Details</h3>
                <p>Estimated Distance: ${distanceKm} km</p>
                <p>Estimated Time: ${durationHours} hour(s) ${durationMinutes} minute(s)</p>
            `;

            // Create a GeoJSON object for the route to display on the map
            const geojson = {
                'type': 'Feature',
                'properties': {},
                'geometry': route
            };

            if (map.getSource('route')) {
                map.getSource('route').setData(geojson);
            } else {
                map.addSource('route', {
                    'type': 'geojson',
                    'data': geojson
                });

                map.addLayer({
                    'id': 'route',
                    'type': 'line',
                    'source': 'route',
                    'layout': {
                        'line-join': 'round',
                        'line-cap': 'round'
                    },
                    'paint': {
                        'line-color': '#ff7b25',
                        'line-width': 4
                    }
                });
            }

            const bounds = new mapboxgl.LngLatBounds();
            route.coordinates.forEach(coord => {
                bounds.extend(coord);
            });
            map.fitBounds(bounds, { padding: 40 });

            // Fetch and display weather at the start and forecasted weather at the end
            displayWeatherAtLocations(route.coordinates[0], route.coordinates[route.coordinates.length - 1], duration);
        })
        .catch(error => console.error('Error fetching the route:', error));
}

// Function to fetch and display weather at start and end locations
function displayWeatherAtLocations(startCoord, endCoord, duration) {
    const weatherInfo = document.getElementById('weather-info');
    weatherInfo.innerHTML = ''; // Clear previous weather information

    // Display current weather at start location
    getWeatherAtPoint(startCoord[1], startCoord[0], 'start');

    // Display forecasted weather at end location based on estimated travel time
    const forecastedTimeOffset = Math.round(duration / 3600); // Convert travel time from seconds to hours
    getWeatherAtPoint(endCoord[1], endCoord[0], 'end', forecastedTimeOffset);
}

// Fetch weather data for a specific latitude and longitude
async function getWeatherAtPoint(lat, lon, label, offsetHours = 0) {
    const weatherUrl = `https://api.weatherapi.com/v1/forecast.json?key=
                        &q=${lat},${lon}&hours=24`; // Fetch up to 24-hour forecast - Enter key in URL

    try {
        const response = await fetch(weatherUrl);
        const data = await response.json();
        
        console.log('Weather API Response:', data); // Log the response to inspect structure

        const weatherInfo = document.getElementById('weather-info');
        const current = data.current;

        // Check if forecast data is available
        if (!current || !data.forecast || !data.forecast.forecastday[0]) {
            throw new Error("Incomplete weather data received.");
        }

        // Calculate the target forecast time based on offsetHours
        const targetTime = new Date();
        targetTime.setHours(targetTime.getHours() + offsetHours);

        // Find the closest forecast hour to the target time
        const forecastHour = data.forecast.forecastday[0].hour.find(hourData => {
            const forecastTime = new Date(hourData.time);
            return Math.abs(forecastTime - targetTime) < 60 * 60 * 1000; // within 1 hour
        });

        if (!forecastHour) {
            throw new Error(`No forecast available for approximately ${offsetHours} hours later`);
        }

        // Display current weather and forecast in the weather info section
        weatherInfo.innerHTML += `
            <h3>Weather at ${label.toUpperCase()} Location</h3>
            <p>Location: ${data.location.name}, ${data.location.region}</p>
            <p><strong>Current Temperature:</strong> ${current.temp_c} °C</p>
            <p><strong>Current Condition:</strong> ${current.condition.text}</p>
            <img src="https:${current.condition.icon}" alt="${current.condition.text}" />
            
            <p><strong>Forecasted Temperature (${offsetHours} hours later):</strong> ${forecastHour.temp_c} °C</p>
            <p><strong>Forecasted Condition (${offsetHours} hours later):</strong> ${forecastHour.condition.text}</p>
            <img src="https:${forecastHour.condition.icon}" alt="${forecastHour.condition.text}" />
        `;
    } catch (error) {
        console.error('Error fetching weather data:', error);
        const weatherInfo = document.getElementById('weather-info');
        weatherInfo.innerHTML += `<p>Error fetching weather data for ${label} location: ${error.message}</p>`;
    }
}