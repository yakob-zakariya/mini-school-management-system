<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),


                \Filament\Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'admin' => 'Admin',
                        'teacher' => 'Teacher',
                        'student' => 'Student',
                    ])
                    ->required()
                    ->default('student')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Clear IDs when type changes
                        if ($state === 'teacher') {
                            $set('student_id', null);
                        } elseif ($state === 'student') {
                            $set('employee_id', null);
                        }
                    }),

                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->label(fn(string $context): string => $context === 'edit' ? 'New Password (leave blank to keep current)' : 'Password'),

                \Filament\Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Roles'),

                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Textarea::make('address')
                    ->columnSpanFull()
                    ->rows(3),
                DatePicker::make('date_of_birth')
                    ->native(false)
                    ->displayFormat('Y-m-d'),


                DatePicker::make('date_of_birth')
                    ->native(false)
                    ->displayFormat('Y-m-d'),

                TextInput::make('employee_id')
                    ->label('Employee ID')
                    ->unique(ignoreRecord: true)
                    ->visible(fn(callable $get) => $get('type') === 'teacher')
                    ->required(fn(callable $get) => $get('type') === 'teacher'),

                TextInput::make('student_id')
                    ->label('Student ID')
                    ->unique(ignoreRecord: true)
                    ->visible(fn(callable $get) => $get('type') === 'student')
                    ->required(fn(callable $get) => $get('type') === 'student'),
            ]);
    }
}
