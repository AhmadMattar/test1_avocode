<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModal">{{__('general.Add_user')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mr-2" id="add" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <Label for="name">{{ __('general.name') }}</Label>
                        <input type="text" name="name" class="form-control">
                        <span class="text-danger error-text" id="name_error"></span>
                    </div>
                    <div class="form-group">
                        <Label for="email">{{ __('general.email') }}</Label>
                        <input type="email" name="email" class="form-control">
                        <span class="text-danger error-text" id="email_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="password">{{__('general.password')}}</label>
                        <input type="text" name="password" value="{{old('password')}}" class="form-control">
                        <span class="text-danger error-text" id="password_error"></span>
                    </div>
                    <div class="row">
                            <div class="form-group">
                                <label for="permission">{{__('general.select_permissions')}}</label>
                                <select name="permission[]" class="form-control" id="permission" multiple>
                                    <option value="">---</option>
                                    <option value="1">@lang('permissions.admin_permission')</option>
                                    <option value="2">@lang('permissions.create_country')</option>
                                    <option value="3">@lang('permissions.edit_country')</option>
                                    <option value="4">@lang('permissions.delete_Country')</option>
                                    <option value="5">@lang('permissions.active_country')</option>
                                    <option value="6">@lang('permissions.disactive_country')</option>

                                    <option value="7">@lang('permissions.create_city')</option>
                                    <option value="8">@lang('permissions.edit_city')</option>
                                    <option value="9">@lang('permissions.delete_city')</option>
                                    <option value="10">@lang('permissions.active_city')</option>
                                    <option value="11">@lang('permissions.disactive_city')</option>

                                    <option value="12">@lang('permissions.create_district')</option>
                                    <option value="13">@lang('permissions.edit_district')</option>
                                    <option value="14">@lang('permissions.delete_district')</option>
                                    <option value="15">@lang('permissions.active_district')</option>
                                    <option value="16">@lang('permissions.disactive_district')</option>

                                    <option value="17">@lang('permissions.create_customer')</option>
                                    <option value="18">@lang('permissions.edit_customer')</option>
                                    <option value="19">@lang('permissions.delete_customer')</option>
                                    <option value="20">@lang('permissions.active_customer')</option>
                                    <option value="21">@lang('permissions.disactive_customer')</option>

                                    <option value="22">@lang('permissions.create_product')</option>
                                    <option value="23">@lang('permissions.edit_product')</option>
                                    <option value="24">@lang('permissions.delete_product')</option>
                                    <option value="25">@lang('permissions.active_product')</option>
                                    <option value="26">@lang('permissions.disactive_product')</option>

                                    <option value="27">@lang('permissions.create_coupoun')</option>
                                    <option value="28">@lang('permissions.edit_coupoun')</option>
                                    <option value="29">@lang('permissions.delete_coupoun')</option>
                                    <option value="30">@lang('permissions.active_coupoun')</option>
                                    <option value="31">@lang('permissions.disactive_coupoun')</option>

                                    <option value="32">@lang('permissions.show_order')</option>
                                    <option value="33">@lang('permissions.delete_order')</option>




                                </select>
                                <span class="text-danger error-text" id="permission_error"></span>
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('general.Status')}}</label>
                        <select name="status" class="form-control">
                            <option value="1" {{old('status') == 1 ? 'selected' : null}}>{{__('general.Active')}}</option>
                            <option value="0" {{old('status') == 0 ? 'selected' : null}}>{{__('general.Disactive')}}</option>
                        </select>
                    </div>
                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">{{__('general.Add')}}</button>
                        <button type="button" class="btn btn-danger" id="cancelAdd" data-bs-dismiss="modal">{{ __('general.Close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
