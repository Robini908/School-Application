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
                        class="icon-plus2"></i> {{ $gradingSystem->name }}</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-gradings">
                <div class="swiper-container" style="height: 500px;">
                    <div class="swiper-wrapper">
                        @foreach ($gradingSystems->chunk(2) as $chunk)
                        <div class="swiper-slide">
                            <div class="row justify-content-center">
                                @foreach ($chunk as $grade)
                                <div class="col-md-6 mb-3 d-flex justify-content-center">
                                    <div class="card fixed-size-card shadow-sm">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="card-title mb-0">{{ $grade->name }}</h5>
                                                <div class="btn-group">
                                                    <a href="{{ route('grading_system.edit', $grade->id) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button class="btn btn-danger" data-toggle="modal"
                                                        data-target="#deleteCardModal" data-id="{{ $grade->id }}">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-scrollable-content flex-grow-1 d-flex flex-column">
                                                <table class="table table-sm mb-0 flex-grow-1">
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
                                                            <td class="d-flex justify-content-end">
                                                                <div style="width: fit-content;">
                                                                    <a class="btn btn-primary"
                                                                        href="{{ route('subject-ranges.show', [$grade->id, $subject->id]) }}">
                                                                        View ranges &rarr;
                                                                    </a>
                                                                </div>
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
                <form action="{{ route('grading_system.update', $gradingSystem->id) }}" method="POST" id="grading_form">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name"><b>Grading Name:</b> </label>
                        <input type="text" class="form-control" id="name" name="name" required
                            value="{{ $gradingSystem->name }}">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<div class="modal fade" id="deleteCardModal" tabindex="-1" role="dialog" aria-labelledby="deleteCardModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCardModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this grading system?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCard">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Include Swiper JS -->


<!-- Initialize Swiper -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {

            clickable: true,
        },
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

    // Calculate and set minimum height for swiper container
    var swiperContainer = document.querySelector('.swiper-container');
    var slides = swiperContainer.querySelectorAll('.swiper-slide');
    var maxHeight = 0;

    slides.forEach(function(slide) {
        var slideHeight = slide.offsetHeight;
        if (slideHeight > maxHeight) {
            maxHeight = slideHeight;
        }
    });

    swiperContainer.style.minHeight = maxHeight + 'px';

    // Handle modal for deletion
    $('#deleteCardModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var gradeId = button.data('id');
        var modal = $(this);
        modal.find('#confirmDeleteCard').data('id', gradeId);
    });

    $('#confirmDeleteCard').click(function() {
        var gradeId = $(this).data('id');
        $.ajax({
            url: '/grading_system/' + gradeId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(result) {
                location.reload();
            }
        });
    });
});
</script>

<style>
.card-scrollable-content {
    flex-grow: 1;
    max-height: 200px;
    /* Adjust as needed */
    overflow-y: auto;
    padding-top: 10px;
}

.fixed-size-card {
    width: 100%;
    max-width: 400px;
    /* Adjust as needed */
    height: 450px;
    /* Adjust as needed */
    display: flex;
    flex-direction: column;
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.btn-danger {
    background-color: #e74a3b;
    border-color: #e74a3b;
}

.btn-danger:hover {
    background-color: #d9534f;
    border-color: #d43f3a;
}
</style>

@endsection