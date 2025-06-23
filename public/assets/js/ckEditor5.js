document.addEventListener("DOMContentLoaded", () => {
    // alert("test");

    destroyAllEditors();
    const textAreas = $("textarea.textarea:not(.no-ckeditor5)");
    textAreas.each((index, textArea) => {
        const currentConfig = { ...CkEditorConfig };
        currentConfig.initialData = textArea.value;

        CkClassicEditor.create(textArea, currentConfig)
            .then((editor) => {
                // console.log("Test");
                // console.log("Editor was initialized", editor);
            })
            .catch((error) => {
                console.error(`Error initializing editor ${index + 1}:`, error);
                // Add error handling here, e.g., display error message to user
                alert(
                    `Error initializing editor ${index + 1}: ${error.message}`
                );
            });
    });
});
const editors = [];
function initializeCKEditor(textAreas) {
    textAreas.each((index, textArea) => {
        $(textArea).attr("data-index", index ?? 0);
        const currentConfig = { ...CkEditorConfig };
        currentConfig.initialData = textArea.value;

        CkClassicEditor.create(textArea, currentConfig)
            .then((editor) => {
                console.log("Editor was initialized", editor);
                editors.push(editor);
            })
            .catch((error) => {
                console.error(`Error initializing editor ${index + 1}:`, error);
                // Add error handling here, e.g., display error message to user
                alert(
                    `Error initializing editor ${index + 1}: ${error.message}`
                );
            });
    });
}
function destroyAllEditors() {
    editors.forEach((editor) => {
        editor
            .destroy()
            .then(() => {
                editor.value = "";
                console.log("Editor destroyed successfully");
            })
            .catch((error) => {
                console.error("Error destroying editor:", error);
            });
    });
    editors.length = 0;
}
