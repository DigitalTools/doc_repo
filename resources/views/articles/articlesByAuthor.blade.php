@extends('layouts.app')

@section('title')
    Articles by {{$author->name}}
@stop

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @include('includes.flash')

            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-file"> {{ $articles->total() }} Articles by {{ $author->name }}</i>
                </div>

                <div class="panel-body">
                    @if ($articles->isEmpty())
                        <p>You have not created any articles.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Score</th>
                                    <th>Magnitude</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($articles as $article)
                                <tr>
                                    <td>
                                        <a href="{{ url('article/'. $article->id) }}">
                                            {{ $article->title }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ( is_null($article->url) )
                                            
                                        @else
                                            <i class="fa fa-link"></i>
                                        @endif
                                    </td>
                                    <td>{{ $article->score }}</td>
                                    <td>{{ $article->magnitude }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $articles->render() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection