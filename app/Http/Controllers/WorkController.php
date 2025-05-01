<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Work;
use Illuminate\Http\Request;
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
}
