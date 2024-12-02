<?php

namespace App\Livewire;

use Livewire\Component;
use Phpml\Classification\NaiveBayes;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;

class Chatbot extends Component
{
    public $userMessage = '';
    public $messages = [];
    private $vectorizer;
    private $classifier;
    private $data = [];

    protected $rules = [
        'userMessage' => 'required|string|min:1|max:500',
    ];

    public function mount()
    {
        $this->vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $this->classifier = new NaiveBayes();
    }

    private $labels = [
        'greeting' => 'Hello! How can I assist you today?',
        'school_info' => 'The school address is 123 School St.',
        'schedule' => 'School starts at 8:00 AM and ends at 3:00 PM.',
        'homework' => 'Please check the homework section in the student portal.',
    ];

    private function classifyMessage($message)
    {
        $samples = $this->getTrainingDataSamples();
        $labels = $this->getTrainingDataLabels();

        // Ensure the vectorizer and classifier are initialized
        if (is_null($this->vectorizer) || is_null($this->classifier)) {
            throw new \Exception("Vectorizer or Classifier is not initialized.");
        }

        // Vectorize the dataset using Token Count Vectorizer
        $this->vectorizer->fit($samples);
        $this->vectorizer->transform($samples);

        $messageVector = $this->vectorizer->transform([$message]);

        if (empty($samples)) {
            $this->classifier->train($samples, $labels);
        }

        $predictedCategory = $this->classifier->predict($messageVector);

        return $this->labels[$predictedCategory[0]] ?? 'Sorry, I did not understand that.';
    }

    private function addTrainingData($message, $category)
    {
        $this->data[] = [$message, $category];

        $samples = $this->getTrainingDataSamples();
        $labels = $this->getTrainingDataLabels();
        $this->classifier->train($samples, $labels);
    }

    private function getTrainingDataSamples()
    {
        return array_column($this->data, 0);
    }

    private function getTrainingDataLabels()
    {
        return array_column($this->data, 1);
    }

    public function sendMessage()
    {
        $this->validate();

        $this->messages[] = ['role' => 'user', 'content' => $this->userMessage];

        $response = $this->classifyMessage($this->userMessage);

        $this->messages[] = ['role' => 'assistant', 'content' => $response];

        if ($this->userMessage == 'I have a question about homework') {
            $this->addTrainingData($this->userMessage, 'homework');
        }

        $this->userMessage = '';
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
