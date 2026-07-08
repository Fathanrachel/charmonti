<?php

namespace App\Filament\Owner\Resources\FinancialReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FinancialReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('formatted_date')
                    ->label('Periode / Tanggal')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->formatted_date ?? \Carbon\Carbon::parse($record->date)->translatedFormat('d M Y');
                    }),
                TextColumn::make('income')
                    ->label('Total Pendapatan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('expense')
                    ->label('Total Pengeluaran')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('profit')
                    ->label('Keuntungan Bersih')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('period')
                    ->label('Tampilkan Berdasarkan')
                    ->options([
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan (Per Minggu)',
                        'monthly' => 'Bulanan (Per Bulan)',
                        'yearly' => 'Tahunan (Per Tahun)',
                    ])
                    ->default('daily')
                    ->query(function ($query, array $data) {
                        $period = $data['value'] ?? 'daily';
                        
                        if ($period === 'weekly') {
                            $query->selectRaw("
                                MIN(date) as date,
                                CONCAT('Minggu ke-', TO_CHAR(date, 'WW, YYYY')) as formatted_date,
                                SUM(income) as income,
                                SUM(expense) as expense,
                                SUM(profit) as profit,
                                1 as id
                            ")
                            ->groupByRaw("TO_CHAR(date, 'WW, YYYY')");
                        } elseif ($period === 'monthly') {
                            $query->selectRaw("
                                MIN(date) as date,
                                TO_CHAR(date, 'Month YYYY') as formatted_date,
                                SUM(income) as income,
                                SUM(expense) as expense,
                                SUM(profit) as profit,
                                1 as id
                            ")
                            ->groupByRaw("TO_CHAR(date, 'YYYY-MM')");
                        } elseif ($period === 'yearly') {
                            $query->selectRaw("
                                MIN(date) as date,
                                TO_CHAR(date, 'YYYY') as formatted_date,
                                SUM(income) as income,
                                SUM(expense) as expense,
                                SUM(profit) as profit,
                                1 as id
                            ")
                            ->groupByRaw("TO_CHAR(date, 'YYYY')");
                        }
                    })
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
