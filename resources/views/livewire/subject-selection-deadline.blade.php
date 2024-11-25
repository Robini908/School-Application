<div class="mb-4">
    <label for="deadline" class="form-label">Set Deadline</label>
    <input type="datetime-local" wire:model.blur="deadline" class="form-control">
    
    <!-- Save Deadline Button with Loading Spinner -->
    <button 
        wire:click="setDeadline"
        class="btn btn-primary mt-2 d-flex align-items-center"
        wire:loading.attr="disabled" 
        wire:target="setDeadline">
        <!-- Show spinner while loading -->
        <span wire:loading.remove>Save Deadline</span>
        <span wire:loading class="spinner-border spinner-border-sm text-light ms-2" role="status"></span>
    </button>


    <div x-data="{
        deadline: @entangle('deadline'),
        countdown: '', // Store the countdown
        updateCountdown() {
            const deadline = new Date(this.deadline);
            const now = new Date();
            const diffInMilliseconds = deadline - now;
    
            if (diffInMilliseconds <= 0) {
                this.countdown = 'Time\'s up!';
                return;
            }
    
            const weeks = Math.floor(diffInMilliseconds / (1000 * 60 * 60 * 24 * 7));
            const days = Math.floor((diffInMilliseconds % (1000 * 60 * 60 * 24 * 7)) / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diffInMilliseconds % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diffInMilliseconds % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diffInMilliseconds % (1000 * 60)) / 1000);
    
            this.countdown = `${weeks}w ${days}d ${hours}h ${minutes}m ${seconds}s`;
        },
        init() {
            setInterval(() => {
                this.updateCountdown();
            }, 1000);
            this.updateCountdown(); // Initial update
        }
    }" x-init="init()">

        <!-- Countdown Marquee -->
        @if ($selectedClassCount > 0)
            <div class="mt-4">
                <marquee behavior="scroll" direction="left" class="text-lg text-red-500 font-bold">
                    Time remaining for subject selection: 
                        <span class="badge bg-primary text-white font-bold px-4 py-2 rounded">
                            Time remaining: <span x-text="countdown"></span>
                        </span>
                   
                </marquee>
            </div>

        @else
            <!-- Display a message if no class is selected -->
            <div class="mt-4">
                <p class="text-red-500 font-bold">No class selected for the current year. Please select classes to set
                    the deadline.</p>
            </div>
        @endif
    </div>
</div>