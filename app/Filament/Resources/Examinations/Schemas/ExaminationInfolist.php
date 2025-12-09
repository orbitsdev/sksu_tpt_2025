<?php

namespace App\Filament\Resources\Examinations\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ExaminationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date(),
                IconEntry::make('is_public')
                    ->boolean(),
                IconEntry::make('application_open')
                    ->boolean(),
                TextEntry::make('school_year'),
                TextEntry::make('exam_type'),
                IconEntry::make('is_results_published')
                    ->boolean(),
                TextEntry::make('application_start_date')
                    ->date(),
                TextEntry::make('application_end_date')
                    ->date(),
                TextEntry::make('results_published_at')
                    ->dateTime(),
                TextEntry::make('results_release_at')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
