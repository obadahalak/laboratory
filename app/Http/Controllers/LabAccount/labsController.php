<?php

namespace App\Http\Controllers\LabAccount;

use App\Models\Lab;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Requests\LabRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class labsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $labs = Lab::where('account_id', auth('lab')->user()->id)->orderBy('id', 'desc')->paginate(10);
        return response()->json($labs);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LabRequest $request)
    {
        $input = $request->validated();
        $input['account_id'] = auth()->user()->id;
        $input['code'] = $request->lab_name . auth('lab')->user()->id;

        if ($request->image) {
            $input['image'] = $request->image->store($request->lab_name . '_lab', 'public');
        }

        $lab = Lab::create($input);

        return response()->json($lab);
    }

    public function searchCode(Request $request)
    {
        $rules = [
            'code' => ['required', function ($attribute, $value, $fail) {
                if (!Account::where('code', $value)->first()) {
                    $fail('code is invalid');
                }
            }, function ($attribute, $value, $fail) {
                if (auth('lab')->user()->code == $value) {
                    $fail('You cannot add your  code');
                }
            }]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        } else {
            $code = Account::where('code', $request->code)->first();

            if ($request->key == 'search') {

                return response()->json($code);
            }
            if ($request->key == 'add') {

                $data = Lab::create([
                    'account_id' => auth('lab')->user()->id,
                    'lab_name'  => $code->name,
                    'phone'     => $code->phone,
                    'image'   => $code->src,
                    'address' => $code->address,
                ]);

                return response()->json($data);
            }
        }
    }




    public function show($id)
    {
        $lab = Lab::find( $id);
        if($lab)
        return response()->json(['data'=>$lab], 200);
        return response()->json(['error','lab id invalid'], 422);
    }

  
    public function update(LabRequest $request)
    {
        $lab = Lab::find($request->id);
        if($lab){
            $lab->update([
                'lab_name'=>$request->lab_name,
                'phone'=>$request->phone,
                'address'=>$request->address,
            ]);

        return response()->json($lab, 200);
    }
    return response()->json(['error','lab id invalid'], 422);
    }

   
    public function destroy(LabRequest $request)
    {
        $ids = explode(',', $request->lab_ids);

        return  Lab::whereIn('id', $ids)->delete();
    }
}
