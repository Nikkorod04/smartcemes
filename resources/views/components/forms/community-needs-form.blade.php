<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;
use App\Models\CommunityNeedsAssessment;
use App\Models\Community;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    use WithFileUploads;
    
    // Community Selection
    #[Validate('required|exists:communities,id')]
    public ?int $community_id = null;
    
    // Section I - Identifying Information
    #[Validate('required|string|max:100|not_regex:/[0-9]/')]
    public string $respondentFirstName = '';

    #[Validate('nullable|string|max:100|not_regex:/[0-9]/')]
    public string $respondentMiddleName = '';

    #[Validate('required|string|max:100|not_regex:/[0-9]/')]
    public string $respondentLastName = '';

    #[Validate('required|in:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150')]
    public string $age = '';

    #[Validate('required|in:Single,Married,Divorced,Widowed,Separated')]
    public string $civilStatus = '';

    #[Validate('required|in:Male,Female,Other')]
    public string $sex = '';

    #[Validate('required|in:Roman Catholic,Protestant,Islamic,Buddhist,Hindu,Seventh-day Adventist,Jehovah\'s Witness,Other')]
    public string $religion = '';

    #[Validate('required|in:None,Elementary,High School,Vocational,College,Post-Graduate')]
    public string $educationalAttainment = '';

    // Section III - Economic Aspect
    #[Validate('required|in:Farming,Fishing,Raising Animals,Selling/Trading,Driving,Remittance,Pension,4Ps Assistance,Rentals,Service Work,Construction,Manufacturing,Other')]
    public string $livelihoods = '';

    #[Validate('required|in:yes,no')]
    public string $interestedInTraining = '';

    #[Validate('required_if:interestedInTraining,yes|string')]
    public string $desiredTraining = '';

    // Section IV - Educational Aspect
    #[Validate('required|string')]
    public string $barangayFacilities = '';

    #[Validate('required|in:yes,no')]
    public string $householdMemberStudying = '';

    #[Validate('required|in:yes,no')]
    public string $interestedInContinuingStudies = '';

    #[Validate('required|string')]
    public string $areasOfInterest = '';

    #[Validate('required|in:Morning (8:00-12:00),Afternoon (1:30-5:00)')]
    public string $preferredTime = '';

    #[Validate('required|string')]
    public string $preferredDays = '';

    // Section V - Health, Sanitation, Environmental
    #[Validate('required|in:Colds,Flu,Asthma,Pneumonia,Diarrhea,Schistosomiasis,Hypertension,Diabetes,Vomiting,Headache,Stomach Ache,Tuberculosis,Dengue,Other')]
    public string $commonIllnesses = '';

    #[Validate('required|string')]
    public string $actionWhenSick = '';

    #[Validate('required|string')]
    public string $barangayMedicalSupplies = '';

    #[Validate('required|in:yes,no')]
    public string $hasBarangayHealthPrograms = '';

    #[Validate('required|in:yes,no')]
    public string $benefitsFromPrograms = '';

    #[Validate('required_if:benefitsFromPrograms,yes|string')]
    public string $programsBenefited = '';

    #[Validate('required|in:NAWASA,Water Pump,Deep Well,Spring Water,River/Stream,Rainwater Collection,Tube Well,Other')]
    public string $waterSource = '';

    #[Validate('required|in:Just outside,250 meters away,No idea')]
    public string $waterSourceDistance = '';

    #[Validate('required|string')]
    public string $garbageDisposal = '';

    #[Validate('required|in:yes,no')]
    public string $hasOwnToilet = '';

    #[Validate('required_if:hasOwnToilet,yes|string')]
    public string $toiletType = '';

    #[Validate('required|in:yes,no')]
    public string $keepsAnimals = '';

    #[Validate('required_if:keepsAnimals,yes|string')]
    public string $animalsKept = '';

    // Section VI - Housing and Basic Amenities
    #[Validate('required|in:Wood/Bamboo,Makeshift,Half Concrete/Wood,Nipa/Bamboo,All Concrete,Mixed Materials,Other')]
    public string $housingType = '';

    #[Validate('required|string')]
    public string $tenureStatus = '';

    #[Validate('required|in:yes,no')]
    public string $hasElectricity = '';

    #[Validate('required_if:hasElectricity,no|string')]
    public string $lightSourceNoPower = '';

    #[Validate('required_if:hasElectricity,yes|string')]
    public string $appliances = '';

    // Section VII - Recreational Facilities
    #[Validate('required|string')]
    public string $barangayRecreationalFacilities = '';

    #[Validate('required|string')]
    public string $useOfFreeTime = '';

    #[Validate('required|in:yes,no')]
    public string $memberOfOrganization = '';

    #[Validate('required_if:memberOfOrganization,yes|string')]
    public string $groupType = '';

    #[Validate('required_if:memberOfOrganization,yes|in:Weekly,Monthly,Twice a month,Yearly')]
    public string $meetingFrequency = '';

    // Section VIII - Other Needs & Problems
    #[Validate('required|string')]
    public string $problemsFamily = '';

    #[Validate('required|string')]
    public string $problemsHealth = '';

    #[Validate('required|string')]
    public string $problemsEducation = '';

    #[Validate('required|string')]
    public string $problemsEmployment = '';

    #[Validate('required|string')]
    public string $problemsInfrastructure = '';

    #[Validate('required|string')]
    public string $problemsEconomy = '';

    #[Validate('required|string')]
    public string $problemsSecurity = '';

    // Section IX - Summary
    #[Validate('required|in:1,2,3,4,5')]
    public string $barangayServiceRatingPolice = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $barangayServiceRatingFire = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $barangayServiceRatingBNS = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $barangayServiceRatingWater = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $barangayServiceRatingRoads = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $barangayServiceRatingClinic = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $barangayServiceRatingMarket = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $barangayServiceRatingCommunityCenter = '';

    #[Validate('required|in:1,2,3,4,5')]
    public string $barangayServiceRatingLights = '';

    #[Validate('required|string')]
    public string $generalFeedback = '';

    #[Validate('required|in:yes,no')]
    public string $availableForTraining = '';

    #[Validate('required_if:availableForTraining,no|string')]
    public string $reasonNotAvailable = '';

    // Section II - Family Composition
    public array $familyMembers = [];
    public int $familyMemberIdCounter = 0;

    public string $submitted = '';
    public bool $showSuccess = false;
    public bool $isSubmitting = false;
    public array $submissionErrors = [];
    public bool $showSubmissionErrors = false;
    public bool $showConfirmModal = false;
    public bool $showSuccessModal = false;
    public string $assessmentId = '';

    public function addFamilyMember()
    {
        $this->familyMembers[] = [
            'id' => $this->familyMemberIdCounter++,
            'firstName' => '',
            'middleName' => '',
            'lastName' => '',
            'age' => '',
            'sex' => '',
            'educationalAttainment' => '',
            'employment' => '',
        ];
    }

    public function removeFamilyMember($id)
    {
        $this->familyMembers = array_filter($this->familyMembers, function($member) use ($id) {
            return $member['id'] !== $id;
        });
        $this->familyMembers = array_values($this->familyMembers);
    }

    #[Computed]
    public function formProgress()
    {
        $filledFields = 0;

        // Count filled Section I fields (9 fields: community, first, last, age, civil, sex, religion, education, middle optional so not counted)
        if (!empty($this->community_id)) $filledFields++;
        if (!empty($this->respondentFirstName)) $filledFields++;
        if (!empty($this->respondentLastName)) $filledFields++;
        if (!empty($this->age)) $filledFields++;
        if (!empty($this->civilStatus)) $filledFields++;
        if (!empty($this->sex)) $filledFields++;
        if (!empty($this->religion)) $filledFields++;
        if (!empty($this->educationalAttainment)) $filledFields++;

        // Count family members (min 1 member = 7 fields: firstName, lastName, age, sex, education, employment - no middle name)
        $familyFieldsFilled = 0;
        if (count($this->familyMembers) > 0) {
            foreach ($this->familyMembers as $member) {
                if (!empty($member['firstName'])) $familyFieldsFilled++;
                if (!empty($member['lastName'])) $familyFieldsFilled++;
                if (!empty($member['age'])) $familyFieldsFilled++;
                if (!empty($member['sex'])) $familyFieldsFilled++;
                if (!empty($member['educationalAttainment'])) $familyFieldsFilled++;
                if (!empty($member['employment'])) $familyFieldsFilled++;
            }
            $filledFields += min($familyFieldsFilled, 7);
        }

        // Count Section III fields (2 fields - only livelihoods and interestedInTraining required. desiredTraining is conditional)
        if (!empty($this->livelihoods)) $filledFields++;
        if (!empty($this->interestedInTraining)) $filledFields++;

        // Count Section IV fields (5 fields - areasOfInterest only counted if interestedInContinuingStudies is yes)
        if (!empty($this->barangayFacilities)) $filledFields++;
        if (!empty($this->householdMemberStudying)) $filledFields++;
        if (!empty($this->interestedInContinuingStudies)) $filledFields++;
        if (!empty($this->preferredTime)) $filledFields++;
        if (!empty($this->preferredDays)) $filledFields++;

        // Count Section V fields (11 fields - excluding conditional fields like toiletType, lightSourceNoPower, waterSourceDistance, appliances)
        if (!empty($this->commonIllnesses)) $filledFields++;
        if (!empty($this->actionWhenSick)) $filledFields++;
        if (!empty($this->barangayMedicalSupplies)) $filledFields++;
        if (!empty($this->hasBarangayHealthPrograms)) $filledFields++;
        if (!empty($this->benefitsFromPrograms)) $filledFields++;
        if (!empty($this->waterSource)) $filledFields++;
        if (!empty($this->garbageDisposal)) $filledFields++;
        if (!empty($this->hasOwnToilet)) $filledFields++;
        if (!empty($this->keepsAnimals)) $filledFields++;

        // Count Section VI fields (3 fields - excluding conditional lightSourceNoPower and appliances)
        if (!empty($this->housingType)) $filledFields++;
        if (!empty($this->tenureStatus)) $filledFields++;
        if (!empty($this->hasElectricity)) $filledFields++;

        // Count Section VII fields (3 fields - excluding conditional groupType and meetingFrequency)
        if (!empty($this->barangayRecreationalFacilities)) $filledFields++;
        if (!empty($this->useOfFreeTime)) $filledFields++;
        if (!empty($this->memberOfOrganization)) $filledFields++;

        // Count Section VIII fields (7 fields)
        if (!empty($this->problemsFamily)) $filledFields++;
        if (!empty($this->problemsHealth)) $filledFields++;
        if (!empty($this->problemsEducation)) $filledFields++;
        if (!empty($this->problemsEmployment)) $filledFields++;
        if (!empty($this->problemsInfrastructure)) $filledFields++;
        if (!empty($this->problemsEconomy)) $filledFields++;
        if (!empty($this->problemsSecurity)) $filledFields++;

        // Count Section IX fields (11 fields - excluding conditional reasonNotAvailable)
        if (!empty($this->barangayServiceRatingPolice)) $filledFields++;
        if (!empty($this->barangayServiceRatingFire)) $filledFields++;
        if (!empty($this->barangayServiceRatingBNS)) $filledFields++;
        if (!empty($this->barangayServiceRatingWater)) $filledFields++;
        if (!empty($this->barangayServiceRatingRoads)) $filledFields++;
        if (!empty($this->barangayServiceRatingClinic)) $filledFields++;
        if (!empty($this->barangayServiceRatingMarket)) $filledFields++;
        if (!empty($this->barangayServiceRatingCommunityCenter)) $filledFields++;
        if (!empty($this->barangayServiceRatingLights)) $filledFields++;
        if (!empty($this->generalFeedback)) $filledFields++;
        if (!empty($this->availableForTraining)) $filledFields++;

        // Total required fields: 7 + 6 + 2 + 5 + 11 + 3 + 3 + 7 + 11 = 55 fields (excluding all conditional)
        $totalFields = 7 + 6 + 2 + 5 + 11 + 3 + 3 + 7 + 11;

        return $totalFields > 0 ? min(round(($filledFields / $totalFields) * 100), 100) : 0;
    }

    // Import functionality
    #[Validate('nullable|file|mimes:csv,xlsx,xls')]
    public $importFile;
    
    // Notification state
    public string $notificationMessage = '';
    public string $notificationType = 'success';

    public function handleFileUpload()
    {
        try {
            $this->validate(['importFile' => 'required|file|mimes:csv,xlsx,xls|max:5120']);
            
            $file = $this->importFile;
            $extension = $file->getClientOriginalExtension();
            
            if ($extension === 'csv') {
                $data = $this->parseCSV($file);
            } else {
                $data = $this->parseExcel($file);
            }
            
            if (empty($data)) {
                $this->importErrors = ['No valid data found in file'];
                $this->showImportErrors = true;
                return;
            }
            
            // Auto-map columns and import data
            $this->autoMapAndImport($data);
            
        } catch (\Exception $e) {
            $this->importErrors = [$e->getMessage()];
            $this->showImportErrors = true;
        }
    }

    private function parseCSV($file)
    {
        $data = [];
        $handle = fopen($file->getRealPath(), 'r');
        
        // Read headers and remove BOM if present
        $headers = fgetcsv($handle);
        if (!empty($headers) && !empty($headers[0])) {
            // Remove UTF-8 BOM from first header if present
            $headers[0] = str_replace("\xEF\xBB\xBF", '', $headers[0]);
            // Clean up headers - trim whitespace
            $headers = array_map('trim', $headers);
        }
        
        while (($row = fgetcsv($handle)) !== false) {
            // Trim each value in the row
            $row = array_map(function($val) { return $val !== null ? trim($val) : $val; }, $row);
            
            if (count($row) > 0 && !empty(array_filter($row))) {
                $combined = array_combine($headers, $row);
                if ($combined !== false) {
                    $data[] = $combined;
                }
            }
        }
        fclose($handle);
        return $data;
    }

    private function parseExcel($file)
    {
        try {
            // For Excel files, treat them as CSV since we don't need complex parsing
            // This works with both .csv and .xlsx files that have been saved as CSV
            $data = [];
            $handle = fopen($file->getRealPath(), 'r');
            $headers = fgetcsv($handle);
            
            if (!$headers) {
                return [];
            }
            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) > 0 && !empty(array_filter($row))) {
                    // Only use as many values as we have headers
                    $row = array_slice($row, 0, count($headers));
                    $data[] = array_combine($headers, $row);
                }
            }
            fclose($handle);
            return $data;
        } catch (\Exception $e) {
            throw new \Exception('Failed to parse Excel file: ' . $e->getMessage());
        }
    }

    private function autoMapAndImport($data)
    {
        $columnMap = $this->getColumnMapping();
        $importedCount = 0;
        $fieldsSet = 0;
        $errors = [];
        $importedFields = [];
        
        foreach ($data as $index => $row) {
            try {
                $rowHasData = false;
                
                foreach ($row as $columnName => $value) {
                    if (empty($value)) continue;
                    
                    // Normalize column name: lowercase, trim, remove extra spaces
                    $normalizedCol = strtolower(trim(preg_replace('/\s+/', ' ', $columnName)));
                    $found = false;
                    
                    foreach ($columnMap as $fieldProperty => $possibleColumnNames) {
                        foreach ($possibleColumnNames as $possibleCol) {
                            // Normalize possible column name the same way
                            $normalizedPossible = strtolower(trim(preg_replace('/\s+/', ' ', $possibleCol)));
                            
                            if ($normalizedPossible === $normalizedCol) {
                                $this->mapFieldValue($fieldProperty, $value);
                                $importedFields[] = "{$fieldProperty} = '{$value}'";
                                $fieldsSet++;
                                $rowHasData = true;
                                $found = true;
                                break 2;
                            }
                        }
                    }
                    
                    // Track unmatched columns
                    if (!$found && !empty($columnName)) {
                        $errors[] = "Column '{$columnName}' did not match any form field";
                    }
                }
                
                if ($rowHasData) {
                    $importedCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        
        if ($importedCount > 0) {
            // Set notification properties
            $this->notificationMessage = "Successfully imported {$importedCount} record(s) - {$fieldsSet} field(s) populated";
            $this->notificationType = 'success';
            
            // Force component refresh
            $this->dispatch('refresh');
        } else {
            // Show error notification
            $this->notificationMessage = 'No matching fields found. Check that column names match the template.';
            $this->notificationType = 'error';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->notificationMessage = $error;
                $this->notificationType = 'error';
            }
        }
        
        $this->importFile = null;
    }

    private function getColumnMapping(): array
    {
        return [
            'respondentFirstName' => ['First Name', 'First name', 'fname', 'first name', 'Given Name', 'given name', 'Forename', 'forename', 'First'],
            'respondentMiddleName' => ['Middle Name', 'Middle name', 'mname', 'middle name', 'Middle Initial', 'MI', 'mi'],
            'respondentLastName' => ['Last Name', 'Last name', 'lname', 'last name', 'Surname', 'surname', 'Family Name', 'family name', 'Last'],
            'age' => ['Age', 'Age (years)', 'Years Old', 'age'],
            'civilStatus' => ['Civil Status', 'Marital Status', 'Status', 'Relationship Status', 'civil status', 'marital status'],
            'sex' => ['Sex', 'Gender', 'Male/Female', 'sex', 'gender'],
            'religion' => ['Religion', 'Religious Affiliation', 'Faith', 'religion'],
            'educationalAttainment' => ['Educational Attainment', 'Education', 'Education Level', 'educational attainment'],
            'livelihoods' => ['Livelihoods', 'Livelihood', 'Occupation', 'Job', 'Work', 'livelihoods'],
            'interestedInTraining' => ['Interested in Training', 'Training Interest', 'Training', 'interested in training'],
            'desiredTraining' => ['Desired Training', 'Training Needed', 'Training Preference', 'desired training'],
            'barangayFacilities' => ['Barangay Facilities', 'Facilities Available', 'Community Facilities', 'barangay facilities'],
            'householdMemberStudying' => ['Household Member Studying', 'School Attendees', 'Students', 'household member studying'],
            'interestedInContinuingStudies' => ['Interested in Continuing Studies', 'Further Education', 'Continuing Education', 'interested in continuing studies'],
            'areasOfInterest' => ['Areas of Interest', 'Interest Areas', 'Subjects of Interest', 'areas of interest'],
            'preferredTime' => ['Preferred Time', 'Training Time', 'Class Time', 'preferred time'],
            'preferredDays' => ['Preferred Days', 'Available Days', 'Training Days', 'preferred days'],
            'commonIllnesses' => ['Common Illnesses', 'Prevalent Diseases', 'Health Issues', 'common illnesses'],
            'actionWhenSick' => ['Action When Sick', 'Health Action', 'Medical Action', 'action when sick'],
            'barangayMedicalSupplies' => ['Barangay Medical Supplies', 'Medical Supplies', 'Health Supplies', 'barangay medical supplies'],
            'hasBarangayHealthPrograms' => ['Has Barangay Health Programs', 'Health Programs', 'Medical Programs', 'has barangay health programs'],
            'benefitsFromPrograms' => ['Benefits from Programs', 'Program Benefits', 'Beneficiary', 'benefits from programs'],
            'programsBenefited' => ['Programs Benefited', 'Benefits Received', 'Program Details', 'programs benefited'],
            'waterSource' => ['Water Source', 'Water Supply', 'Drinking Water', 'water source'],
            'waterSourceDistance' => ['Water Source Distance', 'Distance to Water', 'Water Distance', 'water source distance'],
            'garbageDisposal' => ['Garbage Disposal', 'Waste Disposal', 'Trash', 'garbage disposal'],
            'hasOwnToilet' => ['Has Own Toilet', 'Toilet', 'Sanitation Facility', 'has own toilet'],
            'toiletType' => ['Toilet Type', 'Toilet Facility', 'Comfort Room', 'toilet type'],
            'keepsAnimals' => ['Keeps Animals', 'Livestock', 'Animals', 'keeps animals'],
            'animalsKept' => ['Animals Kept', 'Livestock Type', 'Animal Type', 'animals kept'],
            'housingType' => ['Housing Type', 'House Type', 'House Material', 'housing type'],
            'tenureStatus' => ['Tenure Status', 'Land Ownership', 'Property Status', 'tenure status'],
            'hasElectricity' => ['Has Electricity', 'Electricity', 'Electrical Connection', 'has electricity'],
            'lightSourceNoPower' => ['Light Source No Power', 'Light Source', 'Lighting', 'light source no power'],
            'appliances' => ['Appliances', 'Electrical Appliances', 'Household Appliances', 'appliances'],
            'barangayRecreationalFacilities' => ['Barangay Recreational Facilities', 'Recreational Facilities', 'Recreation', 'barangay recreational facilities'],
            'useOfFreeTime' => ['Use of Free Time', 'Leisure Activities', 'Free Time Activities', 'use of free time'],
            'memberOfOrganization' => ['Member of Organization', 'Organization', 'Group Membership', 'member of organization'],
            'groupType' => ['Group Type', 'Organization Type', 'Group Name', 'group type'],
            'meetingFrequency' => ['Meeting Frequency', 'Meeting Schedule', 'Meeting Interval', 'meeting frequency'],
            'problemsFamily' => ['Problems Family', 'Family Problems', 'Family Issues', 'problems family'],
            'problemsHealth' => ['Problems Health', 'Health Problems', 'Health Issues', 'problems health'],
            'problemsEducation' => ['Problems Education', 'Education Problems', 'Educational Issues', 'problems education'],
            'problemsEmployment' => ['Problems Employment', 'Employment Problems', 'Job Issues', 'problems employment'],
            'problemsInfrastructure' => ['Problems Infrastructure', 'Infrastructure Problems', 'Infrastructure Issues', 'problems infrastructure'],
            'problemsEconomy' => ['Problems Economy', 'Economic Problems', 'Economic Issues', 'problems economy'],
            'problemsSecurity' => ['Problems Security', 'Security Problems', 'Security Issues', 'problems security'],
            'barangayServiceRatingPolice' => ['Barangay Service Rating Police', 'Police Rating', 'barangay service rating police'],
            'barangayServiceRatingFire' => ['Barangay Service Rating Fire', 'Fire Rating', 'barangay service rating fire'],
            'barangayServiceRatingBNS' => ['Barangay Service Rating BNS', 'BNS Rating', 'barangay service rating bns'],
            'barangayServiceRatingWater' => ['Barangay Service Rating Water', 'Water Rating', 'barangay service rating water'],
            'barangayServiceRatingRoads' => ['Barangay Service Rating Roads', 'Roads Rating', 'barangay service rating roads'],
            'barangayServiceRatingClinic' => ['Barangay Service Rating Clinic', 'Clinic Rating', 'barangay service rating clinic'],
            'barangayServiceRatingMarket' => ['Barangay Service Rating Market', 'Market Rating', 'barangay service rating market'],
            'barangayServiceRatingCommunityCenter' => ['Barangay Service Rating Community Center', 'Community Center Rating', 'barangay service rating community center'],
            'barangayServiceRatingLights' => ['Barangay Service Rating Lights', 'Lights Rating', 'barangay service rating lights'],
            'generalFeedback' => ['General Feedback', 'Comments', 'Remarks', 'general feedback'],
            'availableForTraining' => ['Available for Training', 'Training Availability', 'Available', 'available for training'],
            'reasonNotAvailable' => ['Reason Not Available', 'Reason Unavailable', 'Unavailable Reason', 'reason not available'],
        ];
    }

    private function mapFieldValue($fieldProperty, $value)
    {
        // Ensure value is treated as string and trim it
        $value = trim((string)$value);
        
        // Skip if value becomes empty after trim
        if ($value === '') return;
        
        // Handle yes/no conversions
        if (in_array($fieldProperty, ['interestedInTraining', 'householdMemberStudying', 'interestedInContinuingStudies', 
                                       'hasBarangayHealthPrograms', 'benefitsFromPrograms', 'hasOwnToilet', 'keepsAnimals', 
                                       'hasElectricity', 'memberOfOrganization', 'availableForTraining'])) {
            $lowerValue = strtolower($value);
            if (in_array($lowerValue, ['1', 'yes', 'y', 'true', 'oo', 'oui'])) {
                $value = 'yes';
            } elseif (in_array($lowerValue, ['0', 'no', 'n', 'false'])) {
                $value = 'no';
            }
        }
        
        try {
            $this->$fieldProperty = $value;
        } catch (\Exception $e) {
            // Silently fail if property can't be set
            \Log::warning("Failed to set property {$fieldProperty} = {$value}: " . $e->getMessage());
        }
    }

    public function refreshForm()
    {
        // Force Livewire to re-render the entire component
        $this->dispatch('refresh');
    }

    public function downloadTemplate()
    {
        try {
            // Set column headers
            $headers = [
                'First Name',
                'Middle Name',
                'Last Name',
                'Age',
                'Civil Status',
                'Sex',
                'Religion',
                'Educational Attainment',
                'Livelihoods',
                'Interested in Training',
                'Desired Training',
                'Barangay Facilities',
                'Household Member Studying',
                'Interested in Continuing Studies',
                'Areas of Interest',
                'Preferred Time',
                'Preferred Days',
                'Common Illnesses',
                'Action When Sick',
                'Barangay Medical Supplies',
                'Has Barangay Health Programs',
                'Benefits from Programs',
                'Programs Benefited',
                'Water Source',
                'Water Source Distance',
                'Garbage Disposal',
                'Has Own Toilet',
                'Toilet Type',
                'Keeps Animals',
                'Animals Kept',
                'Housing Type',
                'Tenure Status',
                'Has Electricity',
                'Light Source No Power',
                'Appliances',
                'Barangay Recreational Facilities',
                'Use of Free Time',
                'Member of Organization',
                'Group Type',
                'Meeting Frequency',
                'Problems Family',
                'Problems Health',
                'Problems Education',
                'Problems Employment',
                'Problems Infrastructure',
                'Problems Economy',
                'Problems Security',
                'Barangay Service Rating Police',
                'Barangay Service Rating Fire',
                'Barangay Service Rating BNS',
                'Barangay Service Rating Water',
                'Barangay Service Rating Roads',
                'Barangay Service Rating Clinic',
                'Barangay Service Rating Market',
                'Barangay Service Rating Community Center',
                'Barangay Service Rating Lights',
                'General Feedback',
                'Available for Training',
                'Reason Not Available',
            ];
            
            // Create CSV content
            $fileName = 'CNA_Template_' . date('Y-m-d_His') . '.csv';
            $tempPath = storage_path('app/temp_' . $fileName);
            
            // Write CSV file
            $handle = fopen($tempPath, 'w');
            fputcsv($handle, $headers);
            fclose($handle);
            
            return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            $this->importErrors = ['Failed to generate template: ' . $e->getMessage()];
            $this->showImportErrors = true;
        }
    }

    public function confirmSubmit()
    {
        $this->showConfirmModal = true;
    }

    public function cancelSubmit()
    {
        $this->showConfirmModal = false;
    }

    public function submit()
    {
        $this->isSubmitting = true;
        $this->submissionErrors = [];
        $this->showSubmissionErrors = false;

        try {
            // Log submission start
            \Log::info('Form submission started', ['user_id' => Auth::id()]);

            // Validate form - catch validation exceptions
            $validated = $this->validate();
            \Log::info('Form validation passed');

            // Prepare data from all form fields
            $data = [
                'user_id' => Auth::id(),
                'community_id' => (int)$this->community_id,
                'respondent_first_name' => $this->respondentFirstName,
                'respondent_middle_name' => $this->respondentMiddleName,
                'respondent_last_name' => $this->respondentLastName,
                'age' => (int)$this->age,
                'civil_status' => $this->civilStatus,
                'sex' => $this->sex,
                'religion' => $this->religion,
                'educational_attainment' => $this->educationalAttainment,
                'family_members' => $this->familyMembers,
                'livelihoods' => $this->livelihoods,
                'interested_in_training' => $this->interestedInTraining,
                'desired_training' => $this->desiredTraining,
                'barangay_facilities' => $this->barangayFacilities,
                'household_member_studying' => $this->householdMemberStudying,
                'interested_in_continuing_studies' => $this->interestedInContinuingStudies,
                'areas_of_interest' => $this->areasOfInterest,
                'preferred_time' => $this->preferredTime,
                'preferred_days' => $this->preferredDays,
                'common_illnesses' => $this->commonIllnesses,
                'action_when_sick' => $this->actionWhenSick,
                'barangay_medical_supplies' => $this->barangayMedicalSupplies,
                'has_barangay_health_programs' => $this->hasBarangayHealthPrograms,
                'benefits_from_programs' => $this->benefitsFromPrograms,
                'programs_benefited' => $this->programsBenefited,
                'water_source' => $this->waterSource,
                'water_source_distance' => $this->waterSourceDistance,
                'garbage_disposal' => $this->garbageDisposal,
                'has_own_toilet' => $this->hasOwnToilet,
                'toilet_type' => $this->toiletType,
                'keeps_animals' => $this->keepsAnimals,
                'animals_kept' => $this->animalsKept,
                'housing_type' => $this->housingType,
                'tenure_status' => $this->tenureStatus,
                'has_electricity' => $this->hasElectricity,
                'light_source_no_power' => $this->lightSourceNoPower,
                'appliances' => $this->appliances,
                'barangay_recreational_facilities' => $this->barangayRecreationalFacilities,
                'use_of_free_time' => $this->useOfFreeTime,
                'member_of_organization' => $this->memberOfOrganization,
                'group_type' => $this->groupType,
                'meeting_frequency' => $this->meetingFrequency,
                'problems_family' => $this->problemsFamily,
                'problems_health' => $this->problemsHealth,
                'problems_education' => $this->problemsEducation,
                'problems_employment' => $this->problemsEmployment,
                'problems_infrastructure' => $this->problemsInfrastructure,
                'problems_economy' => $this->problemsEconomy,
                'problems_security' => $this->problemsSecurity,
                'barangay_service_rating_police' => (int)$this->barangayServiceRatingPolice,
                'barangay_service_rating_fire' => (int)$this->barangayServiceRatingFire,
                'barangay_service_rating_bns' => (int)$this->barangayServiceRatingBNS,
                'barangay_service_rating_water' => (int)$this->barangayServiceRatingWater,
                'barangay_service_rating_roads' => (int)$this->barangayServiceRatingRoads,
                'barangay_service_rating_clinic' => (int)$this->barangayServiceRatingClinic,
                'barangay_service_rating_market' => (int)$this->barangayServiceRatingMarket,
                'barangay_service_rating_community_center' => (int)$this->barangayServiceRatingCommunityCenter,
                'barangay_service_rating_lights' => (int)$this->barangayServiceRatingLights,
                'general_feedback' => $this->generalFeedback,
                'available_for_training' => $this->availableForTraining,
                'reason_not_available' => $this->reasonNotAvailable,
                'status' => 'submitted',
                'submitted_at' => now(),
                'ip_address' => request()->ip(),
            ];

            \Log::info('Data prepared for database insertion');

            // Create the record
            $assessment = CommunityNeedsAssessment::create($data);

            \Log::info('Assessment created successfully', ['assessment_id' => $assessment->id]);

            // Close confirmation modal and show success modal
            $this->showConfirmModal = false;
            $this->showSuccessModal = true;
            $this->assessmentId = $assessment->id;
            $this->submitted = now()->format('F d, Y h:i A');

            // Dispatch event
            $this->dispatch('form-submitted', ['type' => 'F-CES-001', 'id' => $assessment->id]);

        } catch(\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Form validation failed', ['errors' => $e->errors()]);
            // Flatten error messages for display - just show messages without field names
            $errors = [];
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $errors[] = $message;
                }
            }
            $this->showConfirmModal = false;
            $this->submissionErrors = $errors;
            $this->showSubmissionErrors = true;
            $this->notificationMessage = 'Validation failed. Please check the errors below.';
            $this->notificationType = 'error';

        } catch (\Exception $e) {
            \Log::error('Form submission error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->showConfirmModal = false;
            $this->submissionErrors = [$e->getMessage()];
            $this->showSubmissionErrors = true;
            $this->notificationMessage = 'Error saving assessment. Please see details below.';
            $this->notificationType = 'error';

        } finally {
            $this->isSubmitting = false;
        }
    }

    public function submitAnotherAssessment()
    {
        $this->showSuccessModal = false;
        $this->assessmentId = '';
        $this->submitted = '';

        // Reset Section I
        $this->community_id = null;
        $this->respondentFirstName = '';
        $this->respondentMiddleName = '';
        $this->respondentLastName = '';
        $this->age = '';
        $this->civilStatus = '';
        $this->sex = '';
        $this->religion = '';
        $this->educationalAttainment = '';

        // Reset Section II
        $this->familyMembers = [];
        $this->familyMemberIdCounter = 0;

        // Reset Section III
        $this->livelihoods = '';
        $this->interestedInTraining = '';
        $this->desiredTraining = '';

        // Reset Section IV
        $this->barangayFacilities = '';
        $this->householdMemberStudying = '';
        $this->interestedInContinuingStudies = '';
        $this->areasOfInterest = '';
        $this->preferredTime = '';
        $this->preferredDays = '';

        // Reset Section V
        $this->commonIllnesses = '';
        $this->actionWhenSick = '';
        $this->barangayMedicalSupplies = '';
        $this->hasBarangayHealthPrograms = '';
        $this->benefitsFromPrograms = '';
        $this->programsBenefited = '';
        $this->waterSource = '';
        $this->waterSourceDistance = '';
        $this->garbageDisposal = '';
        $this->hasOwnToilet = '';
        $this->toiletType = '';
        $this->keepsAnimals = '';
        $this->animalsKept = '';

        // Reset Section VI
        $this->housingType = '';
        $this->tenureStatus = '';
        $this->hasElectricity = '';
        $this->lightSourceNoPower = '';
        $this->appliances = '';

        // Reset Section VII
        $this->barangayRecreationalFacilities = '';
        $this->useOfFreeTime = '';
        $this->memberOfOrganization = '';
        $this->groupType = '';
        $this->meetingFrequency = '';

        // Reset Section VIII
        $this->problemsFamily = '';
        $this->problemsHealth = '';
        $this->problemsEducation = '';
        $this->problemsEmployment = '';
        $this->problemsInfrastructure = '';
        $this->problemsEconomy = '';
        $this->problemsSecurity = '';

        // Reset Section IX
        $this->barangayServiceRatingPolice = '';
        $this->barangayServiceRatingFire = '';
        $this->barangayServiceRatingBNS = '';
        $this->barangayServiceRatingWater = '';
        $this->barangayServiceRatingRoads = '';
        $this->barangayServiceRatingClinic = '';
        $this->barangayServiceRatingMarket = '';
        $this->barangayServiceRatingCommunityCenter = '';
        $this->barangayServiceRatingLights = '';
        $this->generalFeedback = '';
        $this->availableForTraining = '';
        $this->reasonNotAvailable = '';

        // Reset error states
        $this->submissionErrors = [];
        $this->showSubmissionErrors = false;

        // Scroll to top
        $this->dispatch('scroll-to-top');
    }

    public function goBackToDashboard()
    {
        return redirect()->route('dashboard');
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
                        Assessment submitted successfully on {{ $submitted }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Submission Errors Modal -->
    @if($showSubmissionErrors && count($submissionErrors) > 0)
        <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-2xl p-6 max-w-lg w-full mx-4 animate-slide-in">
                <div class="flex items-center mb-4">
                    <svg class="h-6 w-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-lg font-semibold text-red-700">Submission Errors</h3>
                </div>

                <div class="max-h-96 overflow-y-auto mb-4">
                    <ul class="space-y-2">
                        @foreach($submissionErrors as $error)
                            <li class="text-sm text-red-600 flex items-start">
                                <span class="text-red-500 mr-2">•</span>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button"
                            wire:click="$set('showSubmissionErrors', false)"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-medium">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-2xl p-8 max-w-md w-full mx-4 animate-slide-in">
                <div class="flex items-center mb-4">
                    <svg class="h-6 w-6 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5zm-2-1a1 1 0 00-1 1v4.586L7.707 9.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0l5-5a1 1 0 00-1.414-1.414L13 10.586V5a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-800">Confirm Submission</h3>
                </div>

                <p class="text-gray-600 mb-6">Are you sure you want to submit this Community Needs Assessment? Please review all entries before confirming.</p>

                <div class="flex gap-3 justify-end">
                    <button type="button"
                            wire:click="cancelSubmit"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-medium">
                        Cancel
                    </button>
                    <button type="button"
                            wire:click="submit"
                            class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Modal -->
    @if($showSuccessModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-2xl p-8 max-w-md w-full mx-4 animate-slide-in">
                @if($isSubmitting)
                    <!-- Loading State -->
                    <div class="flex flex-col items-center justify-center">
                        <svg class="animate-spin h-12 w-12 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-lg font-semibold text-gray-800">Submitting...</p>
                        <p class="text-sm text-gray-600 mt-2">Please wait while we process your assessment.</p>
                    </div>
                @else
                    <!-- Success State -->
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="bg-green-100 rounded-full p-4 mb-4">
                            <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Submitted Successfully!</h3>
                        <p class="text-green-600 font-semibold mb-2">Assessment ID: {{ $assessmentId }}</p>
                        <p class="text-gray-600 mb-6">Your Community Needs Assessment has been successfully submitted.</p>
                        <p class="text-sm text-gray-500 mb-6">Submitted at: {{ $submitted }}</p>

                        <div class="flex gap-3 w-full">
                            <button type="button"
                                    wire:click="submitAnotherAssessment"
                                    class="flex-1 px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                                Submit Another Assessment
                            </button>
                            <button type="button"
                                    wire:click="goBackToDashboard"
                                    class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-medium">
                                Back to Dashboard
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Modal Notification -->
    <div id="modal-notification" 
         x-data="{ 
             message: @entangle('notificationMessage').live,
             type: @entangle('notificationType').live
         }"
         @watch="message" 
         x-effect="if (message) { console.log('Message changed:', message); window.showNotification(message, type); }"
         class="fixed top-4 left-4 z-50 hidden">
        <div id="notification-box" class="bg-white rounded-lg shadow-xl border-l-4 p-4 max-w-sm animate-slide-in">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg id="notification-icon" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"></svg>
                </div>
                <div class="ml-3">
                    <p id="notification-message" class="text-sm font-medium"></p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(-100%);
                opacity: 0;
            }
        }
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        .animate-slide-out {
            animation: slideOut 0.3s ease-out forwards;
        }
    </style>

    <script>
        // Define notification function
        window.showNotification = function(message, type = 'success') {
            console.log('showNotification called:', message, type);
            
            const container = document.getElementById('modal-notification');
            const notificationBox = document.getElementById('notification-box');
            const messageEl = document.getElementById('notification-message');
            const iconEl = document.getElementById('notification-icon');
            
            console.log('Elements found:', { container: !!container, notificationBox: !!notificationBox, messageEl: !!messageEl, iconEl: !!iconEl });
            
            if (!container || !notificationBox) {
                console.error('Notification elements not found!');
                return;
            }
            
            // Reset animation
            notificationBox.classList.remove('animate-slide-out', 'animate-slide-in');
            void notificationBox.offsetWidth; // Trigger reflow
            notificationBox.classList.add('animate-slide-in');
            
            // Set message
            messageEl.textContent = message;
            
            // Set styling based on type
            const styles = {
                'success': {
                    borderColor: 'border-blue-400',
                    bgColor: 'bg-blue-50',
                    textColor: 'text-blue-800',
                    icon: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />'
                },
                'error': {
                    borderColor: 'border-red-400',
                    bgColor: 'bg-red-50',
                    textColor: 'text-red-800',
                    icon: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />'
                },
                'warning': {
                    borderColor: 'border-yellow-400',
                    bgColor: 'bg-yellow-50',
                    textColor: 'text-yellow-800',
                    icon: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />'
                }
            };
            
            const style = styles[type] || styles['success'];
            
            notificationBox.className = `${style.bgColor} ${style.borderColor} rounded-lg shadow-xl border-l-4 p-4 max-w-sm animate-slide-in`;
            messageEl.className = `text-sm font-medium ${style.textColor}`;
            iconEl.className = `h-5 w-5 ${style.textColor}`;
            iconEl.innerHTML = style.icon;
            
            // Show container
            container.classList.remove('hidden');
            
            // Auto-hide after 2.5 seconds
            if (window.notificationTimeout) {
                clearTimeout(window.notificationTimeout);
            }
            
            window.notificationTimeout = setTimeout(() => {
                notificationBox.classList.add('animate-slide-out');
                setTimeout(() => {
                    container.classList.add('hidden');
                }, 300);
            }, 2500);
        };

        // Listen for scroll-to-top event from Livewire
        window.Livewire?.on('scroll-to-top', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

    <form class="space-y-8">
        <!-- Import from Spreadsheet Section -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-4m0 0V8m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Import from Spreadsheet</h3>
                    <button type="button" 
                            wire:click="downloadTemplate" 
                            class="ml-4 text-blue-100 hover:text-white underline text-sm font-medium transition">
                        Template
                    </button>
                </div>
                <span class="text-xs font-semibold bg-blue-600 text-blue-100 px-3 py-1 rounded-full">Optional</span>
            </div>
            
            <div class="px-6 py-4 bg-gray-50">
                <p class="text-sm text-gray-600 mb-4">
                    Upload CSV or Excel files to auto-populate form fields. Column names will be automatically matched to corresponding fields.
                    <span class="block text-xs text-gray-500 mt-1">
                        💡 <strong>Tip:</strong> Click "Template" above to download a blank Excel file with all required column headers.
                    </span>
                </p>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer" 
                     @dragover.prevent="$wire.dragOverFile = true"
                     @dragleave.prevent="$wire.dragOverFile = false"
                     @drop.prevent="$wire.dragOverFile = false"
                     :class="{ 'border-blue-500 bg-blue-50': dragOverFile }">
                    
                    <input type="file" wire:model.live="importFile" accept=".csv,.xlsx,.xls" id="import-file" class="hidden" />
                    
                    <label for="import-file" class="cursor-pointer">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-8-12l6 6m0 0V8m0 6l-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-sm font-medium text-gray-700">
                            Drag and drop your file here, or <span class="text-blue-600 hover:text-blue-700">click to browse</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-2">CSV or Excel files up to 5 MB</p>
                    </label>
                </div>
                
                @if($importFile)
                    <div class="mt-4 flex items-center justify-between bg-white border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 16.5a1 1 0 01-1-1v-5.19l-2.293 2.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L9 10.31V15.5a1 1 0 01-1 1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $importFile->getClientOriginalName() }}</p>
                                <p class="text-xs text-gray-500">{{ round($importFile->getSize() / 1024, 2) }} KB</p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('importFile', null)" class="text-gray-400 hover:text-red-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    
                    <button type="button" 
                            wire:click="handleFileUpload" 
                            class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                        <span wire:loading.remove wire:target="handleFileUpload">Import Data</span>
                        <span wire:loading wire:target="handleFileUpload" class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                @endif
            </div>
        </div>
        <!-- Identifying Information -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-700 to-purple-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Identifying Information</h3>
                <span class="text-xs font-semibold bg-purple-600 text-purple-100 px-3 py-1 rounded-full">Section I</span>
            </div>
            <div class="p-6 space-y-4">
                <!-- Community Selection -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div>
                        <x-input-label for="community_id" value="Community/Barangay *" />
                        <select id="community_id"
                                wire:model.live="community_id"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-600 focus:ring-purple-600">
                            <option value="">-- Select Community --</option>
                            @foreach(\App\Models\Community::where('status', 'active')->orderBy('name')->get() as $community)
                                <option value="{{ $community->id }}">{{ $community->name }} - {{ $community->municipality }}, {{ $community->province }}</option>
                            @endforeach
                        </select>
                        @error('community_id') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="respondentFirstName" value="First Name *" />
                        <input type="text"
                               id="respondentFirstName"
                               wire:model.live="respondentFirstName"
                               value="{{ old('respondentFirstName', $respondentFirstName) }}"
                               placeholder="Enter first name"
                               pattern="[a-zA-Z\s\-\.]+"
                               title="First name can only contain letters, spaces, hyphens, and periods"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-600 focus:ring-purple-600">
                        @error('respondentFirstName') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="respondentMiddleName" value="Middle Name (Optional)" />
                        <input type="text"
                               id="respondentMiddleName"
                               wire:model.live="respondentMiddleName"
                               value="{{ old('respondentMiddleName', $respondentMiddleName) }}"
                               placeholder="Enter middle name"
                               pattern="[a-zA-Z\s\-\.]*"
                               title="Middle name can only contain letters, spaces, hyphens, and periods"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-600 focus:ring-purple-600">
                        @error('respondentMiddleName') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <x-input-label for="respondentLastName" value="Last Name *" />
                    <input type="text"
                           id="respondentLastName"
                           wire:model.live="respondentLastName"
                           value="{{ old('respondentLastName', $respondentLastName) }}"
                           placeholder="Enter last name"
                           pattern="[a-zA-Z\s\-\.]+"
                           title="Last name can only contain letters, spaces, hyphens, and periods"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-600 focus:ring-purple-600">
                    @error('respondentLastName') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="age" value="Age *" />
                        <select id="age" wire:model.live="age" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-600 focus:ring-purple-600">
                            <option value="">Select age...</option>
                            @for($i = 1; $i <= 150; $i++)
                                <option value="{{ $i }}" {{ $age == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('age') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="civilStatus" value="Civil Status *" />
                        <select id="civilStatus" wire:model.live="civilStatus" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-600 focus:ring-purple-600">
                            <option value="">Select...</option>
                            <option value="Single" {{ $civilStatus === 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ $civilStatus === 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Divorced" {{ $civilStatus === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed" {{ $civilStatus === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            <option value="Separated" {{ $civilStatus === 'Separated' ? 'selected' : '' }}>Separated</option>
                        </select>
                        @error('civilStatus') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="sex" value="Sex *" />
                        <select id="sex" wire:model.live="sex" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-600 focus:ring-purple-600">
                            <option value="">Select...</option>
                            <option value="Male" {{ $sex === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $sex === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ $sex === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('sex') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="religion" value="Religion *" />
                        <select id="religion" wire:model.live="religion" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-600 focus:ring-purple-600">
                            <option value="">Select...</option>
                            <option value="Roman Catholic" {{ $religion === 'Roman Catholic' ? 'selected' : '' }}>Roman Catholic</option>
                            <option value="Protestant" {{ $religion === 'Protestant' ? 'selected' : '' }}>Protestant</option>
                            <option value="Islamic" {{ $religion === 'Islamic' ? 'selected' : '' }}>Islamic</option>
                            <option value="Buddhist" {{ $religion === 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                            <option value="Hindu" {{ $religion === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Seventh-day Adventist" {{ $religion === 'Seventh-day Adventist' ? 'selected' : '' }}>Seventh-day Adventist</option>
                            <option value="Jehovah's Witness" {{ $religion === "Jehovah's Witness" ? 'selected' : '' }}>Jehovah's Witness</option>
                            <option value="Other" {{ $religion === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('religion') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <x-input-label for="educationalAttainment" value="Educational Attainment *" />
                    <select id="educationalAttainment" wire:model.live="educationalAttainment" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-600 focus:ring-purple-600">
                        <option value="">Select...</option>
                        <option value="None" {{ $educationalAttainment === 'None' ? 'selected' : '' }}>None</option>
                        <option value="Elementary" {{ $educationalAttainment === 'Elementary' ? 'selected' : '' }}>Elementary</option>
                        <option value="High School" {{ $educationalAttainment === 'High School' ? 'selected' : '' }}>High School</option>
                        <option value="Vocational" {{ $educationalAttainment === 'Vocational' ? 'selected' : '' }}>Vocational</option>
                        <option value="College" {{ $educationalAttainment === 'College' ? 'selected' : '' }}>College</option>
                        <option value="Post-Graduate" {{ $educationalAttainment === 'Post-Graduate' ? 'selected' : '' }}>Post-Graduate</option>
                    </select>
                    @error('educationalAttainment') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Family Composition -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-pink-700 to-pink-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Family Composition</h3>
                <span class="text-xs font-semibold bg-pink-600 text-pink-100 px-3 py-1 rounded-full">Section II</span>
            </div>
            <div class="p-6 space-y-4">
                <div class="mb-6">
                    <button type="button" wire:click="addFamilyMember" class="inline-flex items-center px-4 py-2 bg-pink-600 text-white font-semibold rounded-lg hover:bg-pink-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Family Member
                    </button>
                </div>

                @if(count($familyMembers) > 0)
                    <div class="space-y-4">
                        @foreach($familyMembers as $key => $member)
                            <div class="bg-pink-50 rounded-lg p-4 border-l-4 border-pink-500">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-lg font-semibold text-pink-900">Family Member {{ $key + 1 }}</h4>
                                    <button type="button" wire:click="removeFamilyMember({{ $member['id'] }})" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm font-semibold rounded hover:bg-red-700 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Remove
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="familyMember_firstName_{{ $member['id'] }}" value="First Name *" />
                                        <input type="text" id="familyMember_firstName_{{ $member['id'] }}" wire:model="familyMembers.{{ $key }}.firstName" placeholder="First name" pattern="[a-zA-Z\s\-\.]+" title="Only letters, spaces, hyphens, and periods allowed" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600">
                                    </div>
                                    <div>
                                        <x-input-label for="familyMember_middleName_{{ $member['id'] }}" value="Middle Name (Optional)" />
                                        <input type="text" id="familyMember_middleName_{{ $member['id'] }}" wire:model="familyMembers.{{ $key }}.middleName" placeholder="Middle name" pattern="[a-zA-Z\s\-\.]*" title="Only letters, spaces, hyphens, and periods allowed" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <x-input-label for="familyMember_lastName_{{ $member['id'] }}" value="Last Name *" />
                                        <input type="text" id="familyMember_lastName_{{ $member['id'] }}" wire:model="familyMembers.{{ $key }}.lastName" placeholder="Last name" pattern="[a-zA-Z\s\-\.]+" title="Only letters, spaces, hyphens, and periods allowed" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600">
                                    </div>
                                    <div>
                                        <x-input-label for="familyMember_age_{{ $member['id'] }}" value="Age *" />
                                        <input type="number" id="familyMember_age_{{ $member['id'] }}" wire:model="familyMembers.{{ $key }}.age" placeholder="Age" min="0" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600">
                                    </div>
                                    <div>
                                        <x-input-label for="familyMember_sex_{{ $member['id'] }}" value="Sex *" />
                                        <select id="familyMember_sex_{{ $member['id'] }}" wire:model="familyMembers.{{ $key }}.sex" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600">
                                            <option value="">Select...</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <x-input-label for="familyMember_education_{{ $member['id'] }}" value="Educational Attainment *" />
                                        <select id="familyMember_education_{{ $member['id'] }}" wire:model="familyMembers.{{ $key }}.educationalAttainment" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600">
                                            <option value="">Select...</option>
                                            <option value="Elementary">Elementary</option>
                                            <option value="High School">High School</option>
                                            <option value="College">College</option>
                                            <option value="Vocational">Vocational</option>
                                            <option value="Post-Graduate">Post-Graduate</option>
                                            <option value="None">None</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="familyMember_employment_{{ $member['id'] }}" value="Employment Status *" />
                                        <select id="familyMember_employment_{{ $member['id'] }}" wire:model="familyMembers.{{ $key }}.employment" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600">
                                            <option value="">Select...</option>
                                            <option value="Employed">Employed</option>
                                            <option value="Self-Employed">Self-Employed</option>
                                            <option value="Unemployed">Unemployed</option>
                                            <option value="Student">Student</option>
                                            <option value="Retired">Retired</option>
                                            <option value="Housewife">Housewife</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-pink-100 rounded-lg p-4 text-center text-pink-700">
                        <p class="text-sm font-medium">No family members added yet. Click "Add Family Member" to begin.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Economic Aspect -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Economic Aspect</h3>
                <span class="text-xs font-semibold bg-green-600 text-green-100 px-3 py-1 rounded-full">Section III</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="livelihoods" value="Family Livelihood Options *" />
                    <select id="livelihoods" wire:model="livelihoods" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                        <option value="">Select livelihood...</option>
                        <option value="Farming">Farming</option>
                        <option value="Fishing">Fishing</option>
                        <option value="Raising Animals">Raising Animals</option>
                        <option value="Selling/Trading">Selling/Trading</option>
                        <option value="Driving">Driving</option>
                        <option value="Remittance">Remittance</option>
                        <option value="Pension">Pension</option>
                        <option value="4Ps Assistance">4Ps Assistance</option>
                        <option value="Rentals">Rentals</option>
                        <option value="Service Work">Service Work</option>
                        <option value="Construction">Construction</option>
                        <option value="Manufacturing">Manufacturing</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('livelihoods') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="interestedInTraining" value="Interested in Training? *" />
                    <select id="interestedInTraining" wire:model.live="interestedInTraining" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                        <option value="">Select...</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    @error('interestedInTraining') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                @if($interestedInTraining === 'yes')
                <div>
                    <x-input-label for="desiredTraining" value="Desired Training Types *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="desiredTraining" value="Cosmetology" class="rounded border-gray-300">
                            <span class="ml-2">Cosmetology</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="desiredTraining" value="Handicrafts" class="rounded border-gray-300">
                            <span class="ml-2">Handicrafts</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="desiredTraining" value="Electronics" class="rounded border-gray-300">
                            <span class="ml-2">Electronics</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="desiredTraining" value="Computer" class="rounded border-gray-300">
                            <span class="ml-2">Computer</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="desiredTraining" value="Refrigerator & A/C" class="rounded border-gray-300">
                            <span class="ml-2">Refrigerator & A/C</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="desiredTraining" value="Food Processing" class="rounded border-gray-300">
                            <span class="ml-2">Food Processing</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="desiredTraining" value="Dress Making" class="rounded border-gray-300">
                            <span class="ml-2">Dress Making</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="desiredTraining" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other (Specify)</span>
                        </label>
                    </div>
                    @error('desiredTraining') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
        </div>

        <!-- Educational Aspect -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-700 to-amber-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Educational Aspect</h3>
                <span class="text-xs font-semibold bg-amber-600 text-amber-100 px-3 py-1 rounded-full">Section IV</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="barangayFacilities" value="Barangay Educational Facilities *" />
                    <textarea id="barangayFacilities" wire:model="barangayFacilities" placeholder="Daycare, Elementary, Secondary, College" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-600 focus:ring-amber-600"></textarea>
                    @error('barangayFacilities') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="householdMemberStudying" value="Household Member Currently Studying? *" />
                        <select id="householdMemberStudying" wire:model="householdMemberStudying" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-600 focus:ring-amber-600">
                            <option value="">Select...</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        @error('householdMemberStudying') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="interestedInContinuingStudies" value="Interested in Continuing Studies? *" />
                        <select id="interestedInContinuingStudies" wire:model.live="interestedInContinuingStudies" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-600 focus:ring-amber-600">
                            <option value="">Select...</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        @error('interestedInContinuingStudies') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <x-input-label for="areasOfInterest" value="Areas of Interest *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="areasOfInterest" value="Reading" class="rounded border-gray-300">
                            <span class="ml-2">Reading</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="areasOfInterest" value="Writing" class="rounded border-gray-300">
                            <span class="ml-2">Writing</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="areasOfInterest" value="Math" class="rounded border-gray-300">
                            <span class="ml-2">Math</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="areasOfInterest" value="English" class="rounded border-gray-300">
                            <span class="ml-2">English</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="areasOfInterest" value="Issues & Laws" class="rounded border-gray-300">
                            <span class="ml-2">Issues & Laws</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="areasOfInterest" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('areasOfInterest') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="preferredTime" value="Preferred Training Time *" />
                        <select id="preferredTime" wire:model="preferredTime" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-600 focus:ring-amber-600">
                            <option value="">Select...</option>
                            <option value="Morning (8:00-12:00)">Morning (8:00-12:00)</option>
                            <option value="Afternoon (1:30-5:00)">Afternoon (1:30-5:00)</option>
                        </select>
                        @error('preferredTime') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="preferredDays" value="Preferred Training Days *" />
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="preferredDays" value="Wednesday" class="rounded border-gray-300">
                                <span class="ml-2">Wednesday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="preferredDays" value="Saturday" class="rounded border-gray-300">
                                <span class="ml-2">Saturday</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="preferredDays" value="Sunday" class="rounded border-gray-300">
                                <span class="ml-2">Sunday</span>
                            </label>
                        </div>
                        @error('preferredDays') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-red-700 to-red-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Health and Sanitation</h3>
                <span class="text-xs font-semibold bg-red-600 text-red-100 px-3 py-1 rounded-full">Section V</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="commonIllnesses" value="Common Illnesses *" />
                    <select id="commonIllnesses" wire:model="commonIllnesses" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600">
                        <option value="">Select illness...</option>
                        <option value="Colds">Colds</option>
                        <option value="Flu">Flu</option>
                        <option value="Asthma">Asthma</option>
                        <option value="Pneumonia">Pneumonia</option>
                        <option value="Diarrhea">Diarrhea</option>
                        <option value="Schistosomiasis">Schistosomiasis</option>
                        <option value="Hypertension">Hypertension</option>
                        <option value="Diabetes">Diabetes</option>
                        <option value="Vomiting">Vomiting</option>
                        <option value="Headache">Headache</option>
                        <option value="Stomach Ache">Stomach Ache</option>
                        <option value="Tuberculosis">Tuberculosis</option>
                        <option value="Dengue">Dengue</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('commonIllnesses') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="waterSource" value="Water Source *" />
                    <select id="waterSource" wire:model="waterSource" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600">
                        <option value="">Select water source...</option>
                        <option value="NAWASA">NAWASA</option>
                        <option value="Water Pump">Water Pump</option>
                        <option value="Deep Well">Deep Well</option>
                        <option value="Spring Water">Spring Water</option>
                        <option value="River/Stream">River/Stream</option>
                        <option value="Rainwater Collection">Rainwater Collection</option>
                        <option value="Tube Well">Tube Well</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('waterSource') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="hasOwnToilet" value="Have Own Toilet? *" />
                    <select id="hasOwnToilet" wire:model.live="hasOwnToilet" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600">
                        <option value="">Select...</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    @error('hasOwnToilet') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                @if($hasOwnToilet === 'yes')
                <div>
                    <x-input-label for="toiletType" value="Toilet Type *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="toiletType" value="Flush toilet" class="rounded border-gray-300">
                            <span class="ml-2">Flush Toilet</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="toiletType" value="Water sealed" class="rounded border-gray-300">
                            <span class="ml-2">Water Sealed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="toiletType" value="Antipolo style" class="rounded border-gray-300">
                            <span class="ml-2">Antipolo Style</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="toiletType" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('toiletType') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                <div>
                    <x-input-label for="garbageDisposal" value="Garbage Disposal Method *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="garbageDisposal" value="Compost pit" class="rounded border-gray-300">
                            <span class="ml-2">Compost Pit</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="garbageDisposal" value="Burning" class="rounded border-gray-300">
                            <span class="ml-2">Burning</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="garbageDisposal" value="In the river" class="rounded border-gray-300">
                            <span class="ml-2">In the River</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="garbageDisposal" value="Vacant lot" class="rounded border-gray-300">
                            <span class="ml-2">Vacant Lot</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="garbageDisposal" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('garbageDisposal') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="keepsAnimals" value="Keep Animals? *" />
                        <select id="keepsAnimals" wire:model.live="keepsAnimals" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600">
                            <option value="">Select...</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        @error('keepsAnimals') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    @if($keepsAnimals === 'yes')
                    <div>
                        <x-input-label for="animalsKept" value="Types of Animals *" />
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="animalsKept" value="Dog" class="rounded border-gray-300">
                                <span class="ml-2">Dog</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="animalsKept" value="Duck" class="rounded border-gray-300">
                                <span class="ml-2">Duck</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="animalsKept" value="Chicken" class="rounded border-gray-300">
                                <span class="ml-2">Chicken</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="animalsKept" value="Cat" class="rounded border-gray-300">
                                <span class="ml-2">Cat</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="animalsKept" value="Other" class="rounded border-gray-300">
                                <span class="ml-2">Other</span>
                            </label>
                        </div>
                        @error('animalsKept') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>

                <div>
                    <x-input-label for="actionWhenSick" value="Action When Sick *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="actionWhenSick" value="Hospital/Health Center" class="rounded border-gray-300">
                            <span class="ml-2">Hospital/Health Center</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="actionWhenSick" value="Herbal Medicine" class="rounded border-gray-300">
                            <span class="ml-2">Herbal Medicine</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="actionWhenSick" value="Albularyo" class="rounded border-gray-300">
                            <span class="ml-2">Albularyo</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="actionWhenSick" value="Hilot" class="rounded border-gray-300">
                            <span class="ml-2">Hilot</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="actionWhenSick" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('actionWhenSick') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="barangayMedicalSupplies" value="Available Barangay Medical Supplies/Equipment *" />
                    <textarea id="barangayMedicalSupplies" wire:model="barangayMedicalSupplies" placeholder="Ambulance, Health Center, Medical Equipment, etc." rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600"></textarea>
                    @error('barangayMedicalSupplies') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="hasBarangayHealthPrograms" value="Barangay Health Programs? *" />
                        <select id="hasBarangayHealthPrograms" wire:model.live="hasBarangayHealthPrograms" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600">
                            <option value="">Select...</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        @error('hasBarangayHealthPrograms') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="benefitsFromPrograms" value="Household Benefits? *" />
                        <select id="benefitsFromPrograms" wire:model.live="benefitsFromPrograms" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600">
                            <option value="">Select...</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        @error('benefitsFromPrograms') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($benefitsFromPrograms === 'yes')
                <div>
                    <x-input-label for="programsBenefited" value="Programs Benefited From *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="programsBenefited" value="Free Vaccine" class="rounded border-gray-300">
                            <span class="ml-2">Free Vaccine</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="programsBenefited" value="Free Consultation" class="rounded border-gray-300">
                            <span class="ml-2">Free Consultation</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="programsBenefited" value="Pre-natal Check-up" class="rounded border-gray-300">
                            <span class="ml-2">Pre-natal Check-up</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="programsBenefited" value="Check-up" class="rounded border-gray-300">
                            <span class="ml-2">Check-up</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="programsBenefited" value="Free Medicine" class="rounded border-gray-300">
                            <span class="ml-2">Free Medicine</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="programsBenefited" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('programsBenefited') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                <div>
                    <x-input-label for="waterSourceDistance" value="Water Source Distance *" />
                    <select id="waterSourceDistance" wire:model="waterSourceDistance" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600">
                        <option value="">Select...</option>
                        <option value="Just outside">Just Outside</option>
                        <option value="250 meters away">250 Meters Away</option>
                        <option value="No idea">No Idea</option>
                    </select>
                    @error('waterSourceDistance') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

        <!-- Housing and Basic Amenities -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Housing and Basic Amenities</h3>
                <span class="text-xs font-semibold bg-blue-600 text-blue-100 px-3 py-1 rounded-full">Section VI</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="housingType" value="House Type *" />
                    <select id="housingType" wire:model="housingType" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                        <option value="">Select house type...</option>
                        <option value="Wood/Bamboo">Wood/Bamboo</option>
                        <option value="Makeshift">Makeshift</option>
                        <option value="Half Concrete/Wood">Half Concrete/Wood</option>
                        <option value="Nipa/Bamboo">Nipa/Bamboo</option>
                        <option value="All Concrete">All Concrete</option>
                        <option value="Mixed Materials">Mixed Materials</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('housingType') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="tenureStatus" value="Tenure Status *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="tenureStatus" value="Own house/land" class="rounded border-gray-300">
                            <span class="ml-2">Own House/Land</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="tenureStatus" value="Own house/rent land" class="rounded border-gray-300">
                            <span class="ml-2">Own House/Rent Land</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="tenureStatus" value="Rent house" class="rounded border-gray-300">
                            <span class="ml-2">Rent House</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="tenureStatus" value="NGO/Government given" class="rounded border-gray-300">
                            <span class="ml-2">NGO/Government Given</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="tenureStatus" value="Squatter" class="rounded border-gray-300">
                            <span class="ml-2">Squatter</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="tenureStatus" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('tenureStatus') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="hasElectricity" value="Have Electricity? *" />
                        <select id="hasElectricity" wire:model.live="hasElectricity" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <option value="">Select...</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        @error('hasElectricity') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($hasElectricity === 'no')
                <div>
                    <x-input-label for="lightSourceNoPower" value="Light Source Without Electricity *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="lightSourceNoPower" value="Oil lamp" class="rounded border-gray-300">
                            <span class="ml-2">Oil Lamp</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="lightSourceNoPower" value="Candle" class="rounded border-gray-300">
                            <span class="ml-2">Candle</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="lightSourceNoPower" value="Solar lamp" class="rounded border-gray-300">
                            <span class="ml-2">Solar Lamp</span>
                        </label>
                    </div>
                    @error('lightSourceNoPower') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                @if($hasElectricity === 'yes')
                <div>
                    <x-input-label for="appliances" value="Household Appliances & Equipment *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="appliances" value="TV" class="rounded border-gray-300">
                            <span class="ml-2">TV</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="appliances" value="Washing Machine" class="rounded border-gray-300">
                            <span class="ml-2">Washing Machine</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="appliances" value="Refrigerator" class="rounded border-gray-300">
                            <span class="ml-2">Refrigerator</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="appliances" value="Electric Fan" class="rounded border-gray-300">
                            <span class="ml-2">Electric Fan</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="appliances" value="Computer" class="rounded border-gray-300">
                            <span class="ml-2">Computer</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="appliances" value="Gas Range" class="rounded border-gray-300">
                            <span class="ml-2">Gas Range</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="appliances" value="Rice Cooker" class="rounded border-gray-300">
                            <span class="ml-2">Rice Cooker</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="appliances" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('appliances') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
        </div>

        <!-- Recreational Facilities -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Recreational Facilities & Involvement</h3>
                <span class="text-xs font-semibold bg-teal-600 text-teal-100 px-3 py-1 rounded-full">Section VII</span>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <x-input-label for="barangayRecreationalFacilities" value="Barangay Recreational Facilities Available *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="barangayRecreationalFacilities" value="Basketball Court" class="rounded border-gray-300">
                            <span class="ml-2">Basketball Court</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="barangayRecreationalFacilities" value="Volleyball Court" class="rounded border-gray-300">
                            <span class="ml-2">Volleyball Court</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="barangayRecreationalFacilities" value="Tennis Court" class="rounded border-gray-300">
                            <span class="ml-2">Tennis Court</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="barangayRecreationalFacilities" value="Soccer Field" class="rounded border-gray-300">
                            <span class="ml-2">Soccer Field</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="barangayRecreationalFacilities" value="Community Center" class="rounded border-gray-300">
                            <span class="ml-2">Community Center</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="barangayRecreationalFacilities" value="Park" class="rounded border-gray-300">
                            <span class="ml-2">Park</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="barangayRecreationalFacilities" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('barangayRecreationalFacilities') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="useOfFreeTime" value="How They Use Their Free Time *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="useOfFreeTime" value="Sports/Athletics" class="rounded border-gray-300">
                            <span class="ml-2">Sports/Athletics</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="useOfFreeTime" value="Community Services" class="rounded border-gray-300">
                            <span class="ml-2">Community Services</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="useOfFreeTime" value="Arts/Crafts" class="rounded border-gray-300">
                            <span class="ml-2">Arts/Crafts</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="useOfFreeTime" value="Reading" class="rounded border-gray-300">
                            <span class="ml-2">Reading</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="useOfFreeTime" value="Socializing" class="rounded border-gray-300">
                            <span class="ml-2">Socializing</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="useOfFreeTime" value="Entertainment/TV" class="rounded border-gray-300">
                            <span class="ml-2">Entertainment/TV</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="useOfFreeTime" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('useOfFreeTime') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="memberOfOrganization" value="Member of Organization? *" />
                    <select id="memberOfOrganization" wire:model.live="memberOfOrganization" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-600 focus:ring-teal-600">
                        <option value="">Select...</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    @error('memberOfOrganization') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                @if($memberOfOrganization === 'yes')
                <div>
                    <x-input-label for="groupType" value="Type of Organization/Group *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="groupType" value="Civic Organization" class="rounded border-gray-300">
                            <span class="ml-2">Civic Organization</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="groupType" value="Religious Organization" class="rounded border-gray-300">
                            <span class="ml-2">Religious Organization</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="groupType" value="LGU-related Group" class="rounded border-gray-300">
                            <span class="ml-2">LGU-related Group</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="groupType" value="Cultural Group" class="rounded border-gray-300">
                            <span class="ml-2">Cultural Group</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="groupType" value="Sports/Recreational Group" class="rounded border-gray-300">
                            <span class="ml-2">Sports/Recreational Group</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="groupType" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('groupType') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="meetingFrequency" value="How Often Does Group Meet? *" />
                    <select id="meetingFrequency" wire:model="meetingFrequency" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-600 focus:ring-teal-600">
                        <option value="">Select...</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Twice a month">Twice a month</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                    @error('meetingFrequency') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
        </div>

        <!-- Problems and Needs -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Other Needs & Problems</h3>
                <span class="text-xs font-semibold bg-yellow-600 text-yellow-100 px-3 py-1 rounded-full">Section VIII</span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Family Problems -->
                    <div>
                        <x-input-label for="problemsFamily" value="Family-Related Problems *" />
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsFamily" value="Cannot support family needs" class="rounded border-gray-300">
                                <span class="ml-2">Cannot support family needs</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsFamily" value="Separated couple" class="rounded border-gray-300">
                                <span class="ml-2">Separated couple</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsFamily" value="Domestic violence" class="rounded border-gray-300">
                                <span class="ml-2">Domestic violence</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsFamily" value="Poor family relationship" class="rounded border-gray-300">
                                <span class="ml-2">Poor family relationship</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsFamily" value="Other" class="rounded border-gray-300">
                                <span class="ml-2">Other</span>
                            </label>
                        </div>
                        @error('problemsFamily') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Health Problems -->
                    <div>
                        <x-input-label for="problemsHealth" value="Health-Related Problems *" />
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsHealth" value="Sickly children" class="rounded border-gray-300">
                                <span class="ml-2">Sickly children</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsHealth" value="House near dumpsite" class="rounded border-gray-300">
                                <span class="ml-2">House near dumpsite</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsHealth" value="Lack of health education/equipment/personnel" class="rounded border-gray-300">
                                <span class="ml-2">Lack of health education/equipment/personnel</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsHealth" value="Malnourished" class="rounded border-gray-300">
                                <span class="ml-2">Malnourished</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsHealth" value="Disease outbreak" class="rounded border-gray-300">
                                <span class="ml-2">Disease outbreak</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsHealth" value="Other" class="rounded border-gray-300">
                                <span class="ml-2">Other</span>
                            </label>
                        </div>
                        @error('problemsHealth') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Education Problems -->
                    <div>
                        <x-input-label for="problemsEducation" value="Education-Related Problems *" />
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEducation" value="Lack of equipment (Computer, Lab, Library)" class="rounded border-gray-300">
                                <span class="ml-2">Lack of equipment</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEducation" value="Lack of qualified teachers" class="rounded border-gray-300">
                                <span class="ml-2">Lack of qualified teachers</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEducation" value="Far school" class="rounded border-gray-300">
                                <span class="ml-2">Far school/Long distance</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEducation" value="Other" class="rounded border-gray-300">
                                <span class="ml-2">Other</span>
                            </label>
                        </div>
                        @error('problemsEducation') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Employment Problems -->
                    <div>
                        <x-input-label for="problemsEmployment" value="Employment-Related Problems *" />
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEmployment" value="Lack of employment" class="rounded border-gray-300">
                                <span class="ml-2">Lack of employment</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEmployment" value="Lack of skills" class="rounded border-gray-300">
                                <span class="ml-2">Lack of skills</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEmployment" value="No receiving agency" class="rounded border-gray-300">
                                <span class="ml-2">No receiving agency</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEmployment" value="Other" class="rounded border-gray-300">
                                <span class="ml-2">Other</span>
                            </label>
                        </div>
                        @error('problemsEmployment') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Infrastructure Problems -->
                    <div>
                        <x-input-label for="problemsInfrastructure" value="Infrastructure-Related Problems *" />
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsInfrastructure" value="Difficult roads" class="rounded border-gray-300">
                                <span class="ml-2">Difficult roads</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsInfrastructure" value="No irrigation/waiting shed/equipment" class="rounded border-gray-300">
                                <span class="ml-2">No irrigation/waiting shed</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsInfrastructure" value="Lack of classroom" class="rounded border-gray-300">
                                <span class="ml-2">Lack of classroom</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsInfrastructure" value="Other" class="rounded border-gray-300">
                                <span class="ml-2">Other</span>
                            </label>
                        </div>
                        @error('problemsInfrastructure') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Economic Problems -->
                    <div>
                        <x-input-label for="problemsEconomy" value="Economic-Related Problems *" />
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEconomy" value="Lack of buyers" class="rounded border-gray-300">
                                <span class="ml-2">Lack of buyers</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEconomy" value="No capital" class="rounded border-gray-300">
                                <span class="ml-2">No capital</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEconomy" value="No product transport" class="rounded border-gray-300">
                                <span class="ml-2">No product transport</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEconomy" value="No livelihood ideas" class="rounded border-gray-300">
                                <span class="ml-2">No livelihood ideas</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEconomy" value="Many dependents" class="rounded border-gray-300">
                                <span class="ml-2">Many dependents</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="problemsEconomy" value="Other" class="rounded border-gray-300">
                                <span class="ml-2">Other</span>
                            </label>
                        </div>
                        @error('problemsEconomy') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Security Problems -->
                <div>
                    <x-input-label for="problemsSecurity" value="Security-Related Problems *" />
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="problemsSecurity" value="Always noisy" class="rounded border-gray-300">
                            <span class="ml-2">Always noisy</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="problemsSecurity" value="No police assigned" class="rounded border-gray-300">
                            <span class="ml-2">No police assigned</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="problemsSecurity" value="Theft" class="rounded border-gray-300">
                            <span class="ml-2">Theft</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="problemsSecurity" value="Other" class="rounded border-gray-300">
                            <span class="ml-2">Other</span>
                        </label>
                    </div>
                    @error('problemsSecurity') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Summary & Ratings -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-700 to-indigo-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Summary & Service Ratings</h3>
                <span class="text-xs font-semibold bg-indigo-600 text-indigo-100 px-3 py-1 rounded-full">Section IX</span>
            </div>
            <div class="p-6 space-y-4">
                <!-- Barangay Service Ratings -->
                <div class="bg-indigo-50 rounded-lg p-4 mb-6">
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4">Rate Barangay Services (1 = Poor, 5 = Excellent)</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="barangayServiceRatingPolice" value="Law Enforcement/Police *" />
                            <div class="flex gap-2 mt-2">
                                <label class="flex items-center"><input type="radio" name="policeRating" wire:model="barangayServiceRatingPolice" value="1" class="mr-1"> 1</label>
                                <label class="flex items-center"><input type="radio" name="policeRating" wire:model="barangayServiceRatingPolice" value="2" class="mr-1"> 2</label>
                                <label class="flex items-center"><input type="radio" name="policeRating" wire:model="barangayServiceRatingPolice" value="3" class="mr-1"> 3</label>
                                <label class="flex items-center"><input type="radio" name="policeRating" wire:model="barangayServiceRatingPolice" value="4" class="mr-1"> 4</label>
                                <label class="flex items-center"><input type="radio" name="policeRating" wire:model="barangayServiceRatingPolice" value="5" class="mr-1"> 5</label>
                            </div>
                            @error('barangayServiceRatingPolice') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="barangayServiceRatingFire" value="Fire Protection *" />
                            <div class="flex gap-2 mt-2">
                                <label class="flex items-center"><input type="radio" name="fireRating" wire:model="barangayServiceRatingFire" value="1" class="mr-1"> 1</label>
                                <label class="flex items-center"><input type="radio" name="fireRating" wire:model="barangayServiceRatingFire" value="2" class="mr-1"> 2</label>
                                <label class="flex items-center"><input type="radio" name="fireRating" wire:model="barangayServiceRatingFire" value="3" class="mr-1"> 3</label>
                                <label class="flex items-center"><input type="radio" name="fireRating" wire:model="barangayServiceRatingFire" value="4" class="mr-1"> 4</label>
                                <label class="flex items-center"><input type="radio" name="fireRating" wire:model="barangayServiceRatingFire" value="5" class="mr-1"> 5</label>
                            </div>
                            @error('barangayServiceRatingFire') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="barangayServiceRatingBNS" value="BNS (Barangay Nutrition Scheme) *" />
                            <div class="flex gap-2 mt-2">
                                <label class="flex items-center"><input type="radio" name="bnsRating" wire:model="barangayServiceRatingBNS" value="1" class="mr-1"> 1</label>
                                <label class="flex items-center"><input type="radio" name="bnsRating" wire:model="barangayServiceRatingBNS" value="2" class="mr-1"> 2</label>
                                <label class="flex items-center"><input type="radio" name="bnsRating" wire:model="barangayServiceRatingBNS" value="3" class="mr-1"> 3</label>
                                <label class="flex items-center"><input type="radio" name="bnsRating" wire:model="barangayServiceRatingBNS" value="4" class="mr-1"> 4</label>
                                <label class="flex items-center"><input type="radio" name="bnsRating" wire:model="barangayServiceRatingBNS" value="5" class="mr-1"> 5</label>
                            </div>
                            @error('barangayServiceRatingBNS') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="barangayServiceRatingWater" value="Water Supply System *" />
                            <div class="flex gap-2 mt-2">
                                <label class="flex items-center"><input type="radio" name="waterRating" wire:model="barangayServiceRatingWater" value="1" class="mr-1"> 1</label>
                                <label class="flex items-center"><input type="radio" name="waterRating" wire:model="barangayServiceRatingWater" value="2" class="mr-1"> 2</label>
                                <label class="flex items-center"><input type="radio" name="waterRating" wire:model="barangayServiceRatingWater" value="3" class="mr-1"> 3</label>
                                <label class="flex items-center"><input type="radio" name="waterRating" wire:model="barangayServiceRatingWater" value="4" class="mr-1"> 4</label>
                                <label class="flex items-center"><input type="radio" name="waterRating" wire:model="barangayServiceRatingWater" value="5" class="mr-1"> 5</label>
                            </div>
                            @error('barangayServiceRatingWater') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="barangayServiceRatingRoads" value="Roads & Bridges *" />
                            <div class="flex gap-2 mt-2">
                                <label class="flex items-center"><input type="radio" name="roadsRating" wire:model="barangayServiceRatingRoads" value="1" class="mr-1"> 1</label>
                                <label class="flex items-center"><input type="radio" name="roadsRating" wire:model="barangayServiceRatingRoads" value="2" class="mr-1"> 2</label>
                                <label class="flex items-center"><input type="radio" name="roadsRating" wire:model="barangayServiceRatingRoads" value="3" class="mr-1"> 3</label>
                                <label class="flex items-center"><input type="radio" name="roadsRating" wire:model="barangayServiceRatingRoads" value="4" class="mr-1"> 4</label>
                                <label class="flex items-center"><input type="radio" name="roadsRating" wire:model="barangayServiceRatingRoads" value="5" class="mr-1"> 5</label>
                            </div>
                            @error('barangayServiceRatingRoads') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="barangayServiceRatingClinic" value="Health Clinic/Center *" />
                            <div class="flex gap-2 mt-2">
                                <label class="flex items-center"><input type="radio" name="clinicRating" wire:model="barangayServiceRatingClinic" value="1" class="mr-1"> 1</label>
                                <label class="flex items-center"><input type="radio" name="clinicRating" wire:model="barangayServiceRatingClinic" value="2" class="mr-1"> 2</label>
                                <label class="flex items-center"><input type="radio" name="clinicRating" wire:model="barangayServiceRatingClinic" value="3" class="mr-1"> 3</label>
                                <label class="flex items-center"><input type="radio" name="clinicRating" wire:model="barangayServiceRatingClinic" value="4" class="mr-1"> 4</label>
                                <label class="flex items-center"><input type="radio" name="clinicRating" wire:model="barangayServiceRatingClinic" value="5" class="mr-1"> 5</label>
                            </div>
                            @error('barangayServiceRatingClinic') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="barangayServiceRatingMarket" value="Market/Trading Area *" />
                            <div class="flex gap-2 mt-2">
                                <label class="flex items-center"><input type="radio" name="marketRating" wire:model="barangayServiceRatingMarket" value="1" class="mr-1"> 1</label>
                                <label class="flex items-center"><input type="radio" name="marketRating" wire:model="barangayServiceRatingMarket" value="2" class="mr-1"> 2</label>
                                <label class="flex items-center"><input type="radio" name="marketRating" wire:model="barangayServiceRatingMarket" value="3" class="mr-1"> 3</label>
                                <label class="flex items-center"><input type="radio" name="marketRating" wire:model="barangayServiceRatingMarket" value="4" class="mr-1"> 4</label>
                                <label class="flex items-center"><input type="radio" name="marketRating" wire:model="barangayServiceRatingMarket" value="5" class="mr-1"> 5</label>
                            </div>
                            @error('barangayServiceRatingMarket') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="barangayServiceRatingCommunityCenter" value="Community Center *" />
                            <div class="flex gap-2 mt-2">
                                <label class="flex items-center"><input type="radio" name="commCenterRating" wire:model="barangayServiceRatingCommunityCenter" value="1" class="mr-1"> 1</label>
                                <label class="flex items-center"><input type="radio" name="commCenterRating" wire:model="barangayServiceRatingCommunityCenter" value="2" class="mr-1"> 2</label>
                                <label class="flex items-center"><input type="radio" name="commCenterRating" wire:model="barangayServiceRatingCommunityCenter" value="3" class="mr-1"> 3</label>
                                <label class="flex items-center"><input type="radio" name="commCenterRating" wire:model="barangayServiceRatingCommunityCenter" value="4" class="mr-1"> 4</label>
                                <label class="flex items-center"><input type="radio" name="commCenterRating" wire:model="barangayServiceRatingCommunityCenter" value="5" class="mr-1"> 5</label>
                            </div>
                            @error('barangayServiceRatingCommunityCenter') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label for="barangayServiceRatingLights" value="Street Lights *" />
                            <div class="flex gap-2 mt-2">
                                <label class="flex items-center"><input type="radio" name="lightsRating" wire:model="barangayServiceRatingLights" value="1" class="mr-1"> 1</label>
                                <label class="flex items-center"><input type="radio" name="lightsRating" wire:model="barangayServiceRatingLights" value="2" class="mr-1"> 2</label>
                                <label class="flex items-center"><input type="radio" name="lightsRating" wire:model="barangayServiceRatingLights" value="3" class="mr-1"> 3</label>
                                <label class="flex items-center"><input type="radio" name="lightsRating" wire:model="barangayServiceRatingLights" value="4" class="mr-1"> 4</label>
                                <label class="flex items-center"><input type="radio" name="lightsRating" wire:model="barangayServiceRatingLights" value="5" class="mr-1"> 5</label>
                            </div>
                            @error('barangayServiceRatingLights') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- General Feedback -->
                <div>
                    <x-input-label for="generalFeedback" value="Comments/Feedback for Programs & Livelihood *" />
                    <textarea id="generalFeedback" wire:model="generalFeedback" placeholder="Provide any comments or suggestions for improvement" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600"></textarea>
                    @error('generalFeedback') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Training Availability -->
                <div>
                    <x-input-label for="availableForTraining" value="Available for Training? *" />
                    <select id="availableForTraining" wire:model.live="availableForTraining" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                        <option value="">Select...</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    @error('availableForTraining') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                @if($availableForTraining === 'no')
                <div>
                    <x-input-label for="reasonNotAvailable" value="Reason Not Available for Training *" />
                    <textarea id="reasonNotAvailable" wire:model="reasonNotAvailable" placeholder="Explain reason for unavailability" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600"></textarea>
                    @error('reasonNotAvailable') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="window.history.back()" class="px-6 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition" {{ $isSubmitting ? 'disabled' : '' }}>
                Back
            </button>
            <button type="button"
                    wire:click="confirmSubmit"
                    class="px-6 py-2 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition flex items-center gap-2 {{ $isSubmitting ? 'opacity-75 cursor-not-allowed' : '' }}"
                    {{ $isSubmitting ? 'disabled' : '' }}>
                @if($isSubmitting)
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Submitting...
                @else
                    Submit Assessment
                @endif
            </button>
        </div>
    </form>
</div>