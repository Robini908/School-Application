@extends('layouts.master')
@section('page_title', 'Manage Grading System')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Grading</h6>
        <!-- {!! Qs::getPanelOptions() !!} Include your panel options as needed -->
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-gradings" class="nav-link active" data-toggle="tab">Manage Grading</a>
            </li>
            <li class="nav-item"><a href="#new-gradingsystem" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Grading</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-gradings">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach ($gradingSystems->chunk(4) as $chunk)
                        <div class="swiper-slide">
                            <div class="row">
                                @foreach ($chunk as $grade)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $grade->name }}</h5>
                                            <div class="btn-group">
                                                <a href="{{ route('grading_system.edit', $grade->id) }}" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </div>
                                            <div class="card-scrollable-content">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Subjects</th>
                                                            <th>View</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($subjects as $subject)
                                                        <tr>
                                                            <td class="text-left">{{ $subject->subject_name }}</td>
                                                            <td class="text-right">
                                                                <a class="btn btn-primary" href="{{ route('subject-ranges.show', [$grade->id, $subject->id]) }}">
                                                                    View ranges &rarr;
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="2">No subjects available</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>

                    <!-- Add Navigation -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>

            <div class="tab-pane fade" id="new-gradingsystem">
                <form action="{{ route('grading_system.store') }}" method="POST" id="grading_form">
                    @csrf
                    <div class="form-group">
                        <label for="name"><b>Grading Name:</b> </label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            allowTouchMove: false, // Disable swipe gestures
            breakpoints: {
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                992: {
                    slidesPerView: 1,
                    spaceBetween: 30,
                },
                1200: {
                    slidesPerView: 1,
                    spaceBetween: 40,
                }
            }
        });
    });
</script>

<style>
    .card-scrollable-content {
        max-height: 200px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
    }

    .card-scrollable-content::-webkit-scrollbar {
        width: 8px;
    }

    .card-scrollable-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .card-scrollable-content::-webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 10px;
        border: 3px solid #f1f1f1;
    }

    .card-scrollable-content::-webkit-scrollbar-thumb:hover {
        background-color: #555;
    }

    .swiper-container {
        width: 100%;
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .swiper-pagination-bullet {
        background-color: #bbb;
        opacity: 1;
    }

    .swiper-pagination-bullet-active {
        background-color: #333;
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #fff;
        width: 50px;
        height: 50px;
        background-color: #007bff;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: background-color 0.3s;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background-color: #0056b3;
    }

    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 20px;
    }

    .col-md-6 {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

@endsection