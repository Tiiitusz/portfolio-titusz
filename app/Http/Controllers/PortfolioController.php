<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class PortfolioController extends Controller
{
    public function index(){
        $FeaturedProjects = Project::where('is_featured', true)->get();
        $NonFeaturedProjects = Project::where('is_featured', false)->get();
        $projects = $FeaturedProjects->merge($NonFeaturedProjects);
        return view('home', compact('projects'));
    }

    public function show($id){
        $project = Project::findOrFail($id);
        return view('show', compact('project'));
    }

}
