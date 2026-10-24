FROM dunglas/frankenphp:1.13.2-builder-php8-alpine AS builder
COPY --from=caddy:builder /usr/bin/xcaddy /usr/bin/xcaddy
RUN CGO_ENABLED=1 XCADDY_SETCAP=1 XCADDY_GO_BUILD_FLAGS="-ldflags='-w -s' -tags=nobadger,nomysql,nopgx" CGO_CFLAGS=$(php-config --includes) CGO_LDFLAGS="$(php-config --ldflags) $(php-config --libs)" xcaddy build --output /usr/local/bin/frankenphp --with github.com/dunglas/frankenphp=./ --with github.com/dunglas/frankenphp/caddy=./caddy/ --with github.com/dunglas/caddy-cbrotli
FROM dunglas/frankenphp:1.13.2-php8-alpine AS runner
COPY --from=builder /usr/local/bin/frankenphp /usr/local/bin/frankenphp
RUN install-php-extensions pdo pdo_mysql pdo_pgsql pdo_sqlite mysqli pgsql redis gd intl zip bcmath opcache pcntl exif mbstring xml curl sodium apcu parallel
RUN apk add uv deno composer watchexec

WORKDIR /app
COPY Caddyfile /etc/Caddyfile
COPY .local/bin/svbuild /app/.local/bin/svbuild
COPY app /app/app
COPY server /app/server
COPY public /app/public

