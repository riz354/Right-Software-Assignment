@extends('layout.main-layout')
@section('title', 'Assignment | Category')
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
            <button class="btn btn-primary" id="addCategoryBtn">
                <i class="fa fa-plus"></i> Add Category
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered data-table" id="categoriesTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="categoryForm">
                            <input type="hidden" id="categoryId">
                            <ul id="errorList" style="display: none;"></ul>
                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <button type="submit" class="btn btn-primary" id="categoryModalBtn">Save Category</button>
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
            var table = $('#categoriesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('category.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    searchPlaceHolder: 'Search..',
                }
            });

            $('#addCategoryBtn').click(function() {
                $('#categoryForm')[0].reset();
                $('#categoryId').val('');
                $('#categoryModalLabel').text('Add New Category');
                $('#categoryModalBtn').text('Save Category');
                $('#categoryModal').modal('show');
                $('.error-message').remove();
            });

            $(document).on('click', '.edit-btn', function() {
                var categoryId = $(this).data('id');
                $.ajax({
                    url: '{{ route('category.edit', ['id' => ':categoryId']) }}'.replace(
                        ':categoryId',
                        categoryId),
                    method: 'GET',
                    success: function(response) {
                        $('#categoryId').val(response.category.id);
                        $('#name').val(response.category.name);
                        $('#categoryModalLabel').text(
                            'Edit Category');
                        $('#categoryModalBtn').text(
                            'Update Category');
                        $('#categoryModal').modal('show');
                        $('.error-message').remove();

                    },
                    error: function(xhr) {
                        toastr.error('Error fetching category details');
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this category?')) {
                    $.ajax({
                        url: '{{ route('category.destroy', ['id' => ':id']) }}'.replace(':id', id),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            table.ajax.reload();
                            toastr.success('Category deleted successfully!');
                        },
                        error: function(xhr) {
                            toastr.success('Error deleting category');

                        }
                    });
                }
            });

            $('#categoryForm').validate({
                rules: {
                    name: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a category name",
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass("text-danger");
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    var categoryId = $('#categoryId').val();
                    var categoryName = $('#name').val();

                    if (categoryName != "") {
                        var url = categoryId ?
                            '{{ route('category.update', ['id' => ':categoryId']) }}'.replace(
                                ':categoryId',
                                categoryId) :
                            '{{ route('category.store') }}';

                        var method = categoryId ? 'PUT' : 'POST';
                        $.ajax({
                            url: url,
                            method: method,
                            data: {
                                name: categoryName,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                $('#categoryModal').modal('hide');
                                table.ajax.reload();
                                response.success ? toastr.success(
                                    'Category saved successfully!') : toastr.error(
                                    'Error  saving category ')
                                $('.error-message').remove();
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
                                                console.log('Input Field:',
                                                    inputField
                                                );

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

                }
            });


        });
    </script>


@endsection
