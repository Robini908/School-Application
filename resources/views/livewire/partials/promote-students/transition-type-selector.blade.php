<div>
    <h2 class="text-lg font-medium text-gray-800 mb-2">Select Transition Type</h2>
    <p class="text-sm text-gray-600 mb-6">Choose how you want to transition students between classes.</p>
    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6" x-data="{ localTransitionType: @entangle('transitionType') }">
        <!-- Promotion Card - Google Material Design inspired -->
        <div class="relative cursor-pointer transition-all duration-200 transform hover:scale-102"
             @click="localTransitionType = 'promotion'">
            <input type="radio" wire:model.live="transitionType" id="promotion" value="promotion" class="hidden">
            <label for="promotion" class="block h-full">
                <div class="rounded-xl shadow-md overflow-hidden h-full border transition-all duration-200"
                     :class="{ 
                         'ring-2 ring-emerald-500 bg-emerald-50 border-emerald-200': localTransitionType === 'promotion',
                         'bg-white hover:bg-gray-50 border-gray-200 hover:shadow-lg': localTransitionType !== 'promotion'
                     }">
                    <div class="px-5 py-6 sm:p-6 flex flex-col items-center justify-center text-center h-full relative">
                        <!-- Top corner badge for selected state -->
                        <div x-show="localTransitionType === 'promotion'" 
                             class="absolute top-2 right-2 w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-sm">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        
                        <div class="p-4 rounded-full mb-1"
                             :class="{ 
                                'bg-emerald-100 text-emerald-600': localTransitionType === 'promotion',
                                'bg-gray-100 text-gray-600': localTransitionType !== 'promotion'
                             }">
                            <i class="fas fa-arrow-up text-2xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium" 
                            :class="{ 
                                'text-emerald-700': localTransitionType === 'promotion',
                                'text-gray-900': localTransitionType !== 'promotion'
                            }">
                            Promotion
                        </h3>
                        <p class="mt-2 text-sm" 
                           :class="{ 
                               'text-emerald-600': localTransitionType === 'promotion',
                               'text-gray-500': localTransitionType !== 'promotion'
                           }">
                            Move students to the next higher class
                        </p>
                        
                        <!-- Extra information visible on selection -->
                        <div x-show="localTransitionType === 'promotion'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="mt-4 pt-4 border-t border-emerald-200 w-full">
                            <p class="text-xs text-emerald-600">Suitable for end-of-year transitions when students successfully complete requirements</p>
                        </div>
                    </div>
                </div>
            </label>
        </div>

        <!-- Demotion Card -->
        <div class="relative cursor-pointer transition-all duration-200 transform hover:scale-102"
             @click="localTransitionType = 'demotion'">
            <input type="radio" wire:model.live="transitionType" id="demotion" value="demotion" class="hidden">
            <label for="demotion" class="block h-full">
                <div class="rounded-xl shadow-md overflow-hidden h-full border transition-all duration-200"
                     :class="{ 
                         'ring-2 ring-red-500 bg-red-50 border-red-200': localTransitionType === 'demotion',
                         'bg-white hover:bg-gray-50 border-gray-200 hover:shadow-lg': localTransitionType !== 'demotion'
                     }">
                    <div class="px-5 py-6 sm:p-6 flex flex-col items-center justify-center text-center h-full relative">
                        <!-- Top corner badge for selected state -->
                        <div x-show="localTransitionType === 'demotion'" 
                             class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center shadow-sm">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        
                        <div class="p-4 rounded-full mb-1"
                             :class="{ 
                                'bg-red-100 text-red-600': localTransitionType === 'demotion',
                                'bg-gray-100 text-gray-600': localTransitionType !== 'demotion'
                             }">
                            <i class="fas fa-arrow-down text-2xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium" 
                            :class="{ 
                                'text-red-700': localTransitionType === 'demotion',
                                'text-gray-900': localTransitionType !== 'demotion'
                            }">
                            Demotion
                        </h3>
                        <p class="mt-2 text-sm" 
                           :class="{ 
                               'text-red-600': localTransitionType === 'demotion',
                               'text-gray-500': localTransitionType !== 'demotion'
                           }">
                            Move students to the previous lower class
                        </p>
                        
                        <!-- Extra information visible on selection -->
                        <div x-show="localTransitionType === 'demotion'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="mt-4 pt-4 border-t border-red-200 w-full">
                            <p class="text-xs text-red-600">Used in special circumstances when a student needs additional time in a lower class</p>
                        </div>
                    </div>
                </div>
            </label>
        </div>

        <!-- Repetition Card -->
        <div class="relative cursor-pointer transition-all duration-200 transform hover:scale-102"
             @click="localTransitionType = 'repetition'">
            <input type="radio" wire:model.live="transitionType" id="repetition" value="repetition" class="hidden">
            <label for="repetition" class="block h-full">
                <div class="rounded-xl shadow-md overflow-hidden h-full border transition-all duration-200"
                     :class="{ 
                         'ring-2 ring-amber-500 bg-amber-50 border-amber-200': localTransitionType === 'repetition',
                         'bg-white hover:bg-gray-50 border-gray-200 hover:shadow-lg': localTransitionType !== 'repetition'
                     }">
                    <div class="px-5 py-6 sm:p-6 flex flex-col items-center justify-center text-center h-full relative">
                        <!-- Top corner badge for selected state -->
                        <div x-show="localTransitionType === 'repetition'" 
                             class="absolute top-2 right-2 w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center shadow-sm">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        
                        <div class="p-4 rounded-full mb-1"
                             :class="{ 
                                'bg-amber-100 text-amber-600': localTransitionType === 'repetition',
                                'bg-gray-100 text-gray-600': localTransitionType !== 'repetition'
                             }">
                            <i class="fas fa-redo text-2xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium" 
                            :class="{ 
                                'text-amber-700': localTransitionType === 'repetition',
                                'text-gray-900': localTransitionType !== 'repetition'
                            }">
                            Repetition
                        </h3>
                        <p class="mt-2 text-sm" 
                           :class="{ 
                               'text-amber-600': localTransitionType === 'repetition',
                               'text-gray-500': localTransitionType !== 'repetition'
                           }">
                            Make students repeat the same class
                        </p>
                        
                        <!-- Extra information visible on selection -->
                        <div x-show="localTransitionType === 'repetition'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="mt-4 pt-4 border-t border-amber-200 w-full">
                            <p class="text-xs text-amber-600">Used when students need to repeat a class to strengthen knowledge before advancing</p>
                        </div>
                    </div>
                </div>
            </label>
        </div>
    </div>
    
    @error('transitionType')
        <div class="text-red-500 text-sm mt-2 flex items-center">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $message }}
        </div>
    @enderror

    <!-- Transition Type Selection Help -->
    <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
            <i class="fas fa-lightbulb text-amber-500 mr-2"></i>
            Choosing the Right Transition
        </h3>
        <p class="text-sm text-gray-600">The transition type determines what class students can move to. For promotion, students move up to the next class in sequence. For demotion, they move to the previous class. Repetition keeps students in the same class for another year.</p>
    </div>
</div>

<style>
.hover\:scale-102:hover {
    transform: scale(1.02);
}
</style>
