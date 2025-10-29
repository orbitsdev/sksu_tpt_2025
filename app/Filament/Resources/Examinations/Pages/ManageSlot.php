<?php

namespace App\Filament\Resources\Examinations\Pages;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Filament\Resources\Examinations\ExaminationResource;
use App\Filament\Resources\Examinations\Tables\ExaminationSlotTable;

class ManageSlot extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithRecord;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected static string $resource = ExaminationResource::class;

    protected string $view = 'filament.resources.examinations.pages.manage-slot';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

    }

    public function addSlotAction(): Action
    {
        return Action::make('addSlot')
            ->schema([
                TextInput::make('title'),
            ])
            ->action(function (array $data) {
                dd($data);
            });
    }

    public static function table(Table $table): Table
    {
        return ExaminationSlotTable::configure($table);
    }


}
