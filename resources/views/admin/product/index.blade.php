@extends('admin.layout.main-layout')
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
        <div class="mb-1 d-flex justify-content-end">
            <button class="btn btn-primary" id="addProductBtn">
                <i class="fa fa-plus"></i> Add Product
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered data-table" id="productsTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            {{-- <th>Images</th> --}}
                            <th>Description</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="productForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="productId">
                            <ul id="errorList" style="display: none;"></ul>
                            <div class="mb-2">
                                <label for="name" class="form-label">Product Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-2">
                                <label for="price" class="form-label">Product Price<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-2">
                                <label for="category_id" class="form-label">Category<span
                                        class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" id="category_id">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="images" class="form-label">Product Images<span class="text-danger"
                                        id="req-sign">*</span></label>
                                <input type="file" name="images[]" class="form-control" id="images" multiple
                                    accept=".png,.jpeg,.jpg">
                                <small style="font-size:9px">Select multiple product images</small>
                            </div>
                            <div id="imagePreviews" class="mb-2"></div>

                            <div class="mb-2">
                                <label for="description" class="form-label">Product Description<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" id="productModalBtn">Save Product</button>
                        </form>
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

            var table = $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('product.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    // {
                    //     data: 'image',
                    //     name: 'image'
                    // },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#addProductBtn').click(function() {
                $('#productForm')[0].reset();
                $('#productId').val('');
                $('#productModalLabel').text('Add New Product');
                $('#productModalBtn').text('Save Product');
                $('#productModal').modal('show');
                $('.error-message').remove();
                $('#imagePreviews').empty();
            });

            $('#productForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    price: {
                        required: true,
                        number: true,
                    },
                    description: {
                        required: true,
                    },
                    category_id: {
                        required: true,
                    },
                },
                messages: {
                    name: "Please enter a product name",
                    price: "Please enter a valid price",
                    description: "Please enter product description",
                    category_id: "Please select a category",
                },
                errorPlacement: function(error, element) {
                    error.addClass("text-danger");
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    $('#productModalBtn').attr('disabled', true);
                    var formData = new FormData(form);
                    var productId = $('#productId').val();

                    var url = productId ?
                        '{{ route('product.update', ['id' => ':productId']) }}'.replace(
                            ':productId', productId) :
                        '{{ route('product.store') }}';
                    var method = 'POST';

                    $.ajax({
                        url: url,
                        method: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $('#productModalBtn').attr('disabled', false);
                            if (response.success) {
                                $('#productModal').modal('hide');
                                table.ajax.reload();
                                toastr.success('Product saved successfully!');
                                $('#imagePreviews').empty();

                            } else {
                                toastr.error('Error: Could not save product.');
                                $('#imagePreviews').empty();
                            }
                        },
                        error: function(xhr) {
                            $('#productModalBtn').attr('disabled', false);
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


            $(document).on('click', '.edit-btn', function() {
                var productId = $(this).data('id');
                $.get('{{ route('product.edit', ['id' => ':id']) }}'.replace(':id', productId), function(
                    response) {
                    if (response.success) {
                        $('#productId').val(response.product.id);
                        $('#name').val(response.product.name);
                        $('#price').val(response.product.price);
                        $('#description').val(response.product.description);
                        $('#category_id').val(response.product.category_id);
                        $('#productModalLabel').text('Edit Product');
                        $('#productModalBtn').text('Update Product');
                        $('#productModal').modal('show');
                        $('#imagePreviews').empty();
                        $('.error-message').remove();
                        $('#images').val('');
                        $('#req-sign').text('');

                        if (Array.isArray(response.imagesPath) && response.imagesPath.length > 0) {
                            $.each(response.imagesPath, function(index, image) {
                                var html = `
                                    <div class="image-preview" data-index="${index}">
                                        <img src="${image}" class="img-thumbnail" width="100" height="100">
                                    </div>
                                `;

                                $('#imagePreviews').append(html);
                            });

                        } else {
                            console.log('No images or imagesPath is not an array');
                        }
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                var productId = $(this).data('id');
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: '{{ route('product.destroy', ['id' => ':id']) }}'.replace(':id',
                            productId),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                toastr.success('Product deleted successfully!')
                            } else {
                                toastr.error('Error: Could not delete product.');
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Error: Could not delete product.');
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


            let searchTimeout;
            let typingDelay = 500; 
            setTimeout(function() {
                $('#productsTable_filter input').off('keyup');
            }, 500);
            setTimeout(function() {
                $('#productsTable_filter input').off('input');
            }, 500);
            setTimeout(function() {
                $('#productsTable_filter input').off('blur');
            }, 500);

            $('#productsTable_filter input').on('change', function() {
                clearTimeout(searchTimeout);
                var searchValue = $(this).val().trim();
                searchTimeout = setTimeout(function() {
                    if (searchValue !== '') {
                        table.search(searchValue).draw();
                    } else {
                        table.search('').draw();
                    }
                }, typingDelay);
            });

        });
    </script>
@endsection
