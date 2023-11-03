<div id="myCarousel" class="carousel carousel-dark slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($path_datas AS $key => $item)
            @if ($key == 0)
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="{{ $key }}" class="active" aria-current="true" aria-label="Slide 1"></button>
            @else
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="{{ $key }}" aria-label=""></button>
            @endif
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach($path_datas AS $key => $item)
            @if ($key == 0)
                <div class="carousel-item active">
            @else
                <div class="carousel-item">
            @endif
                <img src='{{ $item }}' style="object-fit: scale-down; height:300px !important" class="rounded mx-auto d-block">
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<script>
    $( document ).ready(function() {
        $(".carousel").carousel({
            interval: 10000,
            pause: true
        });

        $( ".carousel .carousel-inner" ).swipe( {
        swipeLeft: function ( event, direction, distance, duration, fingerCount ) {
            this.parent( ).carousel( 'next' );
        },
        swipeRight: function ( ) {
            this.parent( ).carousel( 'prev' );
        },
        threshold: 0,
        tap: function(event, target) {
            window.location = $(this).find('.carousel-item.active a').attr('href');
        },
        excludedElements:"label, button, input, select, textarea, .noSwipe"
        } );

        $('.carousel .carousel-inner').on('dragstart', 'a', function () {
            return false;
        });
    });
</script>
