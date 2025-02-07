@extends('front-end.layout.main-layout')
@section('title', 'Product Detail')
@section('page-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="container-fluid pb-5 mt-3">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($product->images as $image)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <img class="w-100 h-100" src="{{ asset('storage/' . $image->image_path) }}" alt="Image">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30">
                    <h3>{{ $product->name }}</h3>
                    <h6 class="text-muted ml-2">Rs.{{ $product->price }}</h6>
                    <h6 class="">Description: </h6>
                    <p class="mb-4">{{ $product->description }}</p>
                    <form id="productCommentForm">
                        <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                        <div class="mb-3">
                            <textarea placeholder="Enter Comment" class="form-control" name="comment" id="comment" cols="30" rows="10"
                                id="comment"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" id="categoryModalBtn">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row px-xl-5  pt-5">
            <div class="bg-light" id="comment-box">
                <h2>{{ $product->name }} Reviews</h2>
                @if (isset($product->comments) && count($product->comments) > 0)
                    <div class="container">
                        @foreach ($product->comments->sortByDesc('created_at') as $comment)
                            <div class="comment-container" id="comment-{{ $comment->id }}">
                                <h6>
                                    <img src="{{ asset('assets/assets/img/emptyUser.jpg') }}" height="30px"
                                        width="30px">{{ $comment->user->name }}
                                </h6>
                                <div id="edit-form-{{ $comment->id }}" style="display:none;">
                                    <textarea id="edit-comment-text-{{ $comment->id }}" class="form-control" rows="3" required>{{ $comment->comment }}</textarea>
                                    <button type="button" class="btn btn-sm btn-success"
                                        onclick="saveComment({{ $comment->id }})">Save</button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="cancelEdit({{ $comment->id }})">Cancel</button>
                                </div>
                                <p class="ml-5 comment-text" id="comment-text-{{ $comment->id }}">
                                    {{ $comment->comment }}
                                    @if (Auth::check() && Auth::user()->id == $comment->user_id)
                                        <button class="btn btn-sm btn-primary edit-comment" data-id="{{ $comment->id }}"
                                            data-comment="{{ $comment->comment }}">Edit</button>
                                    @endif

                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <h6>No reviews yet</h6>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var assetBaseUrl = "{{ asset('') }}";

        $('#productCommentForm').validate({
            rules: {
                comment: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                }
            },
            messages: {
                comment: {
                    required: "Please enter comment",
                    min: "Comment should be minimum 3 character",
                    max: "Comment should be maximum 3 character",

                }
            },
            errorPlacement: function(error, element) {
                error.addClass("text-danger");
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                var productId = $('#product_id').val();
                var comment = $('#comment').val();

                var url = '{{ route('product.comment.index', ['id' => ':productId']) }}'.replace(
                    ':productId',
                    productId);

                var method = 'POST';
                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        comment: comment,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#comment').val('');
                            $('#comment-box').empty(response.view);
                            $('#comment-box').html(response.view);
                            toastr.success('Your comment added successfully!')
                        };
                    },
                    error: function(xhr) {
                        var response = JSON.parse(xhr
                            .responseText);

                        if (xhr.status === 401) {
                            window.location.href = '/login';
                        } else if (xhr.status === 422) {
                            $('.error-message')
                                .remove();
                            var errors = response
                                .errors;
                            if (errors) {
                                $.each(errors, function(field, messages) {
                                    var inputField = $('[name="' +
                                        field + '"]'
                                    );
                                    $.each(messages, function(index,
                                        message) {
                                        inputField.after(
                                            '<span class="text-danger error-message">' +
                                            message +
                                            '</span>');
                                    });
                                });
                            }
                        } else {
                            toastr.eroor('Comment not added!')
                        }
                    }
                });

            }
        });

        $(document).on('click', '.edit-comment', function() {
            var commentId = $(this).data('id');
            var commentText = $(this).data('comment');
            $('#comment-text-' + commentId).hide();
            $('#edit-form-' + commentId).show();
            $('#edit-comment-text-' + commentId).val(commentText);
        });

        function saveComment(commentId) {
            var newComment = $('#edit-comment-text-' + commentId).val();
            var url = '{{ route('product.comment.update', ['id' => ':commentId']) }}'.replace(
                ':commentId',
                commentId);
            if (newComment.trim() === "") {
                toastr.error('Please enter a comment');
            } else if (((newComment.trim()).length < 3)) {
                toastr.error('Please enter a comment minimum 3 character');
            } else {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        comment: newComment,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#comment').val('');
                            $('#comment-box').empty(response.view);
                            $('#comment-box').html(response.view);
                            toastr.success('Your comment was updated successfully!');
                        }
                    },
                    error: function(xhr) {
                        var response = JSON.parse(xhr.responseText);
                        toastr.error('Error while updating comment');
                    }
                });
            }

        }

        function cancelEdit(commentId) {
            $('#edit-form-' + commentId).hide();
            $('#comment-text-' + commentId).show();
        }
    </script>
@endsection
