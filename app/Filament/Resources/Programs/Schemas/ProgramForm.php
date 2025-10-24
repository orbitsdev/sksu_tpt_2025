<?php

namespace App\Filament\Resources\Programs\Schemas;

use App\Models\Campus;
use Filament\Support\RawJs;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

Select::make('campus_id')
    ->label('Campus')
    ->options(Campus::query()->pluck('name', 'id'))
    ->searchable()->disabled(function ($operation) {
        return $operation === 'edit';
    }),
              TextInput::make('name')
    ->label('Program Name')
    ->placeholder('e.g. Bachelor of Science in Information Technology')
    ->required()
    ->live(debounce: 800),
     // small delay for smoother typing
    // ->afterStateUpdated(function (Set $set, ?string $state) {
    //     if (! $state) {
    //         $set('abbreviation', null);
    //         return;
    //     }

    //     // 🧠 Auto-generate abbreviation suggestion
    //     $words = preg_split('/\s+/', trim($state));
    //     $abbreviation = collect($words)
    //         ->filter(fn ($word) => !in_array(Str::lower($word), ['of', 'in', 'and', 'the'])) // ignore small words
    //         ->map(fn ($word) => Str::upper(Str::substr($word, 0, 1)))
    //         ->implode('');

    //     $set('abbreviation', $abbreviation);
    // }),

TextInput::make('abbreviation')
    ->label('Abbreviation')
    ->placeholder('Auto-suggested, e.g. BSIT')
    ->mask(RawJs::make(<<<'JS'
        $input.toUpperCase().replace(/[^A-Z0-9]/g, '')
    JS))
    ->stripCharacters([' '])
    ->maxLength(10),

            // 🧾 PROGRAM CODE (format like ABC-123 or short letters)
            TextInput::make('code')
                ->label('Program Code')
                ->placeholder('e.g. IT-01')
                ->mask(RawJs::make(<<<'JS'
                    $input.toUpperCase()
                        .replace(/[^A-Z0-9-]/g, '') // allow A-Z, 0-9, and dash
                JS))
              ->hint('(Optional)')
                ->stripCharacters([' '])
                ->maxLength(10),
                Toggle::make('is_offered'),
            ]);
    }
}
