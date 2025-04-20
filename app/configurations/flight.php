<?php

declare(strict_types=1);

use Illuminate\Container\Container;

Flight::registerContainerHandler(Container::getInstance());
Flight::set('flight.handle_errors', false);
