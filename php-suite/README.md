# PHP Interactive Suite
Three PHP applications with one shared design: session welcome and logout, server and live clock, and age-based access control.

## Run locally (PHP 8.0+)
    php -S localhost:8000
Open http://localhost:8000

## Run with Docker
    docker build -t php-suite .
    docker run --rm -p 10000:10000 php-suite
Open http://localhost:10000

## Deploy on Render
1. Push this folder to a GitHub repository (the Dockerfile must be at the repository root).
2. On Render: New > Web Service > connect the repository > Language: Docker > instance type Free > Create Web Service.
3. Leave the port alone. The app listens on 10000, Render's default.

## Structure
- `app1-session/` sign in, live session timer, secure logout summary
- `app2-clock/` PHP server time next to a live JavaScript clock (time zone set in the file)
- `app3-access/` age check, minimum age is the `MIN_AGE` constant
- `includes/` shared layout. `assets/` shared stylesheet. `docker/` server configuration
