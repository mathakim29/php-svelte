# SveltePHP

![Svelte](https://img.shields.io/badge/Svelte-%23f1413d.svg?logo=svelte&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-%23777BB4.svg?&logo=php&logoColor=white)

-----

A small full-stack application setup using FrankenPHP/FlightPHP for the backend, Svelte 5 and Tailwind CSS for the frontend, and MySQL with RedBeanPHP for persistence.

> [!NOTE]
> This project is meant to be as an experimental template for future projects

## Requirements

- Docker or nerdctl with Compose support
- Ports `8000` and `8080` available

## Start the application

```bash
nerdctl compose up -d --build
```

Open the application at [http://localhost:8000](http://localhost:8000). Open phpMyAdmin at [http://localhost:8080](http://localhost:8080).

phpMyAdmin credentials:

```text
Server: mysql
Username: app
Password: app
Database: app
```

The MySQL root password is `root` for local development.

## Development workflow

The `app/` directory is mounted into the FrankenPHP container. The Deno watcher in `watch.ts` watches it and rebuilds automatically:

- Svelte is bundled to `public/bundle.js` with esbuild.
- Tailwind CSS is generated to `public/style.css`.
- Builds are incremental and debounced.

Edit `app/App.svelte`, save, and refresh the browser. Build output is available with:

```bash
nerdctl logs -f php-svelte-app-1
```

## Backend and API

PHP entrypoint: `public/index.php`.

API route files live in `api/` and are loaded automatically. Add FlightPHP routes to a new `.php` file there.

The example user API is:

```text
GET  /api/user
POST /api/user
```

Create a user:

```bash
curl -X POST http://localhost:8000/api/user \
  -H 'Content-Type: application/json' \
  -d '{"name":"Joey","email":"joey@example.com"}'
```

List users:

```bash
curl http://localhost:8000/api/user
```

## Database

The `app` service connects to the Compose service named `mysql` using these environment variables:

```text
DB_DRIVER=mysql
DB_HOST=mysql
DB_NAME=app
DB_USER=app
DB_PASSWORD=app
```

RedBeanPHP creates tables and columns as records are stored. MySQL data is persisted in the `mysql_data` volume.

## Useful commands

```bash
# Rebuild after Dockerfile or dependency changes
nerdctl compose up -d --build --force-recreate

# View service status
nerdctl compose ps

# View PHP logs
nerdctl logs -f php-svelte-app-1

# Open a shell in the PHP container
nerdctl compose exec app sh

# Stop the stack
nerdctl compose down
```

Named volumes preserve Composer dependencies, Deno cache, FrankenPHP data, and MySQL data between restarts.
