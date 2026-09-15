<?php

namespace App\Http\Controllers;

use App\Models\attendants;
use App\Models\products;
use Illuminate\Http\Request;

class AttendantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendants = attendants::all();

        return view('settings.attendants', compact('attendants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'unique:attendants,name',
            ],
            [
                'name.unique' => 'Attendant already Existing',
            ]
        );

        attendants::create($request->all());

        return back()->with('success', 'Attendant Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(attendants $attendants)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(attendants $attendants)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'unique:attendants,name,'.$id,
            ],
            [
                'name.unique' => 'Attendant already Existing',
            ]
        );

        $attendant = attendants::find($id);
        $attendant->update($request->all());

        return back()->with('success', 'Attendant Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(products $products)
    {
        //
    }
}
