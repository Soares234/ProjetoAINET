@extends('layouts.app')
@section('content')

    <form action="{{action('AeronaveController@update',$aeronave->matricula)}}" method="POST" class="form-group">

        @include('aeronaves.add-edit-aeronave')
        @method("PUT")
        @csrf
            <table class="table table-striped" style="height: 20px; position:relative">
                <thead>
                <tr>
                    <th> </th>
                    <th>Preço</th>
                    <th>Minutos</th>
                </tr>
                </thead>
                @for ($i = 1; $i <=10; $i++)
                    <tr>
                     <td>{{'Preco '.$i}}</td>
                    <td><input type="numeric" name=<{{'precos['.$i.']'}} /></td>
                    <td><input type="numeric" name={{'tempos['.$i.']'}}></td>
                    </tr>
                @endfor
            </table>

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Editar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/aeronaves">Cancelar</a>
        </div>
    </form>
@endsection
