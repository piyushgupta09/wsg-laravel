<?php

namespace Fpaipl\Panel\Traits;

use Spatie\Activitylog\Contracts\Activity;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait ManageMedia
{
    public function getImage($cSize = 's100', $cName = null, $withId = false)
    {
        $collection = collect();
        $allMedia = $this->getMedia($this->getMediaCollectionName());
        foreach ($allMedia as $media) {
            $value = $media->getUrl($cSize);
            if ($withId) {
                $collection->put($media->id, $value);
            } else {
                $collection->push($value);
            }
        }
        return $collection->first();
    }

    public function getImages($cSize = 's100', $cName = null, $withId = false)
    {
        $collection = collect();
        $allMedia = $this->getMedia($this->getMediaCollectionName());
        foreach ($allMedia as $media) {
            $value = $media->getUrl($cSize);
            if ($withId) {
                $collection->put($media->id, $value);
            } else {
                $collection->push($value);
            }
        }
        return $collection;
    }

    public function getMediaCollectionName()
    {
        return get_class($this)::MEDIA_COLLECTION_NAME;
    }

    public function removeMedia($data)
    {
        if (is_array($data)) {
            foreach ($data as $media) {
                $this->deleteImage($media);
            }
        } else {
            if (!empty($data)) {
                $this->deleteImage($data);
            }
        }
    }

    private function deleteImage($media)
    {
        $media = Media::findOrFail($media);
        return $media->delete();
    }

    public function addSingleMediaToModal($media)
    {
        $mediaModel = $this
            ->addMedia($media)
            ->preservingOriginal()
            ->toMediaCollection($this->getMediaCollectionName());

        if ($mediaModel) {
            $this->MediaLog($mediaModel);
        }
    }

    public function addMultipleMediaToModel($media)
    {
        foreach ($media as $image) {

            $mediaModel = $this
                ->addMedia($image)
                ->preservingOriginal()
                ->toMediaCollection($this->getMediaCollectionName());

            if ($mediaModel) {
                $this->MediaLog($mediaModel);
            }
        }
    }

    public function addSingleMediaToModalFromUrl($media)
    {
        $mediaModel = $this
            ->addMediaFromUrl($media)
            ->preservingOriginal()
            ->toMediaCollection($this->getMediaCollectionName('primary'));

        if ($mediaModel) {
            $this->MediaLog($mediaModel);
        }
    }

    public function addMultipleMediaToModelFromUrl($media)
    {
        $mediaModel = $this
            ->addMediaFromUrl($media)
            ->preservingOriginal()
            ->toMediaCollection($this->getMediaCollectionName('secondary'));

        if ($mediaModel) {
            $this->MediaLog($mediaModel);
        }
    }

    private function MediaLog($mediaModel)
    {
        activity()
            ->performedOn($mediaModel)
            ->event('upload')
            ->withProperties(json_encode($mediaModel))
            ->tap(function (Activity $activity) {
                $activity->log_name = 'media_log';
            })
            ->log(get_class($this) . '_' . $this->id);
    }
}
