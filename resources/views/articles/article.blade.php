@foreach ($articles as $article)
    <div class="row featurette">
        <div class="col-auto">
            <img src='{{ $article->path }}'>
        </div>
        <div class="col-8 col-md-7" onclick="moveArticle('{{ $article->id }}')" style="cursor:pointer">
            <h3 class="home-title">{{ $article->title }}</h3>
            <span class="home-content">{{ $article->content }}</span>
            <span class="home-name">{{ $article->user->name }}</span><span class="home-date">{{ $article->created_at }}</span>
        </div>
    </div>
    <hr>
@endforeach
