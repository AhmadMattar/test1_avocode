<div class="mt-5">
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <input type="text" name="code" class="form-control" id="searchCode" placeholder="{{__('general.code')}}">
            </div>
        </div>
        <div class="col-2">
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

