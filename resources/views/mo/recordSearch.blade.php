@extends('layouts.master')

@section('style')
    <style>
    .bd-header {
        position: absolute;
    }
    .scrolled-down{
        transform:translateY(-120%); transition: all 0.3s ease-in-out;
    }
    .scrolled-up{
        transform:translateY(0); transition: all 0.3s ease-in-out;
    }
    </style>
@stop

@php
    $user_rank_title = '';
    $user_rank_id = '0';
    if (Auth::check() !== false) {
        if ($user_rank_map_list) {
            $user_rank_title = $user_rank_map_list[0]->title;
            $user_rank_id = $user_rank_map_list[0]->map_id;
        }
    }
@endphp
@section('contents')
    <div class="position-relative" style="margin-top: 70px;">
        <div class="row mt-1 mb-2">
            <div class="col" id="map_list_items">
                <div class="list-group">
                </div>
            </div>
        </div>
    </div>
@stop
@section('masternav_extra_item')
    <div class="search_navigation row mt-1 mb-2 pr-0">
        <div class="col mr-0" style="padding-right:0px;">
            <span>
                <b style="vertical-align:super">지역 수영장 검색</b>
                <a id="place_name" style="vertical-align:super" class="ml-1 btn btn-sm btn-light" href="javascript:link_geo_map();">지도로보기</a>
            </span>
            <div class="input-group input-group-sm mt-1">
                <span class="input-group-text"  aria-label="Toggle navigation" onclick="page_back();">
                    <svg style="display: inline"  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </span>
                <input placeholder="신목로 53" type="text" id="search_map_data" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                <span class="input-group-text" id="search_map_btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>
                </span>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=96gg9oc940&submodules=geocoder"></script>

	<script type="module">
    window.onload  = function() {
        let rest_height = 0;
        let max_height = 0;
        let box = document.getElementById("list-group-handle");
    }

    let last_scroll_top = 0;
    window.addEventListener('scroll', function() {
        let scroll_top = window.scrollY;
        if(scroll_top < last_scroll_top) {
            $('.search_navigation').removeClass('scrolled-down');
            $('.search_navigation').addClass('scrolled-up');
        }
        else {
            $('.search_navigation').removeClass('scrolled-up');
            $('.search_navigation').addClass('scrolled-down');
        }
        last_scroll_top = scroll_top;
    });
	</script>
    <script>
        function link_map (map_id) {
            if (typeof map_id == 'undefined') return;
            location.href= "/api/record?map_id=" + map_id;
        }

        function page_back() {
            location.href= "/api/map";
        }

        function link_geo_map() {
            location.href= "/api/map?view_type=map";
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
            $('#map_list_items .list-group').empty();
            find_search_map_list($('#search_map_data').val());
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

        let search_data = {
            'page' : 1,
            'search_name' : '',
            'year' : now_date.getFullYear(),
            'month_type' : month_type,
            'sport_code' : sport_code
        }

        $('#month_type').val(month_type).change();

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

            location.href = "/api/record/edit?type=swim&sport_code=" + sport_code_data + "&map_id=" + '{{ $user_rank_id }}';
        });

        function getRankList(param, type) {
            let queryString = "page=" + param.page;
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
                    for (let i = 0; i < record_count / 10; i++) {
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
                    for (let i = 0; i < record_count2 / 10; i++) {
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
                        'search_name' : '',
                        'year' : $('select#year').val(),
                        'month_type' : $('select#month_type').val(),
                        'sport_code' : $('select#sport_code').val()
                    }
                    getRankList(search_data, "");
                });
            }
        }

        find_search_map_list('목동');
        function find_search_map_list(param) {
            if (typeof param == 'undefined' && param == '') return;
            $.ajax({
                url: "/api/map/show?search=" + param,
                method: "GET",
                dataType: "json"
            })
            .done(function(param) {
                if (param == 'undefined') {
                    return;
                }
                $.each(param, function(idx, value) {
                    initMapList(value);
                });
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });
        }

        function find_search_map_idx(item) {
            $.ajax({
                url: "/api/map/show?long=" + item.x + "&lat=" + item.y,
                method: "GET",
                dataType: "json"
            })
            .done(function(param) {
                if (param == 'undefined') {
                    return;
                }
                $.each(param, function(idx, value) {
                    if (idx == 0) {
                        make_marker_UI = getUIContentDetailInfo(param[idx], true);
                        markerSelected.push(getUIMakeMarker(param[idx], make_marker_UI, true));
                    } else {
                        make_marker_UI = getUIContentDetailInfo(param[idx], false);
                        marker_maked = getUIMakeMarker(param[idx], make_marker_UI, false);
                    }
                    initMapList(value);
                });
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });
        }

        function initMapList(value) {
            if (typeof value == 'undefined' || value.length == 0) return $('#map_list').empty();
            let path_name = '';
            if (value.path.length < 12) path_name = '<svg xmlns="http://www.w3.org/2000/svg"  width="64" height="64" class="bi bi-card-image flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/><path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z"/></svg>';
            else path_name = '<img src="' + value.path + '" alt="twbs" width="64" height="64" class="rounded-circle flex-shrink-0">'
            map_list_item = ['<a href="#" style="background-color:white; border-width:0 0 1px 0;" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true" onClick="link_map(' + value.id + ')">',
                path_name,
                '<div class="d-flex gap-2 w-100 justify-content-between">',
                '<div>',
                '<h6 class="mb-0">' + value.name + 'g</h6>',
                '<p class="mb-0 opacity-75">player ' + value.player_cnt + '</p>',
                '<p class="mb-0 opacity-75">record ' + value.record_cnt + '</p>',
                '<p class="mb-0 opacity-75">' + value.description + '</p>',
                '</div>',
                '<small class="opacity-50 text-nowrap">1w</small>',
                '</div>',
                '</a>'
            ].join('');
            $('#map_list_items .list-group').append(map_list_item);
        }
    </script>

@endsection
