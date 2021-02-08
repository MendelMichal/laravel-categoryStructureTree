<h3>{{ __('Add a New Category') }}</h3>

<form method="POST" action="{{ route('addCategories') }}">

    @csrf

    @if ($message = session('add-success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <!--       Select the name of the new category       -->
    <div class="form-group @if($errors->add->first('name')) has-error @endif">
        <label for="name">{{ __('Name') }}</label>
        <input type="text" name="name" class="form-control"/>

        @if($errorMsg = $errors->add->first('name'))
            <div class="alert alert-danger" role="alert">{{ $errorMsg }}</div>
        @endif
    </div>

    <!--       Choosing overcategory       -->
    <div class="form-group @if($errors->add->first('parent_id')) has-error @endif">
        <label for="parent_id">{{ __('Category') }}:</label>
        <select class="form-control" name="parent_id" id="addParentId">
            <option value="0">{{ __('Main') }}</option>
            @foreach ($allCategories as $id => $category)
                <option value="{{ $id }}" {{ old('parent_id') ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        @if($errorMsg = $errors->add->first('parent_id'))
            <div class="alert alert-danger" role="alert">{{ $errorMsg }}</div>
        @endif
    </div>


    <div class="form-group">
        <button class="btn btn-success">{{ __('Add New') }}</button>
    </div>

</form>
