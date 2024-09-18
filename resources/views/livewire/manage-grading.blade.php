<div class="container-fluid p-0">
    <div class="bg-white shadow-md rounded-lg border border-gray-200">
        <!-- Card Header -->
        <div class="card-header px-4 py-3 border-b border-gray-300 bg-gray-50">
            <h6 class="card-title text-lg font-semibold">Manage Grading</h6>
        </div>

        <!-- Card Body -->
        <div class="card-body p-4">
            <!-- Tabs -->
            <ul class="nav nav-tabs border-b border-gray-300 mb-4">
                <li class="nav-item">
                    <a href="#all-gradings" class="nav-link active" data-toggle="tab">
                        <i class="fas fa-list"></i> Manage Grading
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#new-gradingsystem" class="nav-link" data-toggle="tab">
                        <i class="fas fa-plus-circle"></i> Create New Grading
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- All Gradings Tab -->
                <div class="tab-pane fade show active" id="all-gradings">
                    <div class="container">
                        <div class="row">
                            @foreach ($gradingSystems as $grade)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 bg-white shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200">
                                    <div class="card-body d-flex flex-column p-4">
                                        <h5 class="card-title mb-3 font-semibold text-indigo-600">{{ $grade->name }}</h5>

                                        <!-- Edit Button with Bootstrap Tooltip -->
                                        <div class="btn-group mb-3">
                                            <a href="{{ route('grading_system.edit', $grade->id) }}" class="btn btn-light text-gray-600 hover:text-gray-800" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Grading System">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>

                                        <div class="flex-grow-1">
                                            <table class="table table-sm mb-0 text-left">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left">Subjects</th>
                                                        <th class="text-right">View</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($subjects as $subject)
                                                    <tr>
                                                        <td class="text-left">{{ $subject->subject_name }}</td>
                                                        <td class="text-right">
                                                            <a class="btn btn-light text-gray-600 hover:text-gray-800" href="{{ route('subject-ranges.show', [$grade->id, $subject->id]) }}">
                                                                <i class="fas fa-eye"></i> View &rarr;
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center text-gray-500">No subjects available</td>
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

                        
                        <div class="mt-4">
                            {{ $gradingSystems->links() }}
                        </div>
                    </div>
                </div>

                <!-- Create New Grading Tab -->
                <div class="tab-pane fade" id="new-gradingsystem">
                    <form wire:submit.prevent="store">
                        <div class="form-group mb-4">
                            <label for="name" class="font-semibold"><b>Grading Name:</b></label>
                            <input type="text" class="form-control" id="name" wire:model="name" required>
                        </div>
                        <div>
                            <!-- Smaller submit button for grading creation -->
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize Bootstrap Tooltip -->
<script>
    // Initialize all tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
