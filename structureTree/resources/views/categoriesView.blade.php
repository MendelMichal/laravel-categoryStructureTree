@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="@auth col-md-8 @endauth @guest col-md-12 @endguest">

                <div class="card">
                    <div class="card-header">{{ __('Category Tree View') }}</div>

                    @include('partials.categoryActions.list', ['mainCategories' => $mainCategories])

                </div>

                <div class="card categoryDeleteDiv">
                    <div class="card-header">{{ __('Category Delete') }}</div>

                    <div class="card-body">
                        @include('partials.categoryActions.delete', ['allCategories' => $allCategories])
                    </div>
                </div>

            </div>


            @auth
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">{{ __('Category Actions') }}</div>

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
