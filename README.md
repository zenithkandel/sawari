# Sawari

A public transport route planner for navigating bus routes in Nepal.

## Overview

Sawari helps users find the best public transport route between two locations. Enter your starting point and destination, and the app will guide you through:

- Walking to the nearest bus stop
- Which bus to take
- Where to get off
- Walking to your final destination

## Features

- **Route Search** - Find bus routes from Point A to Point B
- **Nearest Bus Stop** - Automatically detect closest bus stops
- **Step-by-Step Directions** - Clear navigation instructions
- **Interactive Map** - Visual route display using OpenStreetMap
- **Admin Data Collection** - GPS-based bus stop mapping system

## Tech Stack

| Component | Technology                 |
| --------- | -------------------------- |
| Frontend  | HTML, CSS, JavaScript      |
| Backend   | PHP                        |
| Database  | MySQL                      |
| Maps      | OpenStreetMap + Leaflet.js |

## Getting Started

1. Clone the repository
2. Set up a local server (XAMPP recommended)
3. Import the database schema
4. Open `index.html` in your browser

## Database Structure

- `bus_stops` - Stop locations with coordinates
- `routes` - Bus route information
- `route_stops` - Mapping of stops to routes

## How Data is Collected

Administrators ride actual bus routes and mark stop locations using GPS. Each stop is tagged as:

- Pickup only
- Drop only
- Both pickup and drop

## Contributing

Contributions are welcome! Feel free to submit issues and pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

Made with love for better public transportation in Nepal
