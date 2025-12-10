<?php

namespace App\Traits;

use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

trait InteractWithTabsTrait
{
    use InteractsWithTable {
        makeTable as makeBaseTable;
    }

    protected function makeTable(): Table
    {
        return $this->makeBaseTable()
            ->modifyQueryUsing($this->modifyQueryWithActiveTab(...));
    }
}
