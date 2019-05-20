@extends('layouts.app')
@section('content')

    <form action="{{action('SocioController@show',$user->id)}}" method="get" class="form-group">
        <div>
            <img src={{asset('storage/fotos/'.$user->foto_url)}} class="img-thumbnail">
        </div>

    </form>
