<?php

if (!extension_loaded('parallel')) {
    die("The 'parallel' extension requires a ZTS build of PHP.\n");
}