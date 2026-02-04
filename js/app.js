/**
 * Sawari - Public Transport Navigator
 * Main Application JavaScript
 * Google Maps-like interface with fullscreen map
 */

// =============================================================================
// Global Variables & State
// =============================================================================
let map = null;
let routingControl = null;
let startMarker = null;
let endMarker = null;
let userLocationMarker = null;
let tempDestinationMarker = null;

// Location state
const state = {
    start: { lat: null, lng: null, name: '' },
    end: { lat: null, lng: null, name: '' },
    isSelectingDestination: false,
    userLocation: null,
    currentRoute: null
};

// Nepal popular locations for suggestions
const popularLocations = [
    { name: 'Ratnapark', address: 'Kathmandu', lat: 27.7030, lng: 85.3147 },
    { name: 'Thamel', address: 'Kathmandu', lat: 27.7154, lng: 85.3123 },
    { name: 'Durbar Marg', address: 'Kathmandu', lat: 27.7136, lng: 85.3206 },
    { name: 'Kalanki', address: 'Kathmandu', lat: 27.6936, lng: 85.2815 },
    { name: 'Koteshwor', address: 'Kathmandu', lat: 27.6781, lng: 85.3491 },
    { name: 'Chabahil', address: 'Kathmandu', lat: 27.7196, lng: 85.3436 },
    { name: 'Balaju', address: 'Kathmandu', lat: 27.7335, lng: 85.3024 },
    { name: 'Lagankhel', address: 'Lalitpur', lat: 27.6680, lng: 85.3227 },
    { name: 'Bhaktapur Durbar Square', address: 'Bhaktapur', lat: 27.6722, lng: 85.4279 },
    { name: 'Boudhanath Stupa', address: 'Kathmandu', lat: 27.7215, lng: 85.3620 },
    { name: 'Swayambhunath', address: 'Kathmandu', lat: 27.7149, lng: 85.2903 },
    { name: 'Patan Durbar Square', address: 'Lalitpur', lat: 27.6727, lng: 85.3250 },
    { name: 'New Bus Park', address: 'Gongabu, Kathmandu', lat: 27.7358, lng: 85.3157 },
    { name: 'Tribhuvan International Airport', address: 'Kathmandu', lat: 27.6966, lng: 85.3591 },
    { name: 'Pulchowk', address: 'Lalitpur', lat: 27.6803, lng: 85.3179 },
    { name: 'Maharajgunj', address: 'Kathmandu', lat: 27.7394, lng: 85.3331 },
    { name: 'Baneshwor', address: 'Kathmandu', lat: 27.6915, lng: 85.3420 },
    { name: 'Sundhara', address: 'Kathmandu', lat: 27.7012, lng: 85.3123 },
    { name: 'Asan', address: 'Kathmandu', lat: 27.7063, lng: 85.3100 },
    { name: 'Basantapur', address: 'Kathmandu', lat: 27.7041, lng: 85.3066 }
];

// =============================================================================
// DOM Elements
// =============================================================================
const elements = {
    map: null,
    startInput: null,
    destInput: null,
    useLocationBtn: null,
    selectOnMapBtn: null,
    swapBtn: null,
    findRouteBtn: null,
    clearBtn: null,
    suggestionsContainer: null,
    suggestionsList: null,
    routePanel: null,
    closeRoutePanel: null,
    routeTime: null,
    routeDistance: null,
    routeDetails: null,
    locateBtn: null,
    zoomInBtn: null,
    zoomOutBtn: null,
    selectionMode: null,
    cancelSelection: null,
    loadingOverlay: null,
    toastContainer: null
};

// =============================================================================
// Initialization
// =============================================================================
document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Sawari App Initializing...');

    // Cache DOM elements
    cacheElements();

    // Initialize map
    initMap();

    // Setup event listeners
    setupEventListeners();

    // Try to get user's location on load
    getUserLocation(false);

    console.log('✅ Sawari App Ready');
});

function cacheElements() {
    elements.map = document.getElementById('map');
    elements.startInput = document.getElementById('startInput');
    elements.destInput = document.getElementById('destInput');
    elements.useLocationBtn = document.getElementById('useLocationBtn');
    elements.selectStartOnMapBtn = document.getElementById('selectStartOnMapBtn');
    elements.selectOnMapBtn = document.getElementById('selectOnMapBtn');
    elements.swapBtn = document.getElementById('swapBtn');
    elements.findRouteBtn = document.getElementById('findRouteBtn');
    elements.clearBtn = document.getElementById('clearBtn');
    elements.suggestionsContainer = document.getElementById('suggestionsContainer');
    elements.suggestionsList = document.getElementById('suggestionsList');
    elements.routePanel = document.getElementById('routePanel');
    elements.closeRoutePanel = document.getElementById('closeRoutePanel');
    elements.routeTime = document.getElementById('routeTime');
    elements.routeDistance = document.getElementById('routeDistance');
    elements.routeDetails = document.getElementById('routeDetails');
    elements.locateBtn = document.getElementById('locateBtn');
    elements.zoomInBtn = document.getElementById('zoomInBtn');
    elements.zoomOutBtn = document.getElementById('zoomOutBtn');
    elements.selectionMode = document.getElementById('selectionMode');
    elements.cancelSelection = document.getElementById('cancelSelection');
    elements.confirmSelection = document.getElementById('confirmSelection');
    elements.loadingOverlay = document.getElementById('loadingOverlay');
    elements.toastContainer = document.getElementById('toastContainer');
}

// =============================================================================
// Map Initialization
// =============================================================================
function initMap() {
    // Initialize Leaflet map centered on Kathmandu
    map = L.map('map', {
        center: [27.7172, 85.3240],
        zoom: 14,
        zoomControl: false,
        attributionControl: true
    });

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    // Map click handler for destination selection
    map.on('click', handleMapClick);

    console.log('🗺️ Map initialized');
}

// =============================================================================
// Event Listeners Setup
// =============================================================================
function setupEventListeners() {
    // Start input
    elements.startInput.addEventListener('focus', () => showSuggestions('start'));
    elements.startInput.addEventListener('input', (e) => filterSuggestions(e.target.value, 'start'));
    elements.startInput.addEventListener('blur', () => setTimeout(hideSuggestions, 200));

    // Destination input
    elements.destInput.addEventListener('focus', () => showSuggestions('end'));
    elements.destInput.addEventListener('input', (e) => filterSuggestions(e.target.value, 'end'));
    elements.destInput.addEventListener('blur', () => setTimeout(hideSuggestions, 200));

    // Use my location button
    elements.useLocationBtn.addEventListener('click', () => getUserLocation(true));

    // Select start location on map button
    elements.selectStartOnMapBtn.addEventListener('click', () => enableDestinationSelection('start'));

    // Select destination on map button
    elements.selectOnMapBtn.addEventListener('click', () => enableDestinationSelection('end'));

    // Swap locations button
    elements.swapBtn.addEventListener('click', swapLocations);

    // Find route button
    elements.findRouteBtn.addEventListener('click', findRoute);

    // Clear button
    elements.clearBtn.addEventListener('click', clearAll);

    // Close route panel
    elements.closeRoutePanel.addEventListener('click', closeRoutePanel);

    // Map control buttons
    elements.locateBtn.addEventListener('click', () => getUserLocation(true));
    elements.zoomInBtn.addEventListener('click', () => map.zoomIn());
    elements.zoomOutBtn.addEventListener('click', () => map.zoomOut());

    // Cancel destination selection
    elements.cancelSelection.addEventListener('click', cancelDestinationSelection);

    // Confirm destination selection
    elements.confirmSelection.addEventListener('click', confirmDestinationSelection);

    // Handle suggestion clicks via event delegation
    elements.suggestionsContainer.addEventListener('click', handleSuggestionClick);
    elements.suggestionsContainer.addEventListener('mousedown', (e) => {
        // Prevent blur from hiding suggestions before click registers
        e.preventDefault();
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.search-input-group') && !e.target.closest('.suggestions-container')) {
            hideSuggestions();
        }
    });
}

// =============================================================================
// Geolocation
// =============================================================================
function getUserLocation(setAsStart = false) {
    if (!navigator.geolocation) {
        showToast('Geolocation is not supported by your browser', 'error');
        return;
    }

    showLoading(true, 'Getting your location...');

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const { latitude, longitude } = position.coords;
            state.userLocation = { lat: latitude, lng: longitude };

            // Update or create user location marker
            if (userLocationMarker) {
                userLocationMarker.setLatLng([latitude, longitude]);
            } else {
                userLocationMarker = L.circleMarker([latitude, longitude], {
                    radius: 8,
                    fillColor: '#4285f4',
                    fillOpacity: 1,
                    color: '#ffffff',
                    weight: 3
                }).addTo(map);

                // Add accuracy circle
                L.circle([latitude, longitude], {
                    radius: position.coords.accuracy,
                    fillColor: '#4285f4',
                    fillOpacity: 0.1,
                    color: '#4285f4',
                    weight: 1
                }).addTo(map);
            }

            // Center map on user location
            map.setView([latitude, longitude], 16);

            if (setAsStart) {
                setStartLocation(latitude, longitude, 'Your Location');
            }

            showLoading(false);
            showToast('Location found!', 'success');
        },
        (error) => {
            showLoading(false);
            let message = 'Unable to get your location';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    message = 'Location permission denied. Please enable it in your browser settings.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = 'Location information unavailable';
                    break;
                case error.TIMEOUT:
                    message = 'Location request timed out';
                    break;
            }
            showToast(message, 'error');
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        }
    );
}

// =============================================================================
// Location Management
// =============================================================================
function setStartLocation(lat, lng, name) {
    state.start = { lat, lng, name };
    elements.startInput.value = name;

    // Update or create start marker
    if (startMarker) {
        startMarker.setLatLng([lat, lng]);
    } else {
        startMarker = createMarker(lat, lng, 'start').addTo(map);
    }

    // If we have both points, auto-calculate route
    if (state.end.lat && state.end.lng) {
        findRoute();
    }
}

function setEndLocation(lat, lng, name) {
    state.end = { lat, lng, name };
    elements.destInput.value = name;

    // Update or create end marker
    if (endMarker) {
        endMarker.setLatLng([lat, lng]);
    } else {
        endMarker = createMarker(lat, lng, 'end').addTo(map);
    }

    // Center map on destination
    map.setView([lat, lng], 15);

    // If we have both points, auto-calculate route
    if (state.start.lat && state.start.lng) {
        findRoute();
    }
}

function createMarker(lat, lng, type) {
    const iconHtml = type === 'start'
        ? '<div class="marker-pin start"><i class="fa-solid fa-circle"></i></div>'
        : '<div class="marker-pin end"><i class="fa-solid fa-location-dot"></i></div>';

    const icon = L.divIcon({
        className: 'custom-marker',
        html: iconHtml,
        iconSize: [32, 40],
        iconAnchor: [16, 40]
    });

    return L.marker([lat, lng], { icon, draggable: true })
        .on('dragend', function (e) {
            const { lat, lng } = e.target.getLatLng();
            if (type === 'start') {
                reverseGeocode(lat, lng, 'start');
            } else {
                reverseGeocode(lat, lng, 'end');
            }
        });
}

// =============================================================================
// Destination Selection Mode (Center Pin Approach)
// =============================================================================
let selectionType = 'end'; // 'start' or 'end'

function enableDestinationSelection(type = 'end') {
    selectionType = type;
    state.isSelectingDestination = true;
    elements.selectionMode.classList.add('active');
    document.getElementById('searchPanel')?.classList.add('minimized');

    // Show center pin
    const centerPin = document.getElementById('centerPin');
    if (centerPin) {
        centerPin.classList.add('active');
        // Color the pin based on selection type
        centerPin.classList.toggle('start-selection', type === 'start');
    }

    // Update selection message
    const messageSpan = elements.selectionMode.querySelector('.selection-message span');
    if (messageSpan) {
        messageSpan.textContent = type === 'start'
            ? 'Move the map to position the pin on your starting point'
            : 'Move the map to position the pin on your destination';
    }
}

function cancelDestinationSelection() {
    state.isSelectingDestination = false;
    elements.selectionMode.classList.remove('active');
    document.getElementById('searchPanel')?.classList.remove('minimized');

    // Hide center pin
    const centerPin = document.getElementById('centerPin');
    if (centerPin) {
        centerPin.classList.remove('active', 'start-selection');
    }

    if (tempDestinationMarker) {
        map.removeLayer(tempDestinationMarker);
        tempDestinationMarker = null;
    }
}

function confirmDestinationSelection() {
    if (!state.isSelectingDestination) return;

    // Get the center of the map
    const center = map.getCenter();
    const lat = center.lat;
    const lng = center.lng;

    // Clean up selection mode
    cancelDestinationSelection();

    // Set the location based on selection type
    reverseGeocode(lat, lng, selectionType);

    showToast('Location selected!', 'success');
}

function handleMapClick(e) {
    // Quick select on map click during selection mode
    if (!state.isSelectingDestination) return;

    const { lat, lng } = e.latlng;

    // Center map on clicked location for precise selection
    map.setView([lat, lng], map.getZoom());

    // Clean up selection mode
    cancelDestinationSelection();

    // Set the destination
    reverseGeocode(lat, lng, 'end');
}

// =============================================================================
// Geocoding
// =============================================================================
async function reverseGeocode(lat, lng, type) {
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
        );
        const data = await response.json();

        let name = 'Selected Location';
        if (data.address) {
            name = data.address.road || data.address.neighbourhood || data.address.suburb ||
                data.address.city_district || data.display_name.split(',')[0];
        }

        if (type === 'start') {
            setStartLocation(lat, lng, name);
        } else {
            setEndLocation(lat, lng, name);
        }
    } catch (error) {
        console.error('Reverse geocode error:', error);
        const name = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        if (type === 'start') {
            setStartLocation(lat, lng, name);
        } else {
            setEndLocation(lat, lng, name);
        }
    }
}

// =============================================================================
// Suggestions
// =============================================================================
let currentInputType = null;

function showSuggestions(type) {
    currentInputType = type;
    elements.suggestionsContainer.classList.add('active');
    filterSuggestions('', type);
}

function hideSuggestions() {
    elements.suggestionsContainer.classList.remove('active');
    currentInputType = null;
}

function filterSuggestions(query, type) {
    const filtered = popularLocations.filter(loc =>
        loc.name.toLowerCase().includes(query.toLowerCase()) ||
        loc.address.toLowerCase().includes(query.toLowerCase())
    ).slice(0, 6);

    renderSuggestions(filtered, type);
}

function renderSuggestions(locations, type) {
    elements.suggestionsList.innerHTML = locations.map(loc => `
        <div class="suggestion-item" data-lat="${loc.lat}" data-lng="${loc.lng}" data-name="${loc.name}" data-type="${type}">
            <div class="suggestion-icon">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div class="suggestion-info">
                <div class="suggestion-name">${loc.name}</div>
                <div class="suggestion-address">${loc.address}</div>
            </div>
        </div>
    `).join('');
}

// Handle suggestion clicks via event delegation (more robust)
function handleSuggestionClick(e) {
    const item = e.target.closest('.suggestion-item');
    if (!item) return;

    e.preventDefault();
    e.stopPropagation();

    const lat = parseFloat(item.dataset.lat);
    const lng = parseFloat(item.dataset.lng);
    const name = item.dataset.name;
    const itemType = item.dataset.type;

    console.log('Suggestion clicked:', { lat, lng, name, itemType });

    if (itemType === 'start') {
        setStartLocation(lat, lng, name);
    } else {
        setEndLocation(lat, lng, name);
    }

    hideSuggestions();
}

// =============================================================================
// Swap Locations
// =============================================================================
function swapLocations() {
    // Swap state
    const tempStart = { ...state.start };
    state.start = { ...state.end };
    state.end = { ...tempStart };

    // Swap input values
    const tempValue = elements.startInput.value;
    elements.startInput.value = elements.destInput.value;
    elements.destInput.value = tempValue;

    // Swap markers
    if (startMarker && endMarker) {
        const startPos = startMarker.getLatLng();
        const endPos = endMarker.getLatLng();

        startMarker.setLatLng(endPos);
        endMarker.setLatLng(startPos);
    }

    // Recalculate route if both points exist
    if (state.start.lat && state.end.lat) {
        findRoute();
    }
}

// =============================================================================
// Routing
// =============================================================================
function findRoute() {
    if (!state.start.lat || !state.start.lng) {
        showToast('Please select a starting point', 'error');
        return;
    }

    if (!state.end.lat || !state.end.lng) {
        showToast('Please select a destination', 'error');
        return;
    }

    showLoading(true, 'Finding best route...');

    // Remove existing routing control
    if (routingControl) {
        map.removeControl(routingControl);
    }

    // Create new routing control
    routingControl = L.Routing.control({
        waypoints: [
            L.latLng(state.start.lat, state.start.lng),
            L.latLng(state.end.lat, state.end.lng)
        ],
        router: L.Routing.osrmv1({
            serviceUrl: 'https://router.project-osrm.org/route/v1',
            profile: 'foot'
        }),
        lineOptions: {
            styles: [
                { color: '#4285f4', weight: 6, opacity: 0.8 },
                { color: '#1a73e8', weight: 4, opacity: 1 }
            ]
        },
        show: false,
        addWaypoints: false,
        routeWhileDragging: false,
        fitSelectedRoutes: true,
        showAlternatives: false
    }).addTo(map);

    // Handle route found
    routingControl.on('routesfound', function (e) {
        const route = e.routes[0];
        state.currentRoute = route;

        // Update UI with route info
        updateRouteInfo(route);

        showLoading(false);
        showRoutePanel();
    });

    // Handle routing errors
    routingControl.on('routingerror', function (e) {
        showLoading(false);
        showToast('Could not find a route. Please try different locations.', 'error');
    });
}

function updateRouteInfo(route) {
    // Calculate time (assuming walking speed of 5 km/h)
    const distanceKm = route.summary.totalDistance / 1000;
    const timeMinutes = Math.round((distanceKm / 5) * 60);

    let timeText;
    if (timeMinutes >= 60) {
        const hours = Math.floor(timeMinutes / 60);
        const mins = timeMinutes % 60;
        timeText = `${hours} hr ${mins} min`;
    } else {
        timeText = `${timeMinutes} min`;
    }

    elements.routeTime.textContent = timeText;
    elements.routeDistance.textContent = `${distanceKm.toFixed(1)} km`;

    // Generate route steps
    const steps = generateRouteSteps(route);
    elements.routeDetails.innerHTML = steps;
}

function generateRouteSteps(route) {
    let html = '';

    // Start step
    html += `
        <div class="route-step">
            <div class="route-step-icon start">
                <i class="fa-solid fa-circle"></i>
            </div>
            <div class="route-step-content">
                <div class="route-step-instruction">Start at ${state.start.name}</div>
            </div>
        </div>
    `;

    // Route instructions
    if (route.instructions) {
        route.instructions.forEach((instruction, index) => {
            if (instruction.text && instruction.distance > 10) {
                const icon = getInstructionIcon(instruction.type);
                const distance = instruction.distance >= 1000
                    ? `${(instruction.distance / 1000).toFixed(1)} km`
                    : `${Math.round(instruction.distance)} m`;

                html += `
                    <div class="route-step">
                        <div class="route-step-icon">
                            <i class="fa-solid ${icon}"></i>
                        </div>
                        <div class="route-step-content">
                            <div class="route-step-instruction">${instruction.text}</div>
                            <div class="route-step-distance">${distance}</div>
                        </div>
                    </div>
                `;
            }
        });
    }

    // End step
    html += `
        <div class="route-step">
            <div class="route-step-icon end">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div class="route-step-content">
                <div class="route-step-instruction">Arrive at ${state.end.name}</div>
            </div>
        </div>
    `;

    return html;
}

function getInstructionIcon(type) {
    const icons = {
        'Head': 'fa-arrow-up',
        'Continue': 'fa-arrow-up',
        'Left': 'fa-arrow-left',
        'Right': 'fa-arrow-right',
        'SlightLeft': 'fa-arrow-up-left',
        'SlightRight': 'fa-arrow-up-right',
        'SharpLeft': 'fa-turn-left',
        'SharpRight': 'fa-turn-right',
        'Uturn': 'fa-arrow-rotate-left',
        'Destination': 'fa-flag-checkered'
    };
    return icons[type] || 'fa-arrow-up';
}

function showRoutePanel() {
    elements.routePanel.classList.add('active');
}

function closeRoutePanel() {
    elements.routePanel.classList.remove('active');
}

// =============================================================================
// Clear All
// =============================================================================
function clearAll() {
    // Reset state
    state.start = { lat: null, lng: null, name: '' };
    state.end = { lat: null, lng: null, name: '' };
    state.currentRoute = null;

    // Clear inputs
    elements.startInput.value = '';
    elements.destInput.value = '';

    // Remove markers
    if (startMarker) {
        map.removeLayer(startMarker);
        startMarker = null;
    }
    if (endMarker) {
        map.removeLayer(endMarker);
        endMarker = null;
    }

    // Remove routing control
    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null;
    }

    // Close route panel
    closeRoutePanel();

    showToast('Cleared!', 'success');
}

// =============================================================================
// UI Helpers
// =============================================================================
function showLoading(show, message = 'Loading...') {
    if (show) {
        elements.loadingOverlay.querySelector('.loading-text').textContent = message;
        elements.loadingOverlay.classList.add('active');
    } else {
        elements.loadingOverlay.classList.remove('active');
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;

    elements.toastContainer.appendChild(toast);

    // Remove after animation
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// =============================================================================
// Utility Functions
// =============================================================================
function formatDistance(meters) {
    if (meters >= 1000) {
        return `${(meters / 1000).toFixed(1)} km`;
    }
    return `${Math.round(meters)} m`;
}

function formatDuration(seconds) {
    const minutes = Math.round(seconds / 60);
    if (minutes >= 60) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours} hr ${mins} min`;
    }
    return `${minutes} min`;
}
