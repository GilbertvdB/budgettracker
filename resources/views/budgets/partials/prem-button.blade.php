<div class="relative">
            @if(auth()->user()->is_premium)
                <!-- Show Add Budget button for premium users -->
            @else
                <!-- Overlay button and disable it for non-premium users -->
                <div class="relative">
                    <a href="javascript:void(0);" class="border bg-blue-500 text-white px-4 py-2 rounded cursor-not-allowed opacity-50">
                        {{ __('Add Budgets') }}
                    </a>
                    <!-- Overlay and SVG for non-premium users -->
                    <div class="absolute left-0 bg-gray-800 bg-opacity-30 flex justify-center items-center">
                        <div class="flex items-center text-amber-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                                <path d="M11.983 1.907a.75.75 0 0 0-1.292-.657l-8.5 9.5A.75.75 0 0 0 2.75 12h6.572l-1.305 6.093a.75.75 0 0 0 1.292.657l8.5-9.5A.75.75 0 0 0 17.25 8h-6.572l1.305-6.093Z" />
                            </svg>
                            <strong>Premium</strong>
                        </div>
                    </div>
                </div>
            @endif
        </div>