@extends('layouts.master')

@section('style')
    @vite(['resources/css/home.css'])
@stop
@section('contents')
    <div id="map" style="width:100%;height:400px;"></div>
    <main id="map_list" class="container">
        <div class="col ml-3 mt-3">
            <span id="place_result" class="h3 text-dark">'목동' 검색결과</span>
        </div>
    </main>
@stop
@section('masternav_extra_item')
    <li class="nav-item">
        <div class="input-group input-group-sm ml-3 mt-1">
            <input placeholder="신목로 53" type="text" id="search_map_data" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            <button class="btn btn-sm btn-outline-secondary" id="search_map_btn" type="button">search</button>
        </div>
    </li>
@endsection
@section('javascript')
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=96gg9oc940&submodules=geocoder"></script>
    @vite(['resources/js/jquery-3.6.1.min.js'])
	<script>
	var map = new naver.maps.Map("map", {
	    center: new naver.maps.LatLng(37.520168953881715, 126.8722931226252),
	    zoom: 15,
	    mapTypeControl: true
	});

    var marker_data = null;
	var infoWindow = new naver.maps.InfoWindow({
	    anchorSkew: true
	});
    var newMarker = null;
    var markerSelected = [];

    var markerList = [];

	map.setCursor('pointer');

	function searchCoordinateToAddress(latlng) {

	    //infoWindow.close();

	    naver.maps.Service.reverseGeocode({
            coords: latlng,
            orders: [
                naver.maps.Service.OrderType.ADDR,
                naver.maps.Service.OrderType.ROAD_ADDR
            ].join(',')
	    }, function(status, response) {
            if (status === naver.maps.Service.Status.ERROR) {
                return alert('Something Wrong!');
            }
            var lat_val = latlng.lat();
            var long_val = latlng.lng();

            var items = response.v2.results,
                address = '',
                htmlAddresses = [];

            for (var i=0, ii=items.length, item, addrType; i<ii; i++) {
                item = items[i];
                address = makeAddress(item) || '';
                addrType = item.name === 'roadaddr' ? '[도로명 주소]' : '[지번 주소]';

                htmlAddresses.push((i+1) +'. '+ addrType +' '+ address);
            }
            // 재설정시 삭제함
            if (newMarker != null) {
                newMarker.setMap(null);
            }

            newMarker = new naver.maps.Marker({
                position: new naver.maps.LatLng(lat_val, long_val),
                map: map,
                icon: {
                    content: [
                        '<div style="position: absolute; width:150px; top: 0px; left: 0px; z-index: 0; margin: 0px; padding: 0px; display: block; cursor: default; box-sizing: content-box !important;">',
                        '<div class="ml-4 mb-1" style="width: 15px; height: 15px; border-radius: 50%; border: solid 5px; border-color:gray;"></div>',
                        '<a class="btn btn-sm btn-secondary" role="button" href="/api/map/edit?latitude=' + lat_val + '&longitude=' + long_val + '&mapType=map">등록</a>',
                        '</div>'
                    ].join(''),
                    size: new naver.maps.Size(38, 58),
                    anchor: new naver.maps.Point(19, 58),
                }
            });

            reInitId = $('#map_marker_selected').attr('data');
            reIdx = false;
            $.each(marker_data, function(idx, data) {
                if (data.id == reInitId) {
                    reIdx = idx;
                }
            });

            if (typeof reInitId != 'undefined' && reIdx !== false) {
                //$('#map_marker_selected').remove();
                if (markerSelected.length > 0) {
                    $.each(markerSelected, function(i, v) {
                        v.setMap(null);
                    });
                    markerSelected = [];
                }
                make_marker_UI = getUIContentDetailInfo(marker_data[reIdx], false);
                getUIMakeMarker(marker_data[reIdx], make_marker_UI, false);
            }
	    });
	}

    function setUIMakeMarker(idx) {
        make_marker_UI = getUIContentDetailInfo(marker_data[idx], false);
        getUIMakeMarker(marker_data[idx], make_marker_UI, false);
    }

	function searchAddressToCoordinate(address, datas) {
	    naver.maps.Service.geocode({
            query: address
	    }, function(status, response) {
            if (status === naver.maps.Service.Status.ERROR) {
                return alert('Something Wrong!');
            }

            if (response.v2.meta.totalCount === 0) {
                return alert('totalCount' + response.v2.meta.totalCount);
            }

            var htmlAddresses = [],
                item = response.v2.addresses[0],
                point = new naver.maps.Point(item.x, item.y);

            if (item.roadAddress) {
                htmlAddresses.push('[도로명 주소] ' + item.roadAddress);
            }

            if (item.jibunAddress) {
                htmlAddresses.push('[지번 주소] ' + item.jibunAddress);
            }

            if (item.englishAddress) {
                htmlAddresses.push('[영문명 주소] ' + item.englishAddress);
            }

            $.ajax({
                url: "/api/map/show?long=" + item.x + "&lat=" + item.y,
                method: "GET",
                dataType: "json"
            })
            .done(function(param) {
                if (param == 'undefined') {
                    return;
                }

                marker_data = param;
                $.each(marker_data, function(idx, value) {
                    if (idx == 0) {
                        make_marker_UI = getUIContentDetailInfo(marker_data[idx], true);
                        markerSelected.push(getUIMakeMarker(marker_data[idx], make_marker_UI, true));
                    } else {
                        make_marker_UI = getUIContentDetailInfo(marker_data[idx], false);
                        marker_maked = getUIMakeMarker(marker_data[idx], make_marker_UI, false);
                    }
                    initMapList(value);
                });

                $('#place_result').html("'" + address + "' 검색결과");
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });
	    });
	}

    function getUIContentDetailInfo(data_content, selected) {
        if (selected) {
            contentUI = [
                            '<div class="pt-1 pl-1 pb-1 pr-1" style="background-color:#ffffff; border-radius: 3%; border: solid 1px; border-color:gray;"',
                            '<div id="map_marker_selected" data="' + data_content.id + '"style="padding:10px;min-width:200px;line-height:150%;">',
                            '<img style="width:100px; height:100px;" src="' + data_content.path + '"/>',
                            '<span id="map_marker_name">' + data_content.name + "</span><br>",
                            '<span id="map_marker_type">' + data_content.type + "</span><br>",
                            '<span id="map_marker_description">' + data_content.description + "</span><br>",
                            '<a class="btn btn-sm btn-secondary" role="button" href="/api/record?map_id=' + data_content.id + '">기록보기</a>',
                            '</div>',
                            '</div>'
                        ].join('\n');
        } else {
            contentUI = [
                            '<div class="div_map_marker" style="position: absolute; top: 0px; left: 0px; z-index: 0; margin: 0px; padding: 0px; display: block; cursor: default; box-sizing: content-box !important;">',
                            '<button id="map_marker_item" data="' + data_content.id + '" type="button" class="btn btn-sm btn-secondary">' + data_content.name + '</button>',
                            '</div>'
                        ].join('');
        }
        return contentUI;
    }

    function getUIMakeMarker(data_content, make_marker_UI, selected) {
        if (selected) {
            point_item = new naver.maps.Point(data_content.long, data_content.lat);
            map.setCenter(point_item);
        }
        var marker_maked = null;
        marker_maked = new naver.maps.Marker({
                        position: new naver.maps.LatLng(data_content.lat, data_content.long),
                        map: map,
                        data: data_content.id,
                        icon: {
                            content: make_marker_UI,
                            size: new naver.maps.Size(38, 58),
                            anchor: new naver.maps.Point(19, 58),
                        }
                    });

        naver.maps.Event.addListener(marker_maked, 'click', function(e) {
            marker_id = $(marker_maked)[0].data;
            $.each(marker_data, function(idx, value) {
                if (value.id == marker_id) {
                    $.each(markerSelected, function(i, v) {
                        v.setMap(null);
                    });
                    markerSelected = [];

                    new_make_marker_UI = getUIContentDetailInfo(marker_data[idx], true);
                    markerSelected.push(getUIMakeMarker(marker_data[idx], new_make_marker_UI, true));
                }
            });
        });

        return marker_maked;
    }

    function initMapList(value) {
        if (typeof value == 'undefined' || value.length == 0) return $('#map_list').empty();
        map_list_item = ['<div class="row mt-3" onClick="link_map(' + value.id + ')">',
                    '<div style="width:130px">',
                    '<img style="width:100px; height:100px; display:inline-block;" src="' + value.path + '"/>',
                    '</div>',
                    '<div class="col">',
                        '<div>' + value.name + '</div>',
                        '<div>player ' + value.player_cnt + '</div>',
                        '<div>record ' + value.record_cnt + ' </div>',
                        '<div>' + value.description + '</div>',
                    '</div>',
            '</div>'
        ].join('');
        $('#map_list').append(map_list_item);
    }

	function initGeocoder() {
	    map.addListener('click', function(e) {
		    searchCoordinateToAddress(e.coord);
	    });

	    $('#address').on('keydown', function(e) {
		var keyCode = e.which;

		if (keyCode === 13) {
		    searchAddressToCoordinate($('#address').val());
		}
	    });

	    $('#submit').on('click', function(e) {
		e.preventDefault();

		searchAddressToCoordinate($('#address').val());
	    });

	    var datas = ['bird_1', '11', 'charbori'];
	    searchAddressToCoordinate('신목로 53', datas);
	}

    function findGeocoder(search_geo_data) {
	    searchAddressToCoordinate(search_geo_data, []);
    }

	function makeAddress(item) {
	    if (!item) {
		return;
	    }

	    var name = item.name,
		region = item.region,
		land = item.land,
		isRoadAddress = name === 'roadaddr';

	    var sido = '', sigugun = '', dongmyun = '', ri = '', rest = '';

	    if (hasArea(region.area1)) {
		sido = region.area1.name;
	    }

	    if (hasArea(region.area2)) {
		sigugun = region.area2.name;
	    }

	    if (hasArea(region.area3)) {
		dongmyun = region.area3.name;
	    }

	    if (hasArea(region.area4)) {
		ri = region.area4.name;
	    }

	    if (land) {
		if (hasData(land.number1)) {
		    if (hasData(land.type) && land.type === '2') {
			rest += '산';
		    }

		    rest += land.number1;

		    if (hasData(land.number2)) {
			rest += ('-' + land.number2);
		    }
		}

		if (isRoadAddress === true) {
		    if (checkLastString(dongmyun, '면')) {
			ri = land.name;
		    } else {
			dongmyun = land.name;
			ri = '';
		    }

		    if (hasAddition(land.addition0)) {
			rest += ' ' + land.addition0.value;
		    }
		}
	    }

	    return [sido, sigugun, dongmyun, ri, rest].join(' ');
	}

	function hasArea(area) {
	    return !!(area && area.name && area.name !== '');
	}

	function hasData(data) {
	    return !!(data && data !== '');
	}

	function checkLastString (word, lastString) {
	    return new RegExp(lastString + '$').test(word);
	}

	function hasAddition (addition) {
	    return !!(addition && addition.value);
	}

	naver.maps.onJSContentLoaded = initGeocoder;

	</script>
@stop

@section('masternav_script')
    <script>
        $('#search_map_btn').on('click', function() {
            if ($('#search_map_data').val() == '') {
                return appendAlert('검색어를 입력해주세요.', 'danger');
            }
            findGeocoder($('#search_map_data').val());
        });

        const alertPlaceholder = document.getElementById('navbarCollapse')
        const appendAlert = (message, type) => {
            const wrapper = document.createElement('div')
            if (typeof $('#search_alert_msg') == 'undefined')   $('#search_alert_msg').remove();
            wrapper.innerHTML = [
                `<div id="search_alert_msg" class="alert alert-${type} alert-dismissible" role="alert" style="padding : 0.5em 2.5em 0.5em 0.5em; margin:0em;" >`,
                `   <div>${message}</div>`,
                '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding : 0.7em 0.5em; margin:0em;"></button>',
                '</div>'
            ].join('');

            alertPlaceholder.append(wrapper);
        }
    </script>
@endsection
