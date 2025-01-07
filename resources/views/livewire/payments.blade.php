<div class="card p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    @if (!$showPaymentForm)
        <h2 class="text-center fw-bold mb-4" style="color: #4a5568;">Manage Student Payments</h2>
    @else
        <h2 class="text-start fw-bold mb-4" style="color: #4a5568;">Add new payment</h2>
    @endif


    <!-- Show Add Payment Button or Form Based on State -->
    @if (!$showPaymentsList)
        <!-- Display Payments List -->
        <form wire:submit.prevent="submitPayment">
            <!-- Search and Select Student -->
            <div class="mb-3 col-md-6">
                <label for="student_search" class="form-label fw-semibold" style="color: #4a5568;">Select Student</label>
                <input type="text" wire:model.live="student_search" id="student_search" class="form-control"
                    placeholder="Search for student...">

                <!-- Display search results -->
                @if ($student_search && count($studentSearchResults))
                <div class="modal-dialog-centered">
                    <ul class="list-group mt-2">
                        @foreach ($studentSearchResults as $student)
                            <li class="list-group-item list-group-item-action"
                                wire:click="selectStudent({{ $student->id }})">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Display selected student -->
                @if ($selected_student)
                    <div class="mt-2">Selected Student: <strong
                            class="font-bold text-success">{{ $selected_student->first_name }}
                            {{ $selected_student->last_name }}</strong></div>
                @endif
                @error('student_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <!-- Payment Amount -->
            <div class="mb-3">
                <label for="amount" class="form-label fw-semibold" style="color: #4a5568;">Payment Amount</label>
                <div class="input-group md-col-6">
                    <span class="input-group-text">KSH</span>
                    <input type="number" wire:model="amount" id="amount" class="form-control"
                        placeholder="Enter payment amount">
                </div>
                @error('amount')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Payment Method -->
            <div class="mb-3">
                <label class="form-label fw-semibold" style="color: #4a5568;">Payment Method</label>
                <div class="d-flex justify-content-around">
                    <div>
                        <input type="radio" wire:model.live="payment_method" id="mpesa" value="mpesa"
                            class="btn-check">
                        <label for="mpesa" class="btn btn-outline-primary"><i class="bi bi-phone"></i> MPesa</label>
                    </div>
                    <div>
                        <input type="radio" wire:model.live="payment_method" id="stripe" value="stripe"
                            class="btn-check">
                        <label for="stripe" class="btn btn-outline-primary"><i class="bi bi-credit-card"></i>
                            Stripe</label>
                    </div>
                    <div>
                        <input type="radio" wire:model.live="payment_method" id="bank" value="bank"
                            class="btn-check">
                        <label for="bank" class="btn btn-outline-primary"><i class="bi bi-bank"></i> Bank</label>
                    </div>
                </div>
                @error('payment_method')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Conditional Field for MPesa -->
            @if ($payment_method === 'mpesa')
                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold" style="color: #4a5568;">Phone Number</label>
                    <input type="text" wire:model="phone" id="phone" class="form-control"
                        placeholder="Enter phone number">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <!-- Submit Button and Cancel -->
            <div class="text-center mt-4 flex justify-center gap-4">
                <button type="submit" class="btn btn-primary px-4 py-2 text-sm font-bold">
                    <i class="bi bi-credit-card"></i> Submit Payment
                </button>
                <button wire:click="closeAction" type="button" class="btn btn-secondary px-4 py-2 text-sm font-bold">
                    <i class="bi bi-arrow-left-circle"></i> Cancel
                </button>
            </div>
        </form>
    @endif

    <!-- Payment details -->
    @if ($showPaymentsList)
        <div>
            <div class="card-header flex justify-between items-center">
                <h5>Recent Payments</h5>
                <button wire:click="create" class="btn btn-primary mb-3">
                    <i class="bi bi-plus-circle"></i> Add Payment
                </button>
            </div>

            <div class="card-body">
                @if ($payments->isEmpty())
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle-fill"></i> No payments recorded yet. Be the first to add a payment!
                    </div>
                @else
                    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                            <thead class="thead-light">
                                <tr>
                                    <th>Student</th>
                                    <th>Amount(KSH)</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->student->first_name }} {{ $payment->student->last_name }}</td>
                                        <td>Ksh{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td>{{ ucfirst($payment->status) }}</td>
                                        <td>
                                            @if ($payment->status == 'pending')
                                                <button wire:click="approvePayment({{ $payment->id }})"
                                                    class="btn btn-sm btn-success" title="Approve">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @elseif(in_array($payment->status, ['approved', 'successful', 'failed', 'deleted']))
                                                <button wire:click="undoAction({{ $payment->id }})"
                                                    class="btn btn-sm btn-secondary" title="Undo">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            @endif

                                            @if ($payment->status == 'approved')
                                                <button wire:click="markPaymentAsSuccessful({{ $payment->id }})"
                                                    class="btn btn-sm btn-primary" title="Mark as Successful">
                                                    <i class="bi bi-check-all"></i>
                                                </button>
                                            @elseif($payment->status == 'successful')
                                                <button wire:click="undoAction({{ $payment->id }})"
                                                    class="btn btn-sm btn-secondary" title="Undo Success">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            @endif

                                            @if ($payment->status == 'pending')
                                                <button wire:click="markPaymentAsFailed({{ $payment->id }})"
                                                    class="btn btn-sm btn-danger" title="Mark as Failed">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            @elseif($payment->status == 'failed')
                                                <button wire:click="undoAction({{ $payment->id }})"
                                                    class="btn btn-sm btn-secondary" title="Undo Failure">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            @endif

                                            @if ($payment->status != 'deleted')
                                                <button wire:click="deletePayment({{ $payment->id }})"
                                                    class="btn btn-sm btn-warning" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @elseif($payment->status == 'deleted')
                                                <button wire:click="undoAction({{ $payment->id }})"
                                                    class="btn btn-sm btn-secondary" title="Undo Deletion">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            @endif
                                        </td>



                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
