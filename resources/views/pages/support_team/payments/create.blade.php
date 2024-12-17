@extends('layouts.master')
@section('page_title', 'Create Payment')
@section('content')

    <div class="card">
        <div class="card-body">
            @livewire('payments')

        </div>
    </div>

    {{-- Payment Create Ends --}}

@endsection
