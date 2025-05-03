<?php

namespace App\Services;

use App\Models\Work;
use App\Models\Invoice;
use App\Models\BabysitterProfile;

class InvoiceService
{
    public static function createPerTaskInvoice(Work $work)
    {
        $invoice = Invoice::create([
            'babysitter_id' => $work->reservation->babysitter_id,
            'period_start'  => $work->scheduled_for,
            'period_end'    => $work->scheduled_for,
            'total_amount'  => $work->amount,
        ]);
        $invoice->items()->create([
            'work_id'     => $work->id,
            'description' => "Garde le {$work->scheduled_for}",
            'hours'       => $work->hours,
            'unit_price'  => $work->reservation->babysitter->profile->price_per_hour,
            'amount'      => $work->amount,
        ]);
        // envoi d’email ou notification
    }

    /**
     * Create a periodic invoice for babysitters based on their payment frequency.
     *
     * @param string $frequency
     * @param \DateTime $start
     * @param \DateTime $end
     */
    public static function createPeriodicInvoice(string $frequency, \DateTime $start, \DateTime $end)
    {
        $babysitters = BabysitterProfile::where('payment_frequency', $frequency)->get();
        foreach ($babysitters as $profile) {
            $works = Work::where('babysitter_id', $profile->user_id)
                ->whereBetween('scheduled_for', [$start, $end])
                ->where('status', 'finished')
                ->get();
            if ($works->isEmpty()) {
                continue;
            }
            $invoice = Invoice::create([
                'babysitter_id' => $profile->user_id,
                'period_start'  => $start,
                'period_end'    => $end,
                'total_amount'  => $works->sum('amount'),
            ]);
            foreach ($works as $work) {
                $invoice->items()->create([
                    'work_id'     => $work->id,
                    'description' => "Garde le {$work->scheduled_for}",
                    'hours'       => $work->hours,
                    'unit_price'  => $profile->price_per_hour,
                    'amount'      => $work->amount,
                ]);
            }
            // notifier la nounou
        }
    }
}
