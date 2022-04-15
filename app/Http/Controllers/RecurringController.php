<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recurring;

class RecurringController extends Controller
{
  public function getAll()
  {
    $data = Recurring::all();
    $respond = [
      "status" => 201,
      "message" => "Successfully get all Transactions",
      "data" => $data
    ];
    return response($respond, $respond["status"]);
  }
  
  public function delete($id)
  {
    $recurring = Recurring::find($id);
    if (isset($recurring)) {
      Recurring::find($id)->delete();
      $recurring = Recurring::all();
      $respond = [
        "status" => 201,
        "message" => "Successfully deleted",
        "data" => $recurring
      ];
    } else {
      $respond = [
        "status" => 404,
        "message" => "id " . $id . " does not exist",
        "data" => $recurring
      ];
    }
    return response($respond, $respond["status"]);
  }
}
