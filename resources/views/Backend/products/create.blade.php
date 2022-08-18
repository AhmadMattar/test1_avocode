<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('general.Add_product') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mr-2" id="add" enctype="multipart/form-data">
                    @csrf
                    @foreach (config('app.langauges') as $key => $value)
                        <div class="form-group">
                            <label for="name_{{ $key }}"> {{ $value }} </label>
                            <input type="text" name="name_{{ $key }}" class="form-control" value="">
                            <span class="text-danger error-text" id="name_{{ $key }}_error"></span>
                        </div>
                    @endforeach

                    <div class="form-group">
                        <label for="price">{{ __('general.price') }}</label>
                        <input type="text" name="price" id="price" class="form-control">
                        <span class="text-danger error-text" id="price_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="quantity">{{ __('general.quantity') }}</label>
                        <input type="text" name="quantity" id="quantity" class="form-control">
                        <span class="text-danger error-text" id="quantity_error"></span>
                    </div>

                    <div class="custom-file-container" data-upload-id="cover">
                        <label for="cover">{{__('general.photo')}}<a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                        <label class="custom-file-container__custom-file" >
                            <input type="file" name="cover" id="Image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                        </label>
                        <div class="custom-file-container__image-preview"></div>
                    </div>

                    <div class="custom-file-container" data-upload-id="images">
                        <label for="images">{{__('general.images')}}<a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                        <label class="custom-file-container__custom-file" >
                            <input type="file" name="images[]" id="product_images" class="custom-file-container__custom-file__custom-file-input" multiple>
                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                        </label>
                        <div class="custom-file-container__image-preview"></div>
                    </div>

                    <div class="form-group pt-4">
                        <button type="submit" name="submit" class="btn btn-primary">{{ __('general.Add') }}</button>
                        <button type="button" class="btn btn-danger" id="cancelAdd" data-bs-dismiss="modal">{{ __('general.Close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
