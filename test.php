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

        /* Custom Path Section Styles */
        .custom-path-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .custom-path-section h2 {
            color: #495057;
            margin-bottom: 20px;
            text-align: center;
        }

        .upload-area {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .file-upload-wrapper {
            flex: 1;
            min-width: 280px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border: 2px dashed #dee2e6;
            text-align: center;
            transition: all 0.3s;
        }

        .file-upload-wrapper:hover {
            border-color: #667eea;
            background: #f0f0ff;
        }

        .file-upload-wrapper input[type="file"] {
            display: none;
        }

        .file-upload-wrapper label {
            cursor: pointer;
            display: block;
            padding: 20px;
            color: #6c757d;
        }

        .file-upload-wrapper label:hover {
            color: #667eea;
        }

        .path-selector-wrapper {
            flex: 1;
            min-width: 280px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
        }

        .path-selector-wrapper h3 {
            color: #495057;
            margin-bottom: 15px;
        }

        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .path-controls {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 10px 20px;
            font-size: 14px;
        }

        .path-info-display {
            margin-top: 15px;
            padding: 15px;
            background: #e9ecef;
            border-radius: 8px;
            display: none;
        }

        .path-info-display.active {
            display: block;
        }

        .path-info-display p {
            margin: 5px 0;
            font-size: 14px;
            color: #495057;
        }

        .json-format-hint {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .json-format-hint h4 {
            color: #856404;
            margin-bottom: 10px;
        }

        .json-format-hint pre {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 12px;
        }

        .path-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #495057;
        }

        .legend-color {
            width: 30px;
            height: 4px;
            border-radius: 2px;
        }

        .tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }

        .tab-btn {
            padding: 10px 25px;
            background: #e9ecef;
            border: none;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            transition: all 0.3s;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .tab-btn:hover:not(.active) {
            background: #dee2e6;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🚶 Walking Route Finder</h1>

        <!-- Tabs -->
        <div class="input-section" style="padding: 15px 25px;">
            <div class="tabs">
                <button class="tab-btn active" onclick="switchTab('route-finder')">📍 Route Finder</button>
                <button class="tab-btn" onclick="switchTab('custom-paths')">🗺️ Custom Paths</button>
            </div>
        </div>

        <!-- Tab 1: Route Finder -->
        <div id="route-finder" class="tab-content active">
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
        </div><!-- End Tab 1 -->

        <!-- Tab 2: Custom Paths -->
        <div id="custom-paths" class="tab-content">
            <div class="custom-path-section">
                <h2>📂 Load Custom Paths from JSON</h2>

                <div class="upload-area">
                    <div class="file-upload-wrapper">
                        <input type="file" id="jsonFileInput" accept=".json" onchange="handleFileUpload(event)">
                        <label for="jsonFileInput">
                            <span style="font-size: 40px;">📁</span><br><br>
                            <strong>Click to upload JSON file</strong><br>
                            <small>or drag and drop</small>
                        </label>
                    </div>

                    <div class="path-selector-wrapper">
                        <h3>🛤️ Select Path to Display</h3>
                        <select id="pathSelector" onchange="displaySelectedPath()">
                            <option value="">-- Upload a JSON file first --</option>
                        </select>

                        <div class="path-controls">
                            <button class="btn btn-sm btn-success" onclick="displaySelectedPath()">Show Path</button>
                            <button class="btn btn-sm btn-primary" onclick="displayAllPaths()">Show All Paths</button>
                            <button class="btn btn-sm btn-danger" onclick="clearCustomPaths()">Clear All</button>
                        </div>

                        <div class="path-info-display" id="pathInfoDisplay">
                            <p><strong>Path:</strong> <span id="pathName">-</span></p>
                            <p><strong>Points:</strong> <span id="pathPoints">-</span></p>
                            <p><strong>Total Distance:</strong> <span id="pathDistance">-</span></p>
                        </div>
                    </div>
                </div>

                <div class="path-legend" id="pathLegend" style="display: none;">
                    <strong>Legend:</strong>
                </div>

                <div class="json-format-hint">
                    <h4>📋 Expected JSON Format:</h4>
                    <pre>{
  "paths": [
    {
      "name": "Path 1",
      "coordinates": [
        { "serial": 1, "lat": 27.7172, "lng": 85.3240 },
        { "serial": 2, "lat": 27.7150, "lng": 85.3200 },
        { "serial": 3, "lat": 27.7130, "lng": 85.3180 }
      ]
    },
    {
      "name": "Path 2",
      "coordinates": [
        { "serial": 1, "lat": 27.7000, "lng": 85.3100 },
        { "serial": 2, "lat": 27.6980, "lng": 85.3080 }
      ]
    }
  ]
}</pre>
                    <button class="btn btn-sm btn-secondary" style="margin-top: 10px;" onclick="loadSampleJSON()">Load
                        Sample Data</button>
                </div>
            </div>
        </div><!-- End Tab 2 -->

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

        // ==========================================
        // CUSTOM PATH FUNCTIONALITY
        // ==========================================

        let customPathsData = null;
        let customPathLayers = [];
        let customMarkers = [];

        // Color palette for different paths
        const pathColors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
            '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9',
            '#F8B500', '#00CED1', '#FF69B4', '#32CD32', '#FF4500'
        ];

        // Tab switching
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabId).classList.add('active');

            // Add active to clicked button
            event.target.classList.add('active');

            // Refresh map sizes
            setTimeout(() => {
                if (osmMap) osmMap.invalidateSize();
            }, 100);
        }

        // Handle JSON file upload
        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                try {
                    const data = JSON.parse(e.target.result);
                    processPathsData(data);
                } catch (error) {
                    alert('Error parsing JSON file: ' + error.message);
                }
            };
            reader.readAsText(file);
        }

        // Process and validate paths data
        function processPathsData(data) {
            if (!data.paths || !Array.isArray(data.paths)) {
                alert('Invalid JSON format. Expected { "paths": [...] }');
                return;
            }

            customPathsData = data;

            // Populate path selector
            const selector = document.getElementById('pathSelector');
            selector.innerHTML = '<option value="">-- Select a path --</option>';
            selector.innerHTML += '<option value="all">📍 Show All Paths</option>';

            data.paths.forEach((path, index) => {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = path.name || `Path ${index + 1}`;
                selector.appendChild(option);
            });

            alert(`Successfully loaded ${data.paths.length} path(s)!`);
        }

        // Display selected path on OSM map
        function displaySelectedPath() {
            const selector = document.getElementById('pathSelector');
            const selectedValue = selector.value;

            if (!selectedValue) {
                alert('Please select a path to display.');
                return;
            }

            if (selectedValue === 'all') {
                displayAllPaths();
                return;
            }

            if (!customPathsData) {
                alert('Please upload a JSON file first.');
                return;
            }

            clearCustomPaths();

            const pathIndex = parseInt(selectedValue);
            const path = customPathsData.paths[pathIndex];

            if (!path || !path.coordinates || path.coordinates.length < 2) {
                alert('Invalid path data or not enough coordinates.');
                return;
            }

            drawPathOnMap(path, pathIndex);
            updatePathInfo(path);
        }

        // Display all paths on the map
        function displayAllPaths() {
            if (!customPathsData || !customPathsData.paths) {
                alert('Please upload a JSON file first.');
                return;
            }

            clearCustomPaths();

            const bounds = L.latLngBounds();

            customPathsData.paths.forEach((path, index) => {
                if (path.coordinates && path.coordinates.length >= 2) {
                    drawPathOnMap(path, index, false);
                    path.coordinates.forEach(coord => {
                        bounds.extend([coord.lat, coord.lng]);
                    });
                }
            });

            // Fit map to show all paths
            if (bounds.isValid()) {
                osmMap.fitBounds(bounds, { padding: [50, 50] });
            }

            // Update legend
            updatePathLegend();

            // Update info display
            document.getElementById('pathInfoDisplay').classList.add('active');
            document.getElementById('pathName').textContent = 'All Paths';
            document.getElementById('pathPoints').textContent = customPathsData.paths.reduce((sum, p) => sum + (p.coordinates ? p.coordinates.length : 0), 0);
            document.getElementById('pathDistance').textContent = 'Multiple paths displayed';
        }

        // Draw a single path on the map
        function drawPathOnMap(path, colorIndex, fitBounds = true) {
            // Sort coordinates by serial number
            const sortedCoords = [...path.coordinates].sort((a, b) => a.serial - b.serial);

            // Create array of LatLng points
            const latLngs = sortedCoords.map(coord => [coord.lat, coord.lng]);

            // Get color for this path
            const color = pathColors[colorIndex % pathColors.length];

            // Create polyline
            const polyline = L.polyline(latLngs, {
                color: color,
                weight: 5,
                opacity: 0.8,
                lineJoin: 'round'
            }).addTo(osmMap);

            // Add popup with path name
            polyline.bindPopup(`<strong>${path.name || 'Path ' + (colorIndex + 1)}</strong><br>Points: ${sortedCoords.length}`);

            customPathLayers.push(polyline);

            // Add markers for start and end points
            const startMarker = L.marker(latLngs[0], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background: #28a745; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold;">S</div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(osmMap);
            startMarker.bindPopup(`<strong>Start:</strong> ${path.name || 'Path ' + (colorIndex + 1)}<br>Lat: ${latLngs[0][0]}, Lng: ${latLngs[0][1]}`);
            customMarkers.push(startMarker);

            const endMarker = L.marker(latLngs[latLngs.length - 1], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background: #dc3545; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold;">E</div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(osmMap);
            endMarker.bindPopup(`<strong>End:</strong> ${path.name || 'Path ' + (colorIndex + 1)}<br>Lat: ${latLngs[latLngs.length - 1][0]}, Lng: ${latLngs[latLngs.length - 1][1]}`);
            customMarkers.push(endMarker);

            // Fit bounds to show the path
            if (fitBounds) {
                osmMap.fitBounds(polyline.getBounds(), { padding: [50, 50] });
            }
        }

        // Update path information display
        function updatePathInfo(path) {
            document.getElementById('pathInfoDisplay').classList.add('active');
            document.getElementById('pathName').textContent = path.name || 'Unnamed Path';
            document.getElementById('pathPoints').textContent = path.coordinates.length;

            // Calculate total distance
            const distance = calculatePathDistance(path.coordinates);
            document.getElementById('pathDistance').textContent = distance.toFixed(2) + ' km';
        }

        // Calculate total distance of a path
        function calculatePathDistance(coordinates) {
            let totalDistance = 0;
            const sortedCoords = [...coordinates].sort((a, b) => a.serial - b.serial);

            for (let i = 0; i < sortedCoords.length - 1; i++) {
                const lat1 = sortedCoords[i].lat;
                const lng1 = sortedCoords[i].lng;
                const lat2 = sortedCoords[i + 1].lat;
                const lng2 = sortedCoords[i + 1].lng;

                totalDistance += haversineDistance(lat1, lng1, lat2, lng2);
            }

            return totalDistance;
        }

        // Haversine formula to calculate distance between two points
        function haversineDistance(lat1, lng1, lat2, lng2) {
            const R = 6371; // Earth's radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        // Update path legend
        function updatePathLegend() {
            const legend = document.getElementById('pathLegend');
            legend.innerHTML = '<strong>Legend:</strong>';

            customPathsData.paths.forEach((path, index) => {
                const color = pathColors[index % pathColors.length];
                const item = document.createElement('div');
                item.className = 'legend-item';
                item.innerHTML = `<span class="legend-color" style="background: ${color}"></span>${path.name || 'Path ' + (index + 1)}`;
                legend.appendChild(item);
            });

            legend.style.display = 'flex';
        }

        // Clear all custom paths from the map
        function clearCustomPaths() {
            // Remove all polylines
            customPathLayers.forEach(layer => {
                osmMap.removeLayer(layer);
            });
            customPathLayers = [];

            // Remove all markers
            customMarkers.forEach(marker => {
                osmMap.removeLayer(marker);
            });
            customMarkers = [];

            // Hide path info
            document.getElementById('pathInfoDisplay').classList.remove('active');
            document.getElementById('pathLegend').style.display = 'none';
        }

        // Load sample JSON data for testing
        function loadSampleJSON() {
            const sampleData = {
                "paths": [
                    {
                        "name": "Kathmandu Walking Trail",
                        "coordinates": [
                            { "serial": 1, "lat": 27.7172, "lng": 85.3240 },
                            { "serial": 2, "lat": 27.7160, "lng": 85.3220 },
                            { "serial": 3, "lat": 27.7145, "lng": 85.3200 },
                            { "serial": 4, "lat": 27.7130, "lng": 85.3185 },
                            { "serial": 5, "lat": 27.7110, "lng": 85.3170 }
                        ]
                    },
                    {
                        "name": "Temple Circuit",
                        "coordinates": [
                            { "serial": 1, "lat": 27.7030, "lng": 85.3145 },
                            { "serial": 2, "lat": 27.7040, "lng": 85.3160 },
                            { "serial": 3, "lat": 27.7055, "lng": 85.3180 },
                            { "serial": 4, "lat": 27.7070, "lng": 85.3195 }
                        ]
                    },
                    {
                        "name": "River Walk",
                        "coordinates": [
                            { "serial": 1, "lat": 27.6950, "lng": 85.3100 },
                            { "serial": 2, "lat": 27.6940, "lng": 85.3120 },
                            { "serial": 3, "lat": 27.6930, "lng": 85.3145 },
                            { "serial": 4, "lat": 27.6920, "lng": 85.3170 },
                            { "serial": 5, "lat": 27.6910, "lng": 85.3190 },
                            { "serial": 6, "lat": 27.6900, "lng": 85.3210 }
                        ]
                    }
                ]
            };

            processPathsData(sampleData);
        }

        // Drag and drop support
        document.addEventListener('DOMContentLoaded', function () {
            const uploadWrapper = document.querySelector('.file-upload-wrapper');

            if (uploadWrapper) {
                uploadWrapper.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    this.style.borderColor = '#667eea';
                    this.style.background = '#f0f0ff';
                });

                uploadWrapper.addEventListener('dragleave', function (e) {
                    e.preventDefault();
                    this.style.borderColor = '#dee2e6';
                    this.style.background = '#f8f9fa';
                });

                uploadWrapper.addEventListener('drop', function (e) {
                    e.preventDefault();
                    this.style.borderColor = '#dee2e6';
                    this.style.background = '#f8f9fa';

                    const file = e.dataTransfer.files[0];
                    if (file && file.name.endsWith('.json')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            try {
                                const data = JSON.parse(e.target.result);
                                processPathsData(data);
                            } catch (error) {
                                alert('Error parsing JSON file: ' + error.message);
                            }
                        };
                        reader.readAsText(file);
                    } else {
                        alert('Please drop a valid JSON file.');
                    }
                });
            }
        });
    </script>
</body>

</html>