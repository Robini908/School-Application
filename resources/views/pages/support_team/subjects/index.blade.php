@extends('layouts.master')
@section('page_title', 'Manage Subjects')
@section('content')

<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Content Container -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <livewire:all-subject-management-actions lazy />
        </div>
    </div>
</div>

@endsection
