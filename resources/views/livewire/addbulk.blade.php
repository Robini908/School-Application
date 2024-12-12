<div x-data="{ showSample: false, showErrorTable: @entangle('showEditTable') }" class="card mt-3 p-3 shadow-lg border rounded"
    style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
    <!-- Toggle Button -->
    <div class="card-body">
        <button @click="showSample = !showSample" class="btn btn-link mb-3">
            <i class="bi" :class="showSample ? 'bi-x' : 'bi-eye'"></i>
            <span x-text="showSample ? 'Close' : 'Sample Table'"></span>
        </button>

        <!-- Sample Table - Toggled Visibility -->
        <div x-show="showSample" class="mb-3">
            <h4 class="text-center text-primary">Sample Excel Data</h4>
        </div>

        <div x-show="showSample" class="mb-4 table-responsive">
            <table class="table table-striped table-bordered table-hover shadow-sm">
                <thead>
                    <tr class="table-info">
                        <th>adm_no</th>
                        <th>first_name</th>
                        <th>middle_name</th>
                        <th>last_name</th>
                        <th>gender</th>
                        <th>dob</th>
                        <th>class_name</th>
                        <th>section_name</th>
                        <th>year_admitted</th>
                        <th>email</th>
                        <th>phone</th>
                        <th>kcpe</th>
                        <th>parent_id_no</th>
                        <th>parent_first_name</th>
                        <th>parent_middle_name</th>
                        <th>parent_last_name</th>
                        <th>parent_phone_number</th>
                        <th>parent_email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studentRecords as $student)
                        <tr>
                            <td>{{ $student->adm_no }}</td>
                            <td>{{ $student->first_name }}</td>
                            <td>{{ $student->middle_name }}</td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->gender }}</td>
                            <td>{{ \Carbon\Carbon::parse($student->dob)->format('Y-m-d') }}</td>
                            <td>{{ $student->my_class->name }}</td>
                            <td>{{ $student->section->name }}</td>
                            <td>{{ $student->year_admitted }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone }}</td>
                            <td>{{ $student->kcpe }}</td>
                            <td>{{ $student->parent_detail->parent_id_no ?? '' }}</td>
                            <td>{{ $student->parent_detail->parent_first_name ?? '' }}</td>
                            <td>{{ $student->parent_detail->parent_middle_name ?? '' }}</td>
                            <td>{{ $student->parent_detail->parent_last_name ?? '' }}</td>
                            <td>{{ $student->parent_detail->parent_phone_number ?? '' }}</td>
                            <td>{{ $student->parent_detail->parent_email ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div>
            <form wire:submit.prevent="importStudents">
                <!-- File Input -->
                <div class="mb-4">
                    <label for="file" class="form-label fs-5 fw-bold text-secondary">Upload Your Excel File</label>
                    <x-filepond::upload wire:model.live="file" max-files="5" allow-multiple="true"
                        accept=".xlsx, .xls" />
                    @error('file')
                        <span class="text-danger small mt-2 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Progress Bar -->
                @if ($progress > 0 && !$uploadCompleted)
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                            aria-valuemax="100">
                            {{ $progress }}%
                        </div>
                    </div>
                @endif

                <!-- Complete Message -->
                @if ($uploadCompleted)
                    <div class="alert alert-success">
                        Upload complete!
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary px-3 py-2 btn-sm">
                        <i class="bi bi-cloud-upload me-2"></i> Import
                        <div wire:loading wire:target="importStudents"
                            class="spinner-border spinner-border-sm text-light ms-2" role="status"></div>
                    </button>
                    <span class="text-muted small">Supported file formats: .xlsx, .xls, .csv</span>
                </div>
            </form>
        </div>






        <!-- Error Message with Correction Option -->
        @if (count($errorDetails) > 0)
            <div class="alert alert-warning mt-4">
                <p>There were validation errors in the uploaded file. Would you like to correct them?</p>
                <button wire:click="$toggle('showErrorTable')" class="btn btn-warning">Correct Errors</button>
            </div>
        @endif

        <!-- Editable Table for Error Correction -->
        {{-- @if ($showErrorTable && count($editableRows) > 0) --}}
        @if ($showErrorTable && !empty($editableRows))

            <div class="mt-4 table-responsive">
                <form wire:submit.prevent="saveCorrections">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="table-danger">
                                <th>Row</th>
                                <th>adm_no</th>
                                <th>first_name</th>
                                <th>middle_name</th>
                                <th>last_name</th>
                                <th>gender</th>
                                <th>dob</th>
                                <th>class_name</th>
                                <th>section_name</th>
                                <th>year_admitted</th>
                                <th>email</th>
                                <th>phone</th>
                                <th>kcpe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($editableRows as $index => $row)
                                <tr>
                                    <td>{{ $row['row'] }}</td>
                                    <td><input type="text"
                                            wire:model="editableRows.{{ $index }}.values.adm_no"
                                            class="form-control" @if (!in_array('adm_no', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="text"
                                            wire:model="editableRows.{{ $index }}.values.first_name"
                                            class="form-control" @if (!in_array('first_name', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="text"
                                            wire:model="editableRows.{{ $index }}.values.middle_name"
                                            class="form-control" @if (!in_array('middle_name', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="text"
                                            wire:model="editableRows.{{ $index }}.values.last_name"
                                            class="form-control" @if (!in_array('last_name', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="text"
                                            wire:model="editableRows.{{ $index }}.values.gender"
                                            class="form-control" @if (!in_array('gender', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="date" wire:model="editableRows.{{ $index }}.values.dob"
                                            class="form-control" @if (!in_array('dob', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="text"
                                            wire:model="editableRows.{{ $index }}.values.class_name"
                                            class="form-control" @if (!in_array('class_name', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="text"
                                            wire:model="editableRows.{{ $index }}.values.section_name"
                                            class="form-control" @if (!in_array('section_name', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="text"
                                            wire:model="editableRows.{{ $index }}.values.year_admitted"
                                            class="form-control" @if (!in_array('year_admitted', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="email"
                                            wire:model="editableRows.{{ $index }}.values.email"
                                            class="form-control" @if (!in_array('email', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="text"
                                            wire:model="editableRows.{{ $index }}.values.phone"
                                            class="form-control" @if (!in_array('phone', $row['errors'])) disabled @endif>
                                    </td>
                                    <td><input type="text" wire:model="editableRows.{{ $index }}.values.kcpe"
                                            class="form-control" @if (!in_array('kcpe', $row['errors'])) disabled @endif>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success">Save Corrections</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

@script
    <script>
        document.addEventListener('livewire:load', function() {
            @this.on('fileUploadProgress', progress => {
                // Update the progress bar width dynamically
                document.querySelector('.progress-bar').style.width = `${progress}%`;
            });

            Livewire.on('fileUploadFinished', () => {
                // Complete the progress bar when file upload finishes
                document.querySelector('.progress-bar').style.width = '100%';
            });
        });
    </script>
@endscript
