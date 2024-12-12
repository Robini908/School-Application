<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\StudentRecord;

class IntelligentSearch extends Component
{
    public $query = '';
    public $results = [];

    // Define a function to process the query without AI/ML
    protected function processQuery($query)
    {
        // Remove any leading or trailing whitespace from the query
        $query = trim($query);

        // Convert the query to lowercase for case-insensitive search
        $query = strtolower($query);

        return $query;
    }

    public function mount()
    {
        // No need to load an API key, we're doing manual processing here
    }

    public function updatedQuery()
    {
        if (!empty($this->query)) {
            // Process the query without AI/ML
            $processedQuery = $this->processQuery($this->query);

            // Perform a fuzzy search using the processed query
            $this->results = $this->fuzzySearch($processedQuery);
        } else {
            $this->results = [];
        }
    }

    /**
     * Call the database to perform a fuzzy search with the processed query
     *
     * @param string $query
     * @return mixed
     */
    protected function fuzzySearch($query)
    {
        return StudentRecord::query()
            ->where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('middle_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('adm_no', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->orWhere('town', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.intelligent-search', [
            'results' => $this->results,
        ]);
    }
}