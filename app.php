<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Route Mapper - Walking Path Finder</title>

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <!-- Leaflet CSS -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />

    <!-- Leaflet Routing Machine CSS -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css"
    />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- Header -->
    <header class="header">
      <div class="header-content">
        <div class="logo">
          <div class="logo-icon">🗺️</div>
          <span>Route Mapper</span>
        </div>
        <div id="roadModeStatus" class="status-badge straight">
          <span class="status-dot"></span> Straight Lines
        </div>
      </div>
    </header>

    <!-- Main Container -->
    <div class="main-container">
      <!-- Sidebar -->
      <aside class="sidebar">
        <!-- Route Card -->
        <div class="card">
          <div class="card-header">
            <span class="card-title"> 📍 Navigation </span>
          </div>
          <div class="card-body">
            <!-- Tabs -->
            <div class="tabs">
              <button class="tab-btn active" data-tab="routeFinderTab">
                Route Finder
              </button>
              <button class="tab-btn" data-tab="customPathsTab">
                Custom Paths
              </button>
            </div>

            <!-- Route Finder Tab -->
            <div class="tab-content active" id="routeFinderTab">
              <!-- Follow Roads Toggle -->
              <div class="toggle-container">
                <div class="toggle-info">
                  <span class="toggle-label">Follow Roads</span>
                  <span class="toggle-desc"
                    >Route along actual walking paths</span
                  >
                </div>
                <label class="toggle-switch">
                  <input type="checkbox" id="followRoadsToggle" />
                  <span class="toggle-slider"></span>
                </label>
              </div>

              <!-- Start Point -->
              <div class="coord-section">
                <div class="coord-header">
                  <div class="coord-marker start"></div>
                  <span class="coord-label">Start Point</span>
                </div>
                <div class="input-row">
                  <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input
                      type="text"
                      class="form-input"
                      id="startLat"
                      placeholder="27.7172"
                    />
                  </div>
                  <div class="form-group">
                    <label class="form-label">Longitude</label>
                    <input
                      type="text"
                      class="form-input"
                      id="startLng"
                      placeholder="85.3240"
                    />
                  </div>
                </div>
                <div class="quick-actions">
                  <button
                    class="quick-btn"
                    data-lat="27.7172"
                    data-lng="85.3240"
                    data-target="start"
                  >
                    Kathmandu
                  </button>
                  <button
                    class="quick-btn"
                    data-lat="27.6710"
                    data-lng="85.4298"
                    data-target="start"
                  >
                    Bhaktapur
                  </button>
                </div>
              </div>

              <!-- End Point -->
              <div class="coord-section">
                <div class="coord-header">
                  <div class="coord-marker end"></div>
                  <span class="coord-label">End Point</span>
                </div>
                <div class="input-row">
                  <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input
                      type="text"
                      class="form-input"
                      id="endLat"
                      placeholder="27.7089"
                    />
                  </div>
                  <div class="form-group">
                    <label class="form-label">Longitude</label>
                    <input
                      type="text"
                      class="form-input"
                      id="endLng"
                      placeholder="85.3189"
                    />
                  </div>
                </div>
                <div class="quick-actions">
                  <button
                    class="quick-btn"
                    data-lat="27.7089"
                    data-lng="85.3189"
                    data-target="end"
                  >
                    Durbar Marg
                  </button>
                  <button
                    class="quick-btn"
                    data-lat="27.7149"
                    data-lng="85.3123"
                    data-target="end"
                  >
                    Thamel
                  </button>
                </div>
              </div>

              <!-- Buttons -->
              <div class="btn-group">
                <button class="btn btn-primary btn-block" id="findRouteBtn">
                  🔍 Find Route
                </button>
                <button class="btn btn-secondary" id="clearRouteBtn">
                  🗑️ Clear
                </button>
              </div>

              <!-- Error Message -->
              <div class="error-msg" id="errorMsg"></div>
              <div class="success-msg" id="successMsg"></div>
            </div>

            <!-- Custom Paths Tab -->
            <div class="tab-content" id="customPathsTab">
              <!-- File Upload Area -->
              <div class="file-upload" id="fileUploadArea">
                <input
                  type="file"
                  id="jsonFileInput"
                  accept=".json"
                  style="display: none"
                />
                <div class="file-upload-icon">📁</div>
                <div class="file-upload-text">
                  <strong>Click to upload</strong> or drag and drop<br />
                  JSON file with path coordinates
                </div>
              </div>

              <!-- File Status -->
              <div class="file-status" id="fileStatus"></div>

              <!-- Path Selector (hidden until file loaded) -->
              <div
                id="pathSelectSection"
                style="display: none; margin-top: 16px"
              >
                <div class="form-group">
                  <label class="form-label">Select Path</label>
                  <select class="form-select" id="pathSelector">
                    <option value="">-- Select a path --</option>
                  </select>
                </div>

                <div class="btn-group" style="margin-top: 12px">
                  <button class="btn btn-success btn-block" id="drawPathBtn">
                    🖊️ Draw Path
                  </button>
                  <button class="btn btn-secondary" id="clearPathsBtn">
                    🗑️ Clear
                  </button>
                </div>
              </div>

              <!-- Path Info -->
              <div class="path-info" id="pathInfo">
                <div class="path-info-row">
                  <span class="path-info-label">Path Name</span>
                  <span class="path-info-value" id="pathName">--</span>
                </div>
                <div class="path-info-row">
                  <span class="path-info-label">Points</span>
                  <span class="path-info-value" id="pathPoints">--</span>
                </div>
                <div class="path-info-row">
                  <span class="path-info-label">Distance</span>
                  <span class="path-info-value" id="pathDistance">--</span>
                </div>
              </div>

              <!-- Legend -->
              <div class="legend" id="pathLegend"></div>

              <!-- JSON Format Help -->
              <div class="json-help">
                <div class="json-help-title">💡 JSON Format</div>
                <pre>
{
  "paths": [
    {
      "name": "Path Name",
      "coordinates": [
        {"serial": 1, "lat": 27.71, "lng": 85.32},
        {"serial": 2, "lat": 27.72, "lng": 85.33}
      ]
    }
  ]
}</pre
                >
              </div>
            </div>
          </div>
        </div>
      </aside>

      <!-- Map Container -->
      <main class="map-container">
        <div class="map-header">
          <span class="map-title">
            <img
              src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b0/Openstreetmap_logo.svg/256px-Openstreetmap_logo.svg.png"
              alt="OSM"
            />
            OpenStreetMap
          </span>
        </div>
        <div id="map"></div>
        <div class="map-footer">
          <div class="route-stats">
            <div class="stat-item">
              <span class="stat-label">Distance</span>
              <span class="stat-value" id="routeDistance">--</span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Est. Walking Time</span>
              <span class="stat-value" id="routeTime">--</span>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Leaflet Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

    <!-- Custom JS -->
    <script src="js/app.js"></script>
  </body>
</html>
