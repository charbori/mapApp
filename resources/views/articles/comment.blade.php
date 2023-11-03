<div class="ps-3 pe-3 mt-5 mb-5">
    <div class="row height d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="card">
                <div class="p-3">
                    <h4 class="fw-bold">Comments</h4>
                </div>
                <div class="col-auto align-items-center p-3 form-color">
                    <div class="row">
                        <div class="col-auto">
                            <img src="https://i.imgur.com/oOCvj8U.jpeg" class="rounded-circle img-reply">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="submit_comment" placeholder="Enter your comment..." aria-describedby="validationServer03Feedback" required>
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                please write reply.
                            </div>
                        </div>

                        <div class="col-auto p-1">
                            <button type="button" id="send_comment" class=".form-control btn btn-secondary">send</button>
                        </div>
                    </div>
                </div>
                <div id="cont_extra_comment">
                    @if (false == empty($comment_data))
                        @foreach ($comment_data AS $comment)
                            <div class="mt-2" data="{{ $comment->id }}">
                                <div class="wrap-comment col-auto p-3">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="col-auto align-items-center inline-items">
                                                <i class="fas fa-2x fa-ghost rounded-circle img-reply"></i>
                                                <span class="mr-2">{{ $comment->user->name }}</span>
                                            </div>
                                            <small>{{ $comment->diffTime }} ago</small>
                                        </div>
                                        <p class="text-justify comment-text mb-0" data="{{ $comment->id }}">{{ $comment->content }}</p>
                                        <div class="row cont-comment-edit comment-off" data="{{ $comment->id }}">
                                            <div class="col">
                                                <input  type="text" class="comment-off form-control comment-text-edit" placeholder="Edit your comment..." data="{{ $comment->id }}" value="{{ $comment->content }}" required/>
                                            </div>
                                            <div class="col-auto p-1">
                                                <button type="button" id="edit_comment" data="{{ $comment->id }}" class=".form-control btn btn-secondary">edit</button>
                                            </div>
                                        </div>
                                        <div class="col-auto user-feed">
                                            <span class="wish"><i class="fa fa-heartbeat mr-2"></i>{{ $comment->like }}</span> <span class="ml-3"></span>
                                            @if ($comment->user->id == Auth::id())
                                                <span class="badge bg-info comment_edit_action" data="{{ $comment->id }}">edit</span> <span class="badge bg-danger comment_del_action" data="{{ $comment->id }}">del</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col"></div>
        <div id="progress_load" class="progress progress-off">
            <div id="progress_bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
        </div>
        <span id="comment_fail" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger progress-off">
            <span id="comment_content">!</span>
            <i id="comment_refresh" class="fa-solid fa-rotate-right" style="display:none"></i>
        </span>
    </div>
</div>
