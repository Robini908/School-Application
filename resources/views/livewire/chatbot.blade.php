<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Chatbot container -->
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #007bff; color: white; font-size: 18px;">
                    <i class="fas fa-robot"></i> Chatbot
                </div>
                <div class="card-body" style="height: 400px; overflow-y: auto; padding: 15px;">
                    <!-- Display messages -->
                    <div id="messages">
                        @foreach ($messages as $message)
                            <div class="message" style="margin-bottom: 10px; text-align: {{ $message['role'] == 'user' ? 'right' : 'left' }};">
                                <div class="message-content" style="max-width: 80%; margin-left: auto;">
                                    @if ($message['role'] == 'user')
                                        <div class="bg-primary text-white rounded p-2 mb-2 d-inline-block" style="background-color: #007bff !important;">
                                            <i class="fas fa-user"></i> {{ $message['content'] }}
                                        </div>
                                    @else
                                        <div class="bg-light text-dark rounded p-2 mb-2 d-inline-block" style="background-color: #f8f9fa !important;">
                                            <i class="fas fa-robot"></i> {{ $message['content'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer" style="background-color: #f7f7f7; border-top: 1px solid #ddd;">
                    <!-- User input form -->
                    <form wire:submit.prevent="sendMessage" class="input-group">
                        <input type="text" wire:model="userMessage" class="form-control" placeholder="Type your message..." required style="border-radius: 30px !important;">
                        <button type="submit" class="btn btn-primary" style="border-radius: 30px !important;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


