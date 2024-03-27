<?php

namespace Fpaipl\Panel\Traits;

trait ManageTag
{
    protected static function booted()
    {
        /**
         * Automatically add fillable fields to tags.
         *
         * This method is triggered after the model is saved. It retrieves the fillable
         * attributes from the model, and if the `storeFillableInTags` method is defined
         * on the model and returns false, it stores the attributes of the model as tags.
         * Otherwise, it stores only the fillable attributes as tags.
         *
         * Note: This will only work if fillable fields are added to the model.
         * Important: Don't do this in the updated event, as it can cause an infinite loop.
         */
       
        static::saved(function ($model) {
            $fillableAttributes = array_intersect_key(
                $model->attributes, array_flip($model->getFillable())
            );

            // Filter out null or empty values
            $filteredAttributes = array_filter($fillableAttributes, function ($value) {
                return !is_null($value) && $value !== '';
            });

            if (method_exists($model, 'storeFillableInTags') && !$model->storeFillableInTags()) {
                $model->tags = implode(",", array_filter($model->attributes, function ($value) {
                    return !is_null($value) && $value !== '';
                }));
            } else {
                $model->tags = implode(",", $filteredAttributes);
            }

            $model->saveQuietly();
        });
    }
}
        