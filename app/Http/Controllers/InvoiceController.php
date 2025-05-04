<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvoiceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show the list of invoices.
     *
     * @return \Inertia\Response
     */
    public function index()
    {

        $this->authorize('viewAny', Invoice::class);
        // Fetch the invoices from the database
        if (Auth::user()->role->name === env('SUPER_ADMIN_ROLE')) {
            // If the user is an admin, fetch all invoices
            $invoices = Invoice::with('items')->get();
        }
        $invoices = Invoice::where('babysitter_id', Auth::id())
            ->with('items')->get();

        return Inertia::render('invoices/Index', [
            'invoices' => $invoices,
        ]);
    }
}
