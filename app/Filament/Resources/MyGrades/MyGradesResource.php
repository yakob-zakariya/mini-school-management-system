<?php

namespace App\Filament\Resources\MyGrades;

use App\Filament\Resources\MyGrades\Pages\ListMyGrades;
use App\Filament\Resources\MyGrades\Pages\ViewGradeStudents;
use App\Filament\Resources\MyGrades\Schemas\MyGradesForm;
use App\Filament\Resources\MyGrades\Tables\MyGradesTable;
use App\Models\Grade;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MyGradesResource extends Resource
{
    protected static ?string $model = Grade::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static UnitEnum|string|null $navigationGroup = 'Teaching';

    protected static ?string $navigationLabel = 'My Classes';

    protected static ?int $navigationSort = 0;

    protected static ?string $pluralModelLabel = 'My Classes';

    public static function form(Schema $schema): Schema
    {
        return MyGradesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MyGradesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMyGrades::route('/'),
            'students' => ViewGradeStudents::route('/{record}/students'),
        ];
    }

    // Only teachers can see this resource
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('teacher');
    }

    // Teachers cannot create/edit/delete grades
    public static function canCreate(): bool
    {
        return false;
    }
}
