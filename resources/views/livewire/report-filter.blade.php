<div class="container mt-4">
    

    <!-- Grid Container for Sections -->
    <div class="row">
        <!-- Report Generator Section -->
        <div class="col-md-6 mb-4" x-show="$wire.entangle('showReportGenerator')" x-transition>
            <div class="bg-white p-4 rounded shadow-sm">
                <h3 class="h5 font-weight-bold mb-4">Generate Multiple Reports</h3>
                <div class="mb-3">
                    <label for="startRow" class="form-label">Start Row:</label>
                    <input type="number" id="startRow" min="1" placeholder="Enter start row" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="endRow" class="form-label">End Row:</label>
                    <input type="number" id="endRow" min="1" placeholder="Enter end row" class="form-control">
                </div>
                <button id="generateReports" class="btn btn-primary">Generate Reports</button>
            </div>
        </div>

        <!-- Advanced Filter Section -->
        <div class="col-md-6 mb-4" x-show="$wire.entangle('showFilter')" x-transition>
            <div class="bg-white p-4 rounded shadow-sm">
                <h3 class="h5 font-weight-bold mb-4">Advanced Filter</h3>
                <form wire:submit.prevent="applyFilter">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="filterName" class="form-label">Name:</label>
                            <input type="text" id="filterName" placeholder="Enter name" class="form-control" wire:model="filterName">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="filterGender" class="form-label">Gender:</label>
                            <select id="filterGender" class="form-select" wire:model="filterGender">
                                <option value="">All</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="filterClass" class="form-label">Class:</label>
                            <input type="text" id="filterClass" placeholder="Enter class" class="form-control" wire:model="filterClass">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="filterStatus" class="form-label">Status:</label>
                            <select id="filterStatus" class="form-select" wire:model="filterStatus">
                                <option value="">All</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success mt-3">Apply Filter</button>
                    </div>
                </form>
                @if($successMessage)
                    <div class="alert alert-success mt-3">
                        {{ $successMessage }}
                    </div>
                @endif
                @if($errorMessage)
                    <div class="alert alert-danger mt-3">
                        {{ $errorMessage }}
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>
