<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('general.Edit_district') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mr-2" id="edit">
                    @method('PUT')
                    @csrf
                    <input type="hidden" class="form-control" name="id" id="coupouns_id" value="">

                    <div class="form-group">
                        <label for="code"> {{ __('general.code') }} </label>
                        <input type="text" id="edit_code" name="code" class="form-control" value="">
                        <span class="text-danger error-text" id="code_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="type">{{__('general.type')}}</label>
                        <select name="type" class="form-control" id="type">
                            <option value="fixed" >{{__('general.fixed')}}</option>
                            <option value="percentage" >{{__('general.percentage')}}</option>
                        </select>
                        <span class="text-danger error-text" id="type_error"></span>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="value">{{__('general.value')}}</label>
                            <input type="text" name="value" class="form-control" id="value">
                            <span class="text-danger error-text" id="value_error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">{{ __('general.Status') }}</label>
                        <select name="status" class="form-control" id="status">
                            <option value="1" {{ old('status') == 1 ? 'selected' : null }}>
                                {{ __('general.Active') }}</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : null }}>
                                {{ __('general.Disactive') }}</option>
                        </select>
                    </div>

                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">{{ __('general.Edit') }}</button>
                        <button type="button" class="btn btn-danger" id="cancelEdit" data-bs-dismiss="modal">{{ __('general.Close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
