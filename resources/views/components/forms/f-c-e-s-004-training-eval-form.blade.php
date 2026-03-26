<?php

use Livewire\Component;
use Livewire\Attributes\Validate;

new class extends Component
{
    #[Validate('required|string|max:255')]
    public string $trainingTitle = '';

    #[Validate('required|date')]
    public string $trainingDate = '';

    #[Validate('required|string|max:255')]
    public string $trainingVenue = '';

    // Quality Ratings
    #[Validate('required|in:1,2,3,4,5')]
    public string $deliveryRating1 = '';

    #[Validate('required|string|max:255')]
    public string $deliveryTopic1 = '';

    #[Validate('required|string|max:255')]
    public string $deliverySpeaker1 = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $groupActivitiesRating = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $facilitationRating = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $accommodationFoodRating = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $venueRating = '';

    // Knowledge Assessment
    #[Validate('required|string|max:255')]
    public string $knowledgeTopic1 = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $knowledgeTopic1Before = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $knowledgeTopic1After = '';

    // Feedback
    #[Validate('required|string')]
    public string $moreInformationNeeded = '';

    #[Validate('required|string')]
    public string $commentsSuggestions = '';

    public string $submitted = '';
    public bool $showSuccess = false;

    public function submit()
    {
        $this->validate();
        
        $this->submitted = now()->format('F d, Y h:i A');
        $this->showSuccess = true;
        
        // Reset form
        $this->reset('trainingTitle', 'trainingDate', 'trainingVenue',
                     'deliveryRating1', 'deliveryTopic1', 'deliverySpeaker1',
                     'groupActivitiesRating', 'facilitationRating', 'accommodationFoodRating',
                     'venueRating', 'knowledgeTopic1', 'knowledgeTopic1Before',
                     'knowledgeTopic1After', 'moreInformationNeeded', 'commentsSuggestions');
        
        $this->dispatch('form-submitted', ['type' => 'F-CES-004']);
    }
};
?>

<div class="space-y-6">
    @if($showSuccess)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        Training evaluation submitted successfully on {{ $submitted }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-8">
        <!-- I. Training Information -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Training Information</h3>
                <span class="text-xs font-semibold bg-blue-700 text-blue-100 px-3 py-1 rounded-full">Section I</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="trainingTitle" value="Training/Activity Title *" />
                    <input type="text" id="trainingTitle" wire:model="trainingTitle" placeholder="Enter training title" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                    @error('trainingTitle') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="trainingDate" value="Date of Training *" />
                        <input type="date" id="trainingDate" wire:model="trainingDate" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                        @error('trainingDate') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="trainingVenue" value="Venue *" />
                        <input type="text" id="trainingVenue" wire:model="trainingVenue" placeholder="Location of training" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-900">
                        @error('trainingVenue') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- II. Quality Rating -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Quality Rating</h3>
                <span class="text-xs font-semibold bg-green-600 text-green-100 px-3 py-1 rounded-full">Section II</span>
            </div>
            <div class="p-6 space-y-6">
                <!-- Topic/Speaker Rating -->
                <div class="border-b pb-4">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Topic 1 - Delivery Rating</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="deliveryTopic1" value="Topic Name *" />
                            <input type="text" id="deliveryTopic1" wire:model="deliveryTopic1" placeholder="Topic name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                            @error('deliveryTopic1') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-input-label for="deliverySpeaker1" value="Speaker Name *" />
                            <input type="text" id="deliverySpeaker1" wire:model="deliverySpeaker1" placeholder="Speaker name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                            @error('deliverySpeaker1') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-input-label for="deliveryRating1" value="Rating *" />
                            <select id="deliveryRating1" wire:model="deliveryRating1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                                <option value="">Select rating...</option>
                                <option value="1">Poor</option>
                                <option value="2">Fair</option>
                                <option value="3">Good</option>
                                <option value="4">Very Good</option>
                                <option value="5">Excellent</option>
                            </select>
                            @error('deliveryRating1') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Other Quality Ratings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="groupActivitiesRating" value="Participant/Group Activities *" />
                        <select id="groupActivitiesRating" wire:model="groupActivitiesRating" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                            <option value="">Select rating...</option>
                            <option value="1">Poor</option>
                            <option value="2">Fair</option>
                            <option value="3">Good</option>
                            <option value="4">Very Good</option>
                            <option value="5">Excellent</option>
                        </select>
                        @error('groupActivitiesRating') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="facilitationRating" value="Facilitation of Activities *" />
                        <select id="facilitationRating" wire:model="facilitationRating" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                            <option value="">Select rating...</option>
                            <option value="1">Poor</option>
                            <option value="2">Fair</option>
                            <option value="3">Good</option>
                            <option value="4">Very Good</option>
                            <option value="5">Excellent</option>
                        </select>
                        @error('facilitationRating') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="accommodationFoodRating" value="Accommodation/Food *" />
                        <select id="accommodationFoodRating" wire:model="accommodationFoodRating" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                            <option value="">Select rating...</option>
                            <option value="1">Poor</option>
                            <option value="2">Fair</option>
                            <option value="3">Good</option>
                            <option value="4">Very Good</option>
                            <option value="5">Excellent</option>
                        </select>
                        @error('accommodationFoodRating') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="venueRating" value="Venue *" />
                        <select id="venueRating" wire:model="venueRating" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-700 focus:ring-green-700">
                            <option value="">Select rating...</option>
                            <option value="1">Poor</option>
                            <option value="2">Fair</option>
                            <option value="3">Good</option>
                            <option value="4">Very Good</option>
                            <option value="5">Excellent</option>
                        </select>
                        @error('venueRating') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- III. Knowledge Assessment -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Knowledge Assessment</h3>
                <span class="text-xs font-semibold bg-yellow-600 text-yellow-100 px-3 py-1 rounded-full">Section III</span>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Rate your knowledge before and after this activity (1 = No Knowledge, 5 = Excellent Knowledge)</p>
                
                <div class="border-b pb-4 mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Topic 1</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="knowledgeTopic1" value="Topic Name *" />
                            <input type="text" id="knowledgeTopic1" wire:model="knowledgeTopic1" placeholder="Topic name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-600 focus:ring-yellow-600">
                            @error('knowledgeTopic1') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-input-label for="knowledgeTopic1Before" value="Before Training *" />
                            <select id="knowledgeTopic1Before" wire:model="knowledgeTopic1Before" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-600 focus:ring-yellow-600">
                                <option value="">Select rating...</option>
                                <option value="1">1 - No Knowledge</option>
                                <option value="2">2 - Limited</option>
                                <option value="3">3 - Moderate</option>
                                <option value="4">4 - Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                            @error('knowledgeTopic1Before') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-input-label for="knowledgeTopic1After" value="After Training *" />
                            <select id="knowledgeTopic1After" wire:model="knowledgeTopic1After" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-600 focus:ring-yellow-600">
                                <option value="">Select rating...</option>
                                <option value="1">1 - No Knowledge</option>
                                <option value="2">2 - Limited</option>
                                <option value="3">3 - Moderate</option>
                                <option value="4">4 - Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                            @error('knowledgeTopic1After') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <p class="text-sm text-gray-500 mb-4 italic">For additional topics, please submit this form and add them separately.</p>
            </div>
        </div>

        <!-- IV. Feedback -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-red-700 to-red-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Feedback</h3>
                <span class="text-xs font-semibold bg-red-600 text-red-100 px-3 py-1 rounded-full">Section IV</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="moreInformationNeeded" value="What topic areas would you like more information on? *" />
                    <textarea id="moreInformationNeeded" wire:model="moreInformationNeeded" placeholder="Specify topics you'd like to learn more about" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-700 focus:ring-red-700"></textarea>
                    @error('moreInformationNeeded') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="commentsSuggestions" value="Other Comments/Suggestions for Improvement *" />
                    <textarea id="commentsSuggestions" wire:model="commentsSuggestions" placeholder="Share your feedback and suggestions" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-700 focus:ring-red-700"></textarea>
                    @error('commentsSuggestions') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="window.history.back()" class="px-6 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">
                Back
            </button>
            <button type="submit" class="px-6 py-2 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition">
                Submit Evaluation
            </button>
        </div>
    </form>
</div>
