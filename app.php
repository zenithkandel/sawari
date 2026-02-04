<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Sawari - Navigate Nepal</title>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml"
    href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚌</text></svg>">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- FontAwesome Pro Icons -->
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/fontawesome.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/solid.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/duotone.css">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

  <!-- Leaflet Routing Machine CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/app.css">
</head>

<body>
  <!-- Full Screen Map -->
  <div id="map"></div>

  <!-- Search Panel -->
  <div class="search-panel" id="searchPanel">
    <!-- Panel Header -->
    <div class="panel-header">
      <a href="index.html" class="back-btn" title="Back to Home">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <div class="brand">
        <span class="brand-name">Sawari</span>
      </div>
      <button class="menu-btn" id="menuBtn" title="Menu">
        <i class="fa-solid fa-ellipsis-vertical"></i>
      </button>
    </div>

    <!-- Search Form -->
    <div class="search-form">
      <!-- Starting Point -->
      <div class="search-input-group">
        <div class="input-marker start"></div>
        <div class="input-wrapper">
          <input type="text" id="startInput" class="search-input" placeholder="Choose starting point"
            autocomplete="off">
          <button class="input-action" id="useLocationBtn" title="Use my location">
            <i class="fa-solid fa-location-crosshairs"></i>
          </button>
        </div>
      </div>

      <!-- Connector Line -->
      <div class="input-connector">
        <div class="connector-line"></div>
      </div>

      <!-- Destination Point -->
      <div class="search-input-group">
        <div class="input-marker end"></div>
        <div class="input-wrapper">
          <input type="text" id="destInput" class="search-input" placeholder="Choose destination" autocomplete="off">
          <button class="input-action" id="selectOnMapBtn" title="Select on map">
            <i class="fa-solid fa-map-pin"></i>
          </button>
        </div>
      </div>

      <!-- Swap Button -->
      <button class="swap-btn" id="swapBtn" title="Swap locations">
        <i class="fa-solid fa-arrow-right-arrow-left fa-rotate-90"></i>
      </button>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <button class="quick-btn" id="findRouteBtn">
        <i class="fa-duotone fa-route"></i>
        <span>Find Route</span>
      </button>
      <button class="quick-btn secondary" id="clearBtn">
        <i class="fa-solid fa-xmark"></i>
        <span>Clear</span>
      </button>
    </div>

    <!-- Search Suggestions -->
    <div class="suggestions-container" id="suggestionsContainer">
      <div class="suggestions-list" id="suggestionsList"></div>
    </div>
  </div>

  <!-- Route Info Panel (shows after route is found) -->
  <div class="route-panel" id="routePanel">
    <div class="route-panel-header">
      <button class="close-panel-btn" id="closeRoutePanel">
        <i class="fa-solid fa-xmark"></i>
      </button>
      <div class="route-summary">
        <div class="route-time" id="routeTime">--</div>
        <div class="route-distance" id="routeDistance">-- km</div>
      </div>
    </div>
    <div class="route-details" id="routeDetails">
      <!-- Route steps will be populated here -->
    </div>
  </div>

  <!-- Map Controls -->
  <div class="map-controls">
    <button class="map-control-btn" id="locateBtn" title="My Location">
      <i class="fa-solid fa-location-crosshairs"></i>
    </button>
    <button class="map-control-btn" id="zoomInBtn" title="Zoom In">
      <i class="fa-solid fa-plus"></i>
    </button>
    <button class="map-control-btn" id="zoomOutBtn" title="Zoom Out">
      <i class="fa-solid fa-minus"></i>
    </button>
  </div>

  <!-- Destination Selection Mode Overlay -->
  <div class="selection-mode" id="selectionMode">
    <div class="selection-message">
      <i class="fa-solid fa-map-pin"></i>
      <span>Tap on the map to select destination</span>
    </div>
    <button class="cancel-selection" id="cancelSelection">Cancel</button>
  </div>

  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
      <i class="fa-duotone fa-spinner-third fa-spin"></i>
    </div>
    <span class="loading-text">Finding best route...</span>
  </div>

  <!-- Toast Notifications -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- Mobile Bottom Sheet (for route details on mobile) -->
  <div class="bottom-sheet" id="bottomSheet">
    <div class="bottom-sheet-handle"></div>
    <div class="bottom-sheet-content" id="bottomSheetContent">
      <!-- Content will be populated dynamically -->
    </div>
  </div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <!-- Leaflet Routing Machine JS -->
  <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

  <!-- Custom JS -->
  <script src="js/app.js"></script>
</body>

</html>