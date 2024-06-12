<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title">Manage Grading</h6>
            <i class="bi bi-gear-fill"></i>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight mb-4">
                <li class="nav-item"><a href="#all-gradings" class="nav-link active" data-toggle="tab">Manage
                        Grading</a></li>
                <li class="nav-item"><a href="#new-gradingsystem" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Add New Grading</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-gradings">
                    <!-- Content for managing existing gradings -->
                </div>
                <div class="tab-pane fade" id="new-gradingsystem">
                    <form action="{{ route('grading_system.store') }}" method="POST" id="grading_form">
                        @csrf
                        <div class="form-group">
                            <label for="name"><b>Grading Name:</b> </label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <span class="input-group-text"><i class="bi bi-edit"></i></span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="grading_table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Subject</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Grade</th>
                                        <th>Remark</th>
                                        <th>GPA</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="grading_ranges">
                                    <tr>


                                        <td>
                                            <input type="text" class="form-control subject" name="subject[]" required>
                                            <div class="invalid-feedback"></div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control range-from" name="range_from[]" required min="0" max="100">
                                            <div class="invalid-feedback"></div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control range-to" name="range_to[]" required min="0" max="100">
                                            <div class="invalid-feedback"></div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control grade" name="grade[]" required pattern="[A-F]{1,2}">
                                            <div class="invalid-feedback"></div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control remark" name="remark[]" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control gpa" name="gpa[]" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm delete-range">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" id="add_more" class="btn btn-primary">Add More Ranges</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <script src="{{ asset('assets/js/grading_system/create.js) }} "></script>
        </div>
    </div>
</div>
{{--Class List Ends--}}
@endsection