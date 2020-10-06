<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Radiology;
use App\Message;
use Yajra\DataTables\DataTables;
use App\helper\Helper;
use App\City;
use App\Area;
use App\RadiologyInsurance;

class RadiologyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("radiology.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() { 
        $query = Radiology::query();

        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('name', 'like', '%' . request()->search_string . '%')
                    ->orWhere('name_ar', 'like', '%' . request()->search_string . '%')
                    ->orWhere('name_fr', 'like', '%' . request()->search_string . '%')
                    ->orWhere('email', 'like', '%' . request()->search_string . '%')
                    ->orWhere('phone2', 'like', '%' . request()->search_string . '%')
                    ->orWhere('avaliable_days', 'like', '%' . request()->search_string . '%')
                    ->orWhere('phone', 'like', '%' . request()->search_string . '%');
        }

        if (request()->insurance_id > 0) {
            $radiologyIds = RadiologyInsurance::whereIn('insurance_id', request()->insurance_id)->get(['radiology_id'])->pluck('radiology_id')->toArray();
            $query->whereIn('id', $radiologyIds);
        }

        if (request()->city_id > 0) {
            $query->where('city_id', request()->city_id);
        }

        if (request()->area_id) {
            $query->where('area_id', request()->area_id);
        }

        if (request()->radiology_doctor_id) {
            $query->where('radiology_doctor_id', request()->radiology_doctor_id);
        }

        if (request()->active == 1) {
            $query->where('active', "1");
        }

        if (request()->active == 2) {
            $query->where('active', "0");
        }

        if (request()->delivery == 1) {
            $query->where('delivery', "1");
        }

        if (request()->delivery == 2) {
            $query->where('delivery', "0");
        }

        return DataTables::of($query)
                        ->addColumn('action', function(Radiology $radiology) {
                            return view("radiology.action", compact("radiology"));
                        })
                        ->editColumn('radiology_doctor_id', function(Radiology $radiology) {
                            return optional($radiology->doctor)->name;
                        })
                        ->addColumn('address', function(Radiology $radiology) {
                            return optional($radiology->city)->name . "-" . optional($radiology->area)->name;
                        }) 
                        ->addColumn('insurance', function(Radiology $radiology) {
                            return implode(", ", $radiology->insuranceNames());
                        })
                        ->editColumn('photo', function(Radiology $radiology) {
                            return "<img src='" . url('/image/radiology') . "/" . $radiology->photo . "' height='30px' width='30px' onclick='viewImage(this)' style='padding: 3px'  class='w3-circle ".Helper::randColor()."' >";
                        })
                        ->editColumn('active', function(Radiology $radiology) {
                            $radiologyel = ($radiology->active == 1) ? "success" : "danger";
                            $text = ($radiology->active == 1) ? "نشط" : "غير نشط";

                            return "<span class='radiologyel radiologyel-$radiologyel' >$text</span>";
                        })
                        ->editColumn('delivery', function(Radiology $radiology) {
                            $radiologyel = ($radiology->delivery == 1) ? "success" : "danger";
                            $text = ($radiology->delivery == 1) ? __('on') : __('off');

                            return "<span class='radiologyel radiologyel-$radiologyel' >$text</span>";
                        })
                        ->rawColumns(['action', 'photo', 'active', 'delivery'])
                        ->make(true);
    }

    /**
     *
     * @param Radiology $radiology
     * @return type
     */
    public function active(Radiology $radiology) {
        try {
            $radiology->active = 1;
            $radiology->update();
            return Message::redirect("radiology", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("radiology", Message::$ERROR, 0);
        }
    }


    /**
     *
     */
    public function deactive(Radiology $radiology) {
        try {
            $radiology->active = 0;
            $radiology->update();
            return Message::redirect("radiology", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("radiology", Message::$ERROR, 0);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // no code here
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function map() {
        $query = Radiology::query();

        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('name', 'like', '%' . request()->search_string . '%')
                    ->orWhere('name_ar', 'like', '%' . request()->search_string . '%')
                    ->orWhere('name_fr', 'like', '%' . request()->search_string . '%')
                    ->orWhere('email', 'like', '%' . request()->search_string . '%')
                    ->orWhere('phone2', 'like', '%' . request()->search_string . '%')
                    ->orWhere('avaliable_days', 'like', '%' . request()->search_string . '%')
                    ->orWhere('phone', 'like', '%' . request()->search_string . '%');
        }

        if (request()->insurance_id > 0) {
            $radiologyIds = RadiologyInsurance::whereIn('insurance_id', request()->insurance_id)->get(['radiology_id'])->pluck('radiology_id')->toArray();
            $query->whereIn('id', $radiologyIds);
        }

        if (request()->city_id > 0) {
            $query->where('city_id', request()->city_id);
        }

        if (request()->area_id) {
            $query->where('area_id', request()->area_id);
        }

        if (request()->radiology_doctor_id) {
            $query->where('radiology_doctor_id', request()->radiology_doctor_id);
        }

        if (request()->active == 1) {
            $query->where('active', "1");
        }

        if (request()->active == 2) {
            $query->where('active', "0");
        }

        if (request()->delivery == 1) {
            $query->where('delivery', "1");
        }

        if (request()->delivery == 2) {
            $query->where('delivery', "0");
        }
        $resources = $query->get();
        return view('radiology.map', compact('resources'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $insurances = explode(',', $request->insurance_id);

        $validator = validator()->make($request->all(), [
            'name' => 'required',
            'phone' => 'required|max:11',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }


        // check phone
        if (Radiology::where("phone", $request->phone)->count() > 0)
            return Message::error(Message::$PHONE_UNIQUE, null, Message::$PHONE_UNIQUE_EN);

        try {
            $user = Radiology::create($request->all());

            $user->update([
                'password' => bcrypt($request->password)
            ]);


            if ($request->hasFile('photo')) {
                $user->photo = Helper::uploadImg($request->file("photo"), "/radiology/");
            }


            foreach ($insurances as $insurance) {
                $d = new RadiologyInsurance;
                $d->radiology_id = $user->id;
                $d->insurance_id = $insurance;
                $d->save();
            }

            $user->save();
            return Message::success(Message::$SUCCESS_REGISTER, $user->getJson(), Message::$SUCCESS_REGISTER_EN);
        } catch (\Exception $e) {
            return Message::error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function workingHours(Radiology $radiology) {
        return view('radiology.working_hours', compact('radiology'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateWorkingHours(Request $request, Radiology $radiology) {
        try {
            $radiology->working_hours()->delete();
            
            for($index = 0; $index < count($request->day); $index ++) { 
                $working = RadiologyWorkingHours::create([
                    "radiology_id" => $radiology->id,
                    "day" => $request->day[$index],
                    "part_from" => $request->part_from[$index],
                    "part_to" => $request->part_to[$index],
                    "active" => $request->active[$index],
                ]);
                
                
            }
            return Message::success(Message::$DONE);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR . $e->getMessage(), null, Message::$ERROR_EN);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Radiology $radiology) {
        return view('radiology.show', compact('radiology'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Radiology $radiology) {
        return $radiology->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Radiology $radiology) {
        $insurances = explode(',', $request->insurance_id);

        $validator = validator()->make($request->all(), [
            'name' => 'required',
            'phone' => 'required|max:11',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }


        // check phone
        if (Radiology::where("phone", $request->phone)->count() > 0 && $radiology->phone != $request->phone)
            return Message::error(Message::$PHONE_UNIQUE, null, Message::$PHONE_UNIQUE_EN);

        try {
            $radiology->update($request->all());

            if ($radiology->password != $request->password)
                $radiology->update([
                    'password' => bcrypt($request->password)
                ]);


            if ($request->hasFile('photo')) {
                $radiology->photo = Helper::uploadImg($request->file("photo"), "/radiology/");
            }

            $radiology->radiology_insurances()->delete();
            foreach ($insurances as $insurance) {
                $d = new RadiologyInsurance;
                $d->radiology_id = $radiology->id;
                $d->insurance_id = $insurance;
                $d->save();
            }

            $radiology->save();
            return Message::success(Message::$SUCCESS_REGISTER);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR . $e->getMessage(), null, Message::$ERROR_EN);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Radiology $radiology) {
        // no code here
    }

}
