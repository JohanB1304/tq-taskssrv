<?php
namespace TqTaskssrv\TqTaskssrv\Traits;

use Illuminate\Support\Facades\Storage;

trait FileTrait{
    
    /**
     * Save files (Pdf, Excel, Word, Png, Jpeg, Jpg)
     */
    public function saveFile($file){
        $file_extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
        $data= substr($file, strpos($file, ',')+1);
        $data=base64_decode($data);
        $extension=$this->validateExtension($file_extension);
        $fileName=time().'_'.'file'.'.'.$extension;
        Storage::disk('local')->put($fileName, $data);
        return $fileName;
    }

    /**Validate extension files */
    public function validateExtension($extension)
    {
        switch($extension){
            case 'xls':
            case 'vnd.ms-excel':
                return 'xls';
                break;

            case 'xlsx':
            case 'vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return 'xlsx';
                break;

            case 'doc':
            case 'msword':
                return 'doc';
                break;

            case 'docx':
            case 'vnd.openxmlformats-officedocument.wordprocessingml.document':
                return 'docx';
                break;
            case 'pdf': 
                return 'pdf';
                break;
            case 'png':
                return 'png';
                break;
            case 'jpeg':
                    return 'jpeg';
                    break;
            case 'jpg':
                    return 'jpg';
                    break;
            case 'gif':
                return 'gif';
                break;
        }
    }

    public function validateFileType($extension){
        switch($extension){
            case 'xls':
                return 'application/vnd.ms-excel';
                break;

            case 'xlsx':
                return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;

            case 'doc':
                return 'application/msword';
                break;

            case 'docx':
                return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;                
            case 'pdf': 
                return 'application/pdf';
                break;
            case 'png':
                return 'image/png';
                break;
            case 'jpeg':
                return 'image/jpeg';
                break;
            case 'jpg':
                return 'image/jpg';
                break;
            case 'gif':
                return 'gif';
                break;
        }
    }

    public function getBase64File($file){        
        $data = base64_encode(Storage::disk('local')->get($file));
        return $data;
    }
}