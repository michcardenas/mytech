<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;

class LocationController extends Controller
{
    // Países
    public function countriesIndex()
    {
        $countries = Country::withCount('cities')->get();
        return view('admin.countries.index', compact('countries'));
    }

    public function countriesStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Country::create($request->only('name'));
        return back()->with('success', 'Country created successfully.');
    }

    public function countriesDestroy($id)
    {
        Country::findOrFail($id)->delete();
        return back()->with('success', 'Country deleted.');
    }

    // Ciudades
    public function citiesIndex()
    {
        $cities = City::with('country')->get();
        $countries = Country::all();
        return view('admin.cities.index', compact('cities', 'countries'));
    }

    public function citiesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);

        City::create($request->only('name', 'country_id'));
        return back()->with('success', 'City created successfully.');
    }

    public function citiesDestroy($id)
    {
        City::findOrFail($id)->delete();
        return back()->with('success', 'City deleted.');
    }
}
