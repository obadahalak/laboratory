<?php

namespace App\Http\Controllers\LabAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\supplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class supplierController extends Controller
{
    
    public function index()
    {
        $user_id = auth('lab')->user()->id;
        $supplier = Supplier::where('account_id', $user_id)->orderBy('id', 'desc')->paginate(10);

        return response()->json($supplier);
    }


    public function store(supplierRequest $request)
    {
        $supplier = $request->validated();

        auth('lab')->user()->suppliers()->create($supplier);

        return response()->json(['message' => 'Supplier created'], 201);
    }


    public function update(Request $request)
    {
        $supplier = Supplier::find($request->id);
        if($supplier){
            $supplier->update([
                'scientific_office_name'=>$request->scientific_office_name,
                'added_date'=>$request->added_date,
                'phone'=>$request->phone,
                'maintain_phone'=>$request->maintain_phone,
                'address'=>$request->address,
            ]);
            
        $response = [
            'status' => 'updated',
        ];
        
        return response()->json($response);
    }
    return response()->json(['error','supplier id invalid'],422);
    }



   
    public function destroy(supplierRequest $request)
    {
        $ids = explode(',', $request->supplier_ids);

        return  Supplier::whereIn('id', $ids)->delete();
    }
}
