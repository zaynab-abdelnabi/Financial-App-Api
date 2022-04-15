<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Recurring;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function getAll()
    {
        $data = Transaction::where('date', '<=', now())->orderBy('date', 'desc')->get();
        foreach ($data as $each) {
            $each->category;
            $each->recurring;
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get all Transactions",
            "data" => $data
        ];
        return response($respond, $respond["status"]);
    }

    public function getAllIncome()
    {
        $data = Transaction::whereRelation("category", "type", 'income')->where('date', '<=', now())->orderBy('date', 'desc')->get();

        // $data = Transaction::all();
        foreach ($data as $each) {
            $each->category;
            $each->recurring;
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get all Transactions",
            "data" => $data
        ];
        return response($respond, $respond["status"]);
    }

    public function getAllExpense()
    {
        $data = Transaction::whereRelation("category", "type", 'expense')->where('date', '<=', now())->orderBy('date', 'desc')->get();

        // $data = Transaction::all();
        foreach ($data as $each) {
            $each->category;
            $each->recurring;
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get all Transactions",
            "data" => $data
        ];
        return response($respond, $respond["status"]);
    }

    public function getByDate()
    {
        $data = Transaction::where('date', '<=', now())->orderBy('date', 'desc')->get();
        foreach ($data as $each) {
            $each->category;
            $each->recurring;
        }
        $respond = [
            "status" => 201,
            "message" => "Successfully get all Transactions",
            "data" => $data
        ];
        return response($respond, $respond["status"]);
    }

    public function getPaginationAll()
    {
        $data = Transaction::where('date', '<=', now())->orderBy('date', 'desc')->paginate(10);

        foreach ($data as $each) {
            $each->category;
            $each->recurring;
        }
        $respond = [
            "status" => 201,
            "message" => "Successfully get all Transactions",
            "data" => $data
        ];
        return response($respond, $respond["status"]);
    }

    public function getPaginationincome()
    {

        $data = Transaction::whereRelation("category", "type", 'income')->where('date', '<=', now())->orderBy('date', 'desc')->paginate(10);

        foreach ($data as $each) {
            $each->category;
            $each->recurring;
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get all Transactions",
            "data" => $data
        ];
        return response($respond, $respond["status"]);
    }

    public function getPaginationExpenses()
    {
        $data = Transaction::whereRelation("category", "type", 'expense')->where('date', '<=', now())->orderBy('date', 'desc')->paginate(10);

        foreach ($data as $each) {
            $each->category;
            $each->recurring;
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get all Transactions",
            "data" => $data
        ];
        return response($respond, $respond["status"]);
    }

    public function getLatestTransactions()
    {
        $data = Transaction::where('date', '<=', now())->orderBy('date', 'desc')->paginate(5);

        foreach ($data as $each) {
            $each->category;
            $each->recurring;
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get latest transactions",
            "data" => $data
        ];
        return response($respond, $respond["status"]);
    }

    public function getIncome()
    {
        $dataDollar = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->sum('amount');

        $dataLira = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->sum('amount');

        $respond = [
            "status" => 201,
            "message" => "Successfully get incomes amount",
            "data" => $dataDollar + $dataLira / 1500
        ];
        return response($respond, $respond["status"]);
    }

    public function getExpense()
    {
        $dataDollar = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->sum('amount');

        $dataLira = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->sum('amount');

        $respond = [
            "status" => 201,
            "message" => "Successfully get expenses amount",
            "data" => $dataDollar + $dataLira / 1500
        ];
        return response($respond, $respond["status"]);
    }

    public function getById($id)
    {
        $transaction = Transaction::find($id);
        $transaction->category;
        $transaction->recurring;

        if (isset($transaction)) {
            $respond = [
                "status" => 201,
                "message" => "Successfully get transaction with id " . $id,
                "data" => $transaction
            ];
        } else {
            $respond = [
                "status" => 404,
                "message" => "id " . $id . " does not exist",
                "data" => $transaction
            ];
        }

        return response($respond, $respond["status"]);
    }

    public function getRecurring($id)
    {
        $transaction = Transaction::where('recurring_id', $id)->where('date', '<=', now())->orderBy('date', 'desc')->get();
        foreach ($transaction as $each) {
            $each->category;
            $each->recurring;
        }

        if (isset($transaction)) {
            $respond = [
                "status" => 201,
                "message" => "Successfully get transaction with id " . $id,
                "data" => $transaction
            ];
        } else {
            $respond = [
                "status" => 404,
                "message" => "id " . $id . " does not exist",
                "data" => $transaction
            ];
        }

        return response($respond, $respond["status"]);
    }

    public function getMonthly(Request $request)
    {

        for ($i = 11; $i >= 0; $i--) {
            $month = date("Y-m-d", strtotime(date('Y-m-01') . " -" . $i - $request->query('range') * 12 . " months"));
            $date = Carbon::createFromFormat('Y-m-d', $month);

            $income = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $incomeLira = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');

            $expense = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $expenseLira = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');

            $incomedata[] = [
                "date" => $date->format('M Y'),
                "amount" => $income + $incomeLira / 1500,
            ];

            $expensedata[] = [
                "date" => $date->format('M Y'),
                "amount" => $expense + $expenseLira / 1500,
            ];
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully records of last 12 months",
            "data" => [$incomedata, $expensedata]
        ];

        return $respond;
    }

    public function getMonthlyMobile(Request $request)
    {

        for ($i = 5; $i >= 0; $i--) {
            $month = date("Y-m-d", strtotime(date('Y-m-01') . " -" . $i - $request->query('range') * 6 . " months"));
            $date = Carbon::createFromFormat('Y-m-d', $month);

            $income = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $incomeLira = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');

            $expense = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $expenseLira = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');

            $data[] = [
                "date" => $date->format('M Y'),
                "income" => $income + $incomeLira / 1500,
                "expense" => $expense + $expenseLira / 1500,
            ];
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully records of last 12 months",
            "data" => $data
        ];

        return $respond;
    }

    public function getWeekly(Request $request)
    {
        for ($i = 6; $i >= 0; $i--) {
            $day = date("Y-m-d", strtotime(date('Y-m-d') . " -" . $i - $request->query('range') * 7 . " days"));

            $date = Carbon::createFromFormat('Y-m-d', $day);

            $income = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $incomeLira = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');


            $expense = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $expenseLira = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');

            $incomedata[] = [
                "date" => $date->format('D d M Y'),
                "amount" => $income + $incomeLira / 1500,
            ];

            $expensedata[] = [
                "date" => $date->format('D d M Y'),
                "amount" => $expense + $expenseLira / 1500,
            ];
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get records of last 7 days",
            "data" => [$incomedata, $expensedata]
        ];

        return $respond;
    }
    public function getWeeklyMobile(Request $request)
    {
        for ($i = 6; $i >= 0; $i--) {
            $day = date("Y-m-d", strtotime(date('Y-m-d') . " -" . $i - $request->query('range') * 7 . " days"));

            $date = Carbon::createFromFormat('Y-m-d', $day);

            $income = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $incomeLira = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');


            $expense = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $expenseLira = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');

            $data[] = [
                "date" => $date->format('d M Y'),
                "income" => $income + $incomeLira / 1500,
                "expense" => $expense + $expenseLira / 1500,
            ];
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get records of last 7 days",
            "data" => $data
        ];

        return $respond;
    }

    public function getYearly(Request $request)
    {
        for ($i = 4; $i >= 0; $i--) {
            $day = date("Y-m-d", strtotime(date('Y-m-d') . " -" . $i - $request->query('range') * 5 . " years"));

            $date = Carbon::createFromFormat('Y-m-d', $day);

            $income = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereYear('date', $date->year)->sum('amount');
            $incomeLira = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->sum('amount');

            $expense = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereYear('date', $date->year)->sum('amount');
            $expenseLira = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->sum('amount');

            $incomedata[] = [
                "date" => $date->format('Y'),
                "amount" => $income + $incomeLira / 1500,
            ];

            $expensedata[] = [
                "date" => $date->format('Y'),
                "amount" => $expense + $expenseLira / 1500,
            ];
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get records of last 5 years",
            "data" => [$incomedata, $expensedata]
        ];

        return $respond;
    }

    public function getYearlyMobile(Request $request)
    {
        for ($i = 4; $i >= 0; $i--) {
            $day = date("Y-m-d", strtotime(date('Y-m-d') . " -" . $i - $request->query('range') * 5 . " years"));

            $date = Carbon::createFromFormat('Y-m-d', $day);

            $income = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereYear('date', $date->year)->sum('amount');
            $incomeLira = Transaction::whereRelation("category", "type", 'income')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->sum('amount');

            $expense = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereYear('date', $date->year)->sum('amount');
            $expenseLira = Transaction::whereRelation("category", "type", 'expense')->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->sum('amount');

            $data[] = [
                "date" => $date->format('Y'),
                "income" => $income + $incomeLira / 1500,
                "expense" => $expense + $expenseLira / 1500,
            ];
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get records of last 5 years",
            "data" => $data
        ];

        return $respond;
    }

    public function getYearCategoryRecords(Request $request)
    {
        $year = date("Y-m-d", strtotime(date('Y-m-d') . " +" . $request->query('range') . " years"));

        $date = Carbon::createFromFormat('Y-m-d', $year);


        $incomeCategories = Category::where('type', '=', 'income')->get();

        foreach ($incomeCategories as $incomeCategory) {
            $data = Transaction::whereRelation("category", "name", $incomeCategory->name)->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereYear('date', $date->year)->sum('amount');
            $dataLira = Transaction::whereRelation("category", "name", $incomeCategory->name)->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereYear('date', $date->year)->sum('amount');

            $incomedata[] = [
                "category" => $incomeCategory->name,
                "amount" => $data + $dataLira / 1500,
            ];
        }

        $expenseCategories = Category::where('type', '=', 'expense')->get();

        foreach ($expenseCategories as $expenseCategory) {
            $data = Transaction::whereRelation("category", "name", $expenseCategory->name)->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereYear('date', $date->year)->sum('amount');
            $dataLira = Transaction::whereRelation("category", "name", $expenseCategory->name)->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereYear('date', $date->year)->sum('amount');
            $expensedata[] = [
                "category" => $expenseCategory->name,
                "amount" => $data + $dataLira / 1500,
            ];
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get records of category",
            "date" => $date->year,
            "data" => [$incomedata, $expensedata]
        ];

        return $respond;
    }

    public function getMonthCategoryRecords(Request $request)
    {
        $year = date("Y-m-d", strtotime(date('Y-m-d') . " +" . $request->query('range') . " months"));

        $date = Carbon::createFromFormat('Y-m-d', $year);


        $incomeCategories = Category::where('type', '=', 'income')->get();

        foreach ($incomeCategories as $incomeCategory) {
            $data = Transaction::whereRelation("category", "name", $incomeCategory->name)->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)->whereYear('date', $date->year)->sum('amount');
            $dataLira = Transaction::whereRelation("category", "name", $incomeCategory->name)->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)->whereYear('date', $date->year)->sum('amount');

            $incomedata[] = [
                "category" => $incomeCategory->name,
                "amount" => $data + $dataLira / 1500,
            ];
        }

        $expenseCategories = Category::where('type', '=', 'expense')->get();

        foreach ($expenseCategories as $expenseCategory) {
            $data = Transaction::whereRelation("category", "name", $expenseCategory->name)->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)->whereYear('date', $date->year)->sum('amount');
            $dataLira = Transaction::whereRelation("category", "name", $expenseCategory->name)->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereMonth('date', $date->month)->whereYear('date', $date->year)->sum('amount');
            $expensedata[] = [
                "category" => $expenseCategory->name,
                "amount" => $data + $dataLira / 1500,
            ];
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get records of category",
            "date" => $date->format('M Y'),
            "data" => [$incomedata, $expensedata]
        ];

        return $respond;
    }

    public function getDayCategoryRecords(Request $request)
    {
        $year = date("Y-m-d", strtotime(date('Y-m-d') . " +" . $request->query('range') . " days"));

        $date = Carbon::createFromFormat('Y-m-d', $year);


        $incomeCategories = Category::where('type', '=', 'income')->get();

        foreach ($incomeCategories as $incomeCategory) {
            $data = Transaction::whereRelation("category", "name", $incomeCategory->name)->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)->whereYear('date', $date->year)->sum('amount');
            $dataLira = Transaction::whereRelation("category", "name", $incomeCategory->name)->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)->whereYear('date', $date->year)->sum('amount');
            $incomedata[] = [
                "category" => $incomeCategory->name,
                "amount" => $data + $dataLira / 1500,
            ];
        }

        $expenseCategories = Category::where('type', '=', 'expense')->get();

        foreach ($expenseCategories as $expenseCategory) {
            $data = Transaction::whereRelation("category", "name", $expenseCategory->name)->where('currency', '=', '$')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)->whereYear('date', $date->year)->sum('amount');
            $dataLira = Transaction::whereRelation("category", "name", $expenseCategory->name)->where('currency', '=', 'L.L.')->where('date', '<=', now())->orderBy('date', 'desc')->whereDay('date', $date->day)->whereMonth('date', $date->month)->whereYear('date', $date->year)->sum('amount');
            $expensedata[] = [
                "category" => $expenseCategory->name,
                "amount" => $data + $dataLira / 1500,
            ];
        }

        $respond = [
            "status" => 201,
            "message" => "Successfully get records of category",
            "date" => $date->format('D d M Y'),
            "data" => [$incomedata, $expensedata]
        ];

        return $respond;
    }

    public function createFixed(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => "required|string",
            'description' => "required|string",
            'category_id' => "required|numeric|min:1",
            'amount' => "required|numeric|min:1",
            'currency' =>  "required|in:USD,$,L.L.",
            'date' => 'required|date|before:tomorrow',
        ]);

        if ($validator->fails()) {
            $respond = [
                "status" => 401,
                "message" => $validator->errors()->first(),
                "data" => null
            ];
        } else {
            $transaction = new Transaction;
            $transaction->title = $request->title;
            $transaction->description = $request->description;
            $transaction->category_id = $request->category_id;
            $transaction->amount = $request->amount;
            $transaction->currency = $request->currency;
            $transaction->date = $request->date;
            $transaction->recurring_id = null;
            $transaction->save();

            $respond = [
                "status" => 201,
                "message" => "successfully added",
                "data" => $transaction
            ];
        }
        return response($respond);
    }

    public function createRecurring(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => "required|string",
            'description' => "required|string",
            'category_id' => "required|numeric|min:1",
            'amount' => "required|numeric|min:1",
            'currency' =>  "required|in:USD,$,L.L.",
            'start_date' => 'required|date|before:tomorrow',
            'end_date' => 'required|date|after:today',
            'duration' => 'required|numeric|min:1',
            'interval' => 'required|in:days,weeks,months,years',
        ]);

        if ($validator->fails()) {
            $respond = [
                "status" => 401,
                "message" => $validator->errors()->first(),
                "data" => null
            ];
        } else {
            $duration = $request->duration . " " . $request->interval;

            $recurring = new Recurring;
            $recurring->name = $request->title;
            $recurring->start_date = $request->start_date;
            $recurring->end_date = $request->end_date;
            $recurring->duration = $duration;

            $recurring->save();

            $date = $request->start_date;
            $dates = [$date];

            while ($date < $request->end_date) {
                $date = date_create($date);
                date_add($date, date_interval_create_from_date_string($duration));
                $date = date_format($date, 'Y-m-d');
                array_push($dates, $date);
            }

            foreach ($dates as $date) {
                $transaction = new Transaction;
                $transaction->title = $request->title;
                $transaction->description = $request->description;
                $transaction->category_id = $request->category_id;
                $transaction->amount = $request->amount;
                $transaction->currency = $request->currency;
                $transaction->date = $date;
                $transaction->recurring_id = $recurring->id;
                $transaction->save();
            }

            $transaction = Transaction::where('recurring_id', $recurring->id)->orderBy('date', 'desc')->get();
            foreach ($transaction as $each) {
                $each->category;
                $each->recurring;
            }

            $respond = [
                "status" => 201,
                "message" => "successfully added",
                "data" => $transaction
            ];
        }
        return response($respond);
    }

    public function updateFixed(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => "string",
            'description' => "string",
            'category_id' => "numeric|min:1",
            'amount' => "numeric|min:1",
            'currency' =>  "in:USD,$,L.L.",
            'date' => 'date|before:tomorrow',
        ]);

        if ($validator->fails()) {
            $respond = [
                "status" => 401,
                "message" => $validator->errors()->first(),
                "data" => null
            ];
        } else {
            $transaction = Transaction::find($id);
            if (isset($transaction)) {

                $transaction->title = $request->title ?? $transaction->title;
                $transaction->description = $request->description ?? $transaction->description;
                $transaction->category_id = $request->category_id ?? $transaction->category_id;
                $transaction->amount = $request->amount ?? $transaction->amount;
                $transaction->currency = $request->currency ?? $transaction->currency;
                $transaction->date = $request->date ?? $transaction->date;
                $transaction->save();

                $respond = [
                    "status" => 201,
                    "message" => "successfully updated",
                    "data" => $transaction
                ];
            } else {
                $respond = [
                    "status" => 404,
                    "message" => "id " . $id . " does not exist",
                    "data" => $transaction
                ];
            }
        }



        return response($respond);
    }

    public function updateRecurring(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => "string",
            'description' => "string",
            'category_id' => "numeric|min:1",
            'amount' => "numeric|min:1",
            'currency' =>  "in:USD,$,L.L.",
            // 'date' => 'date|before:tomorrow',
        ]);

        if ($validator->fails()) {
            $respond = [
                "status" => 401,
                "message" => $validator->errors()->first(),
                "data" => null
            ];
        } else {
            $transaction = Transaction::where('recurring_id', $id)->where('date', '>=', now())->orderBy('date', 'desc')->get();

            foreach ($transaction as $each) {
                $each->category;
                $each->recurring;
            }

            $recurring = Recurring::find($id);

            if ($recurring->start_date != $request->start_date || $recurring->end_date != $request->end_date || $recurring->duration != $request->duration) {
                $respond = [
                    'status' => 401,
                    "message" => "Can't update these information",
                    "date" => null
                ];
            }

            if (isset($transaction)) {
                foreach ($transaction as $each) {
                    $each->title = $request->title ?? $each->title;
                    $each->description = $request->description ?? $each->description;
                    $each->category_id = $request->category_id ?? $each->category_id;
                    $each->amount = $request->amount ?? $each->amount;
                    $each->currency = $request->currency ?? $each->currency;
                    $each->date = $each->date;
                    $each->save();
                }
                $respond = [
                    "status" => 201,
                    "message" => "successfully updated",
                    "data" => $transaction
                ];
            } else {
                $respond = [
                    "status" => 404,
                    "message" => "id " . $id . " does not exist",
                    "data" => $transaction
                ];
            }
        }


        return response($respond);
    }

    public function updateAllRecurring(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => "string",
            'description' => "string",
            'category_id' => "numeric|min:1",
            'amount' => "numeric|min:1",
            'currency' =>  "in:USD,$,L.L.",
            'start_date' => 'date|before:tomorrow',
            'end_date' => 'date|after:today',
            'duration' => 'numeric|min:1',
            'interval' => 'in:days,weeks,months,years',
        ]);

        if ($validator->fails()) {
            $respond = [
                "status" => 401,
                "message" => $validator->errors()->first(),
                "data" => null
            ];
        } else {

            $recurring = Recurring::find($id);

            $duration = explode(" ", $recurring->duration);

            if (($request->start_date && $recurring->start_date != $request->start_date) || ($request->end_date && $recurring->end_date != $request->end_date) || ($request->duration && $duration[0] != $request->duration) || ($request->interval && $duration[1] != $request->interval)) {

                $oldtransactions = Transaction::where('recurring_id', $id)->orderBy('date', 'desc')->get();

                Recurring::find($id)->delete();

                $newduration = ($request->duration ?? $duration[0]) . " " . ($request->interval ?? $duration[1]);

                $newrecurring = new Recurring;
                $newrecurring->name = $request->title ?? $recurring->name;
                $newrecurring->start_date = $request->start_date ?? $recurring->start_date;
                $newrecurring->end_date = $request->end_date ?? $recurring->end_date;
                $newrecurring->duration = $newduration;

                $newrecurring->save();

                $date = $request->start_date ?? $recurring->start_date;
                $dates = [$date];

                while ($date < ($request->end_date ?? $recurring->end_date)) {
                    $date = date_create($date);
                    date_add($date, date_interval_create_from_date_string($newduration));
                    $date = date_format($date, 'Y-m-d');
                    array_push($dates, $date);
                }



                foreach ($dates as $date) {
                    $transaction = new Transaction;
                    $transaction->title = $request->title ?? $oldtransactions[0]->title;
                    $transaction->description = $request->description ?? $oldtransactions[0]->description;
                    $transaction->category_id = $request->category_id ?? $oldtransactions[0]->category_id;
                    $transaction->amount = $request->amount ?? $oldtransactions[0]->amount;
                    $transaction->currency = $request->currency ?? $oldtransactions[0]->currency;
                    $transaction->date = $date;
                    $transaction->recurring_id = $newrecurring->id;
                    $transaction->save();
                }

                $transaction = Transaction::where('recurring_id', $newrecurring->id)->orderBy('date', 'desc')->get();
                foreach ($transaction as $each) {
                    $each->category;
                    $each->recurring;
                }

                $respond = [
                    "status" => 201,
                    "message" => "successfully updated",
                    "data" => $transaction
                ];
            } else {

                $transaction = Transaction::where('recurring_id', $id)->orderBy('date', 'desc')->get();
                foreach ($transaction as $each) {
                    $each->category;
                    $each->recurring;
                }

                if (isset($transaction)) {
                    foreach ($transaction as $each) {
                        $each->title = $request->title ?? $each->title;
                        $each->description = $request->description ?? $each->description;
                        $each->category_id = $request->category_id ?? $each->category_id;
                        $each->amount = $request->amount ?? $each->amount;
                        $each->currency = $request->currency ?? $each->currency;
                        $each->date = $each->date;
                        $each->save();
                    }
                    $respond = [
                        "status" => 201,
                        "message" => "successfully updated",
                        "data" => $transaction
                    ];
                } else {
                    $respond = [
                        "status" => 404,
                        "message" => "id " . $id . " does not exist",
                        "data" => $transaction
                    ];
                }
            }
        }

        return response($respond);
    }

    public function delete($id)
    {
        $transaction = Transaction::find($id);
        if (isset($transaction)) {
            Transaction::find($id)->delete();
            $transaction = Transaction::all();
            $respond = [
                "status" => 201,
                "message" => "Successfully deleted",
                "data" => $transaction
            ];
        } else {
            $respond = [
                "status" => 404,
                "message" => "id " . $id . " does not exist",
                "data" => $transaction
            ];
        }
        return response($respond, $respond["status"]);
    }

    public function deleteRecurring($id)
    {
        $transaction = Transaction::where('recurring_id', $id);
        if (isset($transaction)) {
            Transaction::find($id)->delete();
            $transaction = Transaction::all();
            $respond = [
                "status" => 201,
                "message" => "Successfully deleted",
                "data" => $transaction
            ];
        } else {
            $respond = [
                "status" => 404,
                "message" => "id " . $id . " does not exist",
                "data" => $transaction
            ];
        }
        return response($respond, $respond["status"]);
    }
}
