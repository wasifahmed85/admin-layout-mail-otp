fileTypes = [
    "application/pdf",
    "application/msword",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    "application/vnd.ms-excel",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    "image/jpeg",
    "image/png",
    "image/gif",
];

function file_upload(
    selectors,
    fileTypes = ["image/*"],
    existingFiles = [],
    multipleFile = false,

) {
    $.each(selectors.reverse(), function (index, selector) {
        const inputElement = document.querySelector(selector);
        const fileUrls = existingFiles[selector];

        const pondOptions = {
            acceptedFileTypes: fileTypes,
            allowMultiple: multipleFile,
            storeAsFile: true,
        };

        if (multipleFile) {
            if (Array.isArray(fileUrls) && fileUrls.length) {
                pondOptions.files = fileUrls.map(url => ({
                    source: url,
                    options: { type: "remote" }
                }));
            }
        } else {
            if (typeof fileUrls === "string" && fileUrls.trim() !== "") {
                pondOptions.files = [
                    {
                        source: fileUrls,
                        options: { type: "remote" }
                    }
                ];
            }
        }


        FilePond.create(inputElement, pondOptions);
    });
}
