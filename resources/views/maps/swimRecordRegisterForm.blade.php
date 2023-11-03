@extends('layouts.master')
@section('style')
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/checkout/">
    <!-- Custom styles for this template -->
    @vite(['resources/css/edit.css'])
    <style>
        html,body {
            overflow-x: hidden;
        }
    </style>
@stop

@php
    $sport_codes = array(
        "50",
        "100",
        "200",
        "400",
        "800",
        "1500"
    );
    if (empty($sport_code_selected)) $sport_code_selected = "50";
@endphp
@if (getMobile())
    @section('masternav_extra_item')
    <span class="h1 text-black">기록등록</span>
    @endsection
@endif
@section('contents')
    <main class="container">
        <div class="py-5 text-left"><br></div>
        <form id="upload_files" class="needs-validation" method="POST" action="/api/record/store" enctype="multipart/form-data" novalidate>
            <div class="row g-5">
                <div class="col">
                    {!! csrf_field() !!}
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="title" class="form-label">Sport</label>
                            <select name="type" id="type" class="mt-1 form-select form-select-sm fw-bolder" aria-label=".form-select-sm example">
                                <option value="swim" selected>수영</option>
                            </select>
                            <select name="sport_code" id="sport_code" class="mt-1 form-select form-select-sm fw-bolder" aria-label=".form-select-sm example">

                                @foreach ($sport_codes as $code)
                                    <option value="{{ $code }}" {{ $code == $sport_code_selected ? 'selected' : '' }}>{{ $code }}m</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="title" class="form-label">Record</label>
                            <input type="title" class="form-control" id="title" name="record" placeholder="기록을 입력해주세요." value="">
                            <div class="invalid-feedback">
                                기록을 입력해주세요.
                            </div>
                        </div>
                        <input type="hidden" name="map_id" value="{{ $map_id }}"/>
                        <div class="col-12">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="desc" rows="3" name="desc" placeholder="설명을 입력해주세요." required></textarea>
                            <div class="invalid-feedback">
                                설명을 입력해주세요.
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <button class="w-100 btn btn-primary btn-lg" style="background-color:#0d6efd" type="submit">ok</button>
                </div>
            </div>
        </form>
    </main>
@stop

@section('javascript')
    @vite(['resources/js/edit.js'])
@stop

