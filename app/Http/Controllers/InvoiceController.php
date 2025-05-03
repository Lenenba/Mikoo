<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        // Fetch the invoices from the database
        if (Auth::user()->role->name === env('SUPER_ADMIN_ROLE')) {
            // If the user is an admin, fetch all invoices
            $invoice = \App\Models\Invoice::all();
        } else {
            $invoice = \App\Models\Invoice::where('user_id', auth()->id())->get();
        }
        $invoice = \App\Models\Invoice::all();

        return Inertia::render('invoices/Index');
    }
}
