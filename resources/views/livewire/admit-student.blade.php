<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8" x-data="{ currentStep: 1 }">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Student Admission</h2>
                <div class="mt-2">
                    <div class="flex items-center">
                        <template x-for="step in 4" :key="step">
                            <div class="flex items-center">
                                <div :class="{'bg-blue-600': currentStep >= step, 'bg-gray-300': currentStep < step}" class="rounded-full h-8 w-8 flex items-center justify-center text-white font-semibold">
                                    <span x-text="step"></span>
                                </div>
                                <div x-show="step < 4" class="h-1 w-24" :class="{'bg-blue-600': currentStep > step, 'bg-gray-300': currentStep <= step}"></div>
                            </div>
                        </template>
                    </div>
                    <div class="flex justify-between mt-2 text-sm text-gray-600">
                        <span :class="{'text-blue-600 font-medium': currentStep === 1}">Personal Info</span>
                        <span :class="{'text-blue-600 font-medium': currentStep === 2}">Student Data</span>
                        <span :class="{'text-blue-600 font-medium': currentStep === 3}">Parent Details</span>
                        <span :class="{'text-blue-600 font-medium': currentStep === 4}">Password</span>
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="submit" class="px-6 py-4" enctype="multipart/form-data">
                <!-- Step 1: Personal Data -->
                <div x-show="$wire.currentStep === 1" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- First Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                            <input wire:model="first_name" type="text" placeholder="First Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Middle Name <span class="text-red-500">*</span></label>
                            <input wire:model="middle_name" type="text" placeholder="Middle Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('middle_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                            <input wire:model="last_name" type="text" placeholder="Last Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input wire:model="email" type="email" placeholder="Email Address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender <span class="text-red-500">*</span></label>
                            <select wire:model="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Gender...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input wire:model="phone" type="text" placeholder="Phone Number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input wire:model="dob" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('dob')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nationality -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nationality <span class="text-red-500">*</span></label>
                            <input wire:model="nationality" type="text" placeholder="Enter Nationality" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('nationality')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- County/State -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">County/State <span class="text-red-500">*</span></label>
                            <input wire:model="state" type="text" placeholder="Enter County/State" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Town -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Town <span class="text-red-500">*</span></label>
                            <input wire:model="town" type="text" placeholder="Enter Town" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('town')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Blood Group -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Blood Group</label>
                            <select wire:model="bg_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Blood Group...</option>
                                @foreach ($bloodGroups as $bg)
                                    <option value="{{ $bg->id }}">{{ $bg->name }}</option>
                                @endforeach
                            </select>
                            @error('bg_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Passport Photo -->
                        <div class="col-span-full">
                            <label class="block text-sm font-medium text-gray-700">Passport Photo</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input wire:model="photo" type="file" class="sr-only">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Step 2: Student Data -->
                <div x-show="$wire.currentStep === 2" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Class -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Class <span class="text-red-500">*</span></label>
                            <select wire:model.live="my_class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Class...</option>
                                @foreach ($myClasses as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('my_class_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Section <span class="text-red-500">*</span></label>
                            <select wire:model.live="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Class First</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year Admitted -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year Admitted <span class="text-red-500">*</span></label>
                            <select wire:model="year_admitted" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Year...</option>
                                @for ($y = date('Y', strtotime('- 40 years')); $y <= date('Y'); $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                            @error('year_admitted')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dormitory -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dormitory</label>
                            <select wire:model="dorm_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Dormitory...</option>
                                @foreach ($dorms as $dorm)
                                    <option value="{{ $dorm->id }}">{{ $dorm->name }}</option>
                                @endforeach
                            </select>
                            @error('dorm_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- UPI Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">UPI Number <span class="text-red-500">*</span></label>
                            <input wire:model="upi_number" type="text" placeholder="Enter UPI Number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('upi_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Admission Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Admission Number</label>
                            <input wire:model="adm_no" type="text" placeholder="Enter Admission Number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('adm_no')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- KCPE Marks -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">KCPE Marks</label>
                            <input wire:model="kcpe" type="number" placeholder="Enter KCPE Marks" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('kcpe')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Step 3: Parent Details -->
                <div x-show="$wire.currentStep === 3" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Parent ID Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">National ID Number <span class="text-red-500">*</span></label>
                            <input wire:model="parent_id_no" type="text" placeholder="Enter ID Number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('parent_id_no')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Names -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                            <input wire:model="parent_first_name" type="text" placeholder="Enter First Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('parent_first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                            <input wire:model="parent_middle_name" type="text" placeholder="Enter Middle Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('parent_middle_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                            <input wire:model="parent_last_name" type="text" placeholder="Enter Last Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('parent_last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Contact -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                            <input wire:model="parent_phone" type="text" placeholder="Enter Phone Number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('parent_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input wire:model="parent_email" type="email" placeholder="Enter Email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('parent_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                            <input wire:model="parent_password" type="password" placeholder="Enter Password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('parent_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Step 4: Password -->
                <div x-show="$wire.currentStep === 4" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                            <input wire:model="password" type="password" placeholder="Enter Password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                            <input wire:model="password_confirmation" type="password" placeholder="Confirm Password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-6 flex justify-between">
                    <button 
                        x-show="$wire.currentStep > 1"
                        type="button" 
                        wire:click="previousStep"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Previous
                    </button>
                    
                    <div class="flex justify-end">
                        <button 
                            x-show="$wire.currentStep < 4"
                            type="button" 
                            wire:click="nextStep"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Next
                        </button>
                        
                        <button 
                            x-show="$wire.currentStep === 4"
                            type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
