@extends('master')

@section('content')

    <div class="panel-body">
        <label>New Password</label>
        <input  type="password" class="form-control" id="currentUser.password" required autofocus>

        <button class="btn btn-xs btn-success">Login</button>
    </div>

@endsection

@section('pagescript')
    <script src="js/administrator.js"></script>
@stop