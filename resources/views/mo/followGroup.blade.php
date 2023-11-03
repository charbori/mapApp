<main class="container">
    <b class="p-2">팔로우 &#183; 기록</b>
    @if (Auth::check() && count($result_rank_list) > 0)
        @foreach ($result_rank_list['50'] as $val)
        <div class="row p-2">
            <div class="col" style="padding-left:6px; margin-left:6px; background-color:#e2e8f0; font-weight:bold;">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="{{ $val->path }}"/>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div>
                            <span class="ft-1 pr-1">
                                <a href="/api/follow?id={{ $val->follower }}">
                                    {{ $val->name }}
                                </a>
                            </span>
                        </div>
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
        @endforeach
    @endif
</main>
