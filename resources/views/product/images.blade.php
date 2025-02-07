@extends('layout.main-layout')
@section('title', 'Assignment | Product')
@section('page-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="container mt-3">

        <div class="card">
            <div class="card-body">
                <form id="productImagesForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="productId" value="{{ $product->id }}" name="productId">
                    <ul id="errorList" style="display: none;"></ul>
                    <div class="mb-2">
                        <label for="name" class="form-label">Product Name </label>
                        <input type="text" class="form-control" value="{{ $product->name }}" id="name"
                            name="name" readonly>
                    </div>
                    <div class="mb-2">
                        <label for="images" class="form-label">Product Images<span class="text-danger"
                                id="req-sign">*</span></label>
                        <input type="file" name="images[]" class="form-control" id="images" multiple
                            accept=".png,.jpeg,.jpg">
                        <small style="font-size:9px">Select multiple product images</small>
                    </div>
                    <div id="imagePreviews" class="mb-2"></div>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Upload Images</button>
                </form>
            </div>

            <div class="container my-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">{{ $product->name }} Images</h4>
                        <div class="row px-xl-5 pb-3">
                            @if (isset($product->images))
                                @forelse ($product->images as $image)
                                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1" id="image-box-{{ $image->id }}">
                                        <div class="cat-item align-items-center mb-4">
                                            <div class="overflow-hidden" style="width: 100px; height: 100px;">
                                                <img class="img-fluid" src="{{ asset('storage/' . $image->image_path) }}"
                                                    alt="">
                                            </div>
                                            <div class="flex-fill pl-3">
                                                <a href="javascript:void(0)" class="delete-image"
                                                    data-id="{{ $image->id }}">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <h6>No Images Added Yet</h1>
                                @endforelse

                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#productImagesForm').validate({
                rules: {
                    'images[]': {
                        required: true,
                    },
                },
                messages: {
                    'images[]': "Please select a image",
                },
                errorPlacement: function(error, element) {
                    error.addClass("text-danger");
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    $('#submitBtn').attr('disabled', true);
                    var formData = new FormData(form);
                    var productId = $('#productId').val();

                    var url =
                        '{{ route('product.images.store', ['id' => ':productId']) }}'.replace(
                            ':productId', productId);
                    var method = 'POST';

                    $.ajax({
                        url: url,
                        method: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Product Images saved successfully!');
                                $('#imagePreviews').empty();
                                setTimeout(() => {
                                    location.reload();
                                }, 10000);
                                $('#submitBtn').attr('disabled', false);
                            } else {
                                $('#imagePreviews').empty();
                                $('#submitBtn').attr('disabled', false);
                                toastr.error('Error: Could not save product.');
                            }
                        },
                        error: function(xhr) {
                            try {
                                var response = JSON.parse(xhr
                                    .responseText);
                                if (xhr.status === 422) {
                                    $('.error-message')
                                        .remove();
                                    var errors = response
                                        .errors;
                                    console.log('Validation Errors:',
                                        errors);

                                    if (errors) {
                                        $.each(errors, function(field, messages) {
                                            var inputField = $('[name="' +
                                                field + '"]'
                                            );
                                            if (inputField.length === 0 && field ===
                                                'images') {
                                                inputField = $('[name="images[]"]');
                                            }
                                            $.each(messages, function(index,
                                                message) {
                                                inputField.after(
                                                    '<span class="text-danger error-message">' +
                                                    message +
                                                    '</span>');
                                                console.log(
                                                    'Appended message:',
                                                    message
                                                );
                                            });
                                        });
                                    }
                                }
                            } catch (e) {
                                console.log('Error parsing JSON response: ' + e
                                    .message);
                                toastr.error('Error: Could not save category')
                            }
                        }
                    });
                }
            });

            $(document).on('click', '.delete-image', function() {
                let imageId = $(this).data("id");
                if (confirm('Are you sure you want to delete this Image?')) {
                    $.ajax({
                        url: '{{ route('product.image.destroy', ['id' => ':imageId']) }}'.replace(
                            ':imageId',
                            imageId),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.success) {
                                $("#image-box-" + imageId).fadeOut(300, function() {
                                    $(this).remove();
                                });
                                toastr.success('Image deleted successfully!')
                            } else {
                                toastr.error('Error: Could not delete Image.');
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Error: Could not delete Image.');
                        }
                    });
                }
            });

            var selectedFiles = [];
            $('#images').change(function(e) {
                var files = e.target.files;
                var previewContainer = $('#imagePreviews');
                previewContainer.empty();

                selectedFiles = [];

                $.each(files, function(index, file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var fileIndex = selectedFiles.length;
                        selectedFiles.push(file);

                        var previewHtml = `
                    <div class="image-preview" data-index="${fileIndex}">
                        <img src="${e.target.result}" class="img-thumbnail" width="100">
                    </div>
                `;
                        previewContainer.append(previewHtml);
                    };
                    reader.readAsDataURL(file);
                });
            });

        });
    </script>
@endsection
