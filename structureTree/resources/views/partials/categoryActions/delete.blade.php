<h3>{{ __('Delete Category') }}</h3>

<form method="POST" action="{{ route('deleteCategories') }}">

    @csrf

    @if ($message = session('delete-success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <!--       Selecting a category to delete       -->
    <div class="form-group @if($errors->delete->first('id')) has-error @endif">
        <label for="id">{{ __('Category to remove') }}:</label>
        <select class="form-control" name="id" id="deleteCategoryId">
            @foreach ($allCategories as $id => $category)
                <option value="{{ $id }}" {{ old('id') ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        @if($errorMsg = $errors->delete->first('id'))
            <div class="alert alert-danger" role="alert">{{ $errorMsg }}</div>
        @endif
    </div>

    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCategoryModal">
        {{ __('Delete') }}
    </button>

    <!--       'Are you sure' modal       -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategory" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategory">{{ __('Are you sure?') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('Are you sure that you want to delete the selected category?') }}

                    <div id="deleteHasSubcategories" style="display: none">
                        <div class="alert alert-danger" role="alert">
                            <b>{{ __('The selected category has subcategories') }}</b>
                            <br>
                            {{ __('Please confirm that you want to remove the entire branch') }}
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="confirmDelete" name="confirmDelete">
                            <label class="form-check-label" for="confirmDelete">{{ __('I understand and I still want to delete') }}</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

</form>
