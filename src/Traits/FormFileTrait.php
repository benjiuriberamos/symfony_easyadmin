<?php

namespace App\Traits;

//need this library in your controller :
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//JUST USE IN CONTROLLERS

use Symfony\Component\HttpFoundation\File\UploadedFile;

trait FormFileTrait
{
    //Parametros: ARRAY_FILES - FILE_SIZE_MAX - INDEX_CONSTRAINSTS - FOLDER_STORAGE
    private function saveFiles($array_files, $file_size, $indexConstraint, $folder_name): array
    {
        //VALIDATING FILES
        $values = array_map(function ($file) use ($file_size, $indexConstraint) {
            return $this->validateFile($file, $file_size, $indexConstraint);
        }, $array_files);

        //SAVING FILES
        return array_map(function ($file) use ($folder_name) {
            return $this->storageFile($file, $folder_name);
        }, $array_files);
    }

    //Parametros: FILE - FILE_SIZE_MAX - INDEX_CONSTRAINSTS - FOLDER_STORAGE
    private function saveFile(UploadedFile $file, $file_size, $indexConstraint, $folder_name): string
    {
        //VALIDATING FILE
        $valid = $this->validateFile($file, $file_size, $indexConstraint);

        //SAVING FILE
        if ($valid) {
            return $this->storageFile($file, $folder_name);
        }
        return '';
    }

    //Parametros: FILE - FILE_SIZE_MAX - INDEX_CONSTRAINSTS
    private function validateFile(UploadedFile $file, $file_size, $indexConstraint): bool
    {
        $is_mime_acepted = in_array($file->getClientMimeType(), $this->constraintsFile($indexConstraint));
        $is_size_acepted = $file->getSize() <= $file_size;
        $result = $is_size_acepted && $is_mime_acepted;
        return $this->validate($result);
    }

    private function storageFile(UploadedFile $file, $folder_name): string
    {
        $directorio = 'uploads' . DIRECTORY_SEPARATOR . $folder_name;
        $newFilename = md5(uniqid()) . '.' . $file->getClientOriginalExtension();
        $file->move($directorio, $newFilename);
        return 'uploads/' . $folder_name . '/' . $newFilename;
    }

    //Parametros: INDEX_CONSTRAINTS
    private function constraintsFile($index) :array
    {
        $constraints = [];

        //ALL index 0
        $constraints[] = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
            'application/vnd.openxmlformats-officedocument.presentationml.template',
            'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.presentationml.slide',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
            'application/vnd.ms-excel.addin.macroEnabled.12',
            'application/vnd.ms-excel.sheet.binary.macroEnabled.12',

            'application/msword',
            'application/msword',
            'application/vnd.ms-excel',
            'application/vnd.ms-excel',
            'application/vnd.ms-excel',
            'application/vnd.ms-powerpoint',
            'application/vnd.ms-powerpoint',
            'application/vnd.ms-powerpoint',
            'application/vnd.ms-powerpoint',

            // imagenes
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/bmp',
            'image/ico',
            'image/svg+xml',
            'video/mp4',
            'video/webm',
            'video/ogg',

            'application/pdf'
        ];

        //POF OR WORD index 1
        $constraints[] = [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
            'application/msword',
            'application/pdf'
        ];

        //POF OR WORD index 2 y imagenes
        $constraints[] = [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
            'application/msword',
            'application/pdf',
            'image/jpeg',
            'image/png',
        ];

        return $constraints[$index];
    }

    private function validate($element): bool
    {
        if (!$element) {
            return false;
        }
        return true;
    }
}
