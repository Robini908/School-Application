<div x-data="{ showSample: false, showErrorTable: @entangle('showEditTable') }" 
     class="bg-white rounded-lg shadow-sm overflow-hidden">
    
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-medium text-gray-900">Bulk Student Import</h2>
            <button @click="showSample = !showSample" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg x-show="!showSample" class="mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="showSample" class="mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span x-text="showSample ? 'Hide Sample' : 'View Sample Format'"></span>
        </button>
        </div>

        <!-- Sample Table Section -->
        <div x-show="showSample" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="mb-8">
            
            <div class="bg-blue-50 rounded-lg p-4 mb-4 flex items-start">
                <svg class="h-6 w-6 text-blue-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Sample Excel Format</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Your Excel file should match this format. All columns shown below are required.</p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto shadow-md rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">adm_no</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">first_name</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">middle_name</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">last_name</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">gender</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">dob</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">class_name</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">section_name</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">year_admitted</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">email</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">phone</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">kcpe</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">parent_id_no</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">parent_first_name</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">parent_last_name</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($studentRecords as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->adm_no }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->first_name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->middle_name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->last_name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->gender }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($student->dob)->format('Y-m-d') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->my_class->name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->section->name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->year_admitted }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->email }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->phone }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->kcpe }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->parent_detail->parent_id_no ?? '' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->parent_detail->parent_first_name ?? '' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $student->parent_detail->parent_last_name ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <form wire:submit.prevent="importStudents">
                <div class="mb-6">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Upload Excel File</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload a file</span>
                                    <x-filepond::upload wire:model.live="file" max-files="5" allow-multiple="true" accept=".xlsx, .xls" />
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">
                                XLSX, XLS or CSV up to 10MB
                            </p>
                        </div>
                    </div>
                    @error('file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Progress Bar -->
                @if ($progress > 0 && !$uploadCompleted)
                    <div class="relative pt-1 mb-4">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                    Uploading
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-blue-600">
                            {{ $progress }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                            <div style="width:{{ $progress }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 transition-all duration-300"></div>
                        </div>
                    </div>
                @endif

                <!-- Complete Message -->
                @if ($uploadCompleted)
                    <div class="rounded-md bg-green-50 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    Upload complete! Students have been successfully imported.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="flex justify-between items-center">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span wire:loading.remove wire:target="importStudents">Import Students</span>
                        <span wire:loading wire:target="importStudents">Processing...</span>
                    </button>
                    <span class="text-sm text-gray-500">Supported formats: .xlsx, .xls, .csv</span>
                </div>
            </form>
        </div>

        <!-- Error Correction Section -->
        @if (count($errorDetails) > 0)
            <div class="rounded-md bg-yellow-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Validation Errors Detected
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>There were some validation errors in your uploaded file. Would you like to correct them?</p>
                        </div>
                        <div class="mt-4">
                            <div class="-mx-2 -my-1.5 flex">
                                <button wire:click="$toggle('showErrorTable')" class="px-3 py-1.5 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    {{ $showErrorTable ? 'Hide Error Table' : 'Show & Correct Errors' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Editable Error Table -->
        @if ($showErrorTable && !empty($editableRows))
            <div x-show="showErrorTable"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="mt-6">
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-medium text-gray-700">Correct Validation Errors</h3>
                    </div>
                    
                <form wire:submit.prevent="saveCorrections">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">adm_no</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">first_name</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">last_name</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">gender</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">dob</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">class</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">section</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">year</th>
                            </tr>
                        </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($editableRows as $index => $row)
                                <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $row['row'] }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <input type="text" wire:model="editableRows.{{ $index }}.values.adm_no"
                                                    class="@if (in_array('adm_no', $row['errors'])) border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @else bg-gray-100 @endif shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    @if (!in_array('adm_no', $row['errors'])) disabled @endif>
                                    </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <input type="text" wire:model="editableRows.{{ $index }}.values.first_name"
                                                    class="@if (in_array('first_name', $row['errors'])) border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @else bg-gray-100 @endif shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    @if (!in_array('first_name', $row['errors'])) disabled @endif>
                                    </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <input type="text" wire:model="editableRows.{{ $index }}.values.last_name"
                                                    class="@if (in_array('last_name', $row['errors'])) border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @else bg-gray-100 @endif shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    @if (!in_array('last_name', $row['errors'])) disabled @endif>
                                    </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <select wire:model="editableRows.{{ $index }}.values.gender"
                                                    class="@if (in_array('gender', $row['errors'])) border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @else bg-gray-100 @endif shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    @if (!in_array('gender', $row['errors'])) disabled @endif>
                                                    <option value="">Select</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                    </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <input type="date" wire:model="editableRows.{{ $index }}.values.dob"
                                                    class="@if (in_array('dob', $row['errors'])) border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @else bg-gray-100 @endif shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    @if (!in_array('dob', $row['errors'])) disabled @endif>
                                    </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <input type="text" wire:model="editableRows.{{ $index }}.values.class_name"
                                                    class="@if (in_array('class_name', $row['errors'])) border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @else bg-gray-100 @endif shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    @if (!in_array('class_name', $row['errors'])) disabled @endif>
                                    </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <input type="text" wire:model="editableRows.{{ $index }}.values.section_name"
                                                    class="@if (in_array('section_name', $row['errors'])) border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @else bg-gray-100 @endif shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    @if (!in_array('section_name', $row['errors'])) disabled @endif>
                                    </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <input type="text" wire:model="editableRows.{{ $index }}.values.year_admitted"
                                                    class="@if (in_array('year_admitted', $row['errors'])) border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @else bg-gray-100 @endif shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    @if (!in_array('year_admitted', $row['errors'])) disabled @endif>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                        </div>
                        
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save Corrections
                            </button>
                    </div>
                </form>
                </div>
            </div>
        @endif
    </div>
</div>

@script
    <script>
        document.addEventListener('livewire:load', function() {
            @this.on('fileUploadProgress', progress => {
                // Update the progress bar width dynamically
                if (document.querySelector('.shadow-none')) {
                    document.querySelector('.shadow-none').style.width = `${progress}%`;
                }
            });

            Livewire.on('fileUploadFinished', () => {
                // Complete the progress bar when file upload finishes
                if (document.querySelector('.shadow-none')) {
                    document.querySelector('.shadow-none').style.width = '100%';
                }
            });
        });
    </script>
@endscript
