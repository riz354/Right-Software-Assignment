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
<div class="container comment-box">
    
</div>
    <h6>No reviews yet</h6>
@endif