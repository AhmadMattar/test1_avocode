<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">إضافة دولة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mr-2" id="addCountry" enctype="multipart/form-data">
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
                        <label for="code">الرمز</label>
                        <input type="number" name="code" class="form-control"
                            value="{{ old('code') }}">
                        <span class="text-danger error-text" id="code_error"></span>
                    </div>
                    <div class="row pt-4">
                        <div class="col-12">
                            <label for="cover">الصورة</label>
                            <br>
                            <div class="file-loading">
                                <input type="file" name="cover" id="countryImage" class="file-input-overview">
                                <span class="text-danger error-text" id="cover_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">أضف الدولة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
