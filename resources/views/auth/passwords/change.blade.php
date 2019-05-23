@extends('layouts.app')
@section('content')

    <form  method="POST" class="form-group" action="/password">
        @csrf

    <div class="form-group">
        <label for="old_password">Password Antiga</label>
        <input
            type="password" class="form-control" name="old_password" id="old_password"/>
        @if($errors->has('old_password'))
           <em>{{$errors->first('old_password')}}</em>
        @endif

    </div>

    <div class="form-group">
        <label for="password">Password Nova</label>
        <input type="password" class="form-control" name="password" id="password"/>
        @if ($errors->has('password'))
            <em>{{ $errors->first('password') }}</em>
        @endif

    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirme a Nova Password</label>
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" />
        @if ($errors->has('password_confirmation'))
            <em>{{ $errors->first('password_confirmation') }}</em>
        @endif
    </div>


    <div class="form-group">
        <button type="submit" class="btn btn-success" name="ok">Alterar</button>
        <a type="submit" class="btn btn-default" name="cancel" href="/socios">Cancelar</a>
    </div>
    </form>
@endsection
