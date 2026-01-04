<?php

namespace App\Filament\Resources\MyMarks;

use App\Filament\Resources\MyMarks\Pages\ListMyMarks;
use App\Filament\Resources\MyMarks\Tables\MyMarksTable;
use App\Models\Mark;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MyMarksResource extends Resource
{
    protected static ?string $model = Mark::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static UnitEnum|string|null $navigationGroup = 'Student';

    protected static ?string $navigationLabel = 'My Marks';

    protected static ?int $navigationSort = 0;

    protected static ?string $pluralModelLabel = 'My Marks';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            // No form - students can't create/edit marks
        ]);
    }

    public static function table(Table $table): Table
    {
        return MyMarksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMyMarks::route('/'),
        ];
    }

    // Only students can see this resource
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('student');
    }

    // Students cannot create marks
    public static function canCreate(): bool
    {
        return false;
    }

    // Students cannot edit marks
    public static function canEdit($record): bool
    {
        return false;
    }

    // Students cannot delete marks
    public static function canDelete($record): bool
    {
        return false;
    }
}
