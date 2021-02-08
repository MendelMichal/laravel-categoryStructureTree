<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CategoryHelper;

/**
 * Class MainPageController
 * @author Michal Mendel <mendel.michal096@gmail.com>
 */
class MainPageController extends Controller
{

    /**
     * Show only category list
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('categoriesView', CategoryHelper::getList());
    }
}
