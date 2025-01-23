<template>
    <FolderIcon v-if="file.is_folder" class="h-5 w-5 text-gray-500" />
    <template v-else>
        <!-- Check if the file is an image -->
        <img src="/Images/FileIcons/image.png" alt="Image file" width="25" height="25" v-if="isImage(file)">

        <!-- Check if the file is a PDF -->
        <img src="/Images/FileIcons/pdf.png" alt="PDF file" width="25" height="25" v-if="isPDF(file)">

        <!-- Check if the file is an audio file -->
        <img src="/Images/FileIcons/audio.png" alt="Audio file" width="25" height="25" v-if="isAudio(file)">

        <!-- Check if the file is a video file -->
        <img src="/Images/FileIcons/video.png" alt="Video file" width="25" height="25" v-if="isVideo(file)">

        <!-- Check if the file is a Word document -->
        <img src="/Images/FileIcons/word.png" alt="Word file" width="25" height="25" v-if="isWord(file)">

        <!-- Check if the file is an Excel document -->
        <img src="/Images/FileIcons/excel.png" alt="Excel file" width="25" height="25" v-if="isExcel(file)">

        <!-- Check if the file is a PowerPoint document -->
        <img src="/Images/FileIcons/office.png" alt="PowerPoint file" width="25" height="25"
            v-if="isPowerPoint(file)">

        <!-- Check if the file is a ZIP archive -->
        <img src="/Images/FileIcons/zip.png" alt="ZIP file" width="25" height="25" v-if="isZip(file)">

        <!-- Check if the file is a text file -->
        <img src="/Images/FileIcons/txt-file.png" alt="Text file" width="25" height="25" v-if="isText(file)">

        <!-- Check if the file is an HTML file -->
        <img src="/Images/FileIcons/html-5.png" alt="HTML file" width="25" height="25" v-if="isHTML(file)">

        <!-- Check if the file is a JavaScript file -->
        <img src="/Images/FileIcons/js.png" alt="JavaScript file" width="25" height="25"
            v-if="isJavaScript(file)">

        <!-- Check if the file is a CSS file -->
        <img src="/Images/FileIcons/css3.png" alt="CSS file" width="25" height="25" v-if="isCSS(file)">

        <!-- Check if the file is a RAR archive -->
        <img src="/Images/FileIcons/rar.png" alt="RAR file" width="25" height="25" v-if="isRAR(file)">

        <!-- Check if the file is other archive -->
        <img src="/Images/FileIcons/file.png" alt="RAR file" width="25" height="25" v-if="isOtherType(file)">
    </template>
</template>

<script setup>
    // Imports
    import {
        isAudio,
        isCSS,
        isExcel,
        isHTML,
        isImage,
        isJavaScript,
        isOtherType,
        isPDF,
        isPowerPoint,
        isRAR,
        isText,
        isVideo,
        isWord,
        isZip
    } from '@/Helper/file-helper';
    import {
        FolderIcon
    } from '@heroicons/vue/20/solid';

    // Uses

    // Refs

    // Props & Emits
    const {
        file
    } = defineProps({
        file: Object
    });

    // Computed

    // Methods

    // Hooks
    
</script>

<style scoped>

</style>