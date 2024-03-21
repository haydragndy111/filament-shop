<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PolicyResource\Pages;
use App\Models\Policy;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PolicyResource extends Resource
{
    protected static ?string $model = Policy::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Policies';

    protected static ?string $navigationGroup = 'Authorization';

    protected static ?string $recordTitleAttribute = 'title';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view');
    }

    public static function form(Form $form): Form
    {
        // title
        // description
        // status
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true),

                        Select::make('status')
                            ->options([
                                Policy::STATUS_ACTIVE => 'active',
                                Policy::STATUS_INACTIVE => 'in-active',
                            ])->required(),

                        MarkdownEditor::make('description')
                            ->required()
                            ->columnSpan('full'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Authorization')
                    ->schema([
                        Select::make('groups')
                            ->relationship('groups', 'title')
                            ->preload()
                            ->searchable()
                            ->multiple()
                            ->required(),

                        Select::make('users')
                            ->relationship('users', 'name')
                            ->preload()
                            ->searchable()
                            ->multiple()
                            ->required(),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '2' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPolicies::route('/'),
            'create' => Pages\CreatePolicy::route('/create'),
            'edit' => Pages\EditPolicy::route('/{record}/edit'),
            'view' => Pages\ListPolicies::route('/{record}/view'),
        ];
    }
}
