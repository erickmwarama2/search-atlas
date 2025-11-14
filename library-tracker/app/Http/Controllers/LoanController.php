<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function putExtend(Request $request, Loan $loan) {
        $days = $request->input('additional_days');

        if ($loan->due_date < time()) {
            if ($days > 0 && $days < 14) {
                $new_due_date = Date::fromString($loan->due_date)->add(new DateInterval($days));
                $loan->due_date = $new_due_date;
            }
        }

        $loan->save();

        return $loan;
    }

    public function getOverdueLoans() {
        return Loan::all()->where('due_date', '<', time());
    }
}
