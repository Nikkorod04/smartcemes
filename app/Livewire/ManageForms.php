<?php

namespace App\Livewire;

use Livewire\Component;

class ManageForms extends Component
{
    public string $searchTerm = '';
    public string $filterType = '';

    private function getFormsData()
    {
        return [
            [
                'id' => 1,
                'name' => 'F-CES Narrative Report',
                'code' => 'F-CES #11',
                'description' => 'Extension Project/Activity Evaluation Report',
                'type' => 'Evaluation',
                'frequency' => 'Per Activity',
            ],
            [
                'id' => 2,
                'name' => 'Community Needs Assessment',
                'code' => 'F-CES-001',
                'description' => 'Comprehensive Community Needs and Problems Assessment',
                'type' => 'Assessment',
                'frequency' => 'Quarterly',
            ],
            [
                'id' => 3,
                'name' => 'Attendance Monitoring Form',
                'code' => 'F-CES-003',
                'description' => 'Extension Activity Attendance Monitoring and Certification',
                'type' => 'Registration',
                'frequency' => 'Per Activity',
            ],
            [
                'id' => 4,
                'name' => 'Training Evaluation Form',
                'code' => 'F-CES-004',
                'description' => 'Training Activity Evaluation and Knowledge Assessment',
                'type' => 'Evaluation',
                'frequency' => 'Per Activity',
            ],
            [
                'id' => 5,
                'name' => 'Project Monitoring and Evaluation Form',
                'code' => 'F-CES-005',
                'description' => 'Activity/Project Monitoring and Evaluation',
                'type' => 'Monitoring',
                'frequency' => 'Monthly',
            ],
        ];
    }

    public function render()
    {
        $forms = collect($this->getFormsData());

        // Filter by search term
        if (!empty($this->searchTerm)) {
            $searchLower = strtolower($this->searchTerm);
            $forms = $forms->filter(function ($form) use ($searchLower) {
                return stripos($form['name'], $searchLower) !== false ||
                       stripos($form['code'], $searchLower) !== false ||
                       stripos($form['description'], $searchLower) !== false;
            });
        }

        // Filter by type
        if (!empty($this->filterType)) {
            $forms = $forms->filter(function ($form) {
                return $form['type'] === $this->filterType;
            });
        }

        return view('livewire.manage-forms', [
            'forms' => $forms,
        ]);
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->filterType = '';
    }
}
