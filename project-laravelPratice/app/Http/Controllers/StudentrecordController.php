<?php

namespace App\Http\Controllers;

use App\Models\studentrecord;
use Illuminate\Http\Request;

class StudentrecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $user = studentrecord::all();
        return view('omr',compact('user'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("addUser");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(studentrecord $studentrecord)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(studentrecord $studentrecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, studentrecord $studentrecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(studentrecord $studentrecord)
    {
        //
    }
}
