<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class AccountController extends Controller
{
    public function index(){
        $accounts = Account::all();
        return view('account.index', compact('accounts'));
    }

    public function store(Request $request){
        $request->validate([
            'from'=>['required'],
            'to'=>['required'],
        ]);
        $curentChequeNumber = 'cheque_no:' . $request->from;
        $account = Account::where('cheque_number', $curentChequeNumber)->first();
        if($account){
            return response()->json(['error'=>'Cheque number all ready exist']);
        }
        for ($i = $request->from; $i <= $request->to; $i++) {
            Account::create([
                'cheque_number' => 'cheque_no:'.$i,
                'bank_name' => $request->bank_name ?? "",
                'amount' => $request->amount ?? 0,
                'status' => $request->status ?? 0,
                'remark' => $request->remark ?? "",
            ]);
        }

        $accounts = Account::orderBy('id', 'asc')->get();
        $view = view('account.table', compact('accounts'))->render();
        return response()->json(['accounts'=> $view]);

    }

    public function edit($id){
        $account = Account::findOrFail($id);
        if(!$account){
            return response()->json(['error'=>'Account not found']);
        }
        return response()->json(['account'=>$account]);
    }


    public function update(Request $request){
        $request->validate([
            'cheque_number' => 'required'
        ]);
        $account = Account::findOrFail($request->id);
        if(!$account){
            return response()->json(['error'=> 'Account Not Found']);
        }
        $cheque_number = $request->cheque_number;
        $bank_name = $request->bank_name;
        $amount = $request->amount;

        $account->cheque_number = $cheque_number;
        if($bank_name){
            $account->bank_name = $bank_name;
        }
        if($amount){
            $account->amount = $amount;
        }
        $account->save();
        $accounts = Account::orderBy('id', 'asc')->get();
        $view = view('account.table', compact('accounts'))->render();
        return response()->json(['accounts'=>$view]);
    }


    public function destory($id) {
        $account = Account::findOrFail($id);
        if(!$account){
            return response()->json(['error'=>'Account Not Found']);
        }
        $account->delete();
        $accounts = Account::orderBy('id','asc')->get();
        $view = view('account.table', compact('accounts'))->render();
        return response()->json(['accounts'=> $view]);
    }

    public function search(Request $request){
        $search = $request->search;
        $accounts = Account::where('cheque_number','like','%'.$search.'%')
        ->orWhere('bank_name', 'like','%'.$search.'%')
        ->orWhere('amount','like','%'.$search.'%')
        ->get();
        $view = view('account.table', compact('accounts'))->render();
        return response()->json(['accounts'=>$view]);
    }
}
