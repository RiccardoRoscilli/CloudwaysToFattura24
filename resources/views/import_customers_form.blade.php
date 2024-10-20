@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Importa Clienti</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Form per l'importazione del CSV -->
        <form action="{{ route('import.customers.csv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="csv_file" class="form-label">Carica il file CSV</label>
                <input type="file" name="csv_file" id="csv_file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mb-3">Importa Clienti</button>
        </form>

        <!-- Testo esplicativo -->
        <div class="alert alert-info">
            <p>
                Fattura24 non mette a disposizione un endpoint per l'acquisizione diretta dei clienti tramite API.
                Pertanto, l'unico modo per importare i clienti è esportare un file CSV dalla rubrica di Fattura24 ed
                utilizzare questo form per caricare i dati. Si prega di assicurarsi che il file CSV sia nel formato
                corretto.
            </p>
            <p>Ecco un esempio su come esportare il CSV da Fattura24:</p>
            <img src="{{ asset('images/Fattura24_export_rubrica_csv.png') }}" alt="Esempio di esportazione CSV da Fattura24"
                class="img-fluid">
        </div>





    </div>
@endsection
