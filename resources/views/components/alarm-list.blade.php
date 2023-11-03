<div class="dropdow me-1">
    <ul id="alarm_dropdown_list" class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" aria-current="page" href="#">새로운 알림</a>
        </li>
    </ul>
</div>

@if(Auth::check())
    <script type="module">
        function getAlarm(id) {
            $.ajax({
                url: "/api/alarm?user_id=" + id,
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json"
            })
            .done(function(data) {
                console.log(data);
                if (typeof data != 'undefined' && data.length > 0) {
                    $('#alarm_exist').show();
                    $('#alarm_dropdown_list').empty();
                    $.each(data, function(idx, val) {
                        console.log(val);
                        if (val.type == 'AA') { // 새로운 기록알림
                            let alarm_color = val.alarm_reac == 'Y' ? 'text-black-50 ' : '';
                            $('#alarm_dropdown_list').append(
                                '<li>' +
                                '<a class="' + alarm_color + 'dropdown-item" aria-current="page" href="/api/follow?id=' + val.ref_id +
                                '&alarm_id=' + val.id + '&read=Y">' +
                                val.content + '</a>' +
                                '</li>'
                            );
                        }
                    });
                }
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });
        }
        getAlarm( {{ Auth::user()->id }} );
    </script>
@endif
