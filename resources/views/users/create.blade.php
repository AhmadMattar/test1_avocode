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
                        <Label for="first_name">{{ __('general.first_name') }}</Label>
                        <input type="text" name="first_name" class="form-control">
                        <span class="text-danger error-text" id="first_name_error"></span>
                    </div>
                    <div class="form-group">
                        <Label for="last_name">{{ __('general.last_name') }}</Label>
                        <input type="text" name="last_name" class="form-control">
                        <span class="text-danger error-text" id="last_name_error"></span>
                    </div>
                    <div class="form-group">
                        <Label for="email">{{ __('general.email') }}</Label>
                        <input type="email" name="email" class="form-control">
                        <span class="text-danger error-text" id="email_error"></span>
                    </div>
                    <div class="form-group">
                        <Label for="phone">{{ __('general.phone') }}</Label>
                        <input type="text" name="phone" class="form-control">
                        <span class="text-danger error-text" id="phone_error"></span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="country_id">{{__('general.country')}}</label>
                                <select name="country_id" class="form-control" id="country_id">
                                    <option value="">---</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('country_id') == $country->id ? 'selected' : null }}>
                                            {{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text" id="country_id_error"></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="city_id">{{__('general.city')}}</label>
                            <select name="city_id" id="city_id" class="form-control"></select>
                            <span class="text-danger error-text" id="city_id_error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{old('status') == 1 ? 'selected' : null}}>Active</option>
                            <option value="0" {{old('status') == 0 ? 'selected' : null}}>Inactive</option>
                        </select>
                    </div>
                    <div class="row pt-4">
                        <div class="col-12">
                            <label for="cover">{{ __('general.photo') }}</label>
                            <br>
                            <div class="file-loading">
                                <input type="file" name="cover" id="Image" class="file-input-overview">
                                <span class="text-danger error-text" id="cover_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">{{__('general.Add')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
