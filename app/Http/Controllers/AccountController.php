<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

use function Pest\Laravel\json;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class AccountController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new middleware('permission:view accounts', only: ['index']),
            // new middleware('permission:view permissions', only: ['rolePermission']),
            // new middleware('permission:update permissions', only: ['updatePermission']),
            // new middleware('permission:delete permissions', only: ['destroy']),
            // new middleware('permission:create permissions', only: ['create','store']),
        ];
    }
    public function index()
    {
        $accounts = Account::all();
        return view('account.index', compact('accounts'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'from' => ['required'],
            'to' => ['required'],
        ]);

        $account = Account::where('cheque_number', $request->from)->first();

        if ($account) {
            if ($account->bank_name === $request->bank_name) {
                return response()->json(['error' => 'An Bank with this cheque number already exists']);
            }
        }

        for ($i = $request->from; $i <= $request->to; $i++) {
            Account::create([
                'cheque_number' => $i,
                'bank_name' => $request->bank_name ?? "",
                'amount' => $request->amount ?? 0,
                'status' => $request->status ?? 0,
                'remark' => $request->remark ?? "",
            ]);
        }

        $accounts = Account::orderBy('id', 'asc')->get();
        $view = view('account.table', compact('accounts'))->render();
        return response()->json(['accounts' => $view]);
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        if (!$account) {
            return response()->json(['error' => 'Account not found']);
        }
        return response()->json(['account' => $account]);
    }


    public function update(Request $request, $id)
    {

        $account = Account::findOrFail($id);

        if (!$account) {
            return response()->json(['error' => 'Account Not Found']);
        }

        $bank_name = $request->bank_name;
        $amount = $request->amount;
        $cheque_number = $request->cheque_number;

        // same cheque number but different bank name
        if ($account->cheque_number === $cheque_number) {
            if ($account->bank_name !== $bank_name) {
                $account_exists = Account::where('cheque_number', $cheque_number)->get();
                if (count($account_exists) > 1) {
                    return response()->json(['error' => 'An Bank with this cheque number already exists']);
                } else {
                    $account->bank_name = $bank_name;
                }
            }
        } else {
            // cheque number doesn't match but bank name same
            if ($account->bank_name === $bank_name) {
                $account_exists = Account::where('cheque_number', $cheque_number)
                    ->where('bank_name', $bank_name)->first();
                if ($account_exists) {
                    return response()->json(['error' => 'An Bank with this cheque number already exists']);
                } else {
                    $account->cheque_number = $cheque_number;
                }
            } else {
                // do not same cheque number and bank name
                $account_exists = Account::where('cheque_number', $cheque_number)
                    ->where('bank_name', $bank_name)->first();
                if ($account_exists) {
                    return response()->json(['error' => 'An Bank with this cheque number already exists']);
                } else {
                    $account->cheque_number = $cheque_number;
                    $account->bank_name = $bank_name;
                }
            }
        }

        $account->amount = $amount;
        $account->remark = $request->remark;
        $account->save();
        $accounts = Account::orderBy('id', 'asc')->get();
        $view = view('account.table', compact('accounts'))->render();
        return response()->json(['accounts' => $view]);
    }


    public function destory($id)
    {
        $account = Account::findOrFail($id);
        if (!$account) {
            return response()->json(['error' => 'Account Not Found']);
        }
        $account->delete();
        $accounts = Account::orderBy('id', 'asc')->get();
        $view = view('account.table', compact('accounts'))->render();
        return response()->json(['accounts' => $view]);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $accounts = Account::where('cheque_number', 'like', '%' . $search . '%')
            ->orWhere('bank_name', 'like', '%' . $search . '%')
            ->orWhere('amount', 'like', '%' . $search . '%')
            ->get();
        $view = view('account.table', compact('accounts'))->render();
        return response()->json(['accounts' => $view]);
    }

    public function updateStatus(Request $request, $id)
    {
        // return response()->json($request);
        $request->validate([
            'status' => 'required|integer'
        ]);

        $account = Account::findOrFail($id);
        if (!$account) {
            return response()->json(['error' => 'Account not found']);
        }

        $account->status = $request->status;
        $account->update();

        $accounts = Account::all();
        $view = view('account.table', compact('accounts'))->render();
        return response()->json(['accounts' => $view]);
    }
}
