<?php

namespace App\Filament\Resources\MaintenanceRecommendationResource\Pages;

use App\Filament\Resources\MaintenanceRecommendationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListMaintenanceRecommendations extends ListRecords
{
    protected static string $resource = MaintenanceRecommendationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Maintenance Recommendation')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new maintenance recommendations'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Maintenance Recommendation Information')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('MRNumber')->label('Maintenance Recommendation Number'),
                        TextEntry::make('vehicle.VehicleName')->label('Vehicle Name'),
                        TextEntry::make('user.name')->label('Driver Name'),
                        TextEntry::make('RecommendationType')->label('Recommendation Type'),
                        TextEntry::make('Issues')
                            ->label('Issues')
                            ->formatStateUsing(function ($record) {
                                $issues = json_decode($record->Issues, true);

                                if (is_array($issues)) {
                                    return collect($issues)
                                        ->map(fn($issue, $index) => ($index + 1) . '. ' . $issue['IssueDescription'])
                                        ->implode("\n");
                                }

                                return 'No issues found.';
                            }),
                        TextEntry::make('RecommendationDate')->label('Recommendation Date')->date(),
                        TextEntry::make('DueDate')->label('Due Date')->date(),
                        TextEntry::make('PriorityLevel')->label('Priority Level')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'High Priority' => 'danger',
                                'Medium Priority' => 'warning',
                                'Low Priority' => 'success',
                            }),
                        TextEntry::make('RequestStatus')->label('Request Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Pending' => 'warning',
                                'Approved' => 'success',
                                'Disapproved' => 'danger',
                            }),
                        TextEntry::make('DisapprovalComments')->label('Disapproval Comments')->columnSpanFull()
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
