<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Category;

class NavbarComposer
{
    public function compose(View $view)
    {
        $countriesWithCategories = Category::all()
            ->groupBy('country')
            ->map(function ($categories) {
                return $categories->sortBy('name');
            });

        $view->with('countriesWithCategories', $countriesWithCategories);
    }
}