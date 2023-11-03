function getAlaram(id) {
    $.ajax({
        url: "/api/alarm?user_id=" + id,
        method: "GET",
        dataType: "json"
    })
    .done(function(data) {
    })
    .fail(function(xhr, status, errorThrown) {
        console.log('error');
    });
}
