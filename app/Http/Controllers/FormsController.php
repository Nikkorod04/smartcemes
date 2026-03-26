<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormsController extends Controller
{
    /**
     * Display the forms list for secretary.
     */
    public function index()
    {
        $forms = [
            [
                'id' => 1,
                'name' => 'F-CES Narrative Report',
                'code' => 'F-CES #11',
                'description' => 'Extension Project/Activity Evaluation Report',
                'type' => 'Evaluation',
                'frequency' => 'Per Activity',
                'route' => '#',
            ],
            [
                'id' => 2,
                'name' => 'Community Needs Assessment',
                'code' => 'F-CES-001',
                'description' => 'Comprehensive Community Needs and Problems Assessment',
                'type' => 'Assessment',
                'frequency' => 'Quarterly',
                'route' => '#',
            ],
            [
                'id' => 3,
                'name' => 'Attendance Monitoring Form',
                'code' => 'F-CES-003',
                'description' => 'Extension Activity Attendance Monitoring and Certification',
                'type' => 'Registration',
                'frequency' => 'Per Activity',
                'route' => '#',
            ],
            [
                'id' => 4,
                'name' => 'Training Evaluation Form',
                'code' => 'F-CES-004',
                'description' => 'Training Activity Evaluation and Knowledge Assessment',
                'type' => 'Evaluation',
                'frequency' => 'Per Activity',
                'route' => '#',
            ],
            [
                'id' => 5,
                'name' => 'Project Monitoring and Evaluation Form',
                'code' => 'F-CES-005',
                'description' => 'Activity/Project Monitoring and Evaluation',
                'type' => 'Monitoring',
                'frequency' => 'Monthly',
                'route' => '#',
            ],
        ];

        return view('forms.index', compact('forms'));
    }

    /**
     * Display a specific form.
     */
    public function show($id)
    {
        $forms = [
            [
                'id' => 1,
                'name' => 'F-CES Narrative Report',
                'code' => 'F-CES #11',
                'description' => 'Extension Project/Activity Evaluation Report',
                'type' => 'Evaluation',
                'frequency' => 'Per Activity',
                'route' => '#',
            ],
            [
                'id' => 2,
                'name' => 'Community Needs Assessment',
                'code' => 'F-CES-001',
                'description' => 'Comprehensive Community Needs and Problems Assessment',
                'type' => 'Assessment',
                'frequency' => 'Quarterly',
                'route' => '#',
            ],
            [
                'id' => 3,
                'name' => 'Attendance Monitoring Form',
                'code' => 'F-CES-003',
                'description' => 'Extension Activity Attendance Monitoring and Certification',
                'type' => 'Registration',
                'frequency' => 'Per Activity',
                'route' => '#',
            ],
            [
                'id' => 4,
                'name' => 'Training Evaluation Form',
                'code' => 'F-CES-004',
                'description' => 'Training Activity Evaluation and Knowledge Assessment',
                'type' => 'Evaluation',
                'frequency' => 'Per Activity',
                'route' => '#',
            ],
            [
                'id' => 5,
                'name' => 'Project Monitoring and Evaluation Form',
                'code' => 'F-CES-005',
                'description' => 'Activity/Project Monitoring and Evaluation',
                'type' => 'Monitoring',
                'frequency' => 'Monthly',
                'route' => '#',
            ],
        ];

        $form = collect($forms)->firstWhere('id', $id);

        if (!$form) {
            abort(404, 'Form not found');
        }

        return view('forms.show', compact('form'));
    }
}
