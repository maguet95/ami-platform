<?php

namespace App\Filament\Instructor\Resources\Enrollments\Pages;

use App\Filament\Instructor\Resources\Enrollments\EnrollmentResource;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;
}
