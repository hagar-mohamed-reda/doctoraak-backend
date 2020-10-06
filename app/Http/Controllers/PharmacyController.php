<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pharmacy;
use App\PharmacyInsurance;
use App\PharmacyWorkingHours;
use App\Message;
use Yajra\DataTables\DataTables;
use DB;

class PharmacyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("pharmacy.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() {
        $query = Pharmacy::query();

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
            $pharmacyIds = PharmacyInsurance::whereIn('insurance_id', request()->insurance_id)->get(['pharmacy_id'])->pluck('pharmacy_id')->toArray();
            $query->whereIn('id', $pharmacyIds);
        }

        if (request()->city_id > 0) {
            $query->where('city_id', request()->city_id);
        }

        if (request()->area_id) {
            $query->where('area_id', request()->area_id);
        }

        if (request()->pharmacy_doctor_id) {
            $query->where('pharmacy_doctor_id', request()->pharmacy_doctor_id);
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
                        ->addColumn('action', function(Pharmacy $pharmacy) {
                            return view("pharmacy.action", compact("pharmacy"));
                        })
                        ->addColumn('doctor', function(Pharmacy $pharmacy) {
                            return ($pharmacy->doctor) ? $pharmacy->doctor->name : '';
                        })
                        ->addColumn('insurance', function(Pharmacy $pharmacy) {
                            return $pharmacy->insuranceNames();
                        })
                        ->addColumn('photo', function(Pharmacy $pharmacy) {
                            return "<img src='" . url('/image/pharmacy') . "/" . $pharmacy->photo . "' style='width: 40px;height: 40px' class='w3-circle' onclick='viewImage(this)'  >";
                        })
                        ->editColumn('active', function(Pharmacy $pharmacy) {
                            $pharmacyel = ($pharmacy->active == 1) ? "success" : "danger";
                            $text = ($pharmacy->active == 1) ? "نشط" : "غير نشط";

                            return "<span class='label label-$pharmacyel' >$text</span>";
                        })
                        ->rawColumns(['action', 'photo', 'active'])
                        ->make(true);
    }

    /**
     *
     * @param Pharmacy $pharmacy
     * @return type
     */
    public function active(Pharmacy $pharmacy) {
        try {
            $pharmacy->active = 1;
            $pharmacy->update();
            return Message::redirect("pharmacy", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("pharmacy", Message::$ERROR, 0);
        }
    }

    /**
     *
     */
    public function deactive(Pharmacy $pharmacy) {
        try {
            $pharmacy->active = 0;
            $pharmacy->update();
            return Message::redirect("pharmacy", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("pharmacy", Message::$ERROR, 0);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function map() {$query = Pharmacy::query();

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
            $pharmacyIds = PharmacyInsurance::whereIn('insurance_id', request()->insurance_id)->get(['pharmacy_id'])->pluck('pharmacy_id')->toArray();
            $query->whereIn('id', $pharmacyIds);
        }

        if (request()->city_id > 0) {
            $query->where('city_id', request()->city_id);
        }

        if (request()->area_id) {
            $query->where('area_id', request()->area_id);
        }

        if (request()->pharmacy_doctor_id) {
            $query->where('pharmacy_doctor_id', request()->pharmacy_doctor_id);
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
        return view('pharmacy.map', compact('resources'));
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
        if (Pharmacy::where("phone", $request->phone)->count() > 0)
            return Message::error(Message::$PHONE_UNIQUE, null, Message::$PHONE_UNIQUE_EN);

        try {
            $user = Pharmacy::create($request->all());

            $user->update([
                'password' => bcrypt($request->password)
            ]);


            if ($request->hasFile('photo')) {
                $user->photo = Helper::uploadImg($request->file("photo"), "/pharmacy/");
            }


            foreach ($insurances as $insurance) {
                $d = new PharmacyInsurance;
                $d->pharmacy_id = $user->id;
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
    public function show(Pharmacy $pharmacy) {
        return view('pharmacy.show', compact('pharmacy'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function workingHours(Pharmacy $pharmacy) {
        return view('pharmacy.working_hours', compact('pharmacy'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateWorkingHours(Request $request, Pharmacy $pharmacy) {
        try {
            $pharmacy->working_hours()->delete();
            
            for($index = 0; $index < count($request->day); $index ++) { 
                $working = PharmacyWorkingHours::create([
                    "pharmacy_id" => $pharmacy->id,
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pharmacy $pharmacy) {
        return $pharmacy->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pharmacy $pharmacy) {
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
        if (Pharmacy::where("phone", $request->phone)->count() > 0 && $pharmacy->phone != $request->phone)
            return Message::error(Message::$PHONE_UNIQUE, null, Message::$PHONE_UNIQUE_EN);

        try {
            $pharmacy->update($request->all());

            if ($pharmacy->password != $request->password)
                $pharmacy->update([
                    'password' => bcrypt($request->password)
                ]);


            if ($request->hasFile('photo')) {
                $pharmacy->photo = Helper::uploadImg($request->file("photo"), "/pharmacy/");
            }

            $pharmacy->pharmacy_insurances()->delete();
            foreach ($insurances as $insurance) {
                $d = new PharmacyInsurance;
                $d->pharmacy_id = $pharmacy->id;
                $d->insurance_id = $insurance;
                $d->save();
            }

            $pharmacy->save();
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
    public function destroy(Pharmacy $pharmacy) {
        // no code here
    }

}
