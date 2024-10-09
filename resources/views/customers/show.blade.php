@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dettaglio Cliente: {{ $customer->name }}</h1>

        <div>
            <h2>Applications</h2>
            <ul>
                @foreach($customer->applications as $application)
                    <li>{{ $application->label }} ({{ $application->application }})</li>
                @endforeach
            </ul>
        </div>

        <!-- Form per associare una nuova Application -->
        <form action="{{ route('customers.addApplication', $customer->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="application">Seleziona Application:</label>
                <select name="application_id" id="application_id" class="form-control">
                    @foreach($applications as $application)
                        <option value="{{ $application->id }}">{{ $application->label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Associa Application</button>
        </form>
    </div>
@endsection
