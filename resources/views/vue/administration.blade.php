@extends('master')

@section('content')

                    <div class="panel-body">

                        <router-link to="/admin">Profile</router-link>

                        <router-view></router-view>

                        <admin>

                        </admin>
                    </div>

@endsection

@section('pagescript')
    <script src="js/administrator.js"></script>
@stop

