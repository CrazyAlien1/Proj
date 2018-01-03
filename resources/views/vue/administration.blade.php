@extends('master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Administration</div>

                    <div class="panel-body">
                        Hello Administrator

                        <user-list :users="users">

                        </user-list>

                        <game-list :games="games">

                        </game-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script src="js/administrator.js"></script>
@stop

