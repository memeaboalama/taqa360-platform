<?php

namespace App\Contracts\Services;


use App\Support\Enum\FileTypesEnum;

interface ImagesContract
{
    /**
     * @param $img
     * @param string $path
     * @param FileTypesEnum $type
     * @param string $disk
     * @param string $imageType
     * @param null $oldPath
     * @param null $order
     * @return array
     */
    public function storeImage($img, string $path, FileTypesEnum $type,string $disk='fileDisk',string $imageType= 'webp',$oldPath = null,$order=null) : array;

    /**
     * @param $image
     * @param $request_file
     * @param string $name
     * @param string $path
     * @param FileTypesEnum $type
     * @param string $disk
     * @param string $imageType
     * @param null $order
     * @return array
     */
    public function saveInDir($image,$request_file,string $name,string $path,FileTypesEnum $type, string $disk='fileDisk',string $imageType = 'webp', $order = null): array;


    /**
     * @param string $folder
     * @return void
     */
    public function checkDirectory(string $folder): void;

    /**
     * @param string $imgPath
     * @param string $disk
     * @return void
     */
    public function destroyImg(string $imgPath,string $disk='fileDisk'): void;

    /**
     * @param $url
     * @return void
     */
    public function destroy($url): void;
}
