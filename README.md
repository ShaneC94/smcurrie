
# smcurrie.com Source Code

This repository contains the source code for [smcurrie.com](http://smcurrie.com), a personal portfolio and project showcase website. The site is built to demonstrate my skills in web development and backend programming.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Automation](#automation)
- [Future Plans](#future-plans)
- [Project Structure](#project-structure)

## Overview

smcurrie.com is a dynamic and responsive website that includes projects, such as:

1. **Little Italy**: A restaurant reservation and menu system.
2. **Fantasy Football Tracker (FFTracker)**: A tool for fantasy football enthusiasts to manage their teams.
3. **Trip Planner**: A road trip planning application integrating mapping and weather APIs.
4. **To-Do List**: A task management tool with CRUD functionality.

The repository showcases static files and backend scripts while excluding sensitive database credentials for security.

## Features

- **Dynamic Content**: Built with PHP for backend logic and MySQL for data persistence.
- **Responsive Design**: Styled with CSS for consistent cross-device performance.
- **API Integration**: Uses open-source APIs in the Trip Planner and FFTracker apps.
- **Portfolio Showcase**: Highlights diverse web development projects.

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/ShaneC94/smcurrie.git
    ```

2. Install MySQL and configure a database.

3. Create the database:
   - Import database schema and tables using files such as `create_db.txt` located in the project directories (e.g., FFTracker).

4. Configure database credentials in applicable PHP files:
    ```php
    $host = "YOUR_HOST";
    $user = "YOUR_DBUSERNAME";
    $password = "YOUR_DBPASSWORD";
    $dbname = "YOUR_DBNAME";
    $league_id = "YOUR_SLEEPER_LEAGUE_ID";
    ```

5. Upload the files to a PHP-compatible server or run locally using a tool like XAMPP or WAMP.

## Usage

1. Host the site on a PHP-enabled server or set up locally with a tool like XAMPP.
2. Navigate to the homepage, `index.php`, or explore individual projects like:
   - `/LittleItaly/`
   - `/FFTracker/`
   - `/TripPlanner/`
   - `/ToDoList/`
3. Add API keys where required, as well as the Sleeper Fantasy Football league ID for the FFTracker to gain proper functionality.

## Automation

The Fantasy Football Tracker (FFTracker) includes an automated update feature to ensure the data remains current. This is achieved using the following cron job:

```cron
*/30 * * * *
```

This cron job runs the `update_sleeper_data.php` script every 30 minutes to fetch the latest data.

### Setting Up the Cron Job

1. Ensure you have access to a server that supports cron jobs (e.g., Linux-based servers).
2. Add the cron job to your crontab by running:
   ```sh
   crontab -e
   ```
3. Include the line:
   ```cron
   */30 * * * * php /path/to/FFTracker/update_sleeper_data.php
   ```
   Replace `/path/to/FFTracker/` with the full path to the `update_sleeper_data.php` file on your server.
4. Save and exit.

The server will now automatically execute the script every 30 minutes.

## Future Plans

- **CI/CD Pipeline**: Implement automated build and deployment for static pages.
- **Enhanced Security**: Introduce environment variables for sensitive credentials.
- **Project Expansion**: Add new features and refine existing functionalities.

## Project Structure

```
smcurrie/
├── FFTracker/
├── LittleItaly/
├── ToDoList/
├── TripPlanner/
├── index.php
├── styles.css
└── README.md
```

- **FFTracker/**: Fantasy football management tool.
- **LittleItaly/**: Restaurant website components.
- **ToDoList/**: Simple task management application.
- **TripPlanner/**: Road trip planning app with API integrations.
- **index.php**: Website landing page.
- **styles.css**: Shared styles for static pages.
- **README.md**: Project documentation.
