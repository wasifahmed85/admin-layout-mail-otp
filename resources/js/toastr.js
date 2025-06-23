import toastr from "toastr";
import "toastr/build/toastr.min.css";
window.toastr = toastr;
toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: false,
    progressBar: true,
    positionClass: "toast-top-right", // Positioning
    preventDuplicates: true,
    onclick: null,
    showDuration: "150",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};
