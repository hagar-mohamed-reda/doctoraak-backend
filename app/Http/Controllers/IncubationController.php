<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incubation;
use App\Message;
use App\helper\Helper;
use Yajra\DataTables\DataTables;

class IncubationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("incubation.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() {  
        $query = Incubation::query();
        
        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('name', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_ar', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_fr', 'like', '%'.request()->search_string.'%')
                    ->orWhere('description', 'like', '%'.request()->search_string.'%')
                    ->orWhere('description_ar', 'like', '%'.request()->search_string.'%')
                    ->orWhere('description_fr', 'like', '%'.request()->search_string.'%')
                    ->orWhere('phone', 'like', '%'.request()->search_string.'%');
        }
        
        if (request()->city_id > 0) {
            $query->where('city_id', request()->city_id);
        }
        
        if (request()->area_id > 0) {
            $query->where('area_id', request()->area_id);
        }
        
        if (request()->rate) {
            $query->where('rate', request()->rate);
        } 
        
        if (request()->bed_number) {
            $query->where('bed_number', request()->bed_number);
        } 
        
        return DataTables::of($query)
                        ->addColumn('action', function(Incubation $incubation) { 
                            return view("incubation.action", compact('incubation'));
                        })
                        ->editColumn('name', function(Incubation $incubation) {
                            return "<a href='#' class='w3-text-blue' onclick='showPage(\"incubation/show/".$incubation->id." \")' >" . $incubation->name . "</a>";
                        })
                        ->editColumn('rate', function(Incubation $incubation) {
                            $html = "";
                            for($i = 0; $i < 5; $i ++) {
                                if ($i < $incubation->rate) {
                                    $html .= "<i class='fa fa-star w3-text-orange' ></i>";
                                } else 
                                    $html .= "<i class='fa fa-star w3-text-gray' ></i>";
                            }
                            return $html;
                        })
                        ->editColumn('city', function(Incubation $incubation) {
                            return optional($incubation->city)->name;
                        })
                        ->editColumn('area', function(Incubation $incubation) {
                            return optional($incubation->area)->name;
                        })
                        ->rawColumns(['action', 'name', 'rate'])
                        ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("incubation.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        $validator = validator()->make($request->all(), [
            'city_id' => 'required',
            'area_id' => 'required', 
            'lng' => 'required', 
            'lat' => 'required', 
            'rate' => 'required', 
        ],[
            "lat.required" => __('location is required'),
            "lng.required" => __('location is required'),
            "rate.required" => __('rate is required')
        ]);
        
        
        //return $request->file("cv");

        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }
        
        if (
            Incubation::where("name", $request->name)->count() > 0 ||
            Incubation::where("name_ar", $request->name_ar)->count() > 0 ||
            Incubation::where("name_fr", $request->name_fr)->count() > 0  
        ) {
            return Message::error(__('name already exist'));
        }
        
        try {
            Incubation::create($request->all());

            return Helper::responseJson(1, Message::$DONE);
        } catch (\Exception $ex) {
            return Helper::responseJson(0, $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function map() {
        $query = Incubation::query();
        
        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('name', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_ar', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_fr', 'like', '%'.request()->search_string.'%')
                    ->orWhere('description', 'like', '%'.request()->search_string.'%')
                    ->orWhere('description_ar', 'like', '%'.request()->search_string.'%')
                    ->orWhere('description_fr', 'like', '%'.request()->search_string.'%')
                    ->orWhere('phone', 'like', '%'.request()->search_string.'%');
        }
        
        if (request()->city_id > 0) {
            $query->where('city_id', request()->city_id);
        }
        
        if (request()->area_id > 0) {
            $query->where('area_id', request()->area_id);
        }
        
        if (request()->rate) {
            $query->where('rate', request()->rate);
        } 
        
        if (request()->bed_number) {
            $query->where('bed_number', request()->bed_number);
        } 
        
        $resources = $query->get();
        return view('incubation.map', compact('resources'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Incubation $incubation) {
        return view('incubation.show', compact('incubation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Incubation $incubation) { 
        return $incubation->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incubation $incubation) {
        try {
            $incubation->update($request->all());

            return Helper::responseJson(1, Message::$EDIT);
        } catch (\Exception $ex) {
            return Helper::responseJson(0, Message::$ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Incubation $incubation) {
        try {
            $incubation->delete();
            return Helper::responseJson(1, Message::$REMOVE);
        } catch (\Exception $ex) {
            return Helper::responseJson(0, Message::$DONE);
        }
    }
}
