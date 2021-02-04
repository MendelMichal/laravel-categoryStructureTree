<ul>
    @foreach($subcategories as $subcategory)
        <li>
            <i class="fa"></i> {{ $subcategory->name }}
            @if(count($subcategory->subcategories))
                @include('partials.subcategoriesView', ['subcategories' => $subcategory->subcategories])
            @endif
        </li>
    @endforeach
</ul>
