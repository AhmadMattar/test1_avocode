<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('general.add_role')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mr-2" id="add">
                    @csrf
                    <div class="form-group">
                        <Label for="name">{{ __('general.name') }}</Label>
                        <input type="text" name="name" class="form-control">
                        <span class="text-danger error-text" id="name_error"></span>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="permission_id">{{__('general.select_permissions')}}</label>
                                <select name="permission_id[]" id="permission_id" class="form-control" multiple>
                                    <option value="">---</option>
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->id }}">
                                            {{ $permission->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text" id="permission_id_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">{{ __('general.Add') }}</button>
                        <button type="button" class="btn btn-danger" id="cancelAdd" data-bs-dismiss="modal">{{ __('general.Close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
