<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Print_;

class ListingController extends Controller
{
    // // Show all listings
    public function index() {
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }

    // public function searchDb(Request $request){
    //     $search = $request->search;
    //     $query = DB::table('listings')->where('title', 'like' , '%' . $search . '%')->get();
    //     echo "<pre>"; print_r($query);exit;
    // }

    //Show single listing
    public function show(Listing $listing) {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    // create a listing using form
    public function create(){
        return view('listings.create');
    }

    // store listing data
    public function store(Request $request){
        // dd($request->file('logo'));
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Listing::create($formFields);

        return redirect("/")->with('message', 'Listing created successfully');
    }
}
