<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Stancl\Tenancy\Database\Concerns\UsesTenantConnection;

abstract class TestCase extends BaseTestCase
{
    // use CreatesApplication;
    // use DatabaseMigrations;
}
