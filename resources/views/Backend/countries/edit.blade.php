<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('general.Edit_country')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mr-2" id="edit" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <input type="hidden" class="form-control" name="id" id="country_id" value="">
                    @foreach (config('app.langauges') as $key => $value)
                        <div class="form-group">
                            <label for="name_{{ $key }}"> {{ $value }} </label>
                            <input type="text" name="name_{{ $key }}" id="name_{{ $key }}" class="form-control" value="{{$value}}">
                            <span class="text-danger error-text" id="name_{{ $key }}_error2"></span>
                        </div>
                    @endforeach
                    <div class="form-group">
                        <label for="code">{{__('general.code')}}</label>
                        <input type="number" name="code" id="code" class="form-control" value="">
                        <span class="text-danger error-text" id="code_error2"></span>
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('general.Status')}}</label>
                        <select name="status" class="form-control" id="status">
                            <option value="1" {{old('status') == 1 ? 'selected' : null}}>{{__('general.Active')}}</option>
                            <option value="0" {{old('status') == 0 ? 'selected' : null}}>{{__('general.Disactive')}}</option>
                        </select>
                    </div>
                    <div class="row pt-4">
                        <div class="col-12">
                            <label for="cover">{{__('general.photo')}}</label>
                            <br>
                            <input type="file" name="cover" id="cover" class="form-control mb-3">
                            <img src="" width="150" height="150" id="country-cover">
                            <span class="text-danger error-text" id="cover_error2"></span>
                        </div>
                    </div>
                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">{{__('general.Edit')}}</button>
                        <button type="button" class="btn btn-danger" id="cancelEdit" data-bs-dismiss="modal">{{ __('general.Close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
