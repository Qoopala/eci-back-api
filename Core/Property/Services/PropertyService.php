<?php

namespace Core\Property\Services;

use App\ApiResponse;
use App\ServiceResponse;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PropertyService
{
    static function store(Request $request){

        DB::beginTransaction();
        try {
            $property = new Property();
            $property->title = $request->title;
            $property->address = $request->address;
            $property->reference = $request->reference;
            $property->price = $request->price;
            $property->information = $request->information;
            $property->number_room = $request->number_room;
            $property->number_bath = $request->number_bath;
            $property->square_meter = $request->square_meter;
            $property->energy_certification = $request->energy_certification;
            $property->map = $request->map;
            $property->status = $request->status;
            $property->office_id = $request->office_id;
            $property->locality_id = $request->locality_id;

            $property->save();
            DB::commit();
            return ServiceResponse::created(__('messages.property_create_ok'), $property);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

}
