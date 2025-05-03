<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Services\InvoiceService;

class GenerateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate {frequency : per_task|weekly|biweekly|monthly}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les factures pour une fréquence donnée';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $freq = $this->argument('frequency');

        // Définir la période selon la fréquence
        switch ($freq) {
            case 'daily':
                $start = Carbon::now()->subDay()->startOfDay();
                $end   = Carbon::now()->subDay()->endOfDay();
                break;
            case 'weekly':
                $start = Carbon::now()->subWeek()->startOfWeek();
                $end   = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'biweekly':
                // Exemple : semaines paires impaires
                $start = Carbon::now()->subWeeks((int) floor(now()->weekOfYear / 2) * 2)->startOfWeek();
                $end   = (clone $start)->addWeeks(2)->subDay()->endOfDay();
                break;
            case 'monthly':
                $start = Carbon::now()->subMonth()->startOfMonth();
                $end   = Carbon::now()->subMonth()->endOfMonth();
                break;
            default:
                $this->error("Fréquence invalide : {$freq}");
                return 1;
        }

        InvoiceService::createPeriodicInvoice($freq, $start, $end);

        $this->info("Factures générées pour la période {$start->toDateString()} – {$end->toDateString()}");
    }
}
