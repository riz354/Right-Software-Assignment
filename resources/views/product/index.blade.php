@extends('layout.main-layout')
@section('title', 'AdminLTE v4 | Product')
@section('page-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.css">
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
                            <th>Price</th>
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
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-2">
                                <label for="price" class="form-label">Product Price</label>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-2">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" class="form-select" id="category_id">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="images" class="form-label">Product Images</label>
                                <input type="file" name="images[]" class="form-control" id="images" multiple required>
                                <small style="font-size:9px">Select multiple product images</small>
                            </div>

                            <div class="mb-2">
                                <label for="description" class="form-label">Product Description</label>
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

    <script type="text/javascript">
        $(document).ready(function() {

            var table = $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('product.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
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
                    'images[]': {
                        required: true,
                    }
                },
                messages: {
                    name: "Please enter a product name",
                    price: "Please enter a valid price",
                    description: "Please enter product description",
                    category_id: "Please select a category",
                    'images[]': "Please upload at least one image (JPEG, PNG, GIF)",
                },
                submitHandler: function(form) {
                    var formData = new FormData(form);
                    var productId = $('#productId').val();

                    var url = productId ?
                        '{{ route('product.update', ['id' => ':productId']) }}'.replace(
                            ':productId', productId) :
                        '{{ route('product.store') }}';
                    var method = productId ? 'PUT' : 'POST';

                    $.ajax({
                        url: url,
                        method: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                $('#productModal').modal('hide');
                                table.ajax.reload();
                                alert('Product saved successfully!');
                            } else {
                                alert('Error: Could not save product.');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                $('#errorList').empty();
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(field, messages) {
                                    $.each(messages, function(index,
                                        message) {
                                        $('#errorList').append(
                                            '<li class="text-danger">' +
                                            message +
                                            '</li>');
                                    });
                                });
                                $('#errorList').show();
                            } else {
                                alert('Error: Could not save product');
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
                                alert('Product deleted successfully!');
                            } else {
                                alert('Error: Could not delete product.');
                            }
                        },
                        error: function(xhr) {
                            alert('Error: Could not delete product');
                        }
                    });
                }
            });
        });
    </script>


@endsection

