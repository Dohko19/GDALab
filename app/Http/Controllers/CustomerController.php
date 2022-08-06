<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        Log::info('entry_info', [
            'ip_client' => $request->ip(),
            'data' => $request->all()
        ]);
        try {
            $data = $request->validate( [
                'dni' => 'required',
                'region_id' => ['required','exists:regions,id'],
                'commune_id' => 'required|exists:communes,id',
                'email' => 'email',
                'name' => 'required',
                'last_name' => 'required',
                'address' => 'string',
                'data_reg' => 'date_format:Y-m-d H:i:s'
            ]);

            $communId = $request->commune_id;
            $regionId = $request->region_id;
            $valid = Region::with('communes')->where('id', $request->region_id)->whereHas('communes', function ($query) use ($communId, $regionId){
                $query->where('id', $communId);
                $query->where('region_id', $regionId);
            })->first();
            if ($valid){
                if ($valid->communes->count() > 0){
                    $customer = Customer::create($data);
                    Log::info('entry_info', [
                        'ip_client' => $request->ip(),
                        'data' => $request->all()
                    ]);
                    return response()->json(['success' => 'true'], 200);
                }
            }

            return response()->json(['error' => false]);

        }catch (\Exception $e){
            Log::info('entry_info', [
                'ip_client' => $request->ip(),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function searchCustomer(Request $request)
    {
        Log::info('entry_info', [
            'ip_client' => $request->ip(),
            'data' => $request->all()
        ]);
        $customer = Customer::query();
        try {
            if ($request->has('email')){
                $customer->where('email', $request->get('email'));
            }else{
                $customer->where('dni', $request->get('dni'));
            }

            if ($customer->first()->status === 'A'){

                return response()->json([
                    'name' => $customer->first()->name,
                    'last_name' => $customer->first()->last_name,
                    'address' => $customer->first()->address ?? null,
                    'description' => $customer->first()->load('region')->region->description,
                    'commune' => $customer->first()->load('commune')->region,
                    'success' => true
                ], 200);
            }else{
                Log::info('entry_info', [
                    'ip_client' => $request->ip(),
                    'data' => $request->all(),
                    'message' => 'Estado del cliente inactivo'
                ]);
                return response()->json(['error' => false]);
            }
        }catch (\Exception $exception){
            Log::info('entry_info', [
                'ip_client' => $request->ip(),
                'error' => $exception->getMessage()
            ]);
            return response()->json(['errors' => $exception->getMessage(), 'error' => false]);
        }
    }

    public function destroy($dni)
    {
        Log::info('entry_info', [
            'ip_client' => request()->ip(),
            'data' => $dni,
        ]);
        try {
            $customer = Customer::where('dni', $dni)->first();
            if ($customer->status === 'A' || $customer->status === 'I'){
                Log::info('entry_info', [
                    'ip_client' => request()->ip(),
                    'message' => 'Deleted success',
                ]);
                return response()->json(['success' => true], 200);
            }else{
                Log::info('entry_info', [
                    'ip_client' => request()->ip(),
                    'data' => 'El registro no existe',
                ]);
                return response()->json(['warning' => 'El registro no existe'], 422);
            }
        }catch (\Exception $exception){
            Log::info('entry_info', [
                'ip_client' => request()->ip(),
                'error' => $exception->getMessage()
            ]);
            return response()->json(['errors' => $exception->getMessage(), 'error' => false]);
        }
    }
}
