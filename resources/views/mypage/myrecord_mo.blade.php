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
    $user_param = Auth::user();
    $user_name = $user_param['name'];
@endphp
@section('contents')
<div class="col" style="height:1em; background-color:whitesmoke"></div>
    <main class="container">
        <div class="row">
            <div class="col">
                <strong id="place_name" class='mr-1'>
                </strong>
                <button data="data_table_A" type="button" id="sport_record_first" class="btn fs-6 text-primary fw-bolder" style="padding:0px">50m</button>
                <button data="data_table_B" type="button" id="sport_record_second" class="btn fs-6 text-secondary fw-bolder" style="padding:0px">100m</button>
            </div>
            <div class="col-3">
                <div class="accordion accordion-flush" style="border:none" id="accordionExample">
                    <div class="accordion-item" style="border:none">
                        <h2 class="accordion-header" id="headingOne" style="border:none">
                            <a style="padding:0.5rem 0.5rem 0.5rem 0.5rem; background-color:#ffffff" class="accordion-button collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            </a>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body row">
                        <div class="col-4" style="padding-right:0px;">
                            <select id="year" class="mt-1 form-select form-select-sm fw-bolder" aria-label=".form-select-sm example">
                                <option value="2023" selected>2023</option>
                            </select>
                        </div>
                        <div class="col-4" style="padding-left:6px; padding-right:6px;">
                            <select id="month_type" class="pl-1 mt-1 form-select form-select-sm fw-bolder fs-7" aria-label=".form-select-sm example">
                                <option value="first_half">전반기</option>
                                <option value="last_half">하반기</option>
                            </select>
                        </div>
                        <div class="col-4" style="padding-left:0px;">
                            <select id="sport_code" class="pl-1 mt-1 form-select form-select-sm fw-bolder fs-7" aria-label=".form-select-sm example">
                                <option value="short_lane">단거리</option>
                                <option value="middle_lane">중거리</option>
                                <option value="long_lane">장거리</option>
                            </select>
                        </div>
                        <hr class="mt-1">
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-5">
            <!--div class="col-3">
                <div class="map-content-img-item">
                    <img src="https://search.pstatic.net/common/?src=https%3A%2F%2Fldb-phinf.pstatic.net%2F20150831_112%2F1440987756524Gez5F_JPEG%2F11866967_0.jpg"/>
                </div>
            </div-->
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
                    <nav aria-label="..." id="record_rank_pagination_type1">
                        <ul class="pagination pagination-sm">
                            <li class="page-item active" aria-current="page">
                                <span class="page-link record_rank_pagination_type1_item" data="1">1</span>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div id="liveAlertPlaceholder"></div>
            </div>
            <div id="data_table_B" class="col" style="display:none">
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
                            <tr><td colspan='4'>no record</td></tr>
                        </tbody>
                    </table>
                    <div class="col align-self-center">
                        <nav aria-label="..." id="record_rank_pagination_type2">
                            <ul class="pagination pagination-sm">
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link record_rank_pagination_type2_item" data="1">1</span>
                                </li>
                            </ul>
                        </nav>
                    </div>
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
<span class="h3 text-white">내 기록</span>
@endsection
@section('javascript')
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=96gg9oc940&submodules=geocoder"></script>

    <script>
        function page_back() {
            location.href= "/api/map";
        }
    </script>
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
                url: "/api/record/mypage",
                method: "GET",
                dataType: "json"
            })
            .done(function(datas) {
                if (datas == 'undefined') {
                    return;
                }
                let mp_record_data = datas.map_data;
                $('#place_name').html(mp_record_data[0].title.substring(0,7));
                map_id = mp_record_data[0].map_id;
                let search_data = {
                    'page' : 1,
                    'search_name' : '{{ $user_name }}',
                    'year' : $('select#year').val(),
                    'month_type' : $('select#month_type').val(),
                    'sport_code' : $('select#sport_code').val(),
                    'map_id'     : map_id,
                }
                getRankList(search_data, "FIRST");
                getRankList(search_data, "");
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });

        }
        setRecentPlace();

        function getRankList(param, type) {
            let queryString = "page=" + param.page;
            queryString += "&search_name=" + param.search_name;
            queryString += "&year=" + param.year;
            queryString += "&month_type=" + param.month_type;
            queryString += "&sport_code=" + param.sport_code;
            queryString += "&sport_category=" + param.sport_category;
            queryString += "&map_id=" + param.map_id;
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

                if (datas.data && type == 'FIRST') {
                    let chart_data = [0,0,0,0,0,0]; // 전,후반기 월별 데이터

                    $.each(datas.data, function(key, values) {
                        let month_data_str = datas.data[key][0].reg_date.substring(5,7);

                        if (month_type == 'last_half') {
                            switch (month_data_str) {
                                case '07':
                                    month_data_str = 0;
                                    break;
                                case '08':
                                    month_data_str = 1;
                                    break;
                                case '09':
                                    month_data_str = 2;
                                    break;
                                case '10':
                                    month_data_str = 3;
                                    break;
                                case '11':
                                    month_data_str = 4;
                                    break;
                                case '12':
                                    month_data_str = 5;
                                    break;
                            }
                        } else {
                            switch (month_data_str) {
                                case '01':
                                    month_data_str = 0;
                                    break;
                                case '02':
                                    month_data_str = 1;
                                    break;
                                case '03':
                                    month_data_str = 2;
                                    break;
                                case '04':
                                    month_data_str = 3;
                                    break;
                                case '05':
                                    month_data_str = 4;
                                    break;
                                case '06':
                                    month_data_str = 5;
                                    break;
                            }
                        }
                        chart_data[month_data_str] = datas.data[key][0].record;
                        data.datasets[0].data = chart_data;
                        data.datasets[0].label = datas.data[key][0].sport_code + 'm';
                    });
                    new Chart(ctx, config);
                    //mypage.myrecord_chart_mo
                    //https://www.chartjs.org/docs/latest/samples/line/line.html
                }
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });

        }

        function setRankList(datas, page_id, type) {
            let record_datas = datas.data;
            let record_datas2 = datas.data2;
            let record_count = datas.count;
            let record_count2 = datas.count2;

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

                if (record_count > 10) {
                    for (var i = 0; i < record_count / 10; i++) {
                        if (i == 10) return;
                        page_id_active = "";
                        if ((i + 1) == page_id) {
                            page_id_active = " active";
                        }
                        $('#record_rank_pagination_type1 ul').append('<li class="page-item' + page_id_active + '" aria-current="page">'
                                    + '<span class="page-link record_rank_pagination_type1_item"' + 'data="' + (i + 1) + '">' + (i + 1) + '</span>'
                                    + '</li>');
                    }
                }
                $('.record_rank_pagination_type1_item').on('click', function() {
                    record_id = $(this).attr('data');
                    let search_data = {
                        'page' : record_id,
                        'search_name' : '{{ $user_name }}',
                        'year' : $('select#year').val(),
                        'month_type' : $('select#month_type').val(),
                        'sport_code' : $('select#sport_code').val(),
                        'map_id'     : map_id,
                    }
                    getRankList(search_data, "FIRST");
                });
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
                    let search_data = {
                        'page' : record_id,
                        'search_name' : '{{ $user_name }}',
                        'year' : $('select#year').val(),
                        'month_type' : $('select#month_type').val(),
                        'sport_code' : $('select#sport_code').val(),
                        'map_id'     : map_id,
                    }
                    getRankList(search_data, "");
                });


            }

        }

        $('#sport_record_first, #sport_record_second').on('click', function() {

            btn_id = $(this).attr('data');

            if (btn_id == 'data_table_A') {
                $('#data_table_A').show();
                $('#data_table_B').hide();
                $('#sport_record_first').removeClass('text-secondary').addClass('text-primary');
                $('#sport_record_second').removeClass('text-primary').addClass('text-secondary');
            } else {
                $('#data_table_A').hide();
                $('#data_table_B').show();
                $('#sport_record_first').removeClass('text-primary').addClass('text-secondary');
                $('#sport_record_second').removeClass('text-secondary').addClass('text-primary');
            }
        });
	</script>
@stop

