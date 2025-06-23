// Global variables to store current data
let currentModalData = null;
let currentModalConfig = null;
let currentApiRoute = null;
let currentId = null;

function showDetailsModal(apiRouteWithPlaceholder, id, title = 'Details', detailsConfig = null) {
    const url = apiRouteWithPlaceholder.replace(':id', id);
    const modalTitleElement = document.getElementById('modal-title');
    const modal = document.getElementById('details_modal');

    // Store current values for retry functionality
    currentApiRoute = apiRouteWithPlaceholder;
    currentId = id;
    currentModalConfig = detailsConfig;

    const commonDetailConfig = [
        { label: 'Created By', key: 'creater_name' },
        { label: 'Created Date', key: 'created_at_formatted' },
        { label: 'Updated By', key: 'updater_name' },
        { label: 'Updated Date', key: 'updated_at_formatted' },
    ]

    if (detailsConfig) {
        detailsConfig.push(...commonDetailConfig);
    } else {
        detailsConfig = commonDetailConfig;
    }

    // Set modal title immediately
    modalTitleElement.innerText = title;

    // Show modal with loading state
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Reset states
    document.getElementById('loading-state').classList.remove('hidden');
    document.getElementById('details-content').classList.add('hidden');
    document.getElementById('error-state').classList.add('hidden');

    // Make API call
    axios.post(url)
        .then(res => {
            const data = res.data;
            currentModalData = data;
            loadModalDetails(data, detailsConfig);
        })
        .catch(err => {
            console.error('Error loading details:', err);
            showModalError();
        });
}

function loadModalDetails(data, detailsConfig) {
    try {
        let html = '';

        if (detailsConfig && Array.isArray(detailsConfig)) {
            detailsConfig.forEach(item => {
                const label = item.label || item.key;
                let rawValue = data[item.key];

                let formattedValue;
                if (item.loop && Array.isArray(rawValue)) {
                    // Looping through nested items (e.g., permissions)
                    formattedValue = rawValue.map(subItem =>
                        formatValue(subItem[item.loopKey], item.key, item.type, (data[item.label_color] || 'badge-secondary'))
                    ).join(', ');
                } else {
                    // Single value
                    formattedValue = formatValue(rawValue, item.key, item.type, (data[item.label_color] || 'badge-secondary'));
                }
                const icon = item.icon || getDefaultIcon(item.key);

                // Format different types of values
                let displayValue = formattedValue;

                html += `
                    <div class="detail-item flex items-center justify-between py-4 px-4 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="${icon}" class="w-4 h-4 text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">${label}</span>
                        </div>
                        <div class="text-right ml-4">
                            ${displayValue}
                        </div>
                    </div>
                `;
            });
        } else {
            // Fallback: show everything if no specific config is provided
            for (const [key, value] of Object.entries(data)) {
                const formattedKey = key
                    .replace(/_/g, ' ')
                    .replace(/\b\w/g, l => l.toUpperCase());

                const icon = getDefaultIcon(key);
                const displayValue = formatValue(value, key, label_color);

                html += `
                    <div class="detail-item flex items-center justify-between py-4 px-4 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="${icon}" class="w-4 h-4 text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">${formattedKey}</span>
                        </div>
                        <div class="text-right ml-4">
                            ${displayValue}
                        </div>
                    </div>
                `;
            }
        }

        document.getElementById('details-content').innerHTML = html;
        document.getElementById('loading-state').classList.add('hidden');
        document.getElementById('details-content').classList.remove('hidden');

        // Reinitialize Lucide icons if available
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

    } catch (error) {
        console.error('Error rendering details:', error);
        showModalError();
    }
}

function formatValue(value, key, type, label_color) {
    if (value === null || value === undefined || value === '') {
        return '<span class="text-gray-400 dark:text-gray-500 italic">N/A</span>';
    }

    // Handle image type
    if (type === 'image') {
        return `
            <div class="relative group cursor-pointer" onclick="openImageLightbox('${value}')">
                <img src="${value}" alt="Preview"
                     class="w-20 h-20 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:border-blue-400 transition-all duration-200 shadow-sm hover:shadow-md">
                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 rounded-lg transition-all duration-200 flex items-center justify-center">
                    <i data-lucide="zoom-in" class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                </div>
            </div>
        `;
    }

    // Handle video type
    if (type === 'video') {
        return `
            <div class="relative group cursor-pointer" onclick="openVideoLightbox('${value}')">
                <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:border-blue-400 transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center">
                    <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-40 rounded-lg transition-all duration-200 flex items-center justify-center">
                        <i data-lucide="play" class="w-8 h-8 text-white opacity-80 group-hover:opacity-100 transition-opacity duration-200"></i>
                    </div>
                    <i data-lucide="video" class="w-8 h-8 text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>
        `;
    }

    // Format based on key type
    if (key.toLowerCase().includes('status')) {
        return formatStatus(value, label_color);
    } else if (key.toLowerCase().includes('email')) {
        return `<a href="mailto:${value}" class="text-blue-600 dark:text-blue-400 hover:underline">${value}</a>`;
    } else if (key.toLowerCase().includes('phone')) {
        return `<a href="tel:${value}" class="text-blue-600 dark:text-blue-400 hover:underline">${value}</a>`;
    } else {
        return `<span class="text-gray-900 dark:text-white font-medium">${value}</span>`;
    }
}

function formatStatus(status, label_color) {
    const statusStr = String(status).toLowerCase();
    // let colorClass = '';

    // if (statusStr === 'active' || statusStr === '1' || statusStr === 'enabled') {
    //     colorClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
    // } else if (statusStr === 'inactive' || statusStr === '0' || statusStr === 'disabled') {
    //     colorClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
    // } else if (statusStr === 'pending') {
    //     colorClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
    // } else {
    //     colorClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    // }

    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium badge badge-soft ${label_color}">${status}</span>`;
}

function getDefaultIcon(key) {
    const keyLower = key.toLowerCase();

    if (keyLower.includes('permission')) return 'shield-check';
    if (keyLower.includes('role')) return 'shield';
    if (keyLower.includes('name')) return 'user';
    if (keyLower.includes('email')) return 'mail';
    if (keyLower.includes('phone')) return 'phone';
    if (keyLower.includes('status')) return 'activity';
    if (keyLower.includes('created')) return 'calendar';
    if (keyLower.includes('updated')) return 'edit';
    if (keyLower.includes('date')) return 'calendar';
    if (keyLower.includes('time')) return 'clock';
    if (keyLower.includes('department')) return 'building';
    if (keyLower.includes('address')) return 'map-pin';
    if (keyLower.includes('video')) return 'video';
    if (keyLower.includes('id')) return 'hash';
    if (keyLower.includes('image')) return 'image';

    return 'info';
}

// Image Lightbox Functions
function openImageLightbox(imageSrc) {
    const lightboxHtml = `
        <div id="image-lightbox" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm animate-fade-in">
            <div class="relative max-w-3xl aspect-video mx-4">
                <button onclick="closeLightbox()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors duration-200 z-10">
                    <i data-lucide="x" class="w-8 h-8"></i>
                </button>
                <img src="${imageSrc}" alt="Full Size Preview"
                     class="max-w-full object-contain rounded-lg shadow-2xl animate-scale-in">
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/50 text-white px-4 py-2 rounded-full text-sm backdrop-blur-sm">
                    <i data-lucide="zoom-in" class="w-4 h-4 inline mr-2"></i>
                    Click anywhere to close
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', lightboxHtml);
    document.body.style.overflow = 'hidden';

    // Add click to close functionality
    document.getElementById('image-lightbox').addEventListener('click', function(e) {
        if (e.target === this || e.target.tagName === 'IMG') {
            closeLightbox();
        }
    });

    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Video Lightbox Functions
function openVideoLightbox(videoSrc) {
    const lightboxHtml = `
        <div id="video-lightbox" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm animate-fade-in">
            <div class="relative max-w-4xl max-h-[90vh] mx-4 bg-black rounded-lg overflow-hidden shadow-2xl animate-scale-in">
                <div class="absolute top-4 right-4 z-10">
                    <button onclick="closeLightbox()" class="bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-all duration-200 backdrop-blur-sm">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <video id="lightbox-video" class="w-full h-auto max-h-[90vh]" controls autoplay>
                    <source src="${videoSrc}" type="video/mp4">
                    <source src="${videoSrc}" type="video/webm">
                    <source src="${videoSrc}" type="video/ogg">
                    Your browser does not support the video tag.
                </video>
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/50 text-white px-4 py-2 rounded-full text-sm backdrop-blur-sm">
                    <i data-lucide="play" class="w-4 h-4 inline mr-2"></i>
                    Use controls to play/pause
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', lightboxHtml);
    document.body.style.overflow = 'hidden';

    // Add click to close functionality (but not on video element)
    document.getElementById('video-lightbox').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });

    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Close Lightbox Function
function closeLightbox() {
    const imageLightbox = document.getElementById('image-lightbox');
    const videoLightbox = document.getElementById('video-lightbox');

    if (imageLightbox) {
        imageLightbox.remove();
    }

    if (videoLightbox) {
        // Pause video before removing
        const video = document.getElementById('lightbox-video');
        if (video) {
            video.pause();
        }
        videoLightbox.remove();
    }

    document.body.style.overflow = 'auto';
}

function showModalError() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('details-content').addClass('hidden');
    document.getElementById('error-state').classList.remove('hidden');

    // Reinitialize Lucide icons if available
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function retryLoadDetails() {
    if (currentApiRoute && currentId) {
        document.getElementById('error-state').classList.add('hidden');
        document.getElementById('loading-state').classList.remove('hidden');

        setTimeout(() => {
            showDetailsModal(currentApiRoute, currentId, document.getElementById('modal-title').innerText, currentModalConfig);
        }, 500);
    }
}

function closeDetailsModal() {
    const modal = document.getElementById('details_modal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';

    // Clear stored data
    currentModalData = null;
    currentModalConfig = null;
    currentApiRoute = null;
    currentId = null;
}

function exportDetailsAsCSV() {
    if (!currentModalData) return;

    try {
        let csv = 'Key,Value\n';
        for (const [key, value] of Object.entries(currentModalData)) {
            csv += `"${key}","${value ?? 'N/A'}"\n`;
        }

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `details-${Date.now()}.csv`;
        link.click();
        URL.revokeObjectURL(url);
    } catch (error) {
        console.error('CSV export failed:', error);
        alert('Export to CSV failed. Please try again.');
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('details_modal');
        if (!modal.classList.contains('hidden')) {
            closeDetailsModal();
        }

        // Also close lightbox if open
        const imageLightbox = document.getElementById('image-lightbox');
        const videoLightbox = document.getElementById('video-lightbox');
        if (imageLightbox || videoLightbox) {
            closeLightbox();
        }
    }
});

// Prevent modal from closing when clicking inside the modal content
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('details_modal');
    if (modal) {
        const modalContent = modal.querySelector('.glass-card');
        if (modalContent) {
            modalContent.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }
    }
});
