# Alternakraft Database Dashboard

A PHP and MySQL web application for managing household data, appliance records, and analytical reports for the Alternakraft project.

## Overview

This project is a database-driven web app built for a class project and later refined for a more polished GitHub presentation. It allows users to:

- enter household information
- add and manage appliance records
- view household summaries and dashboard-style reports
- search manufacturer and model data
- review report queries such as popular manufacturers and state-level water heater trends
- explore off-grid household and radius-based analysis

## Features

- household record entry and validation
- appliance add/delete flow
- power generation tracking
- manufacturer and model query search
- report pages for analytics and drill-down views
- heating/cooling and water heater statistics
- off-grid household dashboard and averages-by-radius calculations

## Tech Stack

- PHP
- MySQL
- Apache via MAMP
- HTML / CSS
- SQL data import scripts

## Project Structure

- `app/` – main PHP pages and user-facing application screens
- `lib/` – shared configuration, DB connection, and reusable includes
- `style/` – CSS styling for the interface
- `images/` – branding and logo assets
- `demoData/` – SQL schema and sample data files
- `sql/` – supplemental import scripts and data setup files
- `assets/` – shared classes and supporting app assets
- `docs/` – project documentation and structure notes
- `README.md` – project overview and setup guide

## Local Setup

### Prerequisites

- MAMP or another local Apache + MySQL environment
- PHP 7.x compatible runtime
- MySQL database server

### Database Setup

1. Start Apache and MySQL in MAMP.
2. Open phpMyAdmin or use the MySQL CLI.
3. Create a database named `alternakraft_db`.
4. Import the schema first:
   - `demoData/team052_p3_schema.sql`
5. Then import the seed data in dependency order, such as:
   - `demoData/postalCode.sql`
   - `demoData/household.sql`
   - `demoData/manufacturer.sql`
   - `demoData/Appliance.sql`
   - additional demo data files as needed
6. Confirm the local database credentials in `lib/common.php` match your MAMP environment.

### Run the App

1. Put the project in the MAMP web root.
2. Open the app in a browser at:
   - `http://localhost:8888/app/Main_Menu.php`

## Project Notes

This project was built as a database course assignment and uses direct PHP + MySQL logic instead of a modern framework. The codebase is intentionally simple, readable, and suitable for learning, presentation, and portfolio review.

## Recommended Repository Name

The recommended GitHub repository name is:

- `alternakraft-database-dashboard`

This better communicates that the project is primarily a database application with reporting and dashboard functionality.

## Recommended Next Improvements

- add screenshots to the repository
- create a brief demo walkthrough or video
- refactor repeated SQL into reusable functions or a cleaner service layer
- improve front-end consistency across all pages
- add a stronger project summary for interviews
