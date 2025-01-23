// Check if the file is an image
export function isImage(file) {
    const validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    return validExtensions.includes(getFileExtension(file));
}

// Check if the file is a PDF
export function isPDF(file) {
    return getFileExtension(file) === 'pdf';
}

// Check if the file is an audio file
export function isAudio(file) {
    const validExtensions = ['mp3', 'ogg', 'wav', 'm4a', 'webm'];
    return validExtensions.includes(getFileExtension(file));
}

// Check if the file is a video file
export function isVideo(file) {
    const validExtensions = ['mp4', 'mpeg', 'ogg', 'mov', 'webm'];
    return validExtensions.includes(getFileExtension(file));
}

// Check if the file is a Word document
export function isWord(file) {
    const validExtensions = ['doc', 'docx'];
    return validExtensions.includes(getFileExtension(file));
}

// Check if the file is an Excel document
export function isExcel(file) {
    const validExtensions = ['xls', 'xlsx'];
    return validExtensions.includes(getFileExtension(file));
}

// Check if the file is a PowerPoint document
export function isPowerPoint(file) {
    const validExtensions = ['ppt', 'pptx'];
    return validExtensions.includes(getFileExtension(file));
}

// Check if the file is a ZIP archive
export function isZip(file) {
    return getFileExtension(file) === 'zip';
}

// Check if the file is a text file
export function isText(file) {
    const validExtensions = ['txt', 'csv'];
    return validExtensions.includes(getFileExtension(file));
}

// Check if the file is an HTML file
export function isHTML(file) {
    return getFileExtension(file) === 'html';
}

// Check if the file is a JavaScript file
export function isJavaScript(file) {
    return getFileExtension(file) === 'js';
}

// Check if the file is a CSS file
export function isCSS(file) {
    return getFileExtension(file) === 'css';
}

// Check if the file is a RAR archive
export function isRAR(file) {
    return getFileExtension(file) === 'rar';
}

// Check if the file is none of the above
export function isOtherType(file) {
    return !(
        isImage(file) ||
        isPDF(file) ||
        isAudio(file) ||
        isVideo(file) ||
        isWord(file) ||
        isExcel(file) ||
        isPowerPoint(file) ||
        isZip(file) ||
        isText(file) ||
        isHTML(file) ||
        isJavaScript(file) ||
        isCSS(file) ||
        isRAR(file)
    );
}

// Utility function to get the file extension
function getFileExtension(file) {
    return file.name.split('.').pop().toLowerCase();
}