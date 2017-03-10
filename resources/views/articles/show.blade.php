@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-heading">
                    {{ $article->title }}
                </div>

                <div class="panel-body">
                    @include('includes.flash')

                    <div class="article-info">
                        <p>{{ str_limit($article->body, 825) }}</p>
                        <p>Registered on: {{ $article->created_at->diffForHumans() }}</p>
                        <!--<p>Score: {{ $sentiment['score'] }}</p>
                        <p>Magnitude: {{ $sentiment['magnitude'] }}</p>
                        -->
                    </div>

                    <p>
                        {{ Form::open([ 'method' => 'DELETE', 'route' => ['article.destroy', $article->id] ]) }}
                            {{ Form::hidden('id', $article->id) }}
                            {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                        {{ Form::close() }}
                    </p>

                </div>

            </div>
        </div>
    </div>
@endsection