import swal from 'sweetalert2';

window.showAlert = (type, message) => {
    swal.fire({
        icon: type,
        title: type.charAt(0).toUpperCase() + type.slice(1),
        text: message,
        timer: 3000,
        showConfirmButton: false
    });
};

window.confirmDelete = (callback) => {
    swal.fire({
        title: 'Move to Recycle Bin?',
        text: "You can restore this item from Recycle Bin!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, move it!'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

window.confirmPermanentDelete = (callback) => {
    swal.fire({
        title: 'Delete Permanently?',
        text: "This item will be permanently removed and can't be recovered!",
        icon: 'danger',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete forever!'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};
window.confirmBookIssue = (callback) => {
    swal.fire({
        title: 'Issue Book?',
        text: "Are you sure you want to issue this book?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, issue!'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};
