<?php

namespace App\Http\Controllers\LabAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\BillRequest;
use App\Http\Requests\PaysRequest;
use App\Models\Bills;
use App\Models\Pay;
use App\Models\Supplier;
use Illuminate\Http\Request;

class billController extends Controller
{
    public function addBillToOffice(BillRequest $request)
    {
        $bill = $request->validated();

        $data = Bills::create($bill);

        return response()->json($data);
    }

    public function get_bills()
    {
        $bills = Supplier::with('bills')->where('account_id', auth('lab')->user()->id)->wherehas('bills')->orderBy('id', 'desc')->paginate(10);

        return response()->json($bills);
    }

    public function addPay(PaysRequest $request)
    {
        $pay = Pay::where('bills_id', $request->bills_id)->exists();

        if (!$pay) {

            $total_ID = Bills::find($request->bills_id)->total_ID;

            $total_dolar = Bills::find($request->bills_id)->total_dolar;
            if ($request->Amount_ID <= $total_ID && $request->Amount_dolar <= $total_dolar) {
                $data['bills_id'] = $request->bills_id;
                $data['Amount_$_before_payment'] = $total_dolar;
                $data['Amount_ID_before_payment'] = $total_ID;
                $data['Amount_$_after_payment'] = $total_dolar - $request->Amount_dolar;
                $data['Amount_ID_after_payment'] = $total_ID - $request->Amount_ID;
                $stored = Pay::create($data);
                return response()->json($stored);
            } else {
                return response()->json('invalid operation', 422);
            }
        } else {
            $last_pay = Pay::where('bills_id', $request->bills_id)->latest()->first();
            $last_dolar = Pay::where('id', $last_pay->id)->latest()->value('Amount_$_after_payment');
            $last_ID = Pay::where('id', $last_pay->id)->latest()->value('Amount_ID_after_payment');
            if ($request->Amount_ID <= $last_ID && $request->Amount_dolar <= $last_dolar) {

                $data['bills_id'] = $request->bills_id;
                $data['Amount_$_before_payment'] = $last_dolar;
                $data['Amount_ID_before_payment'] = $last_ID;
                $data['Amount_$_after_payment'] = $last_dolar - $request->Amount_dolar;
                $data['Amount_ID_after_payment'] = $last_ID - $request->Amount_ID;
                $stored = Pay::create($data);
                return response()->json($stored);
            } else {
                return response()->json('invalid operation', 422);
            }
        }
    }

    public function get_pays()
    {

        $bills = Supplier::where('account_id', auth('lab')->user()->id)->with('bills_pays')->paginate(10);

        return response()->json($bills);
    }

    public function deleteBill(BillRequest $request)
    {

        $ids = explode(',', $request->bills_ids);
        return  Bills::whereIn('id', $ids)->delete();
    }

    public function deletePay(PaysRequest $request)
    {
        $ids = explode(',', $request->pays_ids);

        return  Pay::whereIn('id', $ids)->delete();
    }
}
