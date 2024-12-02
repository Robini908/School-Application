<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Http; // For making API calls to external AI services

class IntelligentSearch extends Component
{
    public $query = '';
    public $results = [];

    // OpenAI API key (make sure it's set in your .env file for security)
    private $openAiApiKey;

    public function mount()
    {
        $this->openAiApiKey = env('OPENAI_API_KEY');  // Load API key from environment
    }

    public function updatedQuery()
    {
        if (!empty($this->query)) {
            // Step 1: Use OpenAI for NLP query understanding
            $processedQuery = $this->getProcessedQueryFromOpenAI($this->query);

            // Step 2: Perform the database search with the processed query
            $this->results = $this->fuzzySearch($processedQuery);
        } else {
            $this->results = [];
        }
    }

    /**
     * Call OpenAI API to process the user's query for better understanding
     * 
     * @param string $query
     * @return string
     */
    protected function getProcessedQueryFromOpenAI($query)
    {
        // Send the query to OpenAI for NLP processing with SSL verification disabled
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openAiApiKey,  // Set your OpenAI API Key
        ])
            ->withoutVerifying()  // Disable SSL verification (equivalent to the `-k` flag)
            ->post('https://api.openai.com/v1/completions', [
                'model' => 'gpt-3.5-turbo',  // Specify the GPT model
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an AI that helps process search queries for a student records database.'],
                    ['role' => 'user', 'content' => "Process this query: {$query}"]
                ],
                'max_tokens' => 150,  // Limit the response size
            ]);

        // Check if the response is successful
        if ($response->successful()) {
            // Extract the processed query from the response
            return $response->json()['choices'][0]['message']['content'];
        }

        // In case of failure, return the original query
        return $query;
    }

    /**
     * Perform a fuzzy search or improved query search using the processed query
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
            ->orderBy('created_at', 'desc') // Sort by created_at in descending order
            ->get();
    }
    

    public function render()
    {
        return view('livewire.intelligent-search', [
            'results' => $this->results,
        ]);
    }
}
