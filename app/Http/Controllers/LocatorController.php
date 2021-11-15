<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocatorController extends Controller
{
  //
  public function getAreaData(Request $request)
  {
    try {
      $code = $request['code'];
      $downloadIndex = $request['downloadIndex'];
      $pageCount = 10000;
      $startNum = $downloadIndex * $pageCount;
      $query = "select * from `pc_" . strtolower($code) . "` LIMIT $startNum, $pageCount;";
      $postcodes = DB::select($query);
      return response()->json(['postcodes' => $postcodes], 200, [], JSON_NUMERIC_CHECK);
    } catch (\Exception $e) {
      return response()->json(['message' => 'download failed'], 401);
    }
  }
}
