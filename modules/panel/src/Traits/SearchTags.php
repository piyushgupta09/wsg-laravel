<?php    

namespace Fpaipl\Panel\Traits;

trait SearchTags
{
    /**
     * Update the model's tags.
     * 
     * @param string|null $priorityString Optional string to be given priority in tags.
     * @return $this
     */
    public function updateMyTags($priorityString = null)
    {
        if (property_exists($this, 'searchables') && is_array($this->searchables)) {
            $tagParts = [];

            // Add the priority string first if provided
            if (!is_null($priorityString)) {
                $tagParts[] = $priorityString;
            }

            foreach ($this->searchables as $fieldName) {
                if (isset($this->$fieldName)) {
                    $tagParts[] = $this->$fieldName;
                }
            }

            $tags = implode(', ', $tagParts);
            
            // Limit string to 220 characters
            $this->tags = substr($tags, 0, 220); 
        }

        return $this;
    }
}
