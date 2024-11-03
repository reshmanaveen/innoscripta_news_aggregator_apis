# Innoscripta News Aggregator APIs

## Overview
The Innoscripta News Aggregator API is a backend service built with Laravel that aggregates news articles from various sources and provides them through a RESTful API.

## Features
- User authentication
- Article fetching from multiple sources
- Search functionality
- API documentation

## Prerequisites
Before you begin, ensure you have met the following requirements:
- **WSL** (Windows Subsystem for Linux) installed on Windows
- **Docker Desktop** installed

## Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/reshmanaveen/innoscripta_news_aggregator_apis.git
   cd news-aggregatorcomposer

### 2. Set Up Environment Variables
Copy the `.env.example` file to create a new `.env` file. This file will hold your environment configuration.


### 3. Run Database Migrations and Seed the Database
Initialize your database by applying migrations and seeding it with initial data. This sets up the necessary tables and populates them with sample data.
  - /vendor/bin/sail artisan migrate:fresh --seed

### 4. Generate Application Key
Generate an application key by running the appropriate command in your terminal. This key is used for encryption and should be kept secret.
  - sail artisan l5-swagger:generate

### 5. Generate API Documentation
Generate the API documentation using the specified command. This will create an accessible documentation path for your API.
- sail artisan l5-swagger:generate

### 6. Fetch Articles
Run the command to fetch articles from various sources. This process will pull in the latest news articles to be available through your API.
- sail artisan app:fetch-articles

### 7. Run Tests
Ensure everything is working correctly by executing your test suite. This helps verify that the application behaves as expected.
- sail artisan test

## API Documentation
To view the API documentation, ensure the L5 Swagger package is set up, then access it at:
- **API Documentation Path**: [http://localhost:90/api/documentation]

## Additional Notes
- If you change the default port for Sail, remember to update the API Documentation path accordingly.
- Ensure your database and any other necessary services are configured in the `.env` file.
- APP_URL=http://localhost:90
- APP_PORT=90
- NEWSAPI_API_KEY=
- GUARDIAN_API_KEY=
- NYT_API_KEY=