<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use App\Filament\Resources\BrandResource\RelationManagers\ProductsRelationManager;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBrand extends ViewRecord
{
    protected static string $resource = BrandResource::class;

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
        ];
    }
}
