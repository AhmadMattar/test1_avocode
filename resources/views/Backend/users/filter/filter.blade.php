<div class="mt-5">
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <input type="text" name="first_name" class="form-control" id="searchFirstName" placeholder="{{__('general.first_name')}}">
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <input type="text" name="last_name" class="form-control" id="searchLastName" placeholder="{{__('general.last_name')}}">
            </div>
        </div>
        <div class="col-3">
            <select name="country_id" class="form-control" id="search_country_id">
                <option value="">{{__('general.select_country')}}</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}"
                        {{ old('search_country_id') == $country->id ? 'selected' : null }}>
                        {{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <select name="city_id" id="search_city_id" class="form-control"></select>
            <span class="text-danger error-text" id="city_id_error"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <input type="text" name="email" class="form-control" id="searchEmail" placeholder="{{__('general.email')}}">
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <input type="text" name="phone" class="form-control" id="searchPhone" placeholder="{{__('general.phone')}}">
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <select name="status" class="form-control" id="searchStatus">
                    <option value="">---</option>
                    <option value="1">{{__('general.Activated')}}</option>
                    <option value="0">{{__('general.Inactivated')}}</option>
                </select>
            </div>
        </div>
        <div class="col-1">
            <div class="form-group">
                <button type="button" class="btn btn-secondry" id="search">{{__('general.Search')}}</button>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <button type="button" class="btn btn-danger" id="reset">{{__('general.Reset')}}</button>
            </div>
        </div>
    </div>
</div>

