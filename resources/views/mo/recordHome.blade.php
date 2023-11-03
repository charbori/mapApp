@extends('layouts.master')

@section('style')
    @vite(['resources/css/home.css'])
    <style>
    .bd-header {
        position: absolute;
    }
    body {
        overflow-x: hidden;
    }
    </style>
@stop

@php
    $user_rank_title = '';
    $user_rank_id = '0';
    if (Auth::check() !== false) {
        $user_rank_title = $user_rank_map_list->title;
        $user_rank_id = $user_rank_map_list->map_id;
    }
@endphp
@section('contents')

    <div class="position-relative" style="overflow-y: scroll">
        <!--div id="map" style="width:100%;"></div>
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
        </div-->
        <div></div>
        <main class="container">
            <div class="row">
                <div class="col fs-4" style="margin:12px;">
                    <div class="d-flex justify-content-between">
                        <span class="p-2"data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                            <svg style="display:inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-justify" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M2 12.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                        </span>
                        <b class="p-2">Sport Record</b>
                        <b class="p-2">
                            @if (Auth::check())
                            <svg id="btn_alarm"  data-bs-toggle="dropdown" aria-expanded="false" style="display:inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                            </svg>
                            <span id="alarm_exist" class="position-absolute translate-middle p-2 bg-danger border border-light rounded-circle" style="display:none;">
                                <span class="visually-hidden">New alerts</span>
                            </span>
                            @include('components.alarm-list')
                            @else
                            <a href="/login">
                                <svg style="display:inline;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                </svg>
                            </a>
                            @endif
                        </b>
                    </div>
                </div>
            </div>

            @if (Auth::check())
            <div class="row p-2">
                <div class="col">
                    <div style="text-align:center; background-color:#e2e8f0; font-weight:bold;">
                        <div class="d-flex align-items-center">
                            <b class="p-2">개인 50m</b>
                            <b class="p-2">{{ $now_month_type == 'last_half' ? '후반기' : '전반기' }}</b>
                            @if (strlen($user_rank_title) > 0)
                            <a class="btn btn-sm flex-fill" style="background-color:#e2e8f0; font-weight:bold;" role="button" href="/api/record?map_id={{ $user_rank_id }}">
                                <svg style="display:inline" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg>
                                {{ $user_rank_title }}
                            </a>
                            @else
                            <span class="btn btn-sm flex-fill" style="background-color:#e2e8f0; font-weight:bold;" role="button">
                                <a id="place_name" class='p-2 btn btn-sm btn-dark' href="/api/search">지역검색</a>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col">
                    <div class="d-flex align-items-center p-3 my-3 text-black rounded shadow-sm" style="background-color:#9ba4b1">
                        <strong id="place_name" class='me-auto p-2 mr-1'>다른 지역 선택하기</strong>
                        <a id="place_name" class='p-2 btn btn-sm btn-dark' href="/api/search">지역검색</a>
                    </div>
                </div>
            </div>
            @endif
            <div class="col">
                <div class="map-content--item p-2">
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
        </main>
        @include('mo.followGroup', compact('now_month_type', 'user_rank_title', 'result_rank_list'))
    </div>
@stop

@section('masternav_extra_item')
    <div class="input-group input-group-sm">
    </div>
@endsection
@section('javascript')
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=96gg9oc940&submodules=geocoder"></script>

	<script type="module">
    window.onload  = function() {
        let rest_height = 0;
        let max_height = 0;
        let box = document.getElementById("list-group-handle");
        /*
        box.addEventListener('touchmove', function(e) {
            var touchLocation = e.targetTouches[0];
            rest_height = screen.height - touchLocation.pageY - 25;
            max_height = $(document).height() - screen.height;
            if (max_height > rest_height) {
                $("#list-group-handle").css({'transform':'translate(0px, -' + rest_height + 'px)'})
            }
        });

        box.addEventListener('touchend', function(e) {
            var x = parseInt(box.style.left);
            var y = parseInt(box.style.top);
        });
        */
    }
	</script>
@stop

@section('masternav_script')
	<script type="module">
        const now_date = new Date();
        const now_year = now_date.getYear();
        const now_month = now_date.getMonth();
        const month_type = now_month >= 6 ? 'last_half' : 'first_half';
        const sport_code = "short_lane";

        $('#search_map_btn').on('click', function() {
            if ($('#search_map_data').val() == '') {
                return appendAlert('검색어를 입력해주세요.', 'danger');
            }
            findGeocoder($('#search_map_data').val());
        });

        const alertPlaceholder = document.getElementById('navbarCollapse');
        const appendAlert = (message, type) => {
            const wrapper = document.createElement('div');
            if (typeof $('#search_alert_msg') != 'undefined')   $('#search_alert_msg').remove();
            wrapper.innerHTML = [
                `<div id="search_alert_msg" class="alert alert-${type} alert-dismissible" role="alert" style="padding : 0.5em 2.5em 0.5em 0.5em; margin:0em;" >`,
                `   <div>${message}</div>`,
                '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding : 0.7em 0.5em; margin:0em;"></button>',
                '</div>'
            ].join('');

            alertPlaceholder.append(wrapper);

            setTimeout(() => {
                if (typeof $('#search_alert_msg') != 'undefined') {
                    $('#search_alert_msg').remove();
                }
            }, 2000);
        }

        var search_data = {
            'page' : 1,
            'search_name' : '',
            'year' : now_date.getFullYear(),
            'month_type' : month_type,
            'sport_code' : sport_code
        }

        $('#month_type').val(month_type).change();

        getRankList(search_data, "FIRST");
        //getRankList(search_data, "");

        $('#record_rank_pagination_type1').prepend("<div class='mb-2 d-grid gap-2'><button type='button' class='btn btn-sm btn-light fw-bolder fs-6' id='add_record_type1' colspan='4'>+</button></div>");
        $('#record_rank_pagination_type2').prepend("<div class='mb-2 d-grid gap-2'><button type='button' class='btn btn-sm btn-light fw-bolder fs-6' id='add_record_type2' colspan='4'>+</button></div>");

        $('#add_record_type1, #add_record_type2').on('click', function() {
            sport_code_data = "";

            switch ($('#sport_code').val()) {
                case "short_lane":
                    sport_code_data = ($(this).attr('id') == 'add_record_type1') ? '50' : '100';
                    break;
                case "middle_lane":
                    sport_code_data = ($(this).attr('id') == 'add_record_type1') ? '200' : '400';
                    break;
                case "long_lane":
                    sport_code_data = ($(this).attr('id') == 'add_record_type1') ? '800' : '1500';
                    break;
            }

            location.href = "/api/record/edit?type=swim&sport_code=" + sport_code_data + "&map_id=" + '{{ $user_rank_id }}';
        });

        function getRankList(param, type) {
            var queryString = "page=" + param.page;
            queryString += "&search_name=" + param.search_name;
            queryString += "&year=" + param.year;
            queryString += "&month_type=" + param.month_type;
            queryString += "&sport_code=" + param.sport_code;
            queryString += "&sport_category=" + param.sport_category;
            queryString += "&map_id=" + '{{ $user_rank_id }}';
            $.ajax({
                url: "/api/record/show?" + queryString,
                method: "GET",
                dataType: "json"
            })
            .done(function(datas) {
                if (datas == 'undefined') {
                    return;
                }
                setRankList(datas, param.page, type);
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });

        }

        function setRankList(datas, page_id, type) {
            let record_datas = datas.data;
            let record_datas2 = datas.data2;
            let record_count = datas.count;
            let  record_count2 = datas.count2;

            if (datas.sport_category == 'player') {
                $('#sport_player').addClass('text-primary').removeClass('text-secondary').attr('data', 'Y');
                $('#sport_team').addClass('text-secondary').removeClass('text-primary').attr('data', '');
            } else {
                $('#sport_player').addClass('text-secondary').removeClass('text-primary').attr('data', '');
                $('#sport_team').addClass('text-primary').removeClass('text-secondary').attr('data', 'Y');
            }

            if (type == 'FIRST') {
                $('#record_rank_list_type1 tbody').empty();
                $('#record_rank_pagination_type1 ul').empty();
                $.each(record_datas, function(idx, r_data) {
                    $.each(r_data, function(key, value) {
                        if (key >= 5) {
                            return;
                        }
                        $('#record_rank_list_type1 tbody').append("<tr>"
                            + "<th scope='row'>" + (10 * (page_id - 1) + key + 1) + "</th>"
                            + "<td>" + value.user_id + "</td>"
                            + "<td>" + value.record + "</td>"
                            //+ "<td></td>"
                            + "</tr>");
                    });
                });
                if (record_count == 0) {
                    $('#record_rank_list_type1 tbody').append("<tr><td colspan='4'>no record</td></tr>");
                }
            } else {
                $('#record_rank_list_type2 tbody').empty();
                $('#record_rank_pagination_type2 ul').empty();
                $.each(record_datas2, function(idx, r2_data) {
                    $.each(r2_data, function(key, value) {
                        $('#record_rank_list_type2 tbody').append("<tr>"
                            + "<th scope='row'>" + (10 * (page_id - 1) + key + 1) + "</th>"
                            + "<td>" + value.user_id + "</td>"
                            + "<td>" + value.record + "</td>"
                            //+ "<td></td>"
                            + "</tr>");
                    });
                });
                if (record_count2 == 0) {
                    $('#record_rank_list_type2 tbody').append("<tr><td colspan='4'>no record</td></tr>");
                }
                if (record_count2 > 10) {
                    for (var i = 0; i < record_count2 / 10; i++) {
                        if (i == 10) return;
                        page_id_active = "";
                        if ((i + 1) == page_id) {
                            page_id_active = " active";
                        }
                        $('#record_rank_pagination_type2 ul').append('<li class="page-item' + page_id_active + '" aria-current="page">'
                                    + '<span class="page-link record_rank_pagination_type2_item"' + 'data="' + (i + 1) + '">' + (i + 1) + '</span>'
                                    + '</li>');
                    }
                }
                $('.record_rank_pagination_type2_item').on('click', function() {
                    record_id = $(this).attr('data');
                    search_data = {
                        'page' : record_id,
                        'search_name' : '',
                        'year' : $('select#year').val(),
                        'month_type' : $('select#month_type').val(),
                        'sport_code' : $('select#sport_code').val()
                    }
                    getRankList(search_data, "");
                });
            }
        }

    </script>
@endsection
