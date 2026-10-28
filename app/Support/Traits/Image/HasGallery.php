<?php

namespace App\Support\Traits\Image;


use App\Support\Enum\FileTypesEnum;
use Illuminate\Support\Facades\Storage;

trait HasGallery
{

    public function getLogoUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {

            $logo = $this->files()->whereType(FileTypesEnum::LOGO->value)->latest()?->first();
            return $logo && $logo->storage == "fileDisk" ?
                Storage::disk($logo->storage)->url($logo?->path)
                : ($logo ? Storage::disk($logo->storage)->url($logo?->path) : '');

        }


    }

    public function getProfileUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {

            $logo = $this->files()->whereType(FileTypesEnum::PROFILE->value)->latest()?->first();
            return $logo && $logo->storage == "fileDisk" ?
                Storage::disk($logo?->storage)->url($logo?->path)
                : ($logo ? Storage::disk($logo->storage)->url($logo?->path) : '');

        }


    }

    public function getLicenseImageUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {

            $logo = $this->files()->whereType(FileTypesEnum::LicenseImage->value)->latest()?->first();

            return $logo && $logo->storage == "fileDisk" ?
                Storage::disk($logo?->storage)->url($logo?->path)
                : ($logo ? Storage::disk($logo->storage)->url($logo?->path) : '');

        }


    }

    public function getCarImageUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {

            $logo = $this->files()->whereType(FileTypesEnum::CarImage->value)->latest()?->first();
            return $logo && $logo->storage == "fileDisk" ?
                Storage::disk($logo?->storage)->url($logo?->path)
                : ($logo ? Storage::disk($logo->storage)->url($logo?->path) : '');

        }


    }

    public function getIdImageUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {

            $logo = $this->files()->whereType(FileTypesEnum::IdImage->value)->latest()?->first();
            return $logo && $logo->storage == "fileDisk" ?
                Storage::disk($logo?->storage)->url($logo?->path)
                : ($logo ? Storage::disk($logo->storage)->url($logo?->path) : '');

        }


    }


}
