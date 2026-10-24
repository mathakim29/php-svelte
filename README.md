<div align="center">

# SveltePHP

![Svelte](https://img.shields.io/badge/Svelte-5-FF3E00?logo=svelte&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white)

</div>

A small full-stack starter project with a Svelte frontend, PHP backend, and
MySQL database.

## Start the app

You need [Nerdctl](https://github.com/containerd/nerdctl) with Compose support.

```bash
nerdctl compose up -d --build
```

Open the app at [http://localhost:8010](http://localhost:8010).

phpMyAdmin is available at [http://localhost:8080](http://localhost:8080).

To stop everything:

```bash
nerdctl compose down
```

## Development

Edit the frontend files in `app/`. Changes are picked up automatically while
the app is running. Refresh the browser to see them.

PHP routes live in `server/routes/`. The public entrypoint is
`root/index.php`.

Place images, videos, fonts, and other public assets in `public/`.
They are available at URLs such as `/public/image.png`.

## Example API

List users:

```bash
curl http://localhost:8010/api/user
```

Create a user:

```bash
curl -X POST http://localhost:8010/api/user \
  -H 'Content-Type: application/json' \
  -d '{"name":"Joey","email":"joey@example.com"}'
```

## Database

The local MySQL database uses:

```text
Database: app
Username: app
Password: app
Root password: root
```

Database data is kept in a named volume so it survives container restarts.

## Useful commands

```bash
# View running services
nerdctl compose ps

# Follow application logs
nerdctl compose logs -f app

# Open a shell in the app container
nerdctl compose exec app sh
```
