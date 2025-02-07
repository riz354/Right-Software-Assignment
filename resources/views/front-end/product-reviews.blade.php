<h2>{{ $product->name }} Reviews</h2>

@if (isset($product->comments) && count($product->comments) > 0)
    <div class="container">
        @foreach ($product->comments as $comment)
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
                    @if (Auth::user()->id == $comment->user_id)
                        <button class="btn btn-sm btn-primary edit-comment" data-id="{{ $comment->id }}"
                            data-comment="{{ $comment->comment }}">Edit</button>
                    @endif
                </p>
            </div>
        @endforeach
    </div>
@else
    <div class="container comment-box">
        <p>No reviews yet. Be the first to comment!</p>
    </div>
@endif
