@extends('layouts.app')

@section('title', 'Authors')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @include('includes.flash')

            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-users"> Authors</i>
                </div>

                <div class="panel-body">
                    <a href="{{ url('/authors/register') }}">
                      <span class="btn btn-primary">Add New</span>
                    </a>
                </div>

                <div class="panel-body">
                    @if ($authors->isEmpty())
                        <p>You have not created any author.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Alias</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($authors as $author)
                                <tr>
                                    <td>{{ $author->name }}</td>
                                    <td>
                                        <a href="{{ url('author/'. $author->id) }}">
                                            {{ $author->alias }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection