<ul>
    @foreach($subcategories as $subcategory)
        <li id="{{ $subcategory->id }}" data-index="{{ $subcategory->node_index }}">
            <i class="fa"></i> {{ $subcategory->name }}
            @if(count($subcategory->subcategories))
                @include('partials.categoryActions.subcategoriesView', ['subcategories' => $subcategory->subcategories])
            @endif
        </li>
    @endforeach
</ul>
