# Alternakraft Household Energy Dashboard

A PHP and MySQL web application for exploring household energy data, appliance records, and reporting metrics for the Alternakraft project.

## Overview

This project is a multi-page dashboard built for a database course assignment. It allows users to:

- enter household information
- add and manage appliance records
- review power generation details
- search manufacturer and model data
- view summary reports and drill-down analytics

## Features

- Household entry and validation
- Appliance management flow
- Power generator tracking
- Search and reporting pages
- Manufacturer and model summary queries
- Heating/cooling and water heater statistics
- Off-grid and radius-based household analysis

## Tech Stack

- PHP
- MySQL
- Apache via MAMP
- HTML / CSS / basic PHP rendering

## Project Structure

- `Main_Menu.php` – main landing page
- `Enter_household.php` – household form
- `Add_appliance.php` – appliance insertion flow
- `View_appliance.php` – display current household appliances
- `View_Reports.php` – reporting menu
- `top25_man.php` – top manufacturer report
- `demoData/` – SQL schema and sample data files
- `lib/` – shared PHP configuration and page layout
- `style/` – CSS styling

## Local Setup

### Prerequisites

- MAMP or another local Apache + MySQL environment
- PHP 7.x or compatible version
- MySQL database server

### Database Setup

1. Start MAMP and make sure Apache and MySQL are running.
2. Open phpMyAdmin or MySQL CLI.
3. Create a database named `alternakraft_db`.
4. Import the schema first:
   - `demoData/team052_p3_schema.sql`
5. Then import the seed data in dependency order, typically:
   - `demoData/postalCode.sql`
   - `demoData/household.sql`
   - `demoData/manufacturer.sql`
   - `demoData/Appliance.sql`
   - other related demo data files as needed
6. Confirm the database name and credentials in `lib/common.php` match your local environment.

### Run the App

1. Open the project in your web root for MAMP.
2. Visit:
   - `http://localhost:8888/Main_Menu.php`

## Notes

This project was built as a school database application, so it uses direct PHP + MySQL logic rather than a modern framework. The structure is intentionally simple and easy to follow for coursework and interviews.

## Recommended Next Improvements

- add a more polished frontend structure
- separate app logic from presentation
- convert repeated SQL queries to reusable helper functions
- add project screenshots and a demo video for GitHub
- rename the repository to a more descriptive project name for professional presentation

## Repository Naming Recommendation

For a GitHub portfolio, a more descriptive repository name such as:

- `alternakraft-energy-dashboard`
- `alternakraft-household-energy-analytics`
- `alternakraft-energy-insights`

would look more polished than `Database-Project`.
