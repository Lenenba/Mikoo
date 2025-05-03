<?php

use App\Console\Commands\GenerateInvoices;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// 1) Facturation quotidienne (tous les jours à 00h00)
Schedule::command(GenerateInvoices::class, ['daily'])
    ->dailyAt('00:00');

// 2) Facturation hebdomadaire (tous les lundis à 01h00)
Schedule::command(GenerateInvoices::class, ['weekly'])
    ->weeklyOn(1, '01:00');

// 3) Facturation bimensuelle (toutes les deux semaines le lundi à 00h00)
Schedule::command(GenerateInvoices::class, ['biweekly'])
    ->twiceMonthly(1, 16, '13:00');;

// 4) Facturation mensuelle (le 1er de chaque mois à 02h00)
Schedule::command(GenerateInvoices::class, ['monthly'])
    ->monthlyOn(1, '02:00');
