<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lab;
use App\Message;
use Yajra\DataTables\DataTables;
use App\helper\Helper;
use App\City;
use App\Area;
use App\LabInsurance;

class LabController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("lab.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() {
        $query = Lab::query();

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
            $labIds = LabInsurance::whereIn('insurance_id', request()->insurance_id)->get(['lab_id'])->pluck('lab_id')->toArray();
            $query->whereIn('id', $labIds);
        }

        if (request()->city_id > 0) {
            $query->where('city_id', request()->city_id);
        }

        if (request()->area_id) {
            $query->where('area_id', request()->area_id);
        }

        if (request()->lab_doctor_id) {
            $query->where('lab_doctor_id', request()->lab_doctor_id);
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
                        ->addColumn('action', function(Lab $lab) {
                            return view("lab.action", compact("lab"));
                        })
                        ->editColumn('lab_doctor_id', function(Lab $lab) {
                            return optional($lab->doctor)->name;
                        })
                        ->addColumn('address', function(Lab $lab) {
                            return optional($lab->city)->name . "-" . optional($lab->area)->name;
                        })
                        ->addColumn('insurance', function(Lab $lab) {
                            return implode(", ", $lab->insuranceNames());
                        })
                        ->editColumn('photo', function(Lab $lab) {
                            return "<img src='" . url('/image/lab') . "/" . $lab->photo . "' height='30px' width='30px' onclick='viewImage(this)' style='padding: 3px'  class='w3-circle ".Helper::randColor()."' >";
                        })
                        ->editColumn('active', function(Lab $lab) {
                            $label = ($lab->active == 1) ? "success" : "danger";
                            $text = ($lab->active == 1) ? "نشط" : "غير نشط";

                            return "<span class='label label-$label' >$text</span>";
                        })
                        ->editColumn('delivery', function(Lab $lab) {
                            $label = ($lab->delivery == 1) ? "success" : "danger";
                            $text = ($lab->delivery == 1) ? __('on') : __('off');

                            return "<span class='label label-$label' >$text</span>";
                        })
                        ->rawColumns(['action', 'photo', 'active', 'delivery'])
                        ->make(true);
    }

    /**
     *
     * @param Lab $lab
     * @return type
     */
    public function active(Lab $lab) {
        try {
            $lab->active = 1;
            $lab->update();
            return Message::redirect("lab", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("lab", Message::$ERROR, 0);
        }
    }


    /**
     *
     */
    public function deactive(Lab $lab) {
        try {
            $lab->active = 0;
            $lab->update();
            return Message::redirect("lab", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("lab", Message::$ERROR, 0);
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
        $query = Lab::query();

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
            $labIds = LabInsurance::whereIn('insurance_id', request()->insurance_id)->get(['lab_id'])->pluck('lab_id')->toArray();
            $query->whereIn('id', $labIds);
        }

        if (request()->city_id > 0) {
            $query->where('city_id', request()->city_id);
        }

        if (request()->area_id) {
            $query->where('area_id', request()->area_id);
        }

        if (request()->lab_doctor_id) {
            $query->where('lab_doctor_id', request()->lab_doctor_id);
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
        return view('lab.map', compact('resources'));
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
        if (Lab::where("phone", $request->phone)->count() > 0)
            return Message::error(Message::$PHONE_UNIQUE, null, Message::$PHONE_UNIQUE_EN);

        try {
            $user = Lab::create($request->all());

            $user->update([
                'password' => bcrypt($request->password)
            ]);


            if ($request->hasFile('photo')) {
                $user->photo = Helper::uploadImg($request->file("photo"), "/lab/");
            }


            foreach ($insurances as $insurance) {
                $d = new LabInsurance;
                $d->lab_id = $user->id;
                $d->insurance_id = $insurance;
                $d->save();
            }

            $user->save();
            return Message::success(Message::$SUCCESS_REGISTER, $user->getJson(), Message::$SUCCESS_REGISTER_EN);
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
    public function workingHours(Lab $lab) {
        return view('lab.working_hours', compact('lab'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateWorkingHours(Request $request, Lab $lab) {
        try {
            $lab->working_hours()->delete();

            for($index = 0; $index < count($request->day); $index ++) {
                $working = LabWorkingHours::create([
                    "lab_id" => $lab->id,
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
    public function show(Lab $lab) {
        return view('lab.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Lab $lab) {
        return $lab->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lab $lab) {
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
        if (Lab::where("phone", $request->phone)->count() > 0 && $lab->phone != $request->phone)
            return Message::error(Message::$PHONE_UNIQUE, null, Message::$PHONE_UNIQUE_EN);

        try {
            $data = $request->all();

            if ($lab->password != $request->password)
                $data['password'] = bcrypt($request->password);

            $lab->update($request->all());

            if ($request->hasFile('photo')) {
                $lab->photo = Helper::uploadImg($request->file("photo"), "/lab/");
            }

            $lab->lab_insurances()->delete();
            foreach ($insurances as $insurance) {
                $d = new LabInsurance;
                $d->lab_id = $lab->id;
                $d->insurance_id = $insurance;
                $d->save();
            }

            $lab->save();
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
    public function destroy(Lab $lab) {
        // no code here
    }

}
