@extends('layouts.app')

@section('title', 'Parse URL')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Parse URL</div>

                <div class="panel-body">
                    @include('includes.flash')

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/url') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="url" class="col-md-4 control-label">url</label>

                            <div class="col-md-6">
                                <input id="url" type="text" class="form-control" name="url" value="{{ old('url') }}">

                                @if ($errors->has('url'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-link"></i> Parse URL
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection