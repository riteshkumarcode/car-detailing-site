<?php

namespace App\Filament\Resources\CustomerMembershipResource\Pages;

use App\Filament\Resources\CustomerMembershipResource;
use Filament\Resources\Pages\ListRecords;

class ListCustomerMemberships extends ListRecords
{
    protected static string $resource = CustomerMembershipResource::class;
}
