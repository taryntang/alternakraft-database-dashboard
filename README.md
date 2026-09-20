# Alternakraft Database Dashboard

A PHP and MySQL web application for managing household data, appliance records, and analytical reports for the Alternakraft project.

## Overview

This project is a PHP and MySQL web app for managing household data, appliance records, and reporting dashboards. It supports household entry, manufacturer/model search, and analytics for energy-related trends and appliance summaries.

## Features

- household entry and validation
- appliance management
- manufacturer and model search
- dashboard-style reporting and drill-down views
- heating/cooling and water heater analysis
- off-grid and radius-based summaries

## ERD and Database Design

The project uses a relational MySQL schema built around entities such as households, appliances, manufacturers, utility types, postal codes, and power generation systems. Relationships are modeled with primary and foreign keys to reflect real-world connections and support reporting queries.

This demonstrates practical understanding of ERD design, normalization, and SQL-based analytics.

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
   - `demoData/alternakraft_schema.sql`
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
