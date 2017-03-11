@extends('layouts.app')

@section('title')
    {{$author->name}} (Author Page)
@stop

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @include('includes.flash')

            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-user"> {{ $author->name }} (Author Page)</i>
                </div>

                <div class="panel-body">
                    Articles:
                    <table class="table">
                        <tr>
                          <td>Count</td>
                          <td>{{ count($articles) }}</td>
                        </tr>
                    </table>


                    <h3>Sentiment Analysis</h3>


                    Score:
                    <table class="table">
                        <tr>
                          <td>Min</td>
                          <td>{{ $stats['score']['min'] }}</td>
                        </tr>
                        <tr>
                          <td>Avg</td>
                          <td>{{ round($stats['score']['avg'], 2) }}</td>
                        </tr>
                        <tr>
                          <td>Max</td>
                          <td>{{ $stats['score']['max'] }}</td>
                        </tr>
                        <tr>
                          <td>StdDev</td>
                          <td>{{ round($stats['score']['stddev'], 2) }}</td>
                        </tr>
                    </table>
                    Magnitude:
                    <table class="table">
                        <tr>
                          <td>Min</td>
                          <td>{{ $stats['magnitude']['min'] }}</td>
                        </tr>
                        <tr>
                          <td>Avg</td>
                          <td>{{ round($stats['magnitude']['avg'], 2) }}</td>
                        </tr>
                        <tr>
                          <td>Max</td>
                          <td>{{ $stats['magnitude']['max'] }}</td>
                        </tr>
                        <tr>
                          <td>StdDev</td>
                          <td>{{ round($stats['magnitude']['stddev'], 2) }}</td>
                        </tr>
                    </table>
                </div>



                <div class="panel-body">

                Max Scored:
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($rank['maxScored'] as $article)
                            <tr>
                                <td>
                                    <a href="{{ url('article/'. $article->id) }}">
                                        {{ $article->title }}
                                    </a>
                                </td>
                                <td>{{ $article->score }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>



                <div class="panel-body">

                Min Scored:
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($rank['minScored'] as $article)
                            <tr>
                                <td>
                                    <a href="{{ url('article/'. $article->id) }}">
                                        {{ $article->title }}
                                    </a>
                                </td>
                                <td>{{ $article->score }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>


                <div class="panel-body">

                Max Magnitude:
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Magnitude</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($rank['maxMag'] as $article)
                            <tr>
                                <td>
                                    <a href="{{ url('article/'. $article->id) }}">
                                        {{ $article->title }}
                                    </a>
                                </td>
                                <td>{{ $article->magnitude }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>



                <div class="panel-body">

                Min Magnitude:
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Magnitude</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($rank['minMag'] as $article)
                            <tr>
                                <td>
                                    <a href="{{ url('article/'. $article->id) }}">
                                        {{ $article->title }}
                                    </a>
                                </td>
                                <td>{{ $article->magnitude }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>



            </div>
        </div>
    </div>
@endsection