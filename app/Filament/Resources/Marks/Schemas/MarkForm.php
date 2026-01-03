<?php

namespace App\Filament\Resources\Marks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MarkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('enrollment_id')
                    ->relationship('enrollment', 'id')
                    ->required(),
                TextInput::make('marks')
                    ->required()
                    ->numeric(),
                TextInput::make('total_marks')
                    ->required()
                    ->numeric()
                    ->default(100),
                TextInput::make('exam_type'),
                DatePicker::make('exam_date'),
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
