<div class="card-body">

    @error('generalError')
    <div class="alert alert-danger" role="alert">
        {{ $message }}
    </div>
    @enderror

    <div id="loader" class="loader"></div>
    <h3 class="categoryTitle">{{ __('Category List') }}</h3>

    <button id="expandTree" type="button" class="btn btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off">
        <i class="fa fa-angle-down"> {{ __('Expand') }}</i>
    </button>

    <ul id="categoryTree">
        @foreach($mainCategories as $category)
            <li id="{{ $category->id }}" data-index="{{ $category->node_index }}">
                <i class="fa"></i> {{ $category->name }}
                @if(count($category->subcategories))
                    @include('partials.categoryActions.subcategoriesView',['subcategories' => $category->subcategories])
                @endif
            </li>
        @endforeach
    </ul>
</div>
