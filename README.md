# Cazh Movie API

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and update the configuration
4. Generate application key: `php artisan key:generate`
5. Run database migrations: `php artisan migrate`
6. Seed the database: `php artisan db:seed`

## Running the Application

1. Start the development server: `php artisan serve`
2. The API will be available at `http://localhost:8000/api`

## API Endpoints

### 1. Create Movie
- **URL:** `POST /api/movies`
- **Authentication:** Required
- **Request Body:** `{ "title": "Movie Title", "description": "Movie description", "release_year": 2023, "user_id": 1 }`
- **Validations:** `title` (required, max 255 chars), `description` (optional, string), `release_year` (optional, integer, max current year), `user_id` (required, must exist)

### 2. Update Movie
- **URL:** `PUT /api/movies/{id}`
- **Authentication:** Required
- **Request Body:** (Same as Create Movie)
- **Behavior:** Updates movie details, resynchronizes OMDb movie details

### 3. Delete Movie
- **URL:** `DELETE /api/movies/{id}`
- **Authentication:** Required
- **Behavior:** Deletes movie, cascades deletion to related reviews and movie details

### 4. List Movies
- **URL:** `GET /api/movies`
- **Authentication:** Required
- **Query Parameters:** `user_id` (required), `title` (optional), `sort_by` (optional: title, release_year), `sort_order` (optional: asc, desc)
- **Response Format:** Includes movie details, user information, and reviews

### 5. Create Review
- **URL:** `POST /api/reviews`
- **Authentication:** Required
- **Request Body:** `{ "movie_id": 1, "user_id": 2, "rating": 4, "comment": "Great movie!" }`
- **Validations:** `movie_id` (required, must exist), `user_id` (required, must exist), `rating` (required, 1-5), `comment` (optional)

# Cazh Movie API Documentation

## Overview
This API allows users to manage movies, including creation, retrieval, updating, and deletion of movie records, along with review functionality.

## Authentication
- **Mechanism:** API Key Authentication
- **Header:** `X-API-KEY`
- **Required:** All endpoints

## Endpoints

### 1. Create Movie
- **URL:** `POST /api/movies`
- **Authentication:** Required
- **Request Body:**
```json
{
    "title": "Movie Title",
    "description": "Movie description",
    "release_year": 2023,
    "user_id": 1
}
```
- **Validations:**
  - `title`: Required, max 255 characters
  - `description`: Optional, string
  - `release_year`: Optional, integer, max current year
  - `user_id`: Required, must exist in users table

### 2. Update Movie
- **URL:** `PUT /api/movies/{id}`
- **Authentication:** Required
- **Request Body:** (Same as Create Movie)
- **Behavior:** 
  - Updates movie details
  - Resynchronizes OMDb movie details

### 3. Delete Movie
- **URL:** `DELETE /api/movies/{id}`
- **Authentication:** Required
- **Behavior:** 
  - Deletes movie
  - Cascades deletion to related reviews and movie details

### 4. List Movies
- **URL:** `GET /api/movies`
- **Authentication:** Required
- **Query Parameters:**
  - `user_id`: *Required*
  - `title`: Optional (partial title search)
  - `sort_by`: Optional (title, release_year)
  - `sort_order`: Optional (asc, desc, default: asc)
  - `per_page`: Optional (default: 10)

#### Sorting Behavior
- Default: Sorted by movie ID ascending
- Custom sorting when parameters provided
  - `sort_by=title`: Alphabetical sorting
  - `sort_by=release_year`: Year-based sorting

#### Search Example
- `/api/movies?user_id=1&title=Inception&sort_by=release_year&sort_order=desc`

#### Response Format
```json
[
  {
    "id": 1,
    "title": "Inception",
    "description": "Mind-bending thriller",
    "release_year": 2010,
    "details": {
      "imdb_id": "tt1375666",
      "genre": "Action, Sci-Fi",
      "director": "Christopher Nolan",
      "actors": "Leonardo DiCaprio, Joseph Gordon-Levitt",
      "plot": "Corporate secret stealing adventure",
      "runtime": "148 min"
    },
    "users": {
      "id": 1,
      "name": "User Name",
      "email": "user@example.com"
    },
    "reviews": [
      {
        "user_id": 2,
        "rating": 5,
        "comment": "Amazing movie!"
      }
    ]
  }
]
```

### 5. Create Review
- **URL:** `POST /api/reviews`
- **Authentication:** Required
- **Request Body:**
```json
{
    "movie_id": 1,
    "user_id": 2,
    "rating": 4,
    "comment": "Great movie!"
}
```
- **Validations:**
  - `movie_id`: Required, must exist
  - `user_id`: Required, must exist
  - `rating`: Required, integer between 1-5
  - `comment`: Optional

## Error Handling
- **400:** Validation Errors
- **401:** Unauthorized (Invalid API Key)
- **404:** Resource Not Found

## External Integration
- Uses OMDb API for movie details retrieval
- Automatically fetches and stores additional movie information

## Performance Notes
- Search is case-insensitive
- Partial title matching supported
- Default sorting prevents unnecessary alphabetical ordering

## Security
- API key required for all endpoints
- User-specific data access
- Input validation at request level


# Develop with Laravel


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
