<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sri Lanka NIC Finder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'bounce-in': 'bounceIn 0.6s ease-out',
                        'fade-in': 'fadeIn 0.5s ease-in',
                        'slide-up': 'slideUp 0.4s ease-out'
                    },
                    keyframes: {
                        bounceIn: {
                            '0%': { transform: 'scale(0.3)', opacity: '0' },
                            '50%': { transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-cyan-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="text-3xl">🇱🇰</div>
                    <h1 class="text-2xl font-bold text-gray-800">NIC Details Finder</h1>
                </div>
               
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Input Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 animate-fade-in">
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Enter Your NIC Number</h2>
                <p class="text-gray-500">Supports both old (10-digit) and new (12-digit) NIC formats</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input 
                    type="text" 
                    id="nic" 
                    placeholder="e.g., 200145601234 or 991234567V"
                    class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition-colors duration-200 text-center sm:text-left"
                    maxlength="12"
                >
                <button 
                    id="btn"
                    class="px-8 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-cyan-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                    <span class="flex items-center justify-center space-x-2">
                        <span>Find Details</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loading" class="hidden text-center py-8">
            <div class="inline-flex items-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                <span class="text-gray-600">Processing your request...</span>
            </div>
        </div>

        <!-- Results Section -->
        <div id="results" class="hidden">
            <!-- Results will be dynamically populated here -->
        </div>

        <!-- Error Display -->
        <div id="error" class="hidden bg-red-50 border-l-4 border-red-400 p-4 rounded-lg animate-bounce-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700" id="error-message"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-100 mt-16">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="text-center text-gray-600">
                <p class="mb-2">Sri Lanka NIC Details Finder</p>
                <p class="text-sm">
                    Developed by <span class="font-semibold text-blue-600">Gagana Randimal</span>
                </p>
            </div>
        </div>
    </footer>

    <script src="script.js">
       
    </script>
</body>
</html>