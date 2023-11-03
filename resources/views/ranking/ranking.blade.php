@extends('layouts.master')

@section('style')
    @vite(['resources/css/home.css'])
    <style>
    .bd-header {
        display: inline-block;
        background-color : #6c757d;
    }
    .bd-header nav {
        background-color : #6c757d;
    }
    main {
        margin-top: 1rem;
    }
    body {
        overflow-x: hidden;
    }
    </style>
@stop
@php
    //if (empty($user_param)) $user_param = Auth::user();
    //if (empty($user_name)) $user_name = $user_param['name'];
    $now_month_type = (int) date('m');
    $now_month_type = ($now_month_type > 6) ? 'last_half' : 'before_half';
@endphp
@section('contents')
<div class="col" style="height:1em; background-color:whitesmoke"></div>
    <main class="container">
        <div class="row">
            <div class="col">
                <strong id="place_name" class='mr-1'>
                    총거리
                </strong>
            </div>
            <div class="col-3">
                <div class="accordion accordion-flush" style="border:none" id="accordionExample">
                    <div class="accordion-item" style="border:none">
                        <strong>
                            {{ $now_month_type == 'last_half' ? '후반기' : '전반기' }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-5">
            <div id="data_table_A" class="col">
                <div class="map-content--item">
                    <div id="map_list" style="width:100%"></div>
                    <table class="table" id="record_rank_list_type1">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ID</th>
                                <th scope="col">기록</th>
                                <!--th scope="col">인증</th-->
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan='4'>no record</td></tr>
                        </tbody>
                    </table>
                </div>
                <div id="liveAlertPlaceholder"></div>
            </div>
        </div>
        <div></div>
        <div class="row">
            <div class="col">
                <strong id="place_name" class='mr-1'>
                    기록
                </strong>
            </div>
        </div>
        <div class="row">
            <div id="data_table_B" class="col">
                <div class="map-content--item">
                    <div id="map_list" style="width:100%"></div>
                    <table class="table" id="record_rank_list_type2">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ID</th>
                                <th scope="col">기록</th>
                                <!--th scope="col">인증</th-->
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($ranking_data) > 0)
                                @foreach ($ranking_data as $item)
                                    <tr>
                                        <th scope='row'>{{ $item['id'] }}</th>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['record'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan='4'>no record</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <div id="map_list" style="width:100%"></div>

    @include('mypage.myrecord_chart_mo')
@stop
@section('masternav_extra_item')
<span class="text-white" style="vertical-align:super" onclick="page_back();">
    <svg style="display: inline"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>
</span>
<span class="h3 text-white">랭킹</span>
@endsection
@section('javascript')
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=96gg9oc940&submodules=geocoder"></script>

	<script type="module">
        const now_date = new Date();
        const now_year = now_date.getYear();
        const now_month = now_date.getMonth();
        const month_type = now_month >= 6 ? 'last_half' : 'first_half';
        const sport_code = "short_lane";
        var record_list_select_block = false;

        $('#month_type').val(month_type).change();

        let map_id = "";
        function setRecentPlace() {
            $.ajax({
                url: "http://172.30.1.92:8080/ranking",
                method: "GET",
                dataType: "json",
            })
            .done(function(datas) {
                if (datas == 'undefined') {
                    return;
                }
                setRankList(datas, 'SHORT');
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });

        }
        //setRecentPlace();

        function setRankList(datas, type) {
            if (type == 'DISTANCE') {
                $('#record_rank_list_type1 tbody').empty();
                $.each(datas, function(idx, value) {
                    let distance_format = 0;
                    if (value.distance < 1000) {
                        distance_format = value.distance + "m";
                    } else {
                        distance_format = (parseFloat(value.distance) / 1000) + "km";
                    }
                    $('#record_rank_list_type1 tbody').append("<tr>"
                        + "<th scope='row'>" + (idx + 1) + "</th>"
                        + "<td>" + value.name + "</td>"
                        + "<td>" + distance_format + "</td>"
                        + "</tr>");
                });

                if (datas.length == 0) {
                    $('#record_rank_list_type1 tbody').append("<tr><td colspan='4'>no record</td></tr>");
                }
            } else if (type == "SHORT") {
                $('#record_rank_list_type2 tbody').empty();
                $.each(datas, function(idx, value) {
                    let distance_format = value.sport_code + "m";
                    $('#record_rank_list_type2 tbody').append("<tr>"
                        + "<th scope='row'>" + (idx + 1) + "</th>"
                        + "<td>" + value.name + "</td>"
                        + "<td>" + distance_format + "</td>"
                        + "</tr>");
                });

                if (datas.length == 0) {
                    $('#record_rank_list_type2 tbody').append("<tr><td colspan='4'>no record</td></tr>");
                }
            }
        }

        function page_back() {
            location.href= "/api/map";
        }
	</script>
@stop

