<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use Illuminate\Support\Carbon;
use App\Models\Office;


class RentalController extends Controller
{
    public function index()
    {
        return response()->json(Rental::all());
    }
    public function show($id)
    {
        return response()->json(Rental::findOrFail($id));
    }//

    public function store(Request $request)
    {
       $validated = $request->validate([
            'uid' => 'required|integer',
            'office_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'daily_rate' => 'required|integer',
            'base_fee' => 'required|integer',
        ]);

        // Dátumok feldolgozása
    $start = Carbon::parse($validated['start_date']);
    $end = Carbon::parse($validated['end_date']);
    $days = $start->diffInDays($end);

    // Üzleti szabályok
    if ($start->isBefore(Carbon::tomorrow())) {
        return response()->json(['error' => 'A bérlés kezdőnapja nem lehet korábbi, mint a holnapi nap.'], 400);
    }

    if ($days < 5) {
        return response()->json(['error' => 'A bérlésnek legalább 5 naposnak kell lennie.'], 400);
    }

    if ($days > 90) {
        return response()->json(['error' => 'A bérlés nem lehet hosszabb 90 napnál.'], 400);
    }

    // Átfedés vizsgálat (ugyanarra az irodára)
    $overlap = Rental::where('office_id', $validated['office_id'])
        ->where(function ($query) use ($start, $end) {
            $query->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($query) use ($start, $end) {
                      $query->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                  });
        })->exists();

    if ($overlap) {
        return response()->json(['error' => 'Ez az iroda már foglalt a megadott időszakban.'], 400);
    }

    // Új bérlés mentése
    $rental = Rental::create($validated);

    return response()->json($rental, 201);
}
   

    public function destroy($id)
{
    // 1️⃣ Megkeressük a bérlést az ID alapján, vagy 404-es hibát dobunk, ha nincs ilyen
    $rental = Rental::findOrFail($id);

    // 2️⃣ Töröljük az adatbázisból
    $rental->delete();

    // 3️⃣ Visszajelzés: nincs tartalom, de sikeres törlés (204 No Content) return response()->json(null, 204);
    return response()->json(['message' => 'A bérlés törölve!'], 200);
}

        
 
}
