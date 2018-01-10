@extends('master')

@section('content')
    <div>
        <router-link to="/memorygame">My Games</router-link>
        <router-link to="/profile">Profile</router-link>

            <router-view></router-view>
    </div>
@endsection

@section('pagescript')

    <script src="js/vueapp.js"></script>

@stop
