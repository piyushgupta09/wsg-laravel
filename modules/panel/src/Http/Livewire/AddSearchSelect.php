<?php

namespace Fpaipl\Panel\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;

class AddSearchSelect extends Component
{
    public $perPage = 10;
    public $selectedData;
    public $search = '';
    public Collection $filteredData;
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
    ];

    public $connection;

    // Additional properties for your component
    public $datalist;
    public $modelCreateRoute;
    
    // Component initialization properties
    public $modelName, $name, $placeholder, $options, $model, $attribute, $note, $label, $style, $p_style, $show;

    public function mount(
        $modelName, $name, $placeholder, $options, $connection = null,
        $attribute, $note, $label, $style, $p_style, $show,
        $selectedData = null,
    ) {
        $this->model = $options['model'];
        $this->modelCreateRoute = $options['route'];
        $this->datalist = collect($options['data'] ?? []);
        $this->selectedData = $selectedData;
        // Initializing properties
        $this->connection = $connection;
        $this->modelName = $modelName;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->attribute = $attribute;
        $this->note = $note;
        $this->label = $label;
        $this->style = $style;
        $this->p_style = $p_style;
        $this->show = $show;

        $this->fill(request()->only('search', 's'));
        $this->filteredData = collect();
    }

    public function updatingSearch()
    {
        $this->filteredData = strlen($this->search) > 2 ? $this->getFilteredData() : collect();
    }

    protected function getFilteredData()
    {
        $searchTerm = preg_quote($this->search, '/'); // Escape special characters
        return $this->datalist->filter(function ($item) use ($searchTerm) {
            return preg_match("/$searchTerm/i", $item['tags']);
        })->take($this->perPage);
    }    

    public function selectData($dataId)
    {
        $this->selectedData = $this->datalist->firstWhere('id', $dataId);
        if ($this->connection == 'emit') {
            $this->emitUp('add-search-select-selected', $dataId);
        }
    }

    public function removeData()
    {
        $this->reset('search');
        $this->selectedData = null;
        $this->search = '';
        $this->filteredData = collect();
    }

    public function showAllData()
    {
        $this->search = '';
        $this->perPage = $this->datalist->count();
        $this->filteredData = $this->datalist;
    }

    public function closeAllData()
    {
        $this->perPage = 10;
        $this->filteredData = collect();
    }

    public function render()
    {
        return view('panel::livewire.add-search-select');
    }
}
