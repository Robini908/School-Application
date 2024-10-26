<div id="main-content" class="relative">
    <div class="loading-wrapper">
        <div class="loading-screen d-flex justify-content-center align-items-center">
            <div class="card w-100 h-auto p-4">
                <div class="card-body">
                    <div class="shimmer shimmer-title mb-3"></div>
                    <div class="shimmer shimmer-line mb-2"></div>
                    <div class="shimmer shimmer-line mb-2"></div>
                    <div class="shimmer shimmer-line mb-4"></div>
                    <div class="shimmer shimmer-title mb-3"></div>
                    <div class="shimmer shimmer-line mb-2"></div>
                    <div class="shimmer shimmer-line mb-2"></div>
                    <div class="shimmer shimmer-line mb-2"></div>
                    <div class="shimmer shimmer-image mb-4"></div>

                    <div class="d-flex justify-content-center mt-4">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Loading Wrapper */
        .loading-wrapper {
            position: relative;
            height: 100vh; /* Full viewport height */
            width: 100%;
        }
    
        /* Fullscreen loading screen covering only the main content area */
        .loading-wrapper .loading-screen {
            background-color: #f5f5f5;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
    
        /* Card layout */
        .loading-wrapper .card {
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    
        /* Shimmer effect keyframes */
        @keyframes shimmer {
            0% {
                background-position: -200px 0;
            }
            100% {
                background-position: 200px 0;
            }
        }
    
        /* Shimmer effect styling */
        .loading-wrapper .shimmer {
            background: linear-gradient(90deg, #f5f5f5 25%, #e0e0e0 50%, #f5f5f5 75%);
            background-size: 200px 100%;
            animation: shimmer 1.5s infinite;
        }
    
        .loading-wrapper .shimmer-title {
            height: 30px;
            width: 60%;
        }
    
        .loading-wrapper .shimmer-line {
            height: 16px;
            width: 100%;
        }
    
        .loading-wrapper .shimmer-image {
            height: 180px;
            width: 100%;
            background-color: #e0e0e0;
        }
    
        /* Pulsing dots */
        .loading-wrapper .dot {
            width: 16px;
            height: 16px;
            margin: 0 6px;
            border-radius: 50%;
            background-color: #6c757d;
            animation: pulse 1.2s infinite ease-in-out;
        }
    
        @keyframes pulse {
            0%,
            100% {
                transform: scale(1);
                opacity: 0.6;
            }
            50% {
                transform: scale(1.3);
                opacity: 1;
            }
        }
    
        .loading-wrapper .dot:nth-child(1) {
            animation-delay: 0s;
        }
    
        .loading-wrapper .dot:nth-child(2) {
            animation-delay: 0.2s;
        }
    
        .loading-wrapper .dot:nth-child(3) {
            animation-delay: 0.4s;
        }
    
        /* Smooth fade-in effect */
        .loading-wrapper .loading-page {
            opacity: 0;
            animation: fadeIn 0.6s forwards;
        }
    
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
    
</div>
