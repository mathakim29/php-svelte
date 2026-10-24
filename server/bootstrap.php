<?php

class_alias('RedBeanPHP\\R', 'R');
if (getenv('DB_DRIVER') === 'mysql') {
    R::setup(
        'mysql:host=' . (getenv('DB_HOST') ?: 'mysql') . ';dbname=' . (getenv('DB_NAME') ?: 'app'),
        getenv('DB_USER') ?: 'app',
        getenv('DB_PASSWORD') ?: 'app'
    );
} else {
    R::setup('sqlite:/app/data.db');
}

