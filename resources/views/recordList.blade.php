@extends('layouts.master')

@section('style')
    @vite(['resources/css/home.css'])
@stop

@php
// 필수 변수 초기화
$sport_category = empty($sport_category) ? 'player' : $sport_category;
@endphp

@section('contents')
    <div class="col ml-3 mt-3">
        <span class="h1 text-dark">미진수영장</span>
        <span class="h3 text-dark">순위</span>
    </div>
    <div class="col" style="height:1em; background-color:whitesmoke"></div>
    <main class="container">
        <div class="row g-5">
            <div class="col">
                <button data="{{ $sport_category == 'player' ? 'Y' : '' }}" type="button" id="sport_player" class="btn fs-5 {{ $sport_category == 'player' ? 'text-primary' : 'text-secondary' }} fw-bolder">Player</button>
                <button data="{{ $sport_category == 'team' ? 'Y' : '' }}" type="button" id="sport_team" class="btn fs-5 {{ $sport_category == 'team' ? 'text-primary' : 'text-secondary' }} fw-bolder">Team</button>
                <hr>
                <select id="year" class="mt-1 form-select form-select-sm fw-bolder" aria-label=".form-select-sm example">
                    <option value="2023" selected>2023</option>
                </select>
                <select id="month_type" class="pl-1 mt-1 form-select form-select-sm fw-bolder fs-7" aria-label=".form-select-sm example">
                    <option value="first_half">전반기</option>
                    <option value="last_half">하반기</option>
                </select>
                <hr class="mt-1">
                <select id="sport_code" class="pl-1 mt-1 form-select form-select-sm fw-bolder fs-7" aria-label=".form-select-sm example">
                    <option value="short_lane">단거리</option>
                    <option value="middle_lane">중거리</option>
                    <option value="long_lane">장거리</option>
                </select>
            </div>
            <div class="col-5">
                <div class="map-content--item">
                    <span id="first_theme" class="h3 text-dark">50m</span>
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
                    <div class="input-group input-group-sm mt-1">
                        <span class="input-group-text" id="inputGroup-sizing-sm">ID</span>
                        <input type="text" id="search_name_value" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                        <span class="input-group-text" id="search_name_type1">검색하기</span>
                    </div>
                </div>
                <div id="liveAlertPlaceholder"></div>
            </div>
            <div class="col-5">
                <div class="map-content--item">
                    <span id="second_theme" class="h3 text-dark">100m</span>
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
                        <div class="input-group input-group-sm mt-1">
                            <span class="input-group-text" id="inputGroup-sizing-sm">ID</span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                            <span class="input-group-text" id="search_name_type2">검색하기</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div id="map_list" style="width:100%"></div>
@stop

@section('javascript')
	<script type="module">
        const now_date = new Date();
        const now_month = now_date.getMonth();
        const month_type = now_month > 6 ? 'last_half' : 'first_half';
        const sport_code = "short_lane";

        search_data = {
            'page' : 1,
            'search_name' : '',
            'year' : now_date.getFullYear(),
            'month_type' : month_type,
            'sport_code' : sport_code
        }

        getRankList(search_data, "FIRST");
        getRankList(search_data, "");

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

            @if (Auth::check() !== false)
                location.href = "/api/record/edit?type=swim&sport_code=" + sport_code_data + "&map_id=" + '{{ $view_map_id }}';
            @endif
        });

        function getRankList(param, type) {
            queryString = "page=" + param.page;
            queryString += "&search_name=" + param.search_name;
            queryString += "&year=" + param.year;
            queryString += "&month_type=" + param.month_type;
            queryString += "&sport_code=" + param.sport_code;
            queryString += "&sport_category=" + param.sport_category;
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
            record_datas = datas.data;
            record_datas2 = datas.data2;
            record_count = datas.count;
            record_count2 = datas.count2;

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
                    search_data = {
                        'page' : record_id,
                        'search_name' : '',
                        'year' : $('select#year').val(),
                        'month_type' : $('select#month_type').val(),
                        'sport_code' : $('select#sport_code').val()
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

        const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
        const appendAlert = (message, type) => {
            const wrapper = document.createElement('div')
            wrapper.innerHTML = [
                `<div class="alert alert-${type} alert-dismissible mt-1" role="alert">`,
                `   <div>${message}</div>`,
                '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                '</div>'
            ].join('')

            alertPlaceholder.append(wrapper)
        }

        // 아이디 검색 search id
        $('#search_name_type1').on('click', function() {
            if ($('#search_name_value').val() == "") {
                appendAlert('검색하실 ID를 입력하세요.', 'danger')
            }
            search_data2 = {
                'page' : 1,
                'search_name' : $('#search_name_value').val()
            }
            getRankList(search_data2, "FIRST");
        });

        $('button#sport_team, button#sport_player').on('click', function() {
            if ($(this).attr('id') == 'sport_team' || $(this).attr('id') == 'sport_player') {
                selected_sport_category = $(this).attr('id') == 'sport_player' ? 'player' : 'team';
            } else {
                selected_sport_category = $('button#sport_player').attr('data') == 'Y' ? 'player' : 'team';
            }
            getRecordDatas(selected_sport_category);
        });

        $('select#year, select#month_type, select#sport_code').on('change', function() {
            getRecordDatas('');
        });

        function getRecordDatas(selected_sport_category) {
            search_data = {
                'page' : 1,
                'search_name' : '',
                'year' : $('select#year').val(),
                'month_type' : $('select#month_type').val(),
                'sport_code' : $('select#sport_code').val(),
                'sport_category' : selected_sport_category
            }

            switch ($('#sport_code').val()) {
                case "short_lane":
                    $('#first_theme').html("50m");
                    $('#second_theme').html("100m");
                    break;
                case "middle_lane":
                    $('#first_theme').html("200m");
                    $('#second_theme').html("400m");
                    break;
                case "long_lane":
                    $('#first_theme').html("800m");
                    $('#second_theme').html("1500m");
                    break;
            }
            getRankList(search_data, "FIRST");
            getRankList(search_data, "");
        }

        // 등록하기 (로그인 제한)
        @if (Auth::check() === false)
            const alertTrigger = document.getElementById('add_record_type1')
            if (alertTrigger) {
                alertTrigger.addEventListener('click', () => {
                    appendAlert('로그인해주세요.', 'danger')
                })
            }
        @endif

        setTimeout(() => {
            $(".alert.alert-success").remove();
        }, 5000);
	</script>
@stop
