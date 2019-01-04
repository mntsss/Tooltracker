<?php

namespace App\Http\Controllers;

use App\Http\Services\StatisticService;

class StatisticsController extends Controller
{
  public function get(StatisticService $statisticService)
  {
    return response()->json($statisticService->get());
  }
}
