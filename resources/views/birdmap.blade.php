@extends('layouts.master')

@section('style')
@vite(['resources/css/home.css'])
@stop
@section('contents')
    <div id="map" style="width:100%;height:400px;"></div>
    <div id="map_list" style="width:100%"></div>
@stop
@section('javascript')
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=96gg9oc940&submodules=geocoder"></script>

	<script type="module">
	var map = new naver.maps.Map("map", {
	    center: new naver.maps.LatLng(37.520168953881715, 126.8722931226252),
	    zoom: 15,
	    mapTypeControl: true
	});

	var infoWindow = new naver.maps.InfoWindow({
	    anchorSkew: true
	});

	map.setCursor('pointer');

	function searchCoordinateToAddress(latlng) {

	    infoWindow.close();

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
		infoWindow.setContent([
		    '<div style="padding:10px;min-width:200px;line-height:150%;">',
		    '<h4 style="margin-top:5px;">검색 좌표</h4><br />',
		    htmlAddresses.join('<br />'),
            '<br/> <a style="border:1px; border-radius:10px; padding:10px;min-width:200px;line-height:150%;" href="/api/map/edit?latitude=' + lat_val + '&longitude=' + long_val + '">register</a>',
		    '</div>'
		].join('\n'));

		infoWindow.open(map, latlng);
	    });
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
            var marker_datas = '';
            console.log(point);

            $.ajax({
                url: "/api/map/test",
                method: "GET",
                dataType: "json"
            })
            .done(function(marker_datas) {
                if (marker_datas == 'undefined') {
                    return;
                }
                console.log(marker_datas);
                var infoWindow = new naver.maps.InfoWindow({
                    anchorSkew: true
                });

                var point_item = new naver.maps.Point(marker_datas[0].long, marker_datas[0].lat);
                infoWindow.setContent([
                    '<div style="padding:10px;min-width:200px;line-height:150%;">',
                    '<img style="width:100px; height:100px;" src="' + marker_datas[0].path + '"/>',
                    marker_datas[0].name + "<br>",
                    marker_datas[0].type + "<br>",
                    marker_datas[0].description + "<br>",
                    "발견자:" + marker_datas[0].user_id + "<br>",
                    marker_datas[0].reg_date + "<br>",
                    '</div>'
                ].join('\n'));
                map.setCenter(point_item);
                infoWindow.open(map, point_item);

                $.each(marker_datas, function(idx, value) {
                    var newMarker = new naver.maps.Marker({
                        position: new naver.maps.LatLng(value.lat, value.long),
                        map: map,
                        icon: {
                            content: [
                                '<div style="position: absolute; top: 0px; left: 0px; z-index: 0; margin: 0px; padding: 0px; border: 1px solid rgb(51, 51, 51); display: block; cursor: default; box-sizing: content-box !important; background: rgb(255, 255, 255);">',
                                '<img style="width:100px; height:100px;" src="' + value.path + '"/>',
                                //'<div style="margin: 0px; padding: 0px; border: 0px solid transparent; display: inline-block; box-sizing: content-box !important; width: 238px; height: 240px;">',
                                '<div style="padding:10px;min-width:100px;line-height:150%;"  id="' + value.id + '">',
                                value.name + "<br>",
                                '</div>',
                                //'<div>',
                                '<div style="margin: 0px; padding: 0px; width: 0px; height: 0px; position: absolute; border-width: 24px 10px 0px; border-style: solid; border-color: rgb(51, 51, 51) transparent transparent; border-image: initial; pointer-events: none; box-sizing: content-box !important; bottom: -25px; left: 50px;">',
                                '</div>',
                                '<div style="margin: 0px; padding: 0px; width: 0px; height: 0px; position: absolute; border-width: 24px 10px 0px; border-style: solid; border-color: rgb(255, 255, 255) transparent transparent; border-image: initial; pointer-events: none; box-sizing: content-box !important; bottom: -22px; left: 50px;">',
                                '</div>',
                                '</div>'

                                    ].join(''),
                            size: new naver.maps.Size(38, 58),
                            anchor: new naver.maps.Point(19, 58),
                        }
                    });
                });

                initMapList(marker_datas);
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('error');
            });

	    });
	}

    function initMapList(datas) {
        $.each(datas, function(idx, value) {
            $('#map_list').append('<img style="width:100px; height:100px; display:inline-block;" src="' + value.path + '"/>');
        });
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
	    searchAddressToCoordinate('정자동 178-1', datas);
	}

    function findGeocoder() {

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
