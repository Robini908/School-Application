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
                <div id="card-slider" class="tns-carousel">
                    <div class="tns-slider">
                        @foreach ($gradingSystems->chunk(2) as $chunk)
                        <div class="tns-item">
                            <div class="card-deck">
                                @foreach ($chunk as $grade)
                                <div class="card mb-3">
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
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="tns-controls" aria-label="Carousel Pagination" tabindex="0">
                        @foreach ($gradingSystems->chunk(2) as $index => $chunk)
                        <button type="button" class="tns-indicator" aria-label="Go to slide {{ $index + 1 }}"
                            tabindex="-1" data-controls="indicators" data-index="{{ $index }}"></button>
                        @endforeach
                    </div>
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

<!-- Initialize tiny-slider -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var slider = tns({
        container: '#card-slider .tns-carousel',
        items: 1,
        slideBy: 'page',
        autoplay: false,
        controls: false,
        navContainer: '#card-slider .tns-nav',
        navAsThumbnails: true,
        autoplayButtonOutput: false,
        responsive: {
            768: {
                items: 2,
            },
            992: {
                items: 2,
            },
            1200: {
                items: 2,
            }
        }
    });

    // Manual navigation buttons
    document.querySelector('#card-slider .tns-prev').addEventListener('click', function() {
        slider.goTo('prev');
    });

    document.querySelector('#card-slider .tns-next').addEventListener('click', function() {
        slider.goTo('next');
    });

    // Manual pagination indicators
    document.querySelectorAll('#card-slider .tns-controls .tns-indicator').forEach(function(indicator) {
        indicator.addEventListener('click', function() {
            var index = parseInt(this.getAttribute('data-index'));
            slider.goTo(index);
        });
    });
});
</script>

<style>
.card-deck {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}

.card {
    flex: 0 0 calc(50% - 30px);
    margin: 15px;
}

.card-scrollable-content {
    max-height: 200px;
    overflow-y: auto;
}

.tns-carousel {
    position: relative;
}

.tns-nav {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    text-align: center;
}

.tns-nav button {
    margin: 0 5px;
}

.tns-controls {
    display: flex;
    justify-content: center;
    margin-top: 10px;
}

.tns-controls .tns-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #bbb;
    margin: 0 5px;
    cursor: pointer;
    outline: none;
    border: none;
}

.tns-controls .tns-indicator.active {
    background-color: #333;
}
</style>

@endsection