@extends('layouts.master')
@section('style')
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/checkout/">
    <!-- Custom styles for this template -->
    <link href="/build/assets/css/edit.css" rel="stylesheet">
@stop

@section('contents')
    <main class="container">
        <div class="py-5 text-left"><br></div>
        <form id="upload_files" class="needs-validation" method="POST" action="/articles" enctype="multipart/form-data" novalidate>
            <div class="row g-5">
                <input id="hidden-input" type="file" name="photos[]" multiple class="hidden" />

                @if (getMobile())
                    @include('components.image_upload_mo')
                @else
                    @include('components.image_upload')
                @endif
                <div class="col">
                    {!! csrf_field() !!}
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="title" class="form-label">Title</label>
                            <input type="title" class="form-control" id="title" name="title" placeholder="제목을 입력하세요" value="{{ $title }}">
                            <div class="invalid-feedback">
                                제목을 입력하세요.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" rows="3" name="content" placeholder="내용을 입력하세요" required>{{ $content }}</textarea>
                            <div class="invalid-feedback">
                                내용을 입력하세요.
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <button class="w-100 btn btn-primary btn-lg" type="submit">ok</button>
                </div>
            </div>
        </form>
    </main>
@stop

@section('javascript')
    <script src="/build/assets/js/edit.js"></script>
@stop

