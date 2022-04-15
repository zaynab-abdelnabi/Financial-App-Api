<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{

    //get all
    function listall()
    {
        $category = Category::all();
        $respond = [
            'status' => 200,
            'message' => 'get all categories successfully',
            'data' => $category,
        ];
        return $respond;
    }

    //get by id
    function listCategory($id)
    {
        $category = Category::find($id);

        if (isset($category)) {
            $respond = [
                'status' => 200,
                'message' => 'get category by id',
                'data' => $category,
            ];
            return $respond;
        } else {
            $error = [
                'satus' => 400,
                'message' => 'data not found',
                'data' => $category,
            ];
            return $error;
        }
    }

    //create
    function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required|in:income,expense',
        ]);

        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' => $validator->errors()->first(),
                'data' => null,
            ];

            return $respond;
        } else {

            $category = new Category;
            $category->name = $request->name;
            $category->type = $request->type;
            $category->save();
            // $category = Category::all();
            $respond = [
                'status' => 200,
                'message' => 'category added successfully',
                'data' => $category,
            ];

            return $respond;
        }
    }

    //edit
    function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required|in:income,expense',
        ]);

        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' => $validator->errors()->first(),
                'data' => null,
            ];

            return $respond;
        }
        $data = Category::find($id);
        $data->name = $request->name;
        $data->type = $request->type;
        $data->save();

        $respond = [
            'status' => 200,
            'message' => 'category edited successfully',
            'data' => $data,
        ];

        return $respond;
    }

    //delete by id
    function deleteCategory($id)
    {
        $data = Category::find($id);


        if (isset($data)) {
            $data->delete();
            $respond = [
                'status' => 200,
                'message' => 'category deleted successfully',
                'data' => $data,
            ];
            return $respond;
        } else {
            $error = [
                'satus' => 400,
                'message' => 'id not found',
                'data' => $data,
            ];
            return $error;
        }
    }

    //get by name
    public function getbyname($name)
    {

        $category = Category::where('name', 'like', '%' . $name . '%')->orwhere('type', 'like', '%' . $name . '%')->get();
        // var_dump($category->all());
        if (!empty($category->all())) {

            $respond = [
                'status' => 200,
                'message' => 'get the name and type categories',
                'data' => $category,
            ];
            return $respond;
        } else {
            $error = [
                'satus' => 400,
                'message' => 'not have this data',
                'data' => $category,
            ];
            return $error;
        }
    }
}
