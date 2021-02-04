@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="@auth col-md-8 @endauth @guest col-md-12 @endguest">
                <div class="card">
                    <div class="card-header">{{ __('Category Tree View') }}</div>

                    <div class="card-body">
                        <div id="loader" class="loader"></div>
                        <h3 class="categoryTitle">{{ __('Category List') }}</h3>

                        <button id="expandTree" type="button" class="btn btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off">
                            <i class="fa fa-angle-down"> {{ __('Expand') }}</i>
                        </button>

                        <ul id="categoryTree">
                            @foreach($mainCategories as $category)
                                <li>
                                    <i class="fa"></i> {{ $category->name }}
                                    @if(count($category->subcategories))
                                        @include('partials.subcategoriesView',['subcategories' => $category->subcategories])
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>

            @auth
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">{{ __('Category Tree View') }}</div>

                        <div class="card-body">
                            @include('partials.categoryActions.create', ['allCategories' => $allCategories])

                            <hr class="categoryActions">

                            @include('partials.categoryActions.edit', ['allCategories' => $allCategories])
                        </div>

                    </div>
                </div>
            @endauth

        </div>
    </div>
@endsection
