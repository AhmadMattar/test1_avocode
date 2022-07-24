<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('general.Edit_user')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mr-2" id="edit" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <input type="hidden" class="form-control" name="id" id="user_id">
                    <div class="form-group">
                        <Label for="name">{{ __('general.name') }}</Label>
                        <input type="text" name="name" id="name" class="form-control">
                        <span class="text-danger error-text" id="name_error2"></span>
                    </div>
                    <div class="form-group">
                        <Label for="email">{{ __('general.email') }}</Label>
                        <input type="email" name="email" id="email" class="form-control">
                        <span class="text-danger error-text" id="email_error2"></span>
                    </div>
                    <div class="form-group">
                        <label for="password">{{__('general.password')}}</label>
                        <input type="text" name="password" value="{{old('password')}}" class="form-control">
                        <span class="text-danger error-text" id="password_error2"></span>
                    </div>
                    <div class="row">
                            <div class="form-group">
                                <label for="edit_permission_id">{{__('general.select_permissions')}}</label>
                                <select name="permission_id[]" class="form-control" id="edit_permission_id" multiple>
                                    <option value="">---</option>
                                    <option value="1">@lang('permissions.admin_permission')</option>
                                    <option value="2">@lang('permissions.create_country')</option>
                                    <option value="3">@lang('permissions.edit_country')</option>
                                    <option value="4">@lang('permissions.delete_Country')</option>
                                    <option value="14">@lang('permissions.active_country')</option>
                                    <option value="15">@lang('permissions.disactive_country')</option>

                                    <option value="7">@lang('permissions.create_city')</option>
                                    <option value="6">@lang('permissions.edit_city')</option>
                                    <option value="8">@lang('permissions.delete_city')</option>
                                    <option value="12">@lang('permissions.active_city')</option>
                                    <option value="13">@lang('permissions.disactive_city')</option>

                                    <option value="9">@lang('permissions.create_district')</option>
                                    <option value="10">@lang('permissions.edit_district')</option>
                                    <option value="11">@lang('permissions.delete_district')</option>
                                    <option value="16">@lang('permissions.active_district')</option>
                                    <option value="17">@lang('permissions.disactive_district')</option>

                                    <option value="18">@lang('permissions.create_customer')</option>
                                    <option value="19">@lang('permissions.edit_customer')</option>
                                    <option value="20">@lang('permissions.delete_customer')</option>
                                    <option value="21">@lang('permissions.active_customer')</option>
                                    <option value="22">@lang('permissions.disactive_customer')</option>
                                </select>
                                <span class="text-danger error-text" id="permission_id_error2"></span>
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('general.Status')}}</label>
                        <select name="status" class="form-control" id="status">
                            <option value="1" {{old('status') == 1 ? 'selected' : null}}>{{__('general.Active')}}</option>
                            <option value="0" {{old('status') == 0 ? 'selected' : null}}>{{__('general.Disactive')}}</option>
                        </select>
                    </div>
                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">{{__('general.Edit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

