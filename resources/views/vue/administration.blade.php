@extends('master')

@section('content')
    <div class="panel-body">
        <!--<router-link class="btn btn-default" to="/">Home</router-link>
        <router-view></router-view>-->
        <admin></admin>
    </div>
@endsection

@section('pagescript')
    <script src="js/administrator.js"></script>
@stop
