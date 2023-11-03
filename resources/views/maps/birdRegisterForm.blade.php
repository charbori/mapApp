@extends('layouts.master')
@section('style')
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/checkout/">
    <!-- Custom styles for this template -->
    @vite(['resources/css/edit.css'])
@stop
@if (getMobile())
    @section('masternav_extra_item')
    <span class="h3 text-black">장소등록</span>
    @endsection
@endif
@section('contents')
    <main class="container">
        @if (getMobile())
            <div class="mt-5 pt-5"><br></div>
        @else
            <div class="py-5 text-left"><br></div>
        @endif
        <form id="upload_files" class="needs-validation" method="POST" action="/api/map/mapStore" enctype="multipart/form-data" novalidate>
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
                            <input type="title" class="form-control" id="title" name="title" placeholder="제목을 입력해주세요." value="">
                            <div class="invalid-feedback">
                                제목을 입력해주세요.
                            </div>
                        </div>
                        <input type="hidden" name="type" value="map"/>
                        <div class="col-12">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="desc" rows="3" name="desc" placeholder="설명을 입력해주세요." required></textarea>
                            <div class="invalid-feedback">
                                설명을 입력해주세요.
                            </div>
                        </div>

                        <input type="hidden" name="longitude" value="{{ $_GET['longitude'] }}"/>
                        <input type="hidden" name="latitude" value="{{ $_GET['latitude'] }}"/>
                        <input type="hidden" name="map_address" value="{{ $mapStoreData['map_address'] }}"/>

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

