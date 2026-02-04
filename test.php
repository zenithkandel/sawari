<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Mapper</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--gray-100);
            min-height: 100vh;
            color: var(--gray-800);
        }

        /* Header */
        .header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 16px 24px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            max-width: 1600px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        /* Main Layout */
        .main-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 24px;
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 24px;
            min-height: calc(100vh - 73px);
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body {
            padding: 20px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            background: var(--gray-100);
            padding: 4px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .tab-btn {
            flex: 1;
            padding: 10px 16px;
            border: none;
            background: transparent;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-500);
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .tab-btn.active {
            background: white;
            color: var(--gray-900);
            box-shadow: var(--shadow);
        }

        .tab-btn:hover:not(.active) {
            color: var(--gray-700);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-600);
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
            background: var(--gray-50);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder {
            color: var(--gray-400);
        }

        .input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* Coordinate Groups */
        .coord-section {
            background: var(--gray-50);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 16px;
            border: 1px solid var(--gray-100);
        }

        .coord-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .coord-marker {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .coord-marker.start {
            background: var(--success);
        }

        .coord-marker.end {
            background: var(--danger);
        }

        .coord-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-secondary:hover {
            background: var(--gray-200);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-block {
            width: 100%;
        }

        .btn-sm {
            padding: 8px 14px;
            font-size: 13px;
        }

        .btn-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Toggle Switch */
        .toggle-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            background: var(--gray-50);
            border-radius: 10px;
            margin-bottom: 16px;
            border: 1px solid var(--gray-100);
        }

        .toggle-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .toggle-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
        }

        .toggle-desc {
            font-size: 11px;
            color: var(--gray-500);
        }

        .toggle-switch {
            position: relative;
            width: 48px;
            height: 26px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--gray-300);
            transition: 0.3s;
            border-radius: 26px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch input:checked+.toggle-slider {
            background-color: var(--primary);
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(22px);
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .quick-btn {
            padding: 6px 12px;
            font-size: 12px;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.2s;
        }

        .quick-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* File Upload */
        .file-upload {
            border: 2px dashed var(--gray-200);
            border-radius: 10px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--gray-50);
        }

        .file-upload:hover {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }

        .file-upload input {
            display: none;
        }

        .file-upload-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .file-upload-text {
            font-size: 13px;
            color: var(--gray-600);
        }

        .file-upload-text strong {
            color: var(--primary);
        }

        /* Select */
        .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            background: var(--gray-50);
            cursor: pointer;
            transition: all 0.2s;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Path Info */
        .path-info {
            background: var(--gray-50);
            border-radius: 10px;
            padding: 16px;
            margin-top: 16px;
            display: none;
        }

        .path-info.active {
            display: block;
        }

        .path-info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--gray-100);
            font-size: 13px;
        }

        .path-info-row:last-child {
            border-bottom: none;
        }

        .path-info-label {
            color: var(--gray-500);
        }

        .path-info-value {
            font-weight: 600;
            color: var(--gray-800);
        }

        /* Legend */
        .legend {
            display: none;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 16px;
            padding: 12px;
            background: var(--gray-50);
            border-radius: 8px;
        }

        .legend.active {
            display: flex;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--gray-600);
        }

        .legend-color {
            width: 20px;
            height: 3px;
            border-radius: 2px;
        }

        /* JSON Format Help */
        .json-help {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 10px;
            padding: 16px;
            margin-top: 16px;
        }

        .json-help-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--warning);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .json-help pre {
            background: white;
            padding: 12px;
            border-radius: 8px;
            font-size: 11px;
            overflow-x: auto;
            line-height: 1.5;
        }

        /* Map Container */
        .map-container {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .map-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .map-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .map-title img {
            width: 20px;
            height: 20px;
        }

        #map {
            flex: 1;
            min-height: 600px;
        }

        .map-footer {
            padding: 14px 20px;
            border-top: 1px solid var(--gray-100);
            background: var(--gray-50);
        }

        .route-stats {
            display: flex;
            gap: 32px;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .stat-label {
            font-size: 11px;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-800);
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.roads {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .status-badge.straight {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Error Message */
        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--danger);
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-top: 12px;
            display: none;
        }

        /* Hide Leaflet routing panel */
        .leaflet-routing-container {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                order: 2;
            }

            .map-container {
                order: 1;
            }

            #map {
                min-height: 450px;
            }
        }

        @media (max-width: 640px) {
            .header-content {
                flex-direction: column;
                gap: 12px;
            }

            .main-container {
                padding: 16px;
            }

            .route-stats {
                flex-wrap: wrap;
                gap: 16px;
            }
        }

        /* Loading State */
        .loading {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 20px;
            color: var(--gray-500);
            font-size: 14px;
        }

        .loading.active {
            display: flex;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid var(--gray-200);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <div class="logo-icon">🗺️</div>
                <span>Route Mapper</span>
            </div>
            <div id="modeStatus" class="status-badge straight">
                <span class="status-dot"></span>
                <span>Straight Lines</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Mode Toggle -->
            <div class="card">
                <div class="card-body" style="padding: 16px 20px;">
                    <div class="toggle-container" style="margin-bottom: 0;">
                        <div class="toggle-info">
                            <span class="toggle-label">Follow Roads</span>
                            <span class="toggle-desc">Convert straight lines to actual road paths</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="roadModeToggle" onchange="toggleRoadMode()">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Controls Card -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">📍 Path Controls</span>
                </div>
                <div class="card-body">
                    <!-- Tabs -->
                    <div class="tabs">
                        <button class="tab-btn active" onclick="switchTab('route')">Route Finder</button>
                        <button class="tab-btn" onclick="switchTab('custom')">Custom Paths</button>
                    </div>

                    <!-- Route Finder Tab -->
                    <div id="route-tab" class="tab-content active">
                        <div class="coord-section">
                            <div class="coord-header">
                                <span class="coord-marker start"></span>
                                <span class="coord-label">Start Point</span>
                            </div>
                            <div class="input-row">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" class="form-input" id="lat1" placeholder="27.7172">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" class="form-input" id="lng1" placeholder="85.3240">
                                </div>
                            </div>
                        </div>

                        <div class="coord-section">
                            <div class="coord-header">
                                <span class="coord-marker end"></span>
                                <span class="coord-label">End Point</span>
                            </div>
                            <div class="input-row">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" class="form-input" id="lat2" placeholder="27.7030">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" class="form-input" id="lng2" placeholder="85.3145">
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-block" onclick="calculateRoute()">
                            Show Route
                        </button>

                        <div class="quick-actions">
                            <span style="font-size: 12px; color: var(--gray-500);">Quick load:</span>
                            <button class="quick-btn" onclick="loadSample('kathmandu')">Kathmandu</button>
                            <button class="quick-btn" onclick="loadSample('newyork')">New York</button>
                            <button class="quick-btn" onclick="loadSample('london')">London</button>
                        </div>

                        <div class="error-msg" id="errorMsg"></div>
                    </div>

                    <!-- Custom Paths Tab -->
                    <div id="custom-tab" class="tab-content">
                        <div class="file-upload" id="fileUploadArea"
                            onclick="document.getElementById('jsonFileInput').click()">
                            <input type="file" id="jsonFileInput" accept=".json" style="display: none;">
                            <div class="file-upload-icon">📂</div>
                            <div class="file-upload-text">
                                <strong>Click to upload</strong> or drag & drop<br>
                                <small>JSON file with path data</small>
                            </div>
                        </div>
                        <div id="fileStatus" style="margin-top: 8px; font-size: 12px; color: var(--gray-500);"></div>

                        <div class="form-group" style="margin-top: 16px;">
                            <label class="form-label">Select Path</label>
                            <select class="form-select" id="pathSelector" onchange="onPathSelect()">
                                <option value="">Upload a JSON file first</option>
                            </select>
                        </div>

                        <div class="btn-group">
                            <button class="btn btn-success btn-sm" onclick="displaySelectedPath()">Show</button>
                            <button class="btn btn-primary btn-sm" onclick="displayAllPaths()">Show All</button>
                            <button class="btn btn-danger btn-sm" onclick="clearCustomPaths()">Clear</button>
                        </div>

                        <div class="path-info" id="pathInfoDisplay">
                            <div class="path-info-row">
                                <span class="path-info-label">Path Name</span>
                                <span class="path-info-value" id="pathName">-</span>
                            </div>
                            <div class="path-info-row">
                                <span class="path-info-label">Total Points</span>
                                <span class="path-info-value" id="pathPoints">-</span>
                            </div>
                            <div class="path-info-row">
                                <span class="path-info-label">Distance</span>
                                <span class="path-info-value" id="pathDistance">-</span>
                            </div>
                        </div>

                        <div class="legend" id="pathLegend"></div>

                        <div class="json-help">
                            <div class="json-help-title">📋 JSON Format</div>
                            <pre>{
  "paths": [{
    "name": "Path 1",
    "coordinates": [
      { "serial": 1, "lat": 27.71, "lng": 85.32 },
      { "serial": 2, "lat": 27.70, "lng": 85.31 }
    ]
  }]
}</pre>
                            <button class="btn btn-secondary btn-sm" style="margin-top: 12px;"
                                onclick="loadSampleJSON()">
                                Load Sample Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Map -->
        <div class="map-container">
            <div class="map-header">
                <div class="map-title">
                    <img src="https://www.openstreetmap.org/assets/osm_logo-d4979005d8a03d67bbf051b4e7e6ef1b26c6a34a5cd1b65908e2947c360ca391.svg"
                        alt="OSM">
                    OpenStreetMap
                </div>
            </div>
            <div id="map"></div>
            <div class="map-footer">
                <div class="loading" id="loadingIndicator">
                    <div class="spinner"></div>
                    <span>Calculating route...</span>
                </div>
                <div class="route-stats" id="routeStats">
                    <div class="stat-item">
                        <span class="stat-label">Distance</span>
                        <span class="stat-value" id="statDistance">-</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Duration</span>
                        <span class="stat-value" id="statDuration">-</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Points</span>
                        <span class="stat-value" id="statPoints">-</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <script>
        // Global variables
        let map;
        let routingControl;
        let customPathsData = null;
        let customPathLayers = [];
        let customMarkers = [];
        let straightLineLayer = null;
        let straightLineMarkers = [];
        let useRoadMode = false;

        // Color palette
        const pathColors = [
            '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
            '#ec4899', '#14b8a6', '#f97316', '#06b6d4', '#84cc16'
        ];

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            initMap();
            setupDragDrop();
        });

        // Initialize Map
        function initMap() {
            map = L.map('map').setView([27.7172, 85.3240], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
        }

        // Toggle Road Mode
        function toggleRoadMode() {
            useRoadMode = document.getElementById('roadModeToggle').checked;
            const statusBadge = document.getElementById('modeStatus');

            if (useRoadMode) {
                statusBadge.className = 'status-badge roads';
                statusBadge.innerHTML = '<span class="status-dot"></span><span>Following Roads</span>';
            } else {
                statusBadge.className = 'status-badge straight';
                statusBadge.innerHTML = '<span class="status-dot"></span><span>Straight Lines</span>';
            }

            // Re-render current paths if any
            if (customPathLayers.length > 0 && customPathsData) {
                const selector = document.getElementById('pathSelector');
                if (selector.value === 'all') {
                    displayAllPaths();
                } else if (selector.value) {
                    displaySelectedPath();
                }
            }
        }

        // Tab Switching
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            event.target.classList.add('active');
            document.getElementById(tab + '-tab').classList.add('active');

            setTimeout(() => map.invalidateSize(), 100);
        }

        // Calculate Route
        function calculateRoute() {
            const lat1 = parseFloat(document.getElementById('lat1').value);
            const lng1 = parseFloat(document.getElementById('lng1').value);
            const lat2 = parseFloat(document.getElementById('lat2').value);
            const lng2 = parseFloat(document.getElementById('lng2').value);

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
            clearAll();

            if (useRoadMode) {
                calculateRoadRoute(lat1, lng1, lat2, lng2);
            } else {
                drawStraightLine(lat1, lng1, lat2, lng2);
            }
        }

        // Draw Straight Line
        function drawStraightLine(lat1, lng1, lat2, lng2) {
            const start = [lat1, lng1];
            const end = [lat2, lng2];

            straightLineLayer = L.polyline([start, end], {
                color: '#6366f1',
                weight: 4,
                opacity: 0.8
            }).addTo(map);

            // Add markers
            const startMarker = createMarker(start, 'S', '#10b981');
            const endMarker = createMarker(end, 'E', '#ef4444');
            straightLineMarkers.push(startMarker, endMarker);

            map.fitBounds(straightLineLayer.getBounds(), { padding: [50, 50] });

            // Update stats
            const distance = haversineDistance(lat1, lng1, lat2, lng2);
            const duration = Math.round((distance / 5) * 60); // Assuming 5 km/h walking speed

            updateStats(distance.toFixed(2) + ' km', duration + ' min', '2');
        }

        // Calculate Road Route using OSRM
        function calculateRoadRoute(lat1, lng1, lat2, lng2) {
            showLoading(true);

            if (routingControl) {
                map.removeControl(routingControl);
            }

            routingControl = L.Routing.control({
                waypoints: [L.latLng(lat1, lng1), L.latLng(lat2, lng2)],
                routeWhileDragging: false,
                showAlternatives: false,
                fitSelectedRoutes: true,
                lineOptions: {
                    styles: [{ color: '#6366f1', weight: 5, opacity: 0.8 }]
                },
                createMarker: function (i, waypoint) {
                    const isStart = i === 0;
                    return createMarker(
                        [waypoint.latLng.lat, waypoint.latLng.lng],
                        isStart ? 'S' : 'E',
                        isStart ? '#10b981' : '#ef4444'
                    );
                },
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    profile: 'foot'
                })
            }).addTo(map);

            routingControl.on('routesfound', function (e) {
                showLoading(false);
                const route = e.routes[0];
                const distanceKm = (route.summary.totalDistance / 1000).toFixed(2);
                const durationMin = Math.round(route.summary.totalTime / 60);
                updateStats(distanceKm + ' km', durationMin + ' min', '2');
            });

            routingControl.on('routingerror', function () {
                showLoading(false);
                showError('Could not calculate road route. Try straight line mode.');
            });
        }

        // Create Marker
        function createMarker(latlng, label, color) {
            return L.marker(latlng, {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="
                        background: ${color};
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        border: 3px solid white;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 11px;
                        font-weight: 700;
                    ">${label}</div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                })
            }).addTo(map);
        }

        // Clear All
        function clearAll() {
            if (routingControl) {
                map.removeControl(routingControl);
                routingControl = null;
            }
            if (straightLineLayer) {
                map.removeLayer(straightLineLayer);
                straightLineLayer = null;
            }
            straightLineMarkers.forEach(m => map.removeLayer(m));
            straightLineMarkers = [];
            clearCustomPaths();
        }

        // Sample Coordinates
        function loadSample(city) {
            const samples = {
                kathmandu: { lat1: 27.7172, lng1: 85.3240, lat2: 27.7030, lng2: 85.3145 },
                newyork: { lat1: 40.7580, lng1: -73.9855, lat2: 40.7484, lng2: -73.9857 },
                london: { lat1: 51.5014, lng1: -0.1419, lat2: 51.5007, lng2: -0.1246 }
            };

            const s = samples[city];
            document.getElementById('lat1').value = s.lat1;
            document.getElementById('lng1').value = s.lng1;
            document.getElementById('lat2').value = s.lat2;
            document.getElementById('lng2').value = s.lng2;
        }

        // ==========================================
        // CUSTOM PATHS
        // ==========================================

        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                try {
                    const data = JSON.parse(e.target.result);
                    processPathsData(data);
                } catch (error) {
                    showError('Invalid JSON file: ' + error.message);
                }
            };
            reader.readAsText(file);
        }

        function processPathsData(data) {
            if (!data.paths || !Array.isArray(data.paths)) {
                showError('Invalid format. Expected { "paths": [...] }');
                return;
            }

            customPathsData = data;
            const selector = document.getElementById('pathSelector');
            selector.innerHTML = '<option value="">Select a path</option>';
            selector.innerHTML += '<option value="all">📍 Show All Paths</option>';

            data.paths.forEach((path, i) => {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = path.name || `Path ${i + 1}`;
                selector.appendChild(option);
            });
        }

        function onPathSelect() {
            const value = document.getElementById('pathSelector').value;
            if (value === 'all') {
                displayAllPaths();
            } else if (value) {
                displaySelectedPath();
            }
        }

        function displaySelectedPath() {
            const selector = document.getElementById('pathSelector');
            const value = selector.value;

            if (!value || value === 'all') {
                if (value === 'all') displayAllPaths();
                return;
            }

            if (!customPathsData) {
                showError('Please upload a JSON file first.');
                return;
            }

            clearCustomPaths();
            const path = customPathsData.paths[parseInt(value)];

            if (!path || !path.coordinates || path.coordinates.length < 2) {
                showError('Invalid path data.');
                return;
            }

            drawPath(path, parseInt(value));
            updatePathInfo(path);
        }

        function displayAllPaths() {
            if (!customPathsData) {
                showError('Please upload a JSON file first.');
                return;
            }

            clearCustomPaths();
            const bounds = L.latLngBounds();

            customPathsData.paths.forEach((path, i) => {
                if (path.coordinates && path.coordinates.length >= 2) {
                    drawPath(path, i, false);
                    path.coordinates.forEach(c => bounds.extend([c.lat, c.lng]));
                }
            });

            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }

            updateLegend();
            document.getElementById('pathInfoDisplay').classList.add('active');
            document.getElementById('pathName').textContent = 'All Paths';
            document.getElementById('pathPoints').textContent = customPathsData.paths.reduce((s, p) => s + (p.coordinates?.length || 0), 0);
            document.getElementById('pathDistance').textContent = 'Multiple paths';
        }

        async function drawPath(path, colorIndex, fitBounds = true) {
            const sortedCoords = [...path.coordinates].sort((a, b) => a.serial - b.serial);
            const latLngs = sortedCoords.map(c => [c.lat, c.lng]);
            const color = pathColors[colorIndex % pathColors.length];

            if (useRoadMode && sortedCoords.length >= 2) {
                // Draw road-following path
                await drawRoadPath(sortedCoords, color, path.name || `Path ${colorIndex + 1}`, fitBounds);
            } else {
                // Draw straight line path
                const polyline = L.polyline(latLngs, {
                    color: color,
                    weight: 4,
                    opacity: 0.8
                }).addTo(map);

                polyline.bindPopup(`<strong>${path.name || 'Path ' + (colorIndex + 1)}</strong><br>Points: ${sortedCoords.length}`);
                customPathLayers.push(polyline);

                // Add markers
                customMarkers.push(createMarker(latLngs[0], 'S', '#10b981'));
                customMarkers.push(createMarker(latLngs[latLngs.length - 1], 'E', '#ef4444'));

                if (fitBounds) {
                    map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
                }
            }
        }

        async function drawRoadPath(coords, color, pathName, fitBounds) {
            showLoading(true);

            // Build waypoints string for OSRM
            const waypointsStr = coords.map(c => `${c.lng},${c.lat}`).join(';');
            const url = `https://router.project-osrm.org/route/v1/foot/${waypointsStr}?overview=full&geometries=geojson`;

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.code === 'Ok' && data.routes && data.routes[0]) {
                    const route = data.routes[0];
                    const routeCoords = route.geometry.coordinates.map(c => [c[1], c[0]]);

                    const polyline = L.polyline(routeCoords, {
                        color: color,
                        weight: 4,
                        opacity: 0.8
                    }).addTo(map);

                    const distKm = (route.distance / 1000).toFixed(2);
                    polyline.bindPopup(`<strong>${pathName}</strong><br>Distance: ${distKm} km`);
                    customPathLayers.push(polyline);

                    // Add start/end markers
                    customMarkers.push(createMarker(routeCoords[0], 'S', '#10b981'));
                    customMarkers.push(createMarker(routeCoords[routeCoords.length - 1], 'E', '#ef4444'));

                    if (fitBounds) {
                        map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
                    }
                } else {
                    // Fallback to straight line
                    const latLngs = coords.map(c => [c.lat, c.lng]);
                    const polyline = L.polyline(latLngs, { color: color, weight: 4, opacity: 0.8 }).addTo(map);
                    customPathLayers.push(polyline);
                    customMarkers.push(createMarker(latLngs[0], 'S', '#10b981'));
                    customMarkers.push(createMarker(latLngs[latLngs.length - 1], 'E', '#ef4444'));
                }
            } catch (error) {
                console.error('Road routing failed:', error);
                // Fallback to straight line
                const latLngs = coords.map(c => [c.lat, c.lng]);
                const polyline = L.polyline(latLngs, { color: color, weight: 4, opacity: 0.8 }).addTo(map);
                customPathLayers.push(polyline);
            }

            showLoading(false);
        }

        function updatePathInfo(path) {
            document.getElementById('pathInfoDisplay').classList.add('active');
            document.getElementById('pathName').textContent = path.name || 'Unnamed';
            document.getElementById('pathPoints').textContent = path.coordinates.length;
            document.getElementById('pathDistance').textContent = calculatePathDistance(path.coordinates).toFixed(2) + ' km';
        }

        function updateLegend() {
            const legend = document.getElementById('pathLegend');
            legend.innerHTML = '';

            customPathsData.paths.forEach((path, i) => {
                const item = document.createElement('div');
                item.className = 'legend-item';
                item.innerHTML = `<span class="legend-color" style="background: ${pathColors[i % pathColors.length]}"></span>${path.name || 'Path ' + (i + 1)}`;
                legend.appendChild(item);
            });

            legend.classList.add('active');
        }

        function clearCustomPaths() {
            customPathLayers.forEach(l => map.removeLayer(l));
            customPathLayers = [];
            customMarkers.forEach(m => map.removeLayer(m));
            customMarkers = [];
            document.getElementById('pathInfoDisplay').classList.remove('active');
            document.getElementById('pathLegend').classList.remove('active');
        }

        function loadSampleJSON() {
            processPathsData({
                paths: [
                    {
                        name: "Thamel to Durbar Square",
                        coordinates: [
                            { serial: 1, lat: 27.7152, lng: 85.3123 },
                            { serial: 2, lat: 27.7125, lng: 85.3138 },
                            { serial: 3, lat: 27.7095, lng: 85.3155 },
                            { serial: 4, lat: 27.7045, lng: 85.3070 }
                        ]
                    },
                    {
                        name: "Boudhanath Circuit",
                        coordinates: [
                            { serial: 1, lat: 27.7215, lng: 85.3615 },
                            { serial: 2, lat: 27.7225, lng: 85.3635 },
                            { serial: 3, lat: 27.7210, lng: 85.3645 },
                            { serial: 4, lat: 27.7205, lng: 85.3620 }
                        ]
                    },
                    {
                        name: "Pashupatinath Walk",
                        coordinates: [
                            { serial: 1, lat: 27.7107, lng: 85.3485 },
                            { serial: 2, lat: 27.7095, lng: 85.3500 },
                            { serial: 3, lat: 27.7085, lng: 85.3520 },
                            { serial: 4, lat: 27.7080, lng: 85.3530 }
                        ]
                    }
                ]
            });
        }

        // Drag & Drop
        function setupDragDrop() {
            const area = document.getElementById('fileUploadArea');

            area.addEventListener('dragover', e => {
                e.preventDefault();
                area.style.borderColor = 'var(--primary)';
            });

            area.addEventListener('dragleave', e => {
                e.preventDefault();
                area.style.borderColor = 'var(--gray-200)';
            });

            area.addEventListener('drop', e => {
                e.preventDefault();
                area.style.borderColor = 'var(--gray-200)';

                const file = e.dataTransfer.files[0];
                if (file && file.name.endsWith('.json')) {
                    const reader = new FileReader();
                    reader.onload = ev => {
                        try {
                            processPathsData(JSON.parse(ev.target.result));
                        } catch (err) {
                            showError('Invalid JSON: ' + err.message);
                        }
                    };
                    reader.readAsText(file);
                }
            });
        }

        // Helpers
        function calculatePathDistance(coords) {
            let total = 0;
            const sorted = [...coords].sort((a, b) => a.serial - b.serial);
            for (let i = 0; i < sorted.length - 1; i++) {
                total += haversineDistance(sorted[i].lat, sorted[i].lng, sorted[i + 1].lat, sorted[i + 1].lng);
            }
            return total;
        }

        function haversineDistance(lat1, lng1, lat2, lng2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLng / 2) ** 2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function updateStats(distance, duration, points) {
            document.getElementById('statDistance').textContent = distance;
            document.getElementById('statDuration').textContent = duration;
            document.getElementById('statPoints').textContent = points;
        }

        function showLoading(show) {
            document.getElementById('loadingIndicator').classList.toggle('active', show);
            document.getElementById('routeStats').style.display = show ? 'none' : 'flex';
        }

        function showError(msg) {
            const el = document.getElementById('errorMsg');
            el.textContent = msg;
            el.style.display = 'block';
        }

        function hideError() {
            document.getElementById('errorMsg').style.display = 'none';
        }
    </script>
</body>

</html>