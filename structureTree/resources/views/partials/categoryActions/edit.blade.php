<h3>{{ __('Edit Category') }}</h3>

<form method="POST" action="{{ route('editCategories') }}">

    @csrf

    @if ($message = session('edit-success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <!--       Selecting a category to edit       -->
    <div class="form-group @if($errors->edit->first('id')) has-error @endif">
        <label for="id">{{ __('Category being edited') }}:</label>
        <select class="form-control" name="id" id="editedCategoryId">
            @foreach ($allCategories as $id => $category)
                <option value="{{ $id }}" {{ old('id') ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        @if($errorMsg = $errors->edit->first('id'))
            <div class="alert alert-danger" role="alert">{{ $errorMsg }}</div>
        @endif
    </div>

    <!--       Renaming the edited category       -->
    <div class="form-group @if($errors->edit->first('name')) has-error @endif">
        <label for="name">{{ __('Name') }}</label>
        <input type="text" name="name" class="form-control" id="editedCategoryName" {{ old('name') ?:''}}/>

        @if($errorMsg = $errors->edit->first('name'))
            <div class="alert alert-danger" role="alert">{{ $errorMsg }}</div>
        @endif
    </div>

    <!--       Changing category       -->
    <div class="form-group @if($errors->edit->first('parent_id'))has-error @endif">
        <label for="parent_id">{{ __('Category') }}:</label>
        <select class="form-control" name="parent_id" id="editedCategoryParent">
            <option value="0">{{ __('Main') }}</option>
            @foreach ($allCategories as $id => $category)
                <option value="{{ $id }}" {{ old('parent_id') ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        @if($errorMsg = $errors->edit->first('parent_id'))
            <div class="alert alert-danger" role="alert">{{ $errorMsg }}</div>
        @endif
    </div>

    <!--       Node Index       -->
    <div class="form-group @if($errors->edit->first('node_index'))has-error @endif">
        <label for="parent_id">{{ __('Node in position of') }}:</label>
        <select class="form-control" name="node_index" id="editedCategoryIndex">
            <option value="0">{{ __('First') }}</option>
        </select>

        @if($errorMsg = $errors->edit->first('node_index'))
            <div class="alert alert-danger" role="alert">{{ $errorMsg }}</div>
        @endif
    </div>

    <input type="hidden" id="prevSelectedCategory" value=""/>

    <div class="form-group">
        <button class="btn btn-success">{{ __('Edit') }}</button>
    </div>

</form>
