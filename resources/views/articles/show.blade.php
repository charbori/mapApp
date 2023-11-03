@extends('layouts.master')
@section('style')
    <link href="/build/assets/css/cheatsheet.css" rel="stylesheet">
    <link href="/build/assets/css/reply.css" rel="stylesheet">
@stop
@section('contents')
    <aside class="bd-aside sticky-xl-top text-muted align-self-start mb-3 mb-xl-5 px-2">
        <nav class="small" id="toc">
            <ul class="list-unstyled">
                <li class="my-2">
                    <button class="btn d-inline-flex align-items-center collapsed" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#contents-collapse" aria-controls="contents-collapse">Contents</button>
                    <ul class="list-unstyled ps-3 collapse" id="contents-collapse">
                        <li><a class="d-inline-flex align-items-center rounded" href="#typography">Typography</a></li>
                        <li><a class="d-inline-flex align-items-center rounded" href="#images">Images</a></li>
                        <li><a class="d-inline-flex align-items-center rounded" href="#tables">Tables</a></li>
                        <li><a class="d-inline-flex align-items-center rounded" href="#figures">Figures</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </aside>
    <div class="bd-cheatsheet container-fluid bg-body">
        @if (false == empty($path_datas))
            <section>
                <div></div>
                @include('components.carousel', compact('path_datas'))
            </section>
        @endif
        <section id="content">
            <article class="ps-3 pe-3">
                <!--
                <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2">
                    <h3>Typography</h3>
                    <a class="d-flex align-items-center" href="../content/typography/">Documentation</a>
                </div>
                -->
                <div class="ps-3 pe-3">
                    <h2 class="sticky-xl-top fw-bold pt-3 pt-xl-9 pb-2 pb-xl-8">{{ $article->title }}</h2>
                    <div class="bd-example">
                        <ul class="list-inline">
                            <li class="list-inline-item">{{ $article->content }}</li>
                        </ul>
                    </div>
                    <br>
                </div>
            </article>
        </section>
        <section id="section_reply" data="{{ $article->id }}">
            <article>
		@if (isset($comment_datas))
                @include('articles.comment', ['comment_data' => $comment_datas, 'user_data' => $article->user])
		@else
		@include('articles.comment', ['comment_data' => '', 'user_data' => $article->user])
		@endif
            </article>
        </section>
    </div>
@stop
@section('javascript')
    <script src="/build/assets/js/cheatsheet.js"></script>
    <script src="/build/assets/js/reply.js"></script>
@stop
