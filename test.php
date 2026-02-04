<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walking Route Finder</title>

    <!-- Leaflet CSS for OpenStreetMap -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .input-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .coordinates-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            align-items: flex-end;
        }

        .coord-group {
            flex: 1;
            min-width: 280px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
        }

        .coord-group h3 {
            color: #495057;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .coord-group h3 .marker {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: inline-block;
        }

        .point-a .marker {
            background: #28a745;
        }

        .point-b .marker {
            background: #dc3545;
        }

        .input-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .input-group {
            flex: 1;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .btn {
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .maps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 20px;
        }

        .map-wrapper {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .map-header {
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .map-header h2 {
            font-size: 18px;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .map-header img {
            width: 24px;
            height: 24px;
        }

        .map {
            height: 500px;
            width: 100%;
        }

        .route-info {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .route-info p {
            margin: 5px 0;
            color: #495057;
            font-size: 14px;
        }

        .route-info strong {
            color: #667eea;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            display: none;
        }

        .sample-coords {
            text-align: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }

        .sample-coords p {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .sample-btn {
            padding: 8px 20px;
            font-size: 13px;
            background: #e9ecef;
            color: #495057;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .sample-btn:hover {
            background: #dee2e6;
        }

        @media (max-width: 768px) {
            .maps-container {
                grid-template-columns: 1fr;
            }

            .map {
                height: 400px;
            }
        }

        /* Hide Leaflet routing machine instructions panel */
        .leaflet-routing-container {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🚶 Walking Route Finder</h1>

        <div class="input-section">
            <div class="coordinates-container">
                <div class="coord-group point-a">
                    <h3><span class="marker"></span> Point A (Start)</h3>
                    <div class="input-row">
                        <div class="input-group">
                            <label for="lat1">Latitude</label>
                            <input type="text" id="lat1" placeholder="e.g., 27.7172">
                        </div>
                        <div class="input-group">
                            <label for="lng1">Longitude</label>
                            <input type="text" id="lng1" placeholder="e.g., 85.3240">
                        </div>
                    </div>
                </div>

                <div class="coord-group point-b">
                    <h3><span class="marker"></span> Point B (Destination)</h3>
                    <div class="input-row">
                        <div class="input-group">
                            <label for="lat2">Latitude</label>
                            <input type="text" id="lat2" placeholder="e.g., 27.7030">
                        </div>
                        <div class="input-group">
                            <label for="lng2">Longitude</label>
                            <input type="text" id="lng2" placeholder="e.g., 85.3145">
                        </div>
                    </div>
                </div>

                <div class="btn-container">
                    <button class="btn btn-primary" onclick="calculateRoute()">
                        🗺️ Show Walking Route
                    </button>
                </div>
            </div>

            <div class="sample-coords">
                <p>Don't have coordinates? Try these sample locations:</p>
                <button class="sample-btn" onclick="loadSampleKathmandu()">Kathmandu Sample</button>
                <button class="sample-btn" onclick="loadSampleNewYork()">New York Sample</button>
                <button class="sample-btn" onclick="loadSampleLondon()">London Sample</button>
            </div>

            <div class="error-message" id="errorMsg"></div>
        </div>

        <div class="maps-container">
            <!-- Google Maps -->
            <div class="map-wrapper">
                <div class="map-header">
                    <h2>
                        <img src="https://www.google.com/images/branding/product/1x/maps_48dp.png" alt="Google Maps">
                        Google Maps
                    </h2>
                </div>
                <div id="googleMap" class="map"></div>
                <div class="route-info" id="googleRouteInfo">
                    <p>Enter coordinates and click "Show Walking Route" to see the path.</p>
                </div>
            </div>

            <!-- OpenStreetMap -->
            <div class="map-wrapper">
                <div class="map-header">
                    <h2>
                        <img src="https://www.openstreetmap.org/assets/osm_logo-d4979005d8a03d67bbf051b4e7e6ef1b26c6a34a5cd1b65908e2947c360ca391.svg"
                            alt="OpenStreetMap">
                        OpenStreetMap
                    </h2>
                </div>
                <div id="osmMap" class="map"></div>
                <div class="route-info" id="osmRouteInfo">
                    <p>Enter coordinates and click "Show Walking Route" to see the path.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS for OpenStreetMap -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <!-- Google Maps API - Replace YOUR_API_KEY with your actual API key -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>

    <script>
        let googleMap, osmMap;
        let googleDirectionsRenderer;
        let osmRoutingControl;

        // Initialize maps on page load
        document.addEventListener('DOMContentLoaded', function () {
            initGoogleMap();
            initOSMMap();
        });

        // Initialize Google Map
        function initGoogleMap() {
            const defaultCenter = { lat: 27.7172, lng: 85.3240 };

            googleMap = new google.maps.Map(document.getElementById('googleMap'), {
                zoom: 13,
                center: defaultCenter,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true
            });

            googleDirectionsRenderer = new google.maps.DirectionsRenderer({
                map: googleMap,
                polylineOptions: {
                    strokeColor: '#4285F4',
                    strokeWeight: 5,
                    strokeOpacity: 0.8
                }
            });
        }

        // Initialize OpenStreetMap with Leaflet
        function initOSMMap() {
            const defaultCenter = [27.7172, 85.3240];

            osmMap = L.map('osmMap').setView(defaultCenter, 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(osmMap);
        }

        // Main function to calculate and display route
        function calculateRoute() {
            const lat1 = parseFloat(document.getElementById('lat1').value);
            const lng1 = parseFloat(document.getElementById('lng1').value);
            const lat2 = parseFloat(document.getElementById('lat2').value);
            const lng2 = parseFloat(document.getElementById('lng2').value);

            // Validate inputs
            if (isNaN(lat1) || isNaN(lng1) || isNaN(lat2) || isNaN(lng2)) {
                showError('Please enter valid coordinates for both points.');
                return;
            }

            if (lat1 < -90 || lat1 > 90 || lat2 < -90 || lat2 > 90) {
                showError('Latitude must be between -90 and 90.');
                return;
            }

            if (lng1 < -180 || lng1 > 180 || lng2 < -180 || lng2 > 180) {
                showError('Longitude must be between -180 and 180.');
                return;
            }

            hideError();

            // Calculate route on both maps
            calculateGoogleRoute(lat1, lng1, lat2, lng2);
            calculateOSMRoute(lat1, lng1, lat2, lng2);
        }

        // Calculate route on Google Maps
        function calculateGoogleRoute(lat1, lng1, lat2, lng2) {
            const directionsService = new google.maps.DirectionsService();

            const request = {
                origin: { lat: lat1, lng: lng1 },
                destination: { lat: lat2, lng: lng2 },
                travelMode: google.maps.TravelMode.WALKING
            };

            directionsService.route(request, function (result, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    googleDirectionsRenderer.setDirections(result);

                    const route = result.routes[0].legs[0];
                    document.getElementById('googleRouteInfo').innerHTML = `
                        <p><strong>Distance:</strong> ${route.distance.text}</p>
                        <p><strong>Duration:</strong> ${route.duration.text}</p>
                        <p><strong>From:</strong> ${route.start_address || `${lat1}, ${lng1}`}</p>
                        <p><strong>To:</strong> ${route.end_address || `${lat2}, ${lng2}`}</p>
                    `;
                } else {
                    document.getElementById('googleRouteInfo').innerHTML = `
                        <p style="color: #dc3545;">Could not calculate route. ${getGoogleErrorMessage(status)}</p>
                        <p>Note: Make sure you have a valid Google Maps API key.</p>
                    `;
                }
            });
        }

        // Calculate route on OpenStreetMap
        function calculateOSMRoute(lat1, lng1, lat2, lng2) {
            // Remove existing routing control if any
            if (osmRoutingControl) {
                osmMap.removeControl(osmRoutingControl);
            }

            // Create custom markers
            const startIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background: #28a745; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                iconSize: [25, 25],
                iconAnchor: [12, 12]
            });

            const endIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background: #dc3545; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                iconSize: [25, 25],
                iconAnchor: [12, 12]
            });

            // Add routing control with OSRM
            osmRoutingControl = L.Routing.control({
                waypoints: [
                    L.latLng(lat1, lng1),
                    L.latLng(lat2, lng2)
                ],
                routeWhileDragging: false,
                showAlternatives: false,
                fitSelectedRoutes: true,
                lineOptions: {
                    styles: [
                        { color: '#FF6B6B', opacity: 0.8, weight: 6 }
                    ]
                },
                createMarker: function (i, waypoint, n) {
                    const icon = i === 0 ? startIcon : endIcon;
                    return L.marker(waypoint.latLng, { icon: icon });
                },
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    profile: 'foot'
                })
            }).addTo(osmMap);

            // Listen for route found event
            osmRoutingControl.on('routesfound', function (e) {
                const routes = e.routes;
                const summary = routes[0].summary;

                const distanceKm = (summary.totalDistance / 1000).toFixed(2);
                const durationMin = Math.round(summary.totalTime / 60);
                const hours = Math.floor(durationMin / 60);
                const mins = durationMin % 60;

                let durationText = '';
                if (hours > 0) {
                    durationText = `${hours} hr ${mins} min`;
                } else {
                    durationText = `${mins} min`;
                }

                document.getElementById('osmRouteInfo').innerHTML = `
                    <p><strong>Distance:</strong> ${distanceKm} km</p>
                    <p><strong>Duration:</strong> ${durationText} (walking)</p>
                    <p><strong>From:</strong> ${lat1.toFixed(6)}, ${lng1.toFixed(6)}</p>
                    <p><strong>To:</strong> ${lat2.toFixed(6)}, ${lng2.toFixed(6)}</p>
                `;
            });

            osmRoutingControl.on('routingerror', function (e) {
                document.getElementById('osmRouteInfo').innerHTML = `
                    <p style="color: #dc3545;">Could not calculate walking route.</p>
                    <p>The routing service may be unavailable or no walking path exists.</p>
                `;
            });
        }

        // Helper function to get Google Maps error message
        function getGoogleErrorMessage(status) {
            const messages = {
                'ZERO_RESULTS': 'No walking route found between these points.',
                'NOT_FOUND': 'One or both locations could not be found.',
                'REQUEST_DENIED': 'API key is invalid or missing.',
                'OVER_QUERY_LIMIT': 'API quota exceeded.',
                'UNKNOWN_ERROR': 'Server error. Please try again.'
            };
            return messages[status] || 'Unknown error occurred.';
        }

        // Show error message
        function showError(message) {
            const errorDiv = document.getElementById('errorMsg');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        // Hide error message
        function hideError() {
            document.getElementById('errorMsg').style.display = 'none';
        }

        // Sample coordinates loaders
        function loadSampleKathmandu() {
            document.getElementById('lat1').value = '27.7172';
            document.getElementById('lng1').value = '85.3240';
            document.getElementById('lat2').value = '27.7030';
            document.getElementById('lng2').value = '85.3145';
        }

        function loadSampleNewYork() {
            document.getElementById('lat1').value = '40.7580';
            document.getElementById('lng1').value = '-73.9855';
            document.getElementById('lat2').value = '40.7484';
            document.getElementById('lng2').value = '-73.9857';
        }

        function loadSampleLondon() {
            document.getElementById('lat1').value = '51.5014';
            document.getElementById('lng1').value = '-0.1419';
            document.getElementById('lat2').value = '51.5007';
            document.getElementById('lng2').value = '-0.1246';
        }
    </script>
</body>

</html>