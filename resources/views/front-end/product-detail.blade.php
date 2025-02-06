<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Assignment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                </ul>
                @if (!Auth::check())
                    <a class="nav-link " href="{{ route('login') }}" tabindex="-1" aria-disabled="true"><button
                            class="btn btn-outline-success me-2" type="submit">Login</button></a>
                @else
                    <a href="#" class="nav-link dropdown-toggle me-2" data-bs-toggle="dropdown">
                        <span class="d-none d-md-inline">{{ Auth::user()->name ?? '' }}</span>
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end" style="border: none">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger me-2">Log out</button>
                        </form>
                    </ul>
                @endif
                <a class="nav-link " href="{{ route('category.index') }}" tabindex="-1" aria-disabled="true"><button
                        class="btn btn-outline-success" type="submit">Dashbaord</button></a>
            </div>
        </div>
    </nav>

    <div class="container-fluid pb-5 mt-3">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($product->images as $image)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <img class="w-100 h-100" src="{{ asset('storage/' . $image->image_path) }}"
                                    alt="Image">
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
                        @foreach ($product->comments as $comment)
                            <div class="">
                                <h6> <img
                                        src="
                                     {{ asset('assets/assets/img/emptyUser.jpg') }}"
                                        height="30px" width="30px">{{ $comment->user->name }}</h6>
                                <p class="ml-5">{{ $comment->comment }}</h2>
                            </div>
                        @endforeach
                    </div>
                @else              
                    <h6>No reviews yet</h6>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
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
                }
            },
            messages: {
                comment: {
                    required: "Please enter comment",
                }
            },
            errorPlacement: function(error, element) {
                error.addClass("text-danger");
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                var productId = $('#product_id').val();
                var comment = $('#comment').val();

                var url = '{{ route('product.comment', ['id' => ':productId']) }}'.replace(
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
    </script>
</body>

</html>
