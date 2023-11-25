
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

function setRecentPlace() {
    $.ajax({
        url: "/api/record/mypage?id=",
        method: "GET",
        dataType: "json"
    })
    .done(function(datas) {
        if (datas == 'undefined') {
            return;
        }
        $.each(datas.map_data, function(idx, val) {
            $('#place_name' + idx).html(val.title.substring(0,7));
            $('#place_link' + idx).attr('href','/api/record?map_id=' + val.map_id);
        });
        $.each(datas.map_attachment, function(idx, val) {
            if (val.length > 0) {
                $('#place_img' + idx).remove();
                $('#place_link' + idx).prepend("<img src='http://content1.couhensoft.com/" + val + "'/>");
            } else {
                $('#place_link' + idx).prepend('<svg id="place_img' + idx + '" style="display:inline;" class="bd-placeholder-img rounded" width="36" height="36" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#dddddd"/></svg>');
            }
        });
    })
    .fail(function(xhr, status, errorThrown) {
        console.log('error');
    });
}
setRecentPlace();
