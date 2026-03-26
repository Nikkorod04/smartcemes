<script>
    // Placeholder - function is now in app.js
</script>

<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 my-4" <?php if($summary): ?> data-community-id="<?php echo e($summary->community_id); ?>" <?php endif; ?>>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary): ?>
        <div class="flex items-start justify-between mb-4">
            <h3 class="font-bold text-blue-900">Community Assessment Summary</h3>
            <span class="text-xs bg-blue-900 text-white px-2 py-1 rounded">
                <?php echo e($summary->total_assessments); ?> responses
            </span>
        </div>

        <!-- SECTION 1: DEMOGRAPHICS -->
        <div class="bg-white rounded p-4 border border-blue-100 mb-4">
            <div class="text-lg font-semibold text-blue-900 mb-3 pb-2 border-b border-gray-300">
                📊 Demographics
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div class="bg-blue-50 rounded p-3">
                    <div class="text-xs text-gray-600">Average Age</div>
                    <div class="text-xl font-bold text-blue-900">
                        <?php echo e($summary->avg_age ? round($summary->avg_age) : '—'); ?>

                    </div>
                    <div class="text-xs text-gray-500">years</div>
                </div>

                <div class="bg-blue-50 rounded p-3">
                    <div class="text-xs text-gray-600">Most Common Sex</div>
                    <div class="text-sm font-semibold text-blue-900">
                        <?php echo e($summary->most_common_sex ?? '—'); ?>

                    </div>
                </div>

                <div class="bg-blue-50 rounded p-3">
                    <div class="text-xs text-gray-600">Civil Status</div>
                    <div class="text-sm font-semibold text-blue-900">
                        <?php echo e($summary->most_common_civil_status ?? '—'); ?>

                    </div>
                </div>

                <div class="bg-blue-50 rounded p-3">
                    <div class="text-xs text-gray-600">Religion</div>
                    <div class="text-sm font-semibold text-blue-900">
                        <?php echo e($summary->most_common_religion ?? '—'); ?>

                    </div>
                </div>

                <div class="bg-blue-50 rounded p-3">
                    <div class="text-xs text-gray-600">Education</div>
                    <div class="text-sm font-semibold text-blue-900 truncate">
                        <?php echo e($summary->most_common_educational_attainment ?? '—'); ?>

                    </div>
                </div>

                <div class="bg-blue-50 rounded p-3">
                    <div class="text-xs text-gray-600">Primary Livelihood</div>
                    <div class="text-sm font-semibold text-blue-900 truncate">
                        <?php echo e($summary->most_common_livelihood ?? '—'); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: ECONOMIC & TRAINING -->
        <div class="bg-white rounded p-4 border border-blue-100 mb-4">
            <div class="text-lg font-semibold text-blue-900 mb-3 pb-2 border-b border-gray-300">
                💼 Economic & Training
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-purple-50 rounded p-3">
                    <div class="text-xs text-gray-600">Interested in Training</div>
                    <div class="text-xl font-bold text-purple-900">
                        <?php echo e($summary->percent_interested_in_training ?? '—'); ?>%
                    </div>
                </div>

                <div class="bg-purple-50 rounded p-3">
                    <div class="text-xs text-gray-600">Available for Training</div>
                    <div class="text-xl font-bold text-purple-900">
                        <?php echo e($summary->percent_available_for_training ?? '—'); ?>%
                    </div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->common_desired_trainings): ?>
                <div class="mb-3">
                    <div class="text-sm font-semibold text-gray-800 mb-2">Desired Trainings:</div>
                    <div class="flex flex-wrap gap-2">
                        <?php
                            $trainings = is_string($summary->common_desired_trainings) ? json_decode($summary->common_desired_trainings, true) : (array)$summary->common_desired_trainings;
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded"><?php echo e($training); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- SECTION 3: EDUCATION -->
        <div class="bg-white rounded p-4 border border-blue-100 mb-4">
            <div class="text-lg font-semibold text-blue-900 mb-3 pb-2 border-b border-gray-300">
                🎓 Education
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-green-50 rounded p-3">
                    <div class="text-xs text-gray-600">Household Member Studying</div>
                    <div class="text-xl font-bold text-green-900">
                        <?php echo e($summary->percent_household_member_studying ?? '—'); ?>%
                    </div>
                </div>

                <div class="bg-green-50 rounded p-3">
                    <div class="text-xs text-gray-600">Want to Continue Studies</div>
                    <div class="text-xl font-bold text-green-900">
                        <?php echo e($summary->percent_interested_in_continuing_studies ?? '—'); ?>%
                    </div>
                </div>

                <div class="bg-green-50 rounded p-3">
                    <div class="text-xs text-gray-600">Preferred Time</div>
                    <div class="text-sm font-semibold text-green-900">
                        <?php echo e($summary->most_common_preferred_time ?? '—'); ?>

                    </div>
                </div>

                <div class="bg-green-50 rounded p-3">
                    <div class="text-xs text-gray-600">Preferred Days</div>
                    <div class="text-sm font-semibold text-green-900">
                        <?php echo e($summary->most_common_preferred_days ?? '—'); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 4: HEALTH & SANITATION -->
        <div class="bg-white rounded p-4 border border-blue-100 mb-4">
            <div class="text-lg font-semibold text-blue-900 mb-3 pb-2 border-b border-gray-300">
                🏥 Health & Sanitation
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                <div class="bg-pink-50 rounded p-3">
                    <div class="text-xs text-gray-600">Water Source</div>
                    <div class="text-sm font-semibold text-pink-900">
                        <?php echo e($summary->most_common_water_source ?? '—'); ?>

                    </div>
                </div>

                <div class="bg-pink-50 rounded p-3">
                    <div class="text-xs text-gray-600">Distance to Water</div>
                    <div class="text-sm font-semibold text-pink-900">
                        <?php echo e($summary->most_common_water_source_distance ?? '—'); ?>

                    </div>
                </div>

                <div class="bg-pink-50 rounded p-3">
                    <div class="text-xs text-gray-600">Has Own Toilet</div>
                    <div class="text-xl font-bold text-pink-900">
                        <?php echo e($summary->percent_has_own_toilet ?? '—'); ?>%
                    </div>
                </div>

                <div class="bg-pink-50 rounded p-3">
                    <div class="text-xs text-gray-600">Has Electricity</div>
                    <div class="text-xl font-bold text-pink-900">
                        <?php echo e($summary->percent_has_electricity ?? '—'); ?>%
                    </div>
                </div>

                <div class="bg-pink-50 rounded p-3">
                    <div class="text-xs text-gray-600">Keeps Animals</div>
                    <div class="text-xl font-bold text-pink-900">
                        <?php echo e($summary->percent_keeps_animals ?? '—'); ?>%
                    </div>
                </div>

                <div class="bg-pink-50 rounded p-3">
                    <div class="text-xs text-gray-600">Benefits from Health Programs</div>
                    <div class="text-xl font-bold text-pink-900">
                        <?php echo e($summary->percent_benefits_from_health_programs ?? '—'); ?>%
                    </div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->common_health_programs): ?>
                <div class="mb-3">
                    <div class="text-sm font-semibold text-gray-800 mb-2">Available Health Programs:</div>
                    <div class="flex flex-wrap gap-2">
                        <?php
                            $programs = is_string($summary->common_health_programs) ? json_decode($summary->common_health_programs, true) : (array)$summary->common_health_programs;
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <span class="text-xs bg-pink-100 text-pink-800 px-2 py-1 rounded"><?php echo e($program); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- SECTION 5: HOUSING & AMENITIES -->
        <div class="bg-white rounded p-4 border border-blue-100 mb-4">
            <div class="text-lg font-semibold text-blue-900 mb-3 pb-2 border-b border-gray-300">
                🏠 Housing & Amenities
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                <div class="bg-amber-50 rounded p-3">
                    <div class="text-xs text-gray-600">Housing Type</div>
                    <div class="text-sm font-semibold text-amber-900">
                        <?php echo e($summary->most_common_housing_type ?? '—'); ?>

                    </div>
                </div>

                <div class="bg-amber-50 rounded p-3">
                    <div class="text-xs text-gray-600">Tenure Status</div>
                    <div class="text-sm font-semibold text-amber-900">
                        <?php echo e($summary->most_common_tenure_status ?? '—'); ?>

                    </div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->common_appliances): ?>
                <div>
                    <div class="text-sm font-semibold text-gray-800 mb-2">Common Appliances:</div>
                    <div class="flex flex-wrap gap-2">
                        <?php
                            $appliances = is_string($summary->common_appliances) ? json_decode($summary->common_appliances, true) : (array)$summary->common_appliances;
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $appliances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appliance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded"><?php echo e($appliance); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- SECTION 6: COMMUNITY ORGANIZATION -->
        <div class="bg-white rounded p-4 border border-blue-100 mb-4">
            <div class="text-lg font-semibold text-blue-900 mb-3 pb-2 border-b border-gray-300">
                👥 Community Organization
            </div>
            <div class="grid grid-cols-2 md:grid-cols-2 gap-3">
                <div class="bg-indigo-50 rounded p-3">
                    <div class="text-xs text-gray-600">Organization Members</div>
                    <div class="text-xl font-bold text-indigo-900">
                        <?php echo e($summary->percent_member_of_organization ?? '—'); ?>%
                    </div>
                </div>

                <div class="bg-indigo-50 rounded p-3">
                    <div class="text-xs text-gray-600">Meeting Frequency</div>
                    <div class="text-sm font-semibold text-indigo-900">
                        <?php echo e($summary->most_common_meeting_frequency ?? '—'); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 7: SERVICE SATISFACTION BREAKDOWN -->
        <div class="bg-white rounded p-4 border border-blue-100 mb-4">
            <div class="text-lg font-semibold text-blue-900 mb-3 pb-2 border-b border-gray-300">
                ⭐ Service Satisfaction Ratings (out of 5)
            </div>
            
            <!-- Overall Rating -->
            <div class="mb-4 pb-4 border-b border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-medium text-gray-700 text-base">Overall Satisfaction</span>
                    <span class="text-2xl font-bold text-blue-900"><?php echo e($summary->overall_service_satisfaction ? round($summary->overall_service_satisfaction, 1) : '—'); ?>/5</span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->overall_service_satisfaction): ?>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div 
                            class="bg-blue-900 h-3 rounded-full" 
                            style="width: <?php echo e(($summary->overall_service_satisfaction / 5) * 100); ?>%"
                        ></div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Individual Service Ratings -->
            <?php
                $services = [
                    ['label' => 'Police Service', 'rating' => $summary->avg_service_rating_police, 'color' => 'bg-blue-600'],
                    ['label' => 'Fire Service', 'rating' => $summary->avg_service_rating_fire, 'color' => 'bg-red-600'],
                    ['label' => 'BNS (Barangay Nutrition Scheme)', 'rating' => $summary->avg_service_rating_bns, 'color' => 'bg-green-600'],
                    ['label' => 'Water Supply', 'rating' => $summary->avg_service_rating_water, 'color' => 'bg-cyan-600'],
                    ['label' => 'Roads & Infrastructure', 'rating' => $summary->avg_service_rating_roads, 'color' => 'bg-gray-600'],
                    ['label' => 'Clinic/Health Center', 'rating' => $summary->avg_service_rating_clinic, 'color' => 'bg-pink-600'],
                    ['label' => 'Market/Commercial', 'rating' => $summary->avg_service_rating_market, 'color' => 'bg-yellow-600'],
                    ['label' => 'Community Center', 'rating' => $summary->avg_service_rating_community_center, 'color' => 'bg-purple-600'],
                    ['label' => 'Street Lights', 'rating' => $summary->avg_service_rating_lights, 'color' => 'bg-orange-600'],
                ];
            ?>

            <div class="space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service['rating']): ?>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-700"><?php echo e($service['label']); ?></span>
                                <span class="text-sm font-semibold text-gray-800"><?php echo e(round($service['rating'], 1)); ?>/5</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div 
                                    class="<?php echo e($service['color']); ?> h-2 rounded-full" 
                                    style="width: <?php echo e(($service['rating'] / 5) * 100); ?>%"
                                ></div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>

        <!-- SECTION 8: TOP PROBLEMS (WITH PIE CHARTS) -->
        <div class="bg-white rounded p-4 border border-blue-100 mb-4">
            <div class="text-lg font-semibold text-blue-900 mb-4 pb-2 border-b border-gray-300">
                ⚠️ Community Problems & Needs
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <!-- Top Family Problems -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->top_family_problems): ?>
                    <div class="bg-red-50 rounded p-4 border border-red-100">
                        <div class="text-sm font-semibold text-red-900 mb-4">Top Family Problems</div>
                        <?php
                            $problems = is_string($summary->top_family_problems) ? json_decode($summary->top_family_problems, true) : (array)$summary->top_family_problems;
                            $chartId = 'familyChart-' . uniqid();
                        ?>
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="<?php echo e($chartId); ?>" width="400" height="250" data-chart-config="<?php echo e(base64_encode(json_encode(['type' => 'doughnut', 'data' => ['labels' => array_map(fn($p) => is_array($p) ? $p['problem'] : $p->problem, $problems), 'datasets' => [['data' => array_map(fn($p) => is_array($p) ? $p['frequency'] : $p->frequency, $problems), 'backgroundColor' => ['#dc2626', '#f87171', '#fca5a5', '#fecaca', '#fee2e2'], 'borderColor' => '#fff', 'borderWidth' => 2]]], 'options' => ['responsive' => true, 'maintainAspectRatio' => false, 'plugins' => ['legend' => ['position' => 'bottom', 'labels' => ['font' => ['size' => 11], 'padding' => 10]]]]]))); ?>"></canvas>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Top Health Problems -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->top_health_problems): ?>
                    <div class="bg-pink-50 rounded p-4 border border-pink-100">
                        <div class="text-sm font-semibold text-pink-900 mb-4">Top Health Problems</div>
                        <?php
                            $problems = is_string($summary->top_health_problems) ? json_decode($summary->top_health_problems, true) : (array)$summary->top_health_problems;
                            $chartId = 'healthChart-' . uniqid();
                        ?>
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="<?php echo e($chartId); ?>" width="400" height="250" data-chart-config="<?php echo e(base64_encode(json_encode(['type' => 'doughnut', 'data' => ['labels' => array_map(fn($p) => is_array($p) ? $p['problem'] : $p->problem, $problems), 'datasets' => [['data' => array_map(fn($p) => is_array($p) ? $p['frequency'] : $p->frequency, $problems), 'backgroundColor' => ['#ec4899', '#f472b6', '#f9a8d4', '#fbcfe8', '#fce7f3'], 'borderColor' => '#fff', 'borderWidth' => 2]]], 'options' => ['responsive' => true, 'maintainAspectRatio' => false, 'plugins' => ['legend' => ['position' => 'bottom', 'labels' => ['font' => ['size' => 11], 'padding' => 10]]]]]))); ?>"></canvas>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <!-- Top Education Problems -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->top_education_problems): ?>
                    <div class="bg-green-50 rounded p-4 border border-green-100">
                        <div class="text-sm font-semibold text-green-900 mb-4">Top Education Problems</div>
                        <?php
                            $problems = is_string($summary->top_education_problems) ? json_decode($summary->top_education_problems, true) : (array)$summary->top_education_problems;
                            $chartId = 'educationChart-' . uniqid();
                        ?>
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="<?php echo e($chartId); ?>" width="400" height="250" data-chart-config="<?php echo e(base64_encode(json_encode(['type' => 'doughnut', 'data' => ['labels' => array_map(fn($p) => is_array($p) ? $p['problem'] : $p->problem, $problems), 'datasets' => [['data' => array_map(fn($p) => is_array($p) ? $p['frequency'] : $p->frequency, $problems), 'backgroundColor' => ['#16a34a', '#4ade80', '#86efac', '#bbf7d0', '#dcfce7'], 'borderColor' => '#fff', 'borderWidth' => 2]]], 'options' => ['responsive' => true, 'maintainAspectRatio' => false, 'plugins' => ['legend' => ['position' => 'bottom', 'labels' => ['font' => ['size' => 11], 'padding' => 10]]]]]))); ?>"></canvas>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Top Employment Problems -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->top_employment_problems): ?>
                    <div class="bg-orange-50 rounded p-4 border border-orange-100">
                        <div class="text-sm font-semibold text-orange-900 mb-4">Top Employment Problems</div>
                        <?php
                            $problems = is_string($summary->top_employment_problems) ? json_decode($summary->top_employment_problems, true) : (array)$summary->top_employment_problems;
                            $chartId = 'employmentChart-' . uniqid();
                        ?>
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="<?php echo e($chartId); ?>" width="400" height="250" data-chart-config="<?php echo e(base64_encode(json_encode(['type' => 'doughnut', 'data' => ['labels' => array_map(fn($p) => is_array($p) ? $p['problem'] : $p->problem, $problems), 'datasets' => [['data' => array_map(fn($p) => is_array($p) ? $p['frequency'] : $p->frequency, $problems), 'backgroundColor' => ['#ea580c', '#fb923c', '#fdba74', '#fed7aa', '#ffedd5'], 'borderColor' => '#fff', 'borderWidth' => 2]]], 'options' => ['responsive' => true, 'maintainAspectRatio' => false, 'plugins' => ['legend' => ['position' => 'bottom', 'labels' => ['font' => ['size' => 11], 'padding' => 10]]]]]))); ?>"></canvas>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Top Infrastructure Problems -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->top_infrastructure_problems): ?>
                    <div class="bg-yellow-50 rounded p-4 border border-yellow-100">
                        <div class="text-sm font-semibold text-yellow-900 mb-4">Top Infrastructure Problems</div>
                        <?php
                            $problems = is_string($summary->top_infrastructure_problems) ? json_decode($summary->top_infrastructure_problems, true) : (array)$summary->top_infrastructure_problems;
                            $chartId = 'infrastructureChart-' . uniqid();
                        ?>
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="<?php echo e($chartId); ?>" width="400" height="250" data-chart-config="<?php echo e(base64_encode(json_encode(['type' => 'doughnut', 'data' => ['labels' => array_map(fn($p) => is_array($p) ? $p['problem'] : $p->problem, $problems), 'datasets' => [['data' => array_map(fn($p) => is_array($p) ? $p['frequency'] : $p->frequency, $problems), 'backgroundColor' => ['#ca8a04', '#eab308', '#facc15', '#fde047', '#fef3c7'], 'borderColor' => '#fff', 'borderWidth' => 2]]], 'options' => ['responsive' => true, 'maintainAspectRatio' => false, 'plugins' => ['legend' => ['position' => 'bottom', 'labels' => ['font' => ['size' => 11], 'padding' => 10]]]]]))); ?>"></canvas>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Top Economy Problems -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->top_economy_problems): ?>
                    <div class="bg-blue-50 rounded p-4 border border-blue-100">
                        <div class="text-sm font-semibold text-blue-900 mb-4">Top Economy Problems</div>
                        <?php
                            $problems = is_string($summary->top_economy_problems) ? json_decode($summary->top_economy_problems, true) : (array)$summary->top_economy_problems;
                            $chartId = 'economyChart-' . uniqid();
                        ?>
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="<?php echo e($chartId); ?>" width="400" height="250" data-chart-config="<?php echo e(base64_encode(json_encode(['type' => 'doughnut', 'data' => ['labels' => array_map(fn($p) => is_array($p) ? $p['problem'] : $p->problem, $problems), 'datasets' => [['data' => array_map(fn($p) => is_array($p) ? $p['frequency'] : $p->frequency, $problems), 'backgroundColor' => ['#1e40af', '#3b82f6', '#60a5fa', '#93c5fd', '#dbeafe'], 'borderColor' => '#fff', 'borderWidth' => 2]]], 'options' => ['responsive' => true, 'maintainAspectRatio' => false, 'plugins' => ['legend' => ['position' => 'bottom', 'labels' => ['font' => ['size' => 11], 'padding' => 10]]]]]))); ?>"></canvas>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Top Security Problems -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->top_security_problems): ?>
                    <div class="bg-red-50 rounded p-4 border border-red-100">
                        <div class="text-sm font-semibold text-red-900 mb-4">Top Security Problems</div>
                        <?php
                            $problems = is_string($summary->top_security_problems) ? json_decode($summary->top_security_problems, true) : (array)$summary->top_security_problems;
                            $chartId = 'securityChart-' . uniqid();
                        ?>
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="<?php echo e($chartId); ?>" width="400" height="250" data-chart-config="<?php echo e(base64_encode(json_encode(['type' => 'doughnut', 'data' => ['labels' => array_map(fn($p) => is_array($p) ? $p['problem'] : $p->problem, $problems), 'datasets' => [['data' => array_map(fn($p) => is_array($p) ? $p['frequency'] : $p->frequency, $problems), 'backgroundColor' => ['#991b1b', '#dc2626', '#ef4444', '#f87171', '#fecaca'], 'borderColor' => '#fff', 'borderWidth' => 2]]], 'options' => ['responsive' => true, 'maintainAspectRatio' => false, 'plugins' => ['legend' => ['position' => 'bottom', 'labels' => ['font' => ['size' => 11], 'padding' => 10]]]]]))); ?>"></canvas>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- SECTION 9: KEY FEEDBACK THEMES -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->key_feedback_themes): ?>
            <div class="bg-white rounded p-4 border border-blue-100 mb-4">
                <div class="text-lg font-semibold text-blue-900 mb-3 pb-2 border-b border-gray-300">
                    💡 Key Feedback Themes
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php
                        $themes = is_string($summary->key_feedback_themes) ? json_decode($summary->key_feedback_themes, true) : (array)$summary->key_feedback_themes;
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <span class="text-sm bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium"><?php echo e($theme); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- SECTION 10: SUMMARY NOTES -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->summary_notes): ?>
            <div class="bg-white rounded p-4 border border-blue-100 mb-4">
                <div class="text-lg font-semibold text-blue-900 mb-3 pb-2 border-b border-gray-300">
                    📝 Assessment Notes
                </div>
                <p class="text-sm text-gray-700 leading-relaxed"><?php echo e($summary->summary_notes); ?></p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="mt-4 text-xs text-gray-500 text-right">
            Last calculated: <?php echo e($summary->last_calculated_at?->diffForHumans()); ?>

        </div>

        <!-- SECTION 11: AI ANALYSIS & INSIGHTS -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded p-4 border border-purple-200 mb-4 mt-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🤖</span>
                    <h3 class="text-lg font-semibold text-purple-900">AI-Powered Analysis</h3>
                </div>
                <span class="text-xs bg-purple-200 text-purple-800 px-2 py-1 rounded-full">Beta</span>
            </div>
            
            <div id="aiSummaryContainer" class="space-y-3" x-data="assessmentSummary()" data-community-id="<?php echo e($summary->community_id); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($summary->ai_summary && $summary->ai_summary_generated_at): ?>
                    <div class="bg-white rounded p-3 border border-purple-100">
                        <?php echo $summary->ai_summary; ?>

                        <div class="text-xs text-gray-500 mt-3 pt-3 border-t border-gray-200">
                            Generated: <?php echo e($summary->ai_summary_generated_at->format('M d, Y H:i')); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded p-4 border border-purple-100 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 mb-3">
                            <svg class="w-6 h-6 text-purple-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700">Analyzing assessment data...</p>
                        <p class="text-xs text-gray-500 mt-1">This may take a few moments</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center text-gray-600 py-8">
            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-sm">No assessment summary yet</p>
            <p class="text-xs text-gray-500 mt-1">Community needs assessments will generate a summary automatically</p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\Nikko\Desktop\CAPSTONE\SmartCEMES_FINAL\resources\views/components/community-assessment-summary.blade.php ENDPATH**/ ?>