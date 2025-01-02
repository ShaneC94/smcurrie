// Mapbox API key for authentication with Mapbox services
mapboxgl.accessToken = ''; // Enter Mapbox key

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
