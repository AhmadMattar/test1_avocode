<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('general.Edit_city')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mr-2" id="edit">
                    @method('PUT')
                    @csrf
                    <input type="hidden" class="form-control" name="id" id="district_id" value="">
                    @foreach (config('app.langauges') as $key => $value)
                        <div class="form-group">
                            <label for="name_{{ $key }}"> {{ $value }} </label>
                            <input type="text" name="name_{{ $key }}" id="name_{{ $key }}" class="form-control" value="{{$value}}">
                            <span class="text-danger error-text" id="name_{{ $key }}_error2"></span>
                        </div>
                    @endforeach

                    <div class="form-group">
                        <label for="status">{{__('general.Status')}}</label>
                        <select name="status" class="form-control" id="status">
                            <option value="1" {{old('status') == 1 ? 'selected' : null}}>{{__('general.Active')}}</option>
                            <option value="0" {{old('status') == 0 ? 'selected' : null}}>{{__('general.Disactive')}}</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="edit_country_id">{{__('general.country')}}</label>
                                <select name="country_id" class="form-control" id="edit_country_id">
                                    <option value="">{{__('general.select_country')}}</option>
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
                            <label for="edit_city_id">{{__('general.city')}}</label>
                            <select name="city_id" id="edit_city_id" class="form-control"></select>
                            <span class="text-danger error-text" id="city_id_error"></span>
                        </div>
                    </div>

                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">{{__('general.Edit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
