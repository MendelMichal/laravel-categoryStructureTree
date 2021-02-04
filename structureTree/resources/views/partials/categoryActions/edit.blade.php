<h3>{{ __('Edit Category') }}</h3>

<form method="POST" action="{{ route('editCategories') }}">

    @csrf

    @if ($message = session('edit-success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    {{--        Select a category to edit       --}}
    <div class="form-group @if($errors->edit->first('id')) has-error @endif">
        <label for="id">{{ __('Edited Category') }}:</label>
        <select class="form-control" name="id">
            @foreach ($allCategories as $id => $category)
                <option value="{{ $id }}" {{ old('category_id') ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        @if($errorMsg = $errors->edit->first('id'))
            <span class="text-danger">{{ $errorMsg }}</span>
        @endif
    </div>

    {{--        Renaming the edited category       --}}
    <div class="form-group @if($errors->edit->first('name')) has-error @endif">
        <label for="name">{{ __('Name') }}</label>
        <input type="text" name="name" class="form-control"/>

        @if($errorMsg = $errors->edit->first('name'))
            <span class="text-danger">{{ $errorMsg }}</span>
        @endif
    </div>


    {{--        Change overcategory      --}}
    <div class="form-group @if($errors->edit->first('parent_id'))has-error @endif">
        <label for="parent_id">{{ __('Category') }}:</label>
        <select class="form-control" name="parent_id">
            <option value="0">{{ __('Main') }}</option>
            @foreach ($allCategories as $id => $category)
                <option value="{{ $id }}" {{ old('parent_id') ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        @if($errorMsg = $errors->edit->first('parent_id'))
            <span class="text-danger">{{ $errorMsg }}</span>
        @endif
    </div>


    <div class="form-group">
        <button class="btn btn-success">{{ __('Edit') }}</button>
    </div>

</form>

