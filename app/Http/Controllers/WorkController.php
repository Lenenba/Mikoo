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

        $query = Work::withReservation(); // scope: eager-load + tri

        switch ($user->role->name) {
            case env('PARENT_ROLE'):
                $query->forParent($user->id); // scope: filtrer par user_id
                break;

            case env('BABYSITTER_ROLE'):
                $query->forBabysitter($user->id); // scope: filtrer par babysitter_id
                break;

            case env('SUPER_ADMIN_ROLE'):
                $query->forSuperAdmin(); // scope: filtrer par babysitter_id
                break;

            default:
                return redirect()->route('dashboard');
        }


        return inertia('works/Index', [
            'works' => $query->get(),
        ]);
    }
}
