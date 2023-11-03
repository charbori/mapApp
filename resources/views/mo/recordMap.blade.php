@extends('layouts.master')

@section('style')
@vite(['resources/css/home.css'])
    <style>
    .bd-header {
        position: absolute;
    }
    body {
        overflow-x: hidden;
        overflow-y: hidden;
    }
    </style>
@stop
@section('contents')
    <div class="position-relative">
        <div id="map" style="width:100%;"></div>
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
        </div>
    </div>
@stop
@section('masternav_extra_item')
    <div class="input-group input-group-sm ml-3 mt-1">
        <span class="input-group-text"  aria-label="Toggle navigation" onclick="page_link_search();">
            <svg style="display: inline"  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
            </svg>
        </span>
        <span class="input-group-text"  aria-label="Toggle navigation" onclick="page_back();">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
            </svg>
        </span>
        <input placeholder="신목로 53" type="text" id="search_map_data" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
        <span class="input-group-text" id="search_map_btn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
        </svg></span>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=96gg9oc940&submodules=geocoder"></script>
	<script type="module">
    $('#map').css('height', $(document).height() + 'px');
    $('#map_list_items').css('width', ($(document).width()) + 'px');
	var map = new naver.maps.Map("map", {
	    center: new naver.maps.LatLng(37.520168953881715, 126.8722931226252),
	    zoom: 15,
	});
    var marker_data;
	var infoWindow = new naver.maps.InfoWindow({
	    anchorSkew: true
	});
    var newMarker;
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
                address = '';

            for (var i=0, ii=items.length, item, addrType; i<ii; i++) {
                item = items[i];
                address = makeAddress(item) || '';
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
                        '<a class="btn btn-sm btn-secondary" role="button" href="/api/map/edit?latitude=' + lat_val + '&longitude=' + long_val + '&mapType=map'
                        + '&map_address=' + address + '"'
                        + '>등록</a>',
                        '</div>'
                    ].join(''),
                    size: new naver.maps.Size(38, 58),
                    anchor: new naver.maps.Point(19, 58),
                }
            });

            let reInitId = $('#map_marker_selected').attr('data');
            let reIdx = false;
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
                let make_marker_UI = getUIContentDetailInfo(marker_data[reIdx], false);
                getUIMakeMarker(marker_data[reIdx], make_marker_UI, false);
            }
	    });
	}

    function setUIMakeMarker(idx) {
        let make_marker_UI = getUIContentDetailInfo(marker_data[idx], false);
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

            var item = response.v2.addresses[0],
                point = new naver.maps.Point(item.x, item.y);

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
                        let make_marker_UI = getUIContentDetailInfo(marker_data[idx], true);
                        markerSelected.push(getUIMakeMarker(marker_data[idx], make_marker_UI, true));
                    } else {
                        let make_marker_UI = getUIContentDetailInfo(marker_data[idx], false);
                        getUIMakeMarker(marker_data[idx], make_marker_UI, false);
                    }
                    initMapList(value);
                });

            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });
	    });
	}

    function getUIContentDetailInfo(data_content, selected) {
        let contentUI;
        if (selected) {
            contentUI = [
                            '<div class="" style="background-color:#ffffff; border-radius: 3%; border: solid 0px; border-color:gray; box-shadow: 0.5px 0.5px 0.5px 0.5px lightgray;"',
                            '<div id="map_marker_selected" data="' + data_content.id + '"style="padding:10px;min-width:200px;line-height:150%;">',
                            '<img style="border-radius: 3% 3% 0 0;  min-width:100px; max-width:150px; height:100px;" src="' + data_content.path + '"/>',
                            '<span class="pl-1" id="map_marker_name"><b>' + data_content.name + "</b></span><br>",
                            //'<span class="pl-1" id="map_marker_type">' + data_content.type + "</span><br>",
                            '<span class="pl-1" id="map_marker_description">' + data_content.description + "</span><br>",
                            '<div style="text-align:center; background-color:#e2e8f0; font-weight:bold;"><a class="btn btn-sm" style="width:100%; height:100%; background-color:#e2e8f0; font-weight:bold;" role="button" href="/api/record?map_id=' + data_content.id + '">',
                            '<svg style="display:inline" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg>',
                            '기록보기</a></div>',
                            '</div>',
                            '</div>'
                        ].join('\n');
        } else {
            contentUI = [
                            '<div class="div_map_marker" style="width:150px; position: absolute; top: 0px; left: 0px; z-index: 0; margin: 0px; padding: 0px; display: block; cursor: default; box-sizing: content-box !important;">',
                            '<button id="map_marker_item" data="' + data_content.id + '" type="button" style="background-color:white; font-color:black; box-shadow: 0.5px 0.5px 0.5px 0.5px lightgray;" class="btn btn-sm">' + data_content.name + '</button>',
                            '</div>'
                        ].join('');
        }
        return contentUI;
    }

    function getUIMakeMarker(data_content, make_marker_UI, selected) {
        if (selected) {
            let point_item = new naver.maps.Point(data_content.long, data_content.lat);
            map.setCenter(point_item);
        }
        let marker_maked = new naver.maps.Marker({
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
            let marker_id = $(marker_maked)[0].data;
            $.each(marker_data, function(idx, value) {
                if (value.id == marker_id) {
                    $.each(markerSelected, function(i, v) {
                        v.setMap(null);
                    });
                    markerSelected = [];

                    let new_make_marker_UI = getUIContentDetailInfo(marker_data[idx], true);
                    markerSelected.push(getUIMakeMarker(marker_data[idx], new_make_marker_UI, true));
                }
            });
        });

        return marker_maked;
    }

    function initMapList(value) {
        if (typeof value == 'undefined' || value.length == 0) return $('#map_list').empty();
        let path_name = '';
        if (value.path.length < 12) path_name = '<svg xmlns="http://www.w3.org/2000/svg"  width="64" height="64" class="bi bi-card-image flex-shrink-0" fill="currentColor" viewBox="0 0 16 16"><path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/><path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z"/></svg>';
        else path_name = '<img src="' + value.path + '" alt="twbs" width="64" height="64" class="rounded-circle flex-shrink-0">'
        let map_list_item = ['<a href="#" style="background-color:white; border-width:0 0 1px 0;" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true" onClick="link_map(' + value.id + ')">',
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

	function initGeocoder() {
	    map.addListener('click', function(e) {
		    searchCoordinateToAddress(e.coord);
	    });

	    $('#address').on('keydown', function(e) {
        let  keyCode = e.which;

		if (keyCode === 13) {
		    searchAddressToCoordinate($('#address').val());
		}
	    });

	    $('#submit').on('click', function(e) {
		e.preventDefault();

		searchAddressToCoordinate($('#address').val());
	    });

	    let init_geo_code_data = ['bird_1', '11', 'charbori'];
	    searchAddressToCoordinate('신목로 53', init_geo_code_data);
	}

    function findGeocoder(search_geo_data) {
	    searchAddressToCoordinate(search_geo_data, []);
    }

	function makeAddress(item) {
	    if (!item) {
		    return;
	    }

	    let name = item.name,
		region = item.region,
		land = item.land,
		isRoadAddress = name === 'roadaddr';

	    let sido = '', sigugun = '', dongmyun = '', ri = '', rest = '';

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

    function link_map (map_id) {
        if (typeof map_id == 'undefined') return;
        location.href= "/api/record?map_id=" + map_id;
    }

    window.onload  = function() {
        let rest_height = 0;
        let max_height = 0;
        let box = document.getElementById("list-group-handle");
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
    }

	</script>
    <script>
        function page_link_search() {
            location.href= "/api/search";
        }

        function page_back() {
            location.href= "/api/map";
        }
    </script>
@stop

@section('masternav_script')
    <script type="module">
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
    </script>
@endsection
