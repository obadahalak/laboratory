<?php

namespace App\Http\Controllers\LabAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutStoreRequest;
use App\Http\Requests\storeRequest;
use App\Http\Resources\OutSideResource;
use App\Http\Resources\OutStoreResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Models\Out_store;
use Illuminate\Http\Request;
use Symfony\Component\Console\Output\Output;

class storeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_inside()
    {
        $user_id = auth('lab')->user()->id;
        $store = Store::with('test_unit')->where('account_id' , $user_id)
        ->whereNotNull('expire_date')
        ->orderBy('id', 'desc')->paginate(10);

        return response()->json($store);
    }

    public function deleteOutside(OutStoreRequest $request)
    {
        $ids = explode(',', $request->out_store_ids);

        return  Out_store::whereIn('id', $ids)->delete();
    }

    public function get_outside()
    {
        OutSideResource::collection(
        $store = Out_store::with('store')->where('account_id' , auth('lab')->user()->id)
        ->orderBy('id', 'desc')->paginate(10));

        return response()->json($store);
    }


    public function store(storeRequest $request)
    {
        $store = $request->validated();
        $store['account_id'] = auth('lab')->user()->id;

        if ($request->image)
        {
            $store['image'] = $request->image->store($request->product_name . '_product' , 'public');

        }


        $store_data = Store::create($store);

        return response()->json($store_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_quantity(Request $request , $id)
    {
        $store = Store::find($id);
        $quantity = Store::find($id)->quantity;

        if($quantity >= $request->quantity)
        {

            $new_quantity = $quantity - $request->quantity;

            $store->update([
                'quantity' => $new_quantity,
            ]);
            $out_data['account_id'] = auth('lab')->user()->id;
            $out_data['store_id'] = $id;
            $out_data['quantity'] = $request->quantity;
            $out_data['out_date'] = now();
            $out = Out_store::create($out_data);

            $response = [
                'status'    => 'quantity updated',
                'data'      => $new_quantity
            ];


            return response()->json($response);
        }
        else{
            return response()->json(['error'=>'the existed quantity is smaller then the requested quantity'] , 422);
        }



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(storeRequest $request, $id)
    {
        $store_data = Store::where('id' , $id)->first();

        $store['product_name']           = $request->product_name  ;
        $store['description']            = $request->description   ;
        $store['company']                = $request->company       ;
        $store['expire_date']            = $request->expire_date   ;
        $store['out_date']               = $request->out_date      ;
        $store['model']                  = $request->model         ;
        $store['quantity']               = $request->quantity      ;
        $store['test_unit_id']      = $request->test_unit_id  ;

        if ($request->image)
        {
            $store['image'] = $request->image->store($request->product_name . '_product' , 'public');

        }

        $store_data->update($store);
        $response = [
            'status'    => 'updated',
            'data'      => $store_data
        ];

        return response()->json($response);
    }


    public function destroy(storeRequest $request)
    {
        $ids = explode(',', $request->store_ids);

        return  Store::whereIn('id', $ids)->delete();
    }

    public function get_filter_data()
    {
        $store_data = Out_store::with('store')->where('account_id' , auth('lab')->user()->id)->get();

        return OutStoreResource::collection($store_data);

    }
}
