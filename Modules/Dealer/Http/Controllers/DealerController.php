<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    public function index()
    {
        return Dealer::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'numeric'],
            'address' => ['required'],
            'google_map_location' => ['required'],
        ]);

        return Dealer::create($data);
    }

    public function show(Dealer $dealer)
    {
        return $dealer;
    }

    public function update(Request $request, Dealer $dealer)
    {
        $data = $request->validate([
            'user_id' => ['required', 'numeric'],
            'address' => ['required'],
            'google_map_location' => ['required'],
        ]);

        $dealer->update($data);

        return $dealer;
    }

    public function destroy(Dealer $dealer)
    {
        $dealer->delete();

        return response()->json();
    }
}
