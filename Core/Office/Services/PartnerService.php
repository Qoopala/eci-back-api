<?php

namespace Core\Office\Services;

use App\Models\Partner;
use App\ServiceResponse;
use Core\Image\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerService
{
    static function store(Request $request){
        DB::beginTransaction();
        try {
            $partner = new  Partner();
            $partner->name = $request->name;
            $partner->role = $request->role;
            $partner->office_id = $request->office_id;
            $partner->save();

            $images = ImageService::store($request, 'partner', $partner->id);
            if($images['success']) {
                foreach ($images['data'] as $path_image) {
                    $partner->path_image = $path_image;
                }
            }

            $partner->save();
            DB::commit();

            $response = Partner::with('office')->find($partner->id);
            return ServiceResponse::created(__('messages.partner_create_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

    static function update(Request $request, $id){
        $data = $request->all();
        $partner =  Partner::find($id);
        if(!$partner) return ServiceResponse::not_found(__('messages.partner_not_found'));

        DB::beginTransaction();
        try {
            $partner->update($data);
            
            if(count($request->file())){
                $delete_old_images = ImageService::delete('partner', $partner->id);
                if(!$delete_old_images) return ServiceResponse::badRequest(__('messages.image_update_badrequest'));
    
                $images = ImageService::store($request, 'partner', $partner->id);
                if($images['success']) {
                    foreach ($images['data'] as $path_image) {
                    $partner->path_image = $path_image;
                    }
                }
            }
        
            DB::commit();
            $response = Partner::with('office')->find($id);
            return ServiceResponse::created(__('messages.partner_update_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

}
