<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('secretary.extension-programs')); ?>" class="text-blue-900 hover:text-blue-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Program Management</h2>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-2 px-3">
        <div class="w-full">
            <div class="min-h-screen bg-gradient-to-br from-white to-gray-50 px-8 py-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Program Tabs Navigation -->
                    <div class="bg-white shadow-md rounded-t-lg border-b border-gray-200 mb-0">
                        <div class="flex justify-between items-center">
                            <div class="flex overflow-x-auto flex-1">
                                <a href="#details" class="tab-link px-6 py-4 border-b-2 border-blue-900 font-semibold text-blue-900 bg-blue-50" data-tab="details">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Program Details
                                </a>
                                <a href="#plan" class="tab-link px-6 py-4 border-b-2 border-transparent text-gray-700 hover:text-gray-900 font-semibold hover:border-gray-300" data-tab="plan">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Plan
                                </a>
                                <a href="#assessment" class="tab-link px-6 py-4 border-b-2 border-transparent text-gray-700 hover:text-gray-900 font-semibold hover:border-gray-300" data-tab="assessment">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Assessment
                                </a>
                                <a href="#activities" class="tab-link px-6 py-4 border-b-2 border-transparent text-gray-700 hover:text-gray-900 font-semibold hover:border-gray-300" data-tab="activities">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Activities
                                </a>
                            </div>
                            <!-- Statistics Button -->
                            <button id="statisticsBtn" class="px-6 py-4 border-b-2 border-transparent text-gray-700 hover:text-gray-900 font-semibold hover:border-gray-300 whitespace-nowrap border-b-2 border-gray-200">
                                Statistics
                            </button>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="bg-white rounded-b-lg shadow-md overflow-hidden">
                        <!-- Details Tab -->
                        <div id="details" class="tab-content block p-8">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('ManageExtensionProgramsDetail', ['program_id' => $program_id]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-533317169-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
                        </div>

                        <!-- Plan Tab -->
                        <div id="plan" class="tab-content hidden p-8">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('ManageProgramLogicModel', ['program_id' => $program_id]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-533317169-1', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
                        </div>

                        <!-- Assessment Tab -->
                        <div id="assessment" class="tab-content hidden p-8">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('ManageProgramBaseline', ['program_id' => $program_id]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-533317169-2', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
                        </div>

                        <!-- Activities Tab -->
                        <div id="activities" class="tab-content hidden p-8">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('ManageProgramActivities', ['program_id' => $program_id]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-533317169-3', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
                        </div>
                    </div>

                    <!-- Statistics Modal -->
                    <div id="statisticsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-lg shadow-2xl w-full max-w-6xl max-h-[90vh] flex flex-col">
                            <!-- Modal Header -->
                            <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-8 py-6 flex justify-between items-center flex-shrink-0">
                                <h2 class="text-2xl font-bold">Program Statistics</h2>
                                <button id="closeStatisticsModal" class="text-white hover:text-blue-200 text-2xl font-semibold">
                                    ✕
                                </button>
                            </div>
                            
                            <!-- Modal Content - Scrollable Area -->
                            <div class="overflow-y-auto flex-1 p-8">
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('ManageProgramOutputs', ['program_id' => $program_id]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-533317169-4', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.tab-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const tabId = this.getAttribute('data-tab');
                
                // Hide all tabs
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Remove active styles from all links
                document.querySelectorAll('.tab-link').forEach(l => {
                    l.classList.remove('border-blue-900', 'font-semibold', 'text-blue-900', 'bg-blue-50');
                    l.classList.add('border-transparent', 'text-gray-700', 'hover:text-gray-900');
                });
                
                // Show active tab
                document.getElementById(tabId).classList.remove('hidden');
                
                // Add active styles to clicked link
                this.classList.add('border-blue-900', 'font-semibold', 'text-blue-900', 'bg-blue-50');
                this.classList.remove('border-transparent', 'text-gray-700', 'hover:text-gray-900');
            });
        });

        // Statistics Modal Functionality
        const statisticsBtn = document.getElementById('statisticsBtn');
        const statisticsModal = document.getElementById('statisticsModal');
        const closeStatisticsModal = document.getElementById('closeStatisticsModal');

        statisticsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            statisticsModal.classList.remove('hidden');
        });

        closeStatisticsModal.addEventListener('click', function() {
            statisticsModal.classList.add('hidden');
        });

        // Close modal when clicking outside
        statisticsModal.addEventListener('click', function(e) {
            if (e.target === statisticsModal) {
                statisticsModal.classList.add('hidden');
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !statisticsModal.classList.contains('hidden')) {
                statisticsModal.classList.add('hidden');
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Nikko\Desktop\CAPSTONE\SmartCEMES_FINAL\resources\views/programs/manage.blade.php ENDPATH**/ ?>