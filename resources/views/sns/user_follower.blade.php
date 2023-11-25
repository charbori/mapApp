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
@section('contents')
<div class="col" style="height:1em; background-color:whitesmoke"></div>
    <main class="container">
        <div class="row p-2">
            <div class="col" style="padding-left:6px; margin-left:6px; background-color:#e2e8f0; font-weight:bold;">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="{{ $user_data->path }}"/>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div>
                            <span class="ft-3 pr-1">
                                {{ $user_data->name }}
                            </span>
                            @if (Auth::check() && $is_my_follow == false)
                            <a id="btn-followed" class="badge bg-primary" style="{{ $is_followed ? 'display:none;' : '' }} background-color:#e2e8f0; font-weight:bold;" role="button" href="javascript:user_follow('follow', {{ $user_data->id }})">follow</a>
                            <a id="btn-unfollowed" class="badge bg-light text-dark" style="{{ $is_followed == false ? 'display:none;' : '' }} background-color:#e2e8f0; font-weight:bold;" role="button" href="javascript:user_follow('unfollow', {{ $user_data->id }})">unfollow</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-1">
                    <div class="flex-fill text-center">
                        <span>Follow</span>
                        <div>{{ $follow_cnt }}</div>
                    </div>
                    <div class="flex-fill text-center">
                        <span>Follower</span>
                        <div>{{ $follower_cnt }} </div>
                    </div>
                    <div class="flex-fill text-center">
                        <span>Badge</span>
                        <div>{{ $badge_cnt }} </div>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="mt-1" style="font-weight: bold; font-size:24px;">Records</h2>
        <div class="row p-2">
            @if (count($result_rank_list) > 0)
                @foreach ($result_rank_list as $val)
                <div class="col mt-2" style="padding-left:6px; margin-left:6px; background-color:#e2e8f0; font-weight:bold;">
                    <div class="row p-2">
                        <div class="col" style="padding-left:6px; margin-left:6px; background-color:#e2e8f0; font-weight:bold;">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ $my_user_attach }}"/>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div>
                                        <img id="swim_img" src="/build/images/swimming_icon.png" style="display:inline" width="16" height="16"/>
                                        <span class="ft-1 align-middle">
                                            {{ $val->created_at }}
                                            <a class="" style="background-color:#e2e8f0; font-weight:bold;" role="button" href="/api/record?map_id={{ $val->map_id }}">
                                                {{ $val->title }}
                                                <svg style="display:inline" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mb-1 bi bi-box-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-1">
                                <div class="flex-fill">
                                    <div>종목</div>
                                    <span class="">{{ $val->sport_code }}m</span>
                                </div>
                                <div class="flex-fill">
                                    <div>기록</div>
                                    <span>{{ $val->record }}초</span>
                                </div>
                                <div class="flex-fill">
                                    <div>award</div>
                                    <span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </main>

    <div id="map_list" style="width:100%"></div>
@stop
@section('masternav_extra_item')
<span class="text-white" style="vertical-align:super" onclick="page_back();">
    <svg style="display: inline"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>
</span>
<span class="h3 text-white">{{ $user_data->name }}</span>
@endsection
@section('javascript')
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=96gg9oc940&submodules=geocoder"></script>

    <script>
        function page_back() {
            location.href = "/api/map";
        }
        function user_follow(type, user_id) {
            $.ajax({
                url: "/api/follow",
                method: "post",
                data : { "type" : type, "user_id" : user_id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json"
            })
            .done(function(datas) {
                if (type == 'follow') {
                    $('#btn-followed').hide();
                    $('#btn-unfollowed').show();
                } else {
                    $('#btn-followed').show();
                    $('#btn-unfollowed').hide();
                }
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });
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
	</script>
@stop

