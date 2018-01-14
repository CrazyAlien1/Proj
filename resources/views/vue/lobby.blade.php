@extends('master')

@section('content')
    <div>
        <router-view></router-view>
    </div>
@endsection

@section('pagescript')

    <script src="js/vueapp.js"></script>

@stop
