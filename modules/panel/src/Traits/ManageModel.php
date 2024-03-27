<?php

namespace Fpaipl\Panel\Traits;
use Illuminate\Database\Eloquent\Collection;

trait ManageModel
{
    
    /**
     * Checks if there are dependent relationships.
     *
     * This method checks if the model has dependent relationships defined in the $modelRelations 
     * property. It returns a boolean indicating the presence of these relationships.
     *
     * @return bool Whether there are dependent relationships.
     */
    public function hasRelationalRecords(): Bool
    {
        return count($this->modelRelations);
    }

    /**
     * Retrieves the dependent relationships.
     *
     * This method returns the dependent relationships of the model as defined 
     * in the $modelRelations property.
     *
     * @return array The dependent relationships.
     */
    public function getRelationalRecords(): array
    {
        return $this->modelRelations;
    }

    /**
     * Safe delete the provided models if there are no relational records.
     *
     * @param array $records An array of record IDs to delete.
     * @param string $model The Eloquent model class to delete records from.
     * @return string A string indicating the success, failure, or presence of dependent records.
     */
    public static function safeDeleteModels(array $records, string $model): string
    {
        $hasRelationalRecord = collect($records)->map(function($record) use ($model) {
            $modelInstance = $model::findOrFail($record);
            if($modelInstance->hasRelationalRecords()){
                foreach($modelInstance->getRelationalRecords() as $modelRelation){
                    $relatedRecords = $modelInstance->$modelRelation;
                    $relationCount = $relatedRecords instanceof Collection ? $relatedRecords->count() : (empty($relatedRecords) ? 0 : 1);
                    if($relationCount) return true;
                }
            }
            return false;
        })->contains(true);
    
        if ($hasRelationalRecord) {
            return 'dependent';
        } else {
            $deletedRecordsCount = 0;
            foreach ($records as $record) {
                $modelInstance = $model::findOrFail($record);
                if ($modelInstance->delete()) {
                    $deletedRecordsCount++;
                }
            }
            return $deletedRecordsCount === count($records) ? 'success' : 'failure';
        }
    }    

    /**
     * Force delete the provided models with their relations.
     *
     * @param array $selectedRecords An array of record IDs to delete.
     * @param string $model The Eloquent model class to delete records from.
     * @return int|null The number of records deleted or null if the array was empty.
     */
    public static function forceDeleteModels($selectedRecords, $model)
    {
        if (!empty($selectedRecords)) {
            $deletedRecordsCount = 0;
            foreach ($selectedRecords as $record) {
                $modelInstance = $model::findOrFail($record);
                if ($modelInstance->forceDelete()) {
                    $deletedRecordsCount++;
                }
            }
            return $deletedRecordsCount;
        }
    }

    /**
     * Restore the provided models.
     *
     * @param array|string $selectedRecords An array of record IDs or a single ID to restore.
     * @param string $model The Eloquent model class to restore records from.
     */
    public static function restoreModels($selectedRecords, $model)
    {
        $selectedRecords = is_array($selectedRecords) ? $selectedRecords : [$selectedRecords];

        foreach ($selectedRecords as $id) {
            self::restoreRecord($id, $model);
        }
    }

    /**
     * Restore a single record.
     *
     * @param int $id The ID of the record to restore.
     * @param string $model The Eloquent model class the record belongs to.
     * @return bool|null True if the operation succeeded, false if it failed, null if the model was not found.
     */
    private static function restoreRecord($id, $model)
    {
        $restoreModel = $model::withTrashed()->findOrFail($id);
        return $restoreModel->restore();
    }
    
}
