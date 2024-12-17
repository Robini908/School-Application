<?php

namespace App\Livewire;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\StudentPayment;
use App\Models\StudentRecord;
use App\Services\MpesaService;
use App\Services\StripeService;
use Exception;
use Illuminate\Support\Facades\Log;

class Payments extends Component
{
    use LivewireAlert;

    public $student_id;
    public $paymentStatusHistory = [];

    public $students = [];
    public $student_search = '';
    public $selected_student = null;
    public $amount;
    public $payment_method;
    public $description;
    public $phone;
    public $payment_id;
    public $showPaymentForm = true;
    public $showPaymentsList = false;
    public $payments = [];

    // For the search input and dynamic student list
    public $studentSearchResults = [];

    public function mount()
    {
        // Initial fetch of recent payments
        $this->payments = StudentPayment::with('student')->latest()->take(10)->get();
    }

    // Search for students when the user types in the search box
    public function updatedStudentSearch()
    {
        $this->studentSearchResults = StudentRecord::where('first_name', 'like', '%' . $this->student_search . '%')
            ->orWhere('last_name', 'like', '%' . $this->student_search . '%')
            ->get();
    }

    public function closeAction()
    {
        $this->resetForm();
        $this->showPaymentForm = false;
        $this->showPaymentsList = true;
    }

    public function create(){
        $this->resetForm();
        $this->showPaymentForm = true;
        $this->showPaymentsList = false;
    }

    // Select a student from the search results
    public function selectStudent($student_id)
    {
        $this->student_id = $student_id;
        $this->selected_student = StudentRecord::find($student_id);
        $this->student_search = ''; // Clear search input after selection
        $this->studentSearchResults = []; // Clear search results
    }

    // Submit a new payment
    public function submitPayment()
    {
        $this->validate([
            'student_id' => 'required|exists:student_records,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:mpesa,stripe,bank',
            'phone' => 'required_if:payment_method,mpesa',
        ]);

        try {
            $payment = new StudentPayment();
            $payment->student_id = $this->student_id;
            $payment->amount = $this->amount;
            $payment->payment_method = $this->payment_method;
            $payment->status = 'pending';
            $payment->description = $this->description;

            // Process the payment based on the selected method
            if ($this->payment_method === 'mpesa') {
                $mpesaService = new MpesaService();
                $response = $mpesaService->initiatePayment($this->amount, $this->phone, $this->description);

                if ($response['status'] === 'error') {
                    $this->alert('error', $response['message']);
                    return;
                }

                // Save the MPesa transaction ID if available
                $payment->transaction_id = $response['transaction_id'] ?? null; // Adjust this based on the actual MPesa response format
                $payment->save();
                $this->alert('success', 'Mpesa payment initiated.');
            } elseif ($this->payment_method === 'stripe') {
                $stripeService = new StripeService();
                $intent = $stripeService->createPaymentIntent($this->amount);

                if (isset($intent->error)) {
                    $this->alert('error', $intent->error->message);
                    return;
                }

                // Save Stripe transaction ID
                $payment->transaction_id = $intent->id;
                $payment->save();
                $this->alert('success', 'Stripe payment initiated.');
            } else {
                // For bank payments, generate a unique transaction ID or leave it null
                $payment->transaction_id = 'BANK-' . time();  // Example: Use timestamp for unique ID, or leave as null
                $payment->save();
                $this->alert('success', 'Bank payment recorded.');
            }

            // Reset form and switch view to the payment list
            $this->resetForm();
            $this->showPaymentForm = false;
            $this->showPaymentsList = true;
            $this->payments = StudentPayment::with('student')->latest()->take(10)->get(); // Refresh payments list

        } catch (Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            $this->alert('error', 'An error occurred while processing the payment. Please try again later.');
        }
    }


    public function undoAction($paymentId)
    {
        $payment = StudentPayment::find($paymentId);

        if (isset($this->paymentStatusHistory[$paymentId])) {
            // Restore the previous status
            $payment->status = $this->paymentStatusHistory[$paymentId];
            $payment->save();

            // Clear the history after undo
            unset($this->paymentStatusHistory[$paymentId]);

            $this->alert('success', 'Action undone successfully.');
        } else {
            $this->alert('error', 'No previous action found to undo.');
        }

        // Refresh the payments list
        $this->payments = StudentPayment::with('student')->latest()->take(10)->get();
    }

    // Approve a payment
    public function approvePayment($payment_id)
    {
        try {
            $payment = StudentPayment::find($payment_id);
            if ($payment) {
                // Save current status before changing it
                $this->paymentStatusHistory[$payment_id] = $payment->status;

                // Update the status
                $payment->status = 'approved';
                $payment->save();

                $this->alert('success', 'Payment approved successfully.');
            } else {
                $this->alert('error', 'Payment not found.');
            }

            // Refresh the payments list
            $this->payments = StudentPayment::with('student')->latest()->take(10)->get();
        } catch (Exception $e) {
            Log::error('Approve Payment Error: ' . $e->getMessage());
            $this->alert('error', 'An error occurred while approving the payment.');
        }
    }

    // Mark a payment as failed
    public function markPaymentAsFailed($payment_id)
    {
        try {
            $payment = StudentPayment::find($payment_id);
            if ($payment) {
                // Save current status before changing it
                $this->paymentStatusHistory[$payment_id] = $payment->status;

                $payment->status = 'failed';
                $payment->save();
                $this->alert('success', 'Payment marked as failed.');
            } else {
                $this->alert('error', 'Payment not found.');
            }

            // Refresh the payments list
            $this->payments = StudentPayment::with('student')->latest()->take(10)->get();
        } catch (Exception $e) {
            Log::error('Mark Payment as Failed Error: ' . $e->getMessage());
            $this->alert('error', 'An error occurred while marking the payment as failed.');
        }
    }

    // Mark a payment as successful
    public function markPaymentAsSuccessful($payment_id)
    {
        try {
            $payment = StudentPayment::find($payment_id);
            if ($payment) {
                // Save current status before changing it
                $this->paymentStatusHistory[$payment_id] = $payment->status;

                $payment->status = 'successful';
                $payment->save();
                $this->alert('success', 'Payment marked as successful.');
            } else {
                $this->alert('error', 'Payment not found.');
            }

            // Refresh the payments list
            $this->payments = StudentPayment::with('student')->latest()->take(10)->get();
        } catch (Exception $e) {
            Log::error('Mark Payment as Successful Error: ' . $e->getMessage());
            $this->alert('error', 'An error occurred while marking the payment as successful.');
        }
    }

    // Delete a payment
    public function deletePayment($payment_id)
    {
        try {
            $payment = StudentPayment::find($payment_id);

            // Save current status before deletion
            $this->paymentStatusHistory[$payment_id] = $payment->status;

            $payment->delete();
            $this->alert('success', 'Payment deleted successfully.');

            // Refresh the payments list
            $this->payments = StudentPayment::with('student')->latest()->take(10)->get();
        } catch (Exception $e) {
            Log::error('Delete Payment Error: ' . $e->getMessage());
            $this->alert('error', 'An error occurred while deleting the payment.');
        }
    }


    // Reset the payment form
    public function resetForm()
    {
        $this->student_id = null;

        $this->selected_student = null;
        $this->amount = null;
        $this->payment_method = null;
        $this->description = null;
        $this->phone = null;
        $this->student_search = '';
        $this->studentSearchResults = [];
    }

    public function render()
    {
        return view('livewire.payments');
    }
}
