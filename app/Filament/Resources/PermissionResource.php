<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Models\Permission;
use App\Models\Policy;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Permissions';

    protected static ?string $navigationGroup = 'Authorization';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Stats')
                    ->schema([
                        TextInput::make('title')
                            ->required(),

                        Select::make('status')
                            ->options([
                                Policy::STATUS_ACTIVE => 'active',
                                Policy::STATUS_INACTIVE => 'in-active',
                            ])->required(),

                        Checkbox::make('full_access')
                            ->label('Full Access')
                            ->helperText('all actions are given to this permission')
                            ->live(),

                        Select::make('action')
                            ->dehydrated()
                            ->options([
                                Permission::ACTION_VIEW => 'view',
                                Permission::ACTION_CREATE => 'create',
                                Permission::ACTION_UPDATE => 'update',
                                Permission::ACTION_DELETE => 'delete',
                            ])
                            ->helperText(function (Get $get) {
                                $fullAccessCheckBox = empty($get('full_access'));

                                if ($fullAccessCheckBox) {
                                    return "select permissions";
                                } else {
                                    return "full access is enabled";
                                }
                            })
                            ->disabled(function (Get $get): bool {
                                $fullAccessCheckBox = $get('full_access');
                                Log::info('disable ' . $fullAccessCheckBox);

                                if ($fullAccessCheckBox == 1) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }, false)
                            ->multiple()
                            ->required(),

                        Select::make('model_type')
                            ->label('Permission Target Type')
                            ->helperText('select the target fot this permission')
                            ->options([
                                Permission::MODEL_PRODUCT => 'Product',
                                Permission::MODEL_CATEGORY => 'Category',
                                Permission::MODEL_BRAND => 'Brand',
                                Permission::MODEL_CUSTOMER => 'Customer',
                                Permission::MODEL_ORDER => 'Order',
                            ])
                            ->afterStateUpdated(function (Set $set) {
                                $set('model_id', null);
                            })
                            ->live()
                            ->required(),

                        Select::make('model_id')
                            ->label('Permission Target')
                            ->helperText(function (Get $get) {
                                $selectedModelType = $get('model_type');

                                if ($selectedModelType) {
                                    $modelName = Permission::matchModel()[$selectedModelType];
                                    return "selected target { " . $modelName . " }";
                                } else {
                                    return "select the target for this permission";
                                }

                            })
                            ->options(function (Get $get) {
                                $selectedModelType = $get('model_type');

                                if ($selectedModelType) {
                                    $model = "App\Models\\" . Permission::matchModel()[$selectedModelType];
                                    $models = $model::all();

                                    $options = $models->mapWithKeys(function ($model) {
                                        $FILAMENT_MATCH_ATTRIBUTE = $model::FILAMENT_MATCH_ATTRIBUTE;

                                        return [
                                            $model->id => $model->$FILAMENT_MATCH_ATTRIBUTE,
                                        ];
                                    });
                                    return $options;
                                }

                                return [];
                            })
                            ->multiple()
                            ->required(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Authorization')
                    ->schema([
                        Select::make('policies')
                            ->relationship('policies', 'title')
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
                //
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
