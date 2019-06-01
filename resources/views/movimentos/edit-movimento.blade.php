@extends('layouts.app')
@section('content')
    <form action={{'movimentos/'.$movimento->id}} method="POST" class="form-group">
    @method("PUT")
    @csrf





    </form>
@endsection
