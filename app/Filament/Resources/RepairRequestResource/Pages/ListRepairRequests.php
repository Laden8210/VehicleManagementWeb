<?php

namespace App\Filament\Resources\RepairRequestResource\Pages;

use App\Filament\Resources\RepairHistoryResource\Widgets\LatestRepairs;
use App\Filament\Resources\RepairRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListRepairRequests extends ListRecords
{
    protected static string $resource = RepairRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Repair Request')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new repair requests'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Vehicle Service Record')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('RRNumber')->label('Repair Request Number'),
                        TextEntry::make('vehicle.VehicleName')->label('Vehicle Name'),
                        TextEntry::make('user.name')->label('Driver Name'),
                        TextEntry::make('RequestDate')->label('Request Date'),
                        TextEntry::make('ReportedIssue')->label('Reported Issue'),
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
                        TextEntry::make('DisapprovalComments')->label('Disapproval Comments')
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
