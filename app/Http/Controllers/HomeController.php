<?php

namespace App\Http\Controllers;

use App\Models\AppliedJob;
use App\Models\Blog;
use App\Models\Career;
use App\Models\Query;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get counts for dashboard metrics
        $data = [
            'totalCareers' => Career::count(),
            'totalAppliedJobs' => AppliedJob::count(),
            'totalBlogs' => Blog::count(),
            'totalQueries' => Query::count(),
        ];

        return view('dashboard', $data);
    }
}
