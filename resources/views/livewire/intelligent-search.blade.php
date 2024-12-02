<div style="position: relative;">
    <!-- Search Input -->
    <input 
        type="text" 
        wire:model.live.debounce.100ms="query" 
        placeholder="Search for a student..." 
        class="form-control"
        style="padding: 10px; border-radius: 8px; outline: none; border: 1px solid #ced4da;"
    />

    <!-- Results Modal -->
    @if(isset($query) && !empty($query ?? '') && collect($results)->isNotEmpty())        <div 
            style="position: absolute; z-index: 1050; width: 100%; margin-top: 5px; background-color: white; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-height: 300px; overflow-y: auto;"
        >
            @foreach($results as $student)
                <div 
                    class="d-flex align-items-center" 
                    style="padding: 10px; cursor: pointer; border-bottom: 1px solid #e9ecef; transition: background-color 0.3s;" 
                    onmouseover="this.style.backgroundColor='#f8f9fa'" 
                    onmouseout="this.style.backgroundColor='white'"
                >
                    <!-- Student Photo (Optional) -->
                    <div 
                        style="width: 40px; height: 40px; background-color: #f1f1f1; border-radius: 50%; overflow: hidden; margin-right: 10px;"
                    >
                        <img 
                            src="{{ $student->photo ?? 'https://via.placeholder.com/40' }}" 
                            alt="Student Photo" 
                            style="width: 100%; height: 100%; object-fit: cover;"
                        />
                    </div>

                    <!-- Student Info -->
                    <div>
                        <p style="margin: 0; font-size: 14px; font-weight: 500; color: #343a40;">
                            {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                        </p>
                        <p style="margin: 0; font-size: 12px; color: #6c757d;">
                            {{ $student->adm_no }} • {{ $student->town }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif(!empty($query))
        <div 
            style="position: absolute; z-index: 1050; width: 100%; margin-top: 5px; background-color: white; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-height: 300px; overflow-y: auto;"
        >
            <div style="padding: 10px; font-size: 14px; color: #6c757d;">No students found.</div>
        </div>
    @endif
</div>
