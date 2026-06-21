<?php

namespace App\Http\Controllers;

use App\Models\Explore;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('q', '');
        $results = Explore::search($query);

        return view('explore.index', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
