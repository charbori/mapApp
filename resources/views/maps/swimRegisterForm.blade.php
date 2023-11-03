@extends('layouts.master')
@section('style')
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/checkout/">
    <!-- Custom styles for this template -->
    @vite(['resources/css/edit.css'])
@stop
@if (getMobile())
    @section('masternav_extra_item')
    <span class="h1 text-black">기록등록</span>
    @endsection
@endif
@section('contents')
    <main class="container">
        @if (getMobile())
            <div class="mt-5 pt-5"><br></div>
        @else
            <div class="py-5 text-left"><br></div>
        @endif
        <form id="upload_files" class="needs-validation" method="POST" action="/api/map/store" enctype="multipart/form-data" novalidate>
            <div class="row g-5">
                <div class="col">
                    {!! csrf_field() !!}
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="title" class="form-label">Name</label>
                            <input type="title" class="form-control" id="title" name="title" placeholder="기록을 입력해주세요." value="">
                            <div class="invalid-feedback">
                                수영장 이름을 입력해주세요.
                            </div>
                        </div>
                        <input type="hidden" name="type" value="swim"/>
                        <div class="col-12">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="desc" rows="3" name="desc" placeholder="설명을 입력해주세요." required></textarea>
                            <div class="invalid-feedback">
                                설명을 입력해주세요.
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
    @vite(['resources/js/edit.js'])
@stop

