<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any invoices.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Super admin can view all invoices
        if ($user->role->name === env('SUPER_ADMIN_ROLE')) {
            return true;
        }

        // Babysitter can view invoices they own
        return $user->role->name === env('BABYSITTER_ROLE');
    }

    /**
     * Determine whether the user can view a specific invoice.
     *
     * @param  \App\Models\User    $user
     * @param  \App\Models\Invoice $invoice
     * @return bool
     */
    public function view(User $user, Invoice $invoice): bool
    {
        // Super admin can view any invoice
        if ($user->role->name === env('SUPER_ADMIN_ROLE')) {
            return true;
        }

        // Babysitter can view their own invoice
        return $user->role->name === env('BABYSITTER_ROLE')
            && $user->id === $invoice->babysitter_id;
    }
}
