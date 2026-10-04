<?php

namespace App\Filament\Resources\HealthCheckResource\Pages;

use App\Filament\Resources\HealthCheckResource;
use Filament\Resources\Pages\ListRecords;

class ListHealthChecks extends ListRecords
{
    protected static string $resource = HealthCheckResource::class;
}
