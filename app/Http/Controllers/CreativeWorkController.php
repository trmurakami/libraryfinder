<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        if (!$request->per_page) {
            $request->per_page = 10;
        }

        $query = CreativeWork::with('authors')->where('name', 'LIKE',  '%' . $request->input('search') . '%');

        if ($request->type) {
            $query->where('type', $request->type);
        }
        $creative_works = $query->orderBy('datePublished', 'desc')->paginate($request->per_page)->appends(request()->query());
        return $creative_works;
    }

    /**
     * Facet simple field.
     *
     * @return \Illuminate\Http\Response
     */
    public function facetSimple(Request $request)
    {   
        $query = DB::table('creative_works')->select($request->field, DB::raw('count(*) as total'));
        $query->where('name', 'LIKE',  '%' . $request->input('search') . '%');
        if ($request->type) {
            $query->where('type', $request->type);
        }
        $facets = $query->groupBy($request->field)->get();
        return $facets;
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
    public function show($id)
    {
        $record = CreativeWork::with('authors')->find($id);

        if (is_null($record)) {
            return response()->json('', 204);
        }

        return response()->json($record);
  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CreativeWork  $creativeWork
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request)
    {

        $record = CreativeWork::with('authors')->findOrFail(request('id'));

        return view('pages.creativework', compact('record'));

        // $record = CreativeWork::find($request->id);
        // if (is_null($record)) {
        //     return response()->json('', 204);
        // }
        // return view('pages.creativework')->with('record', $record);
  
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
