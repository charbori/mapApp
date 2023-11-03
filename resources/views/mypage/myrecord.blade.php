@extends('layouts.master')

@section('style')
    @vite(['resources/css/home.css'])
    <style>
    .bd-header {
        position: absolute;
    }
    body {
        overflow-x: hidden;
        overflow-y: hidden;
    }
    </style>
@stop
@section('contents')
    <div class="position-relative">
        <div id="map" style="width:100%;"></div>
        <div id="map_list_items" style="transform: translate(0px, -50px) !important; z-index:101" class="position-absolute top-100 start-0 d-flex flex-column flex-md-row gap-4 py-md-5 justify-content-center">
            <div class="list-group" id="list-group-handle">
                <div class="col" style="border-radius: 15px 15px 0 0; border-color:#e2e8f0; background-color:white; text-align:center; z-index:102">
                    <div>
                        <svg style="display: inline" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                            <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('masternav_extra_item')
    <div class="input-group input-group-sm ml-3 mt-1">
        <span class="input-group-text"  data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-justify" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2 12.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
            </svg>
        </span>
        <input placeholder="신목로 53" type="text" id="search_map_data" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
        <span class="input-group-text" id="search_map_btn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
        </svg></span>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=96gg9oc940&submodules=geocoder"></script>
@stop

