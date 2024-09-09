<?php

namespace App\Configuration;

class MariaDBServiceConfiguration extends DatabaseServiceConfiguration
{
    public readonly string $dns;

    public function __construct()
    {
        parent::__construct();
    }
}
