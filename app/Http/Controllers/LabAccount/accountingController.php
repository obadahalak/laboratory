<?php

namespace App\Http\Controllers\LabAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountingRequest;
use App\Http\Requests\AccountRequest;
use App\Models\Accounting;
use Illuminate\Http\Request;

class accountingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exports()
    {
        $account_id = Accounting::where('account_id' , auth()->user()->id)
        ->where('status' , 0)
        ->paginate(10);

        $response = [
            'message' => 'done',
            'data'    => $account_id
        ];
        return response()->json($response);
    }

    public function outcome()
    {
        $account_id = Accounting::where('account_id' , auth()->user()->id)
        ->where('status' , 1)
        ->paginate(10);

        $response = [
            'message' => 'done',
            'data'    => $account_id
        ];
        return response()->json($response);
    }


    public function store(AccountingRequest $request)
    {
        $data = $request->validated();
        $data['account_id'] = auth()->user()->id;

        $data_created = Accounting::create($data);

        $response = [
            'message' => 'added',
            'data'   => $data_created,
        ];

        return response()->json($response);
    }

    public function update(AccountingRequest $request, $id)
    {
        $record = Accounting::where('id' , $id)->first();
        $data['date'] = $request->date ;
        $data['day'] = $request->day ;
        $data['Payment_amount'] = $request->Payment_amount ;
        $data['Note'] = $request->Note ;

        $updated = $record->update($data);
        $response = [
            'message' => 'updated',
            'data'  => $updated,
        ];
        return response()->json($response);
    }

    public function destroy(AccountingRequest $request)
    {
        $ids = explode(',', $request->accounting_ids);

        return  Accounting::whereIn('id', $ids)->delete();
    }
}
