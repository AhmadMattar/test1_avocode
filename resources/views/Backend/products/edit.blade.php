<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('general.Edit_product') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mr-2" id="edit" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <input type="hidden" class="form-control" name="id" id="product_id" value="">
                    @foreach (config('app.langauges') as $key => $value)
                        <div class="form-group">
                            <label for="name_{{ $key }}"> {{ $value }} </label>
                            <input type="text" name="name_{{ $key }}" id="name_{{ $key }}"
                                class="form-control" value="{{ $value }}">
                            <span class="text-danger error-text" id="name_{{ $key }}_error2"></span>
                        </div>
                    @endforeach

                    <div class="form-group">
                        <label for="price">{{ __('general.price') }}</label>
                        <input type="text" name="price" id="edit_price" class="form-control">
                        <span class="text-danger error-text" id="price_error2"></span>
                    </div>

                    <div class="form-group">
                        <label for="quantity">{{ __('general.quantity') }}</label>
                        <input type="text" name="quantity" id="edit_quantity" class="form-control">
                        <span class="text-danger error-text" id="quantity_error2"></span>
                    </div>

                    <div class="row">
                        <label for="cover">{{ __('general.photo') }}</label>
                        <div class="col-6">
                            <div class="upload mt-4 pr-md-4">
                                <input type="file" name="cover" id="input-file-max-fs" class="dropify"
                                    data-default-file="{{ asset('backend/assets/img/200x200.jpg') }}"
                                    data-max-file-size="2M" />
                            </div>
                        </div>
                        <span class="text-danger error-text" id="cover_error2"></span>
                    </div>

                    <div class="row pt-4">
                        <div class="col-12">
                            <label for="images">{{ __('general.images') }}</label>
                            <br>
                            <input type="file" name="images[]" id="images" class="form-control mb-3" multiple>
                            <div class="mb-3" id="edit_images_container">
                            </div>
                            <span class="text-danger error-text" id="images_error2"></span>
                        </div>
                    </div>

                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">{{ __('general.Edit') }}</button>
                        <button type="button" class="btn btn-danger" id="cancelEdit"
                            data-bs-dismiss="modal">{{ __('general.Close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
