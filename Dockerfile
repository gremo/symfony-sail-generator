###############################################################################
# Franken base stage
###############################################################################
FROM dunglas/frankenphp AS frankenphp_base
SHELL ["/bin/bash", "-euxo", "pipefail", "-c"]

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN \
    # Install OS packages
    apt-get update; \
    apt-get -y --no-install-recommends install \
        acl \
        cron \
        git \
        libnss3-tools \
        openssh-client \
        rsync \
        supervisor \
        unzip \
    ; \
    # Configure OS
    sed -i '/^exec "$@"$/i # Start supervisor service\nchmod 644 /etc/cron.d/app\nservice supervisor start\n' /usr/local/bin/docker-php-entrypoint; \
    sed -i '/^exec "$@"$/i # Start cron service\nchmod 644 /etc/supervisor/conf.d/app.conf\nservice cron start\n' /usr/local/bin/docker-php-entrypoint; \
    # Install PHP extensions
    install-php-extensions \
        @composer \
        apcu \
        intl \
        opcache \
        pdo_mysql \
        xsl \
        zip \
    ; \
    # Configure PHP
    { \
        echo apc.enable_cli = 1; \
        echo memory_limit = 256M; \
        echo opcache.interned_strings_buffer = 16; \
        echo opcache.max_accelerated_files = 20000; \
        echo opcache.memory_consumption = 256; \
        echo realpath_cache_ttl = 600; \
        echo session.use_strict_mode = 1; \
        echo variables_order = EGPCS; \
        echo zend.detect_unicode = 0; \
    } >> "$PHP_INI_DIR/conf.d/10-php.ini"; \
    # Install Node.js
    curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -; \
    apt-get install -y --no-install-recommends nodejs; \
    npm install -g yarn; \
    # Cleanup
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*

###############################################################################
# Franken dev stage
###############################################################################
FROM frankenphp_base AS frankenphp_dev
SHELL ["/bin/bash", "-euxo", "pipefail", "-c"]

RUN \
    cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
    { \
        echo xdebug.client_host = host.docker.internal; \
    } >> "$PHP_INI_DIR/conf.d/10-php.ini"

###############################################################################
# Franken prod stage
###############################################################################
FROM frankenphp_base AS frankenphp_prod
SHELL ["/bin/bash", "-euxo", "pipefail", "-c"]

ENV APP_RUNTIME=Runtime\\FrankenPhpSymfony\\Runtime
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

RUN \
    cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"; \
    { \
        echo expose_php = 0; \
        echo opcache.preload = /app/config/preload.php; \
        echo opcache.preload_user = root; \
    } >> "$PHP_INI_DIR/conf.d/10-php.ini"

COPY --link config/docker/cron /etc/cron.d/app
COPY --link config/docker/php.ini $PHP_INI_DIR/conf.d/20-php.ini
COPY --link config/docker/php.ini $PHP_INI_DIR/conf.d/20-php.ini
COPY --link config/docker/supervisor.conf /etc/supervisor/conf.d/app.conf

COPY --link composer.* symfony.* ./

RUN composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

COPY --link . /app

RUN \
    mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    composer dump-env prod; \
    bin/console cache:clear
