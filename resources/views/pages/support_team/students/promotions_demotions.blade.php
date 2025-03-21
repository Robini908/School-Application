@extends('layouts.master')
@section('page_title', 'Student Promotions and Demotions')
@section('content')

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-exchange-alt text-blue-600 mr-2"></i>
                    Student Promotions & Demotions
                </h1>
                <div class="flex items-center space-x-2">
                    {!! Qs::getPanelOptions() !!}
                </div>
            </div>

            <div class="bg-gray-50 px-4 py-5 sm:px-6">
                <p class="text-sm text-gray-600">
                    Promote, demote, or make students repeat classes efficiently. Select students from a class/section and transition them to another class/section.
                </p>
            </div>

            <div class="p-6">
                <livewire:promote-students lazy />
            </div>
        </div>
    </div>

@endsection
