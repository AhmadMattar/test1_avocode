<div class="modal fade" id="addCityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('general.Add_city')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mr-2" id="addCity">
                    @csrf
                    @foreach (config('app.langauges') as $key => $value)
                        <div class="form-group">
                            <label for="name_{{ $key }}"> {{ $value }} </label>
                            <input type="text" name="name_{{ $key }}"
                                class="form-control" value="">
                            <span class="text-danger error-text" id="name_{{ $key }}_error"></span>
                        </div>
                    @endforeach

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{old('status') == 1 ? 'selected' : null}}>Active</option>
                            <option value="0" {{old('status') == 0 ? 'selected' : null}}>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="country_id">{{__('general.country')}}</label>
                        <select name="country_id" class="form-control">
                            <option value="">---</option>
                            @foreach ($countries as $country)
                            <option value="{{$country->id}}" {{old('country_id') == $country->id ? 'selected' : null}}>{{$country->name}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text" id="country_id_error"></span>
                    </div>

                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">{{__('general.Add')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
