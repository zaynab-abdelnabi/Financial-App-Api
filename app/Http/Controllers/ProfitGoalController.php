<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\ProfitGoal;

class ProfitGoalController extends Controller
{
    function getProfitGoal()
    {
        $ProfitGoal = ProfitGoal::all();
        $respond = [
            'status' => 200,
            'message' => 'successfully get profit goal',
            'data' => $ProfitGoal,
        ];
        return $respond;
    }

    function ListProfitGoal($id)
    {
        $ProfitGoal = ProfitGoal::find($id);
        if (isset($ProfitGoal)) {
            $respond = [
                'status' => 200,
                'message' => 'successfully get profit goal by id',
                'data' => $ProfitGoal,
            ];
            return $respond;
        } else {
            $error = [
                'satus' => 400,
                'message' => 'have no data',
                'data' => $ProfitGoal,
            ];
            return $error;
        }
    }

    function updateProfitGoal(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' => $validator->errors()->first(),
                'data' => null,
            ];
            return $respond;
        } else {

            $data = ProfitGoal::find($id);
            $data->amount = $request->amount;
            $data->save();
            // $data = ProfitGoal::all();

            $respond = [
                'status' => 200,
                'message' => 'profit goal edited successfully',
                'data' => $data,
            ];

            return $respond;
        }
    }
}
