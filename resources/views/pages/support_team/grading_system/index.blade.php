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
            <li class="nav-item"><a href="#new-gradingsystem" class="nav-link" data-toggle="tab"><i
                        class="icon-plus2"></i> Create New Grading</a></li>
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
                                                <a href="{{ route('grading_system.edit', $grade->id) }}"
                                                    class="btn btn-primary">
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
                                                                <a class="btn btn-primary"
                                                                    href="{{ route('subject-ranges.show', [$grade->id, $subject->id]) }}">
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


@endsection