@extends('master')
@section('content')

@if (count($aeronaves)) 

<table class="table table-striped">
    <thead>
        <tr>
            <th>Matricula</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Nº de lugares</th>
            <th>Conta-horas</th>
            <th>Preço/hora</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($aeronaves as $aeronave)        
        <tr>
            <td>{{$aeronave->matricula}}</td>
            <td>{{$aeronave->marca}}</td>
            <td>{{$aeronave->modelo}}</td>
            <td>{{$aeronave->num_lugares}}</td>
            <td>{{$aeronave->conta_horas}}</td>
            <td>{{$aeronave->preco_hora}}</td>
            
        </tr>
        @endforeach
</table>
    @else
        <h2>Não foram encontradas Aeronaves</h2>
    @endif
@endsection