<div x-data="{ showSample: false, progress: 0, isUploading: false }" class="card mt-3  p-3 shadow-lg border rounded"
    style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
    <!-- Toggle Button -->
    <div class="card-body">
        <button @click="showSample = !showSample" class="btn btn-info mb-3">
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
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <form wire:submit.prevent="import" x-ref="form" @submit="isUploading = true" >
            <div class="mb-4">
                <label for="file" class="form-label fs-5 fw-bold text-secondary">Upload Your Excel File</label>
                
                <!-- File Input for Excel File -->
                {{-- <input type="file" wire:model="file" id="file" class="form-control" accept=".xlsx, .xls" multiple /> --}}
                <x-filepond::upload wire:model.live="file" 
                max-files="5" 
                allow-multiple="true"
                accept=".xlsx, .xls" />


                
                @error('file')
                    <span class="text-danger small mt-2 d-block">{{ $message }}</span>
                @enderror
            </div>
        
            <!-- Progress Bar for Upload -->
            <div x-show="isUploading" class="progress mb-3">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="'width: ' + progress + '%'" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary px-3 py-2 btn-sm">
                    <i class="bi bi-cloud-upload me-2"></i> Import
                </button>
                <span class="text-muted small">Supported file formats: .xlsx, .xls</span>
            </div>
        </form>
        
        

    </div>
</div>

@script
    <script>
        document.addEventListener('livewire:load', function() {
            @this.on('fileUploadProgress', progress => {
                // Update the progress bar width dynamically
                document.querySelector('.progress-bar').style.width = `${progress}%`;
            });
        });

        Livewire.on('fileUploadFinished', () => {
            // Complete the progress bar when file upload finishes
            document.querySelector('.progress-bar').style.width = '100%';
        });
    </script>
@endscript
