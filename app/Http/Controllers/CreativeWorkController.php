<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCreativeWorkRequest;
use App\Http\Requests\UpdateCreativeWorkRequest;
use App\Models\CreativeWork;


class CreativeWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $creative_works = CreativeWork::where('name', 'LIKE',  '%' . $request->input('search') . '%')->paginate(10);
        return $creative_works;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCreativeWorkRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCreativeWorkRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CreativeWork  $creativeWork
     * @return \Illuminate\Http\Response
     */
    public function show(CreativeWork $creativeWork)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CreativeWork  $creativeWork
     * @return \Illuminate\Http\Response
     */
    public function edit(CreativeWork $creativeWork)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCreativeWorkRequest  $request
     * @param  \App\Models\CreativeWork  $creativeWork
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCreativeWorkRequest $request, CreativeWork $creativeWork)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CreativeWork  $creativeWork
     * @return \Illuminate\Http\Response
     */
    public function destroy(CreativeWork $creativeWork)
    {
        //
    }
}
