{{-- Modern Details Modal Component --}}
@props(['title' => 'Details Modal'])

<div id="details_modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm animate-fade-in" onclick="closeDetailsModal()">
    </div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div
            class="glass-card relative w-full max-w-2xl mx-auto rounded-2xl shadow-2xl animate-slide-up bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg border border-white/20 dark:border-gray-700/30">

            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <i data-lucide="layout-list" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 id="modal-title" class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ __($title) }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('View detailed information') }}</p>
                    </div>
                </div>

                <button onclick="closeDetailsModal()"
                    class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Content -->
            <div id="modal-content" class="p-6 max-h-96 overflow-y-auto">
                <!-- Loading State -->
                <div id="loading-state" class="flex flex-col items-center justify-center py-12">
                    <div class="w-6 h-6 border-2 border-gray-300 border-t-blue-600 rounded-full animate-spin mb-4">
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Loading details...') }}</p>
                </div>

                <!-- Content will be populated here -->
                <div id="details-content" class="hidden space-y-1"></div>

                <!-- Error State -->
                <div id="error-state" class="hidden text-center py-12">
                    <div
                        class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="alert-circle" class="w-8 h-8 text-red-500"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Failed to Load') }}</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                        {{ __('Unable to fetch details. Please try again.') }}</p>
                    <button onclick="retryLoadDetails()"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                        <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                        {{ __('Retry') }}
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 rounded-b-2xl">
                <button class="btn btn-sm btn-primary" onclick="exportDetailsAsCSV()"><i
                        class="bx bx-download text-white"></i> {{ __('CSV') }}</button>
                <button onclick="closeDetailsModal()" class="btn btn-sm btn-error">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('css')
    <style>
        .detail-item {
            transition: all 0.2s ease;
        }

        .detail-item:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .dark .detail-item:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .detail-item {
            transition: all 0.2s ease;
        }

        .detail-item:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .dark .detail-item:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        /* Lightbox Animation Styles */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-scale-in {
            animation: scaleIn 0.3s ease-out;
        }

        /* Media Preview Hover Effects */
        .group:hover .w-20.h-20 {
            transform: scale(1.05);
        }

        /* Custom scrollbar for video controls */
        video::-webkit-media-controls-panel {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            #image-lightbox .max-w-7xl,
            #video-lightbox .max-w-4xl {
                max-width: 95vw;
                margin: 1rem;
            }

            #image-lightbox img,
            #video-lightbox video {
                max-height: 80vh;
            }
        }

        /* Focus styles for accessibility */
        button:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Loading state for images */
        .detail-item img {
            transition: opacity 0.3s ease;
        }

        .detail-item img:not([src]) {
            opacity: 0;
        }

        /* Backdrop blur support */
        @supports (backdrop-filter: blur(4px)) {
            .backdrop-blur-sm {
                backdrop-filter: blur(4px);
            }
        }
    </style>
@endpush
