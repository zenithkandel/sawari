/**
 * Route Mapper Application
 * JavaScript for handling map, routing, and custom paths
 */

console.log('🚀 App.js loaded');

// Global variables
let map = null;
let routingControl = null;
let customPathLayers = [];
let pathMarkersLayers = [];
let loadedPaths = [];
let currentPathData = null;

// Path colors for multiple paths
const pathColors = [
    '#6366f1', '#10b981', '#f59e0b', '#ef4444',
    '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16'
];

/**
 * Initialize the application when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', function () {
    console.log('✅ DOM Content Loaded');

    try {
        initMap();
        setupTabs();
        setupFileUpload();
        setupEventListeners();
        console.log('✅ All initialization complete');
    } catch (error) {
        console.error('❌ Initialization error:', error);
    }
});

/**
 * Initialize the Leaflet map
 */
function initMap() {
    console.log('🗺️ Initializing map...');

    map = L.map('map').setView([27.7172, 85.3240], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    console.log('✅ Map initialized');
}

/**
 * Setup tab switching functionality
 */
function setupTabs() {
    console.log('📑 Setting up tabs...');

    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const tabId = this.dataset.tab;
            console.log('📑 Tab clicked:', tabId);

            // Update button states
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Update content visibility
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === tabId) {
                    content.classList.add('active');
                }
            });
        });
    });

    console.log('✅ Tabs setup complete');
}

/**
 * Setup file upload functionality
 */
function setupFileUpload() {
    console.log('📁 Setting up file upload...');

    const fileInput = document.getElementById('jsonFileInput');
    const uploadArea = document.getElementById('fileUploadArea');
    const fileStatus = document.getElementById('fileStatus');

    console.log('📁 File input element:', fileInput);
    console.log('📁 Upload area element:', uploadArea);

    if (!fileInput) {
        console.error('❌ File input not found!');
        return;
    }

    if (!uploadArea) {
        console.error('❌ Upload area not found!');
        return;
    }

    // Click on upload area triggers file input
    uploadArea.addEventListener('click', function (e) {
        console.log('📁 Upload area clicked');
        e.stopPropagation();
        fileInput.click();
    });

    // File input change event
    fileInput.addEventListener('change', function (e) {
        console.log('📁 File input changed');
        const file = e.target.files[0];
        console.log('📁 Selected file:', file);

        if (file) {
            handleFileUpload(file);
        }
    });

    // Drag and drop events
    uploadArea.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('dragover');
        console.log('📁 Drag over');
    });

    uploadArea.addEventListener('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
        console.log('📁 Drag leave');
    });

    uploadArea.addEventListener('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
        console.log('📁 File dropped');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileUpload(files[0]);
        }
    });

    console.log('✅ File upload setup complete');
}

/**
 * Handle file upload and parse JSON
 */
function handleFileUpload(file) {
    console.log('📄 Handling file upload:', file.name);

    const fileStatus = document.getElementById('fileStatus');

    // Show loading status
    showFileStatus('loading', 'Loading file...');

    // Check file type
    if (!file.name.endsWith('.json')) {
        showFileStatus('error', 'Please select a JSON file');
        return;
    }

    // Read file
    const reader = new FileReader();

    reader.onload = function (e) {
        console.log('📄 File read complete');
        console.log('📄 File content length:', e.target.result.length);

        try {
            const content = e.target.result;
            console.log('📄 First 200 chars:', content.substring(0, 200));

            const data = JSON.parse(content);
            console.log('📄 Parsed data:', data);

            processPathsData(data);
            showFileStatus('success', `Loaded ${loadedPaths.length} path(s) successfully`);

        } catch (error) {
            console.error('❌ JSON parse error:', error);
            showFileStatus('error', 'Invalid JSON file: ' + error.message);
        }
    };

    reader.onerror = function (error) {
        console.error('❌ File read error:', error);
        showFileStatus('error', 'Error reading file');
    };

    reader.readAsText(file);
}

/**
 * Show file status message
 */
function showFileStatus(type, message) {
    const fileStatus = document.getElementById('fileStatus');
    if (fileStatus) {
        fileStatus.className = 'file-status show ' + type;
        fileStatus.textContent = message;
        console.log('📄 Status:', type, message);
    }
}

/**
 * Process paths data from JSON
 */
function processPathsData(data) {
    console.log('🛣️ Processing paths data');

    // Handle different JSON structures
    if (data.paths && Array.isArray(data.paths)) {
        loadedPaths = data.paths;
    } else if (Array.isArray(data)) {
        loadedPaths = data;
    } else {
        loadedPaths = [data];
    }

    console.log('🛣️ Loaded paths:', loadedPaths.length);

    // Populate path selector
    const pathSelector = document.getElementById('pathSelector');
    if (pathSelector) {
        pathSelector.innerHTML = '<option value="">-- Select a path --</option>';
        pathSelector.innerHTML += '<option value="all">📍 Show All Paths</option>';

        loadedPaths.forEach((path, index) => {
            const name = path.name || `Path ${index + 1}`;
            pathSelector.innerHTML += `<option value="${index}">${name}</option>`;
        });

        console.log('🛣️ Path selector populated');
    }

    // Show path selector section
    const pathSelectSection = document.getElementById('pathSelectSection');
    if (pathSelectSection) {
        pathSelectSection.style.display = 'block';
    }
}

/**
 * Setup all event listeners
 */
function setupEventListeners() {
    console.log('🎧 Setting up event listeners...');

    // Route Finder - Find Route button
    const findRouteBtn = document.getElementById('findRouteBtn');
    if (findRouteBtn) {
        findRouteBtn.addEventListener('click', findRoute);
        console.log('✅ Find route button listener added');
    }

    // Clear Route button
    const clearRouteBtn = document.getElementById('clearRouteBtn');
    if (clearRouteBtn) {
        clearRouteBtn.addEventListener('click', clearRoute);
    }

    // Follow Roads toggle
    const followRoadsToggle = document.getElementById('followRoadsToggle');
    if (followRoadsToggle) {
        followRoadsToggle.addEventListener('change', function () {
            updateRoadModeStatus();
            // Re-draw current route if exists
            if (document.getElementById('startLat').value) {
                findRoute();
            }
        });
    }

    // Path selector change
    const pathSelector = document.getElementById('pathSelector');
    if (pathSelector) {
        pathSelector.addEventListener('change', function () {
            const value = this.value;
            console.log('🛣️ Path selected:', value);

            if (value === 'all') {
                displayAllPaths();
            } else if (value !== '') {
                displaySinglePath(parseInt(value));
            }
        });
    }

    // Draw selected path button
    const drawPathBtn = document.getElementById('drawPathBtn');
    if (drawPathBtn) {
        drawPathBtn.addEventListener('click', function () {
            const pathSelector = document.getElementById('pathSelector');
            const value = pathSelector.value;

            if (value === 'all') {
                displayAllPaths();
            } else if (value !== '') {
                displaySinglePath(parseInt(value));
            }
        });
    }

    // Clear paths button
    const clearPathsBtn = document.getElementById('clearPathsBtn');
    if (clearPathsBtn) {
        clearPathsBtn.addEventListener('click', clearCustomPaths);
    }

    // Quick location buttons
    setupQuickLocations();

    console.log('✅ Event listeners setup complete');
}

/**
 * Setup quick location buttons
 */
function setupQuickLocations() {
    const quickBtns = document.querySelectorAll('.quick-btn');
    quickBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const lat = this.dataset.lat;
            const lng = this.dataset.lng;
            const target = this.dataset.target;

            if (target === 'start') {
                document.getElementById('startLat').value = lat;
                document.getElementById('startLng').value = lng;
            } else {
                document.getElementById('endLat').value = lat;
                document.getElementById('endLng').value = lng;
            }
        });
    });
}

/**
 * Find and display route between two points
 */
function findRoute() {
    console.log('🔍 Finding route...');

    const startLat = parseFloat(document.getElementById('startLat').value);
    const startLng = parseFloat(document.getElementById('startLng').value);
    const endLat = parseFloat(document.getElementById('endLat').value);
    const endLng = parseFloat(document.getElementById('endLng').value);

    // Validate coordinates
    if (isNaN(startLat) || isNaN(startLng) || isNaN(endLat) || isNaN(endLng)) {
        showError('Please enter valid coordinates');
        return;
    }

    const followRoads = document.getElementById('followRoadsToggle').checked;
    console.log('🔍 Follow roads:', followRoads);

    // Clear previous route
    clearRoute();

    if (followRoads) {
        // Use OSRM routing for road-following path
        try {
            routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(startLat, startLng),
                    L.latLng(endLat, endLng)
                ],
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    profile: 'foot'
                }),
                lineOptions: {
                    styles: [{ color: '#6366f1', weight: 5, opacity: 0.8 }]
                },
                createMarker: function (i, waypoint, n) {
                    const icon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background: ${i === 0 ? '#10b981' : '#ef4444'}; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>`,
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });
                    return L.marker(waypoint.latLng, { icon: icon });
                },
                addWaypoints: false,
                draggableWaypoints: false,
                fitSelectedRoutes: true,
                show: false
            }).addTo(map);

            routingControl.on('routesfound', function (e) {
                const route = e.routes[0];
                updateRouteStats(route.summary.totalDistance, route.summary.totalTime);
            });

        } catch (error) {
            console.error('❌ Routing error:', error);
            showError('Error finding route: ' + error.message);
        }

    } else {
        // Draw straight line
        const latlngs = [
            [startLat, startLng],
            [endLat, endLng]
        ];

        const polyline = L.polyline(latlngs, {
            color: '#10b981',
            weight: 4,
            opacity: 0.8,
            dashArray: '10, 10'
        }).addTo(map);

        // Add markers
        addMarker(startLat, startLng, '#10b981', 'Start');
        addMarker(endLat, endLng, '#ef4444', 'End');

        // Calculate distance
        const distance = calculateDistance(startLat, startLng, endLat, endLng);
        updateRouteStats(distance * 1000, (distance / 5) * 3600); // Assume 5 km/h walking

        map.fitBounds(polyline.getBounds(), { padding: [50, 50] });

        // Store for clearing
        customPathLayers.push(polyline);
    }

    updateRoadModeStatus();
}

/**
 * Add a marker to the map
 */
function addMarker(lat, lng, color, label) {
    const icon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="background: ${color}; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="${label}"></div>`,
        iconSize: [16, 16],
        iconAnchor: [8, 8]
    });

    const marker = L.marker([lat, lng], { icon: icon }).addTo(map);
    marker.bindPopup(label);
    customPathLayers.push(marker);

    return marker;
}

/**
 * Add a small numbered marker for path coordinates
 */
function addCoordinateMarker(lat, lng, number, color) {
    const icon = L.divIcon({
        className: 'coord-marker',
        html: `<div style="background: ${color}; color: white; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">${number}</div>`,
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    const marker = L.marker([lat, lng], { icon: icon }).addTo(map);
    marker.bindPopup(`Point ${number}<br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`);

    return marker;
}

/**
 * Clear the current route
 */
function clearRoute() {
    console.log('🧹 Clearing route...');

    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null;
    }

    customPathLayers.forEach(layer => {
        map.removeLayer(layer);
    });
    customPathLayers = [];

    // Clear path markers too
    pathMarkersLayers.forEach(layer => {
        map.removeLayer(layer);
    });
    pathMarkersLayers = [];

    // Reset stats
    document.getElementById('routeDistance').textContent = '--';
    document.getElementById('routeTime').textContent = '--';
}

/**
 * Clear custom paths from JSON
 */
function clearCustomPaths() {
    console.log('🧹 Clearing custom paths...');

    clearRoute();

    document.getElementById('pathInfo').classList.remove('active');
    document.getElementById('pathLegend').classList.remove('active');
}

/**
 * Display all loaded paths
 */
function displayAllPaths() {
    console.log('🛣️ Displaying all paths');

    clearCustomPaths();

    if (loadedPaths.length === 0) {
        showError('No paths loaded');
        return;
    }

    const allBounds = [];

    loadedPaths.forEach((path, index) => {
        const color = pathColors[index % pathColors.length];
        const bounds = drawPath(path, color, index);
        if (bounds) {
            allBounds.push(bounds);
        }
    });

    // Fit map to show all paths
    if (allBounds.length > 0) {
        const combinedBounds = allBounds[0];
        allBounds.forEach(b => combinedBounds.extend(b));
        map.fitBounds(combinedBounds, { padding: [50, 50] });
    }

    // Show legend
    updateLegend();
}

/**
 * Display a single path
 */
function displaySinglePath(index) {
    console.log('🛣️ Displaying path:', index);

    clearCustomPaths();

    if (!loadedPaths[index]) {
        showError('Path not found');
        return;
    }

    const path = loadedPaths[index];
    const color = pathColors[index % pathColors.length];
    const bounds = drawPath(path, color, index);

    if (bounds) {
        map.fitBounds(bounds, { padding: [50, 50] });
    }

    // Show path info
    showPathInfo(path);
}

/**
 * Draw a path on the map with coordinate markers
 */
function drawPath(pathData, color, index) {
    console.log('🖊️ Drawing path:', pathData.name || `Path ${index + 1}`);

    const coordinates = pathData.coordinates || pathData.coords || pathData.points || [];

    if (coordinates.length === 0) {
        console.warn('⚠️ No coordinates in path');
        return null;
    }

    // Extract lat/lng from various formats
    const latlngs = coordinates.map(coord => {
        if (Array.isArray(coord)) {
            return coord;
        } else if (coord.lat !== undefined && coord.lng !== undefined) {
            return [coord.lat, coord.lng];
        } else if (coord.latitude !== undefined && coord.longitude !== undefined) {
            return [coord.latitude, coord.longitude];
        }
        return null;
    }).filter(c => c !== null);

    if (latlngs.length === 0) {
        console.warn('⚠️ Could not parse coordinates');
        return null;
    }

    console.log('🖊️ Drawing', latlngs.length, 'points');

    const followRoads = document.getElementById('followRoadsToggle').checked;

    if (followRoads && latlngs.length >= 2) {
        // Use routing for road-following path
        const waypoints = latlngs.map(ll => L.latLng(ll[0], ll[1]));

        const control = L.Routing.control({
            waypoints: waypoints,
            router: L.Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1',
                profile: 'foot'
            }),
            lineOptions: {
                styles: [{ color: color, weight: 4, opacity: 0.8 }]
            },
            createMarker: function () { return null; }, // We'll add our own markers
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: false,
            show: false
        }).addTo(map);

        customPathLayers.push(control);

        // Add coordinate markers for each point
        coordinates.forEach((coord, i) => {
            let lat, lng;
            if (Array.isArray(coord)) {
                [lat, lng] = coord;
            } else {
                lat = coord.lat || coord.latitude;
                lng = coord.lng || coord.longitude;
            }

            const serial = coord.serial || (i + 1);
            const marker = addCoordinateMarker(lat, lng, serial, color);
            pathMarkersLayers.push(marker);
        });

        return L.latLngBounds(latlngs);

    } else {
        // Draw straight line polyline
        const polyline = L.polyline(latlngs, {
            color: color,
            weight: 4,
            opacity: 0.8
        }).addTo(map);

        customPathLayers.push(polyline);

        // Add coordinate markers for each point
        coordinates.forEach((coord, i) => {
            let lat, lng;
            if (Array.isArray(coord)) {
                [lat, lng] = coord;
            } else {
                lat = coord.lat || coord.latitude;
                lng = coord.lng || coord.longitude;
            }

            const serial = coord.serial || (i + 1);
            const marker = addCoordinateMarker(lat, lng, serial, color);
            pathMarkersLayers.push(marker);
        });

        // Add start and end markers with labels
        if (latlngs.length >= 2) {
            addMarker(latlngs[0][0], latlngs[0][1], '#10b981', 'Start: ' + (pathData.name || 'Path'));
            addMarker(latlngs[latlngs.length - 1][0], latlngs[latlngs.length - 1][1], '#ef4444', 'End: ' + (pathData.name || 'Path'));
        }

        return polyline.getBounds();
    }
}

/**
 * Show path information panel
 */
function showPathInfo(path) {
    const pathInfo = document.getElementById('pathInfo');
    const coordinates = path.coordinates || path.coords || path.points || [];

    document.getElementById('pathName').textContent = path.name || 'Unnamed Path';
    document.getElementById('pathPoints').textContent = coordinates.length;

    // Calculate total distance
    let totalDistance = 0;
    for (let i = 1; i < coordinates.length; i++) {
        const prev = coordinates[i - 1];
        const curr = coordinates[i];

        const lat1 = prev.lat || prev.latitude || prev[0];
        const lng1 = prev.lng || prev.longitude || prev[1];
        const lat2 = curr.lat || curr.latitude || curr[0];
        const lng2 = curr.lng || curr.longitude || curr[1];

        totalDistance += calculateDistance(lat1, lng1, lat2, lng2);
    }

    document.getElementById('pathDistance').textContent = totalDistance.toFixed(2) + ' km';

    pathInfo.classList.add('active');
}

/**
 * Update legend for multiple paths
 */
function updateLegend() {
    const legend = document.getElementById('pathLegend');
    legend.innerHTML = '';

    loadedPaths.forEach((path, index) => {
        const color = pathColors[index % pathColors.length];
        const name = path.name || `Path ${index + 1}`;

        legend.innerHTML += `
            <div class="legend-item">
                <div class="legend-color" style="background: ${color}"></div>
                <span>${name}</span>
            </div>
        `;
    });

    legend.classList.add('active');
}

/**
 * Update road mode status badge
 */
function updateRoadModeStatus() {
    const statusBadge = document.getElementById('roadModeStatus');
    const followRoads = document.getElementById('followRoadsToggle').checked;

    if (followRoads) {
        statusBadge.className = 'status-badge roads';
        statusBadge.innerHTML = '<span class="status-dot"></span> Following Roads';
    } else {
        statusBadge.className = 'status-badge straight';
        statusBadge.innerHTML = '<span class="status-dot"></span> Straight Lines';
    }
}

/**
 * Update route statistics display
 */
function updateRouteStats(distanceMeters, timeSeconds) {
    const distanceKm = (distanceMeters / 1000).toFixed(2);
    const timeMin = Math.round(timeSeconds / 60);

    document.getElementById('routeDistance').textContent = distanceKm + ' km';
    document.getElementById('routeTime').textContent = timeMin + ' min';
}

/**
 * Calculate distance between two points (Haversine formula)
 */
function calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 6371; // Earth's radius in km
    const dLat = toRad(lat2 - lat1);
    const dLng = toRad(lng2 - lng1);
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
        Math.sin(dLng / 2) * Math.sin(dLng / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

/**
 * Convert degrees to radians
 */
function toRad(deg) {
    return deg * (Math.PI / 180);
}

/**
 * Show error message
 */
function showError(message) {
    const errorEl = document.getElementById('errorMsg');
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.style.display = 'block';
        setTimeout(() => {
            errorEl.style.display = 'none';
        }, 5000);
    }
    console.error('❌ Error:', message);
}

/**
 * Show success message
 */
function showSuccess(message) {
    const successEl = document.getElementById('successMsg');
    if (successEl) {
        successEl.textContent = message;
        successEl.style.display = 'block';
        setTimeout(() => {
            successEl.style.display = 'none';
        }, 3000);
    }
    console.log('✅ Success:', message);
}

// Export for global access
window.findRoute = findRoute;
window.clearRoute = clearRoute;
window.clearCustomPaths = clearCustomPaths;
