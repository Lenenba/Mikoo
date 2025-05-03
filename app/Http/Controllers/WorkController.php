<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Auth;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        // Base query with eager-loaded reservation

        $query = Work::withReservation();

        switch ($user->role->name) {
            case env('PARENT_ROLE'):
                $query->forParent($user->id);
                break;

            case env('BABYSITTER_ROLE'):
                $query->forBabysitter($user->id);
                break;

            case env('SUPER_ADMIN_ROLE'):
                $query->forSuperAdmin();
                break;

            default:
                return redirect()->route('dashboard');
        }


        return inertia('works/Index', [
            'works' => $query->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $workId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $workId)
    {
        $work = Work::findOrFail($workId);

        // if (
        //     $work->reservation->babysitter_id !== Auth::user()->id
        //     || $work->reservation->user_id !== Auth::user()->id
        // ) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }


        if (
            $work->status === 'finished'
            && $work->reservation->babysitter->profile->payment_frequency === 'per_task'
        ) {
            InvoiceService::createPerTaskInvoice($work);
        }
        // Update the work with the new data
        $work->update($request->all());

        return back()->with('success', 'Work updated successfully!');
    }
}
