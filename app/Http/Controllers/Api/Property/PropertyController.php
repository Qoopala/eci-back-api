<?php

namespace App\Http\Controllers\Api\Property;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Core\Property\Services\PropertyService;
use Core\Property\Validations\PropertyValidation;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        try {
            $query = Property::query();
            $properties = $query->paginate($perPage);
            if($properties) return ApiResponse::ok(__('messages.property_get_ok'), $properties);
            else return ApiResponse::not_found(__('messages.property_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // $array = json_decode($data['features']);
        dd($request);
        $validate = PropertyValidation::validateStore($data);
        if($validate) return ApiResponse::badRequest($validate);

        try {
            $response = PropertyService::store($request);
            if($response['success']) return ApiResponse::created($response['message'], $response['data']);
            else {
                switch ($response['code']) {
                    case 500:
                        return ApiResponse::serverError($response['message']); break;
                }
            }
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
