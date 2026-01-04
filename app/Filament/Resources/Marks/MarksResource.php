<?php

namespace App\Filament\Resources\Marks;

use App\Filament\Resources\Marks\Pages\CreateMarks;
use App\Filament\Resources\Marks\Pages\EditMarks;
use App\Filament\Resources\Marks\Pages\EnterMarks;
use App\Filament\Resources\Marks\Pages\ListMarks;
use App\Filament\Resources\Marks\Schemas\MarksForm;
use App\Filament\Resources\Marks\Tables\MarksTable;
use App\Models\Mark;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MarksResource extends Resource
{
    protected static ?string $model = Mark::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static UnitEnum|string|null $navigationGroup = 'Academic Management';

    protected static ?string $navigationLabel = 'Marks';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return MarksForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMarks::route('/'),
            'create' => CreateMarks::route('/create'),
            'edit' => EditMarks::route('/{record}/edit'),
            'enter' => EnterMarks::route('/enter'),
        ];
    }

    // Only teachers and admins can see this resource
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole(['super_admin', 'admin', 'teacher']);
    }
}
