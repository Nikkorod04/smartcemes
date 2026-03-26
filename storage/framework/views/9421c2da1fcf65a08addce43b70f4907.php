<div class="py-2 px-3 relative">
    <!-- About Program Baseline Section -->
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <h3 class="font-semibold text-gray-800 mb-2">About Program Baseline</h3>
        <ul class="text-sm text-gray-700 space-y-1">
            <li>• <strong>Community Baseline:</strong> Document the community's existing conditions before the program starts</li>
            <li>• <strong>Target Indicators:</strong> Set literacy, income, and skill targets that the program aims to achieve</li>
            <li>• <strong>Baseline Date:</strong> Use this date for comparison with endline assessments later</li>
            <li>• <strong>Status Tracking:</strong> Mark as "Draft" while collecting data, "Approved" when finalized</li>
        </ul>
    </div>

    <!-- Baseline Records -->
    <div class="bg-white rounded-lg shadow-md overflow-visible">
        <div class="bg-gray-50 p-4 border-b flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Baseline Records</h2>
            <button wire:click="$set('showModal', true)" type="button"
                class="px-4 py-2 bg-blue-900 text-white font-semibold rounded hover:bg-blue-800 hover:text-yellow-400 transition text-sm">
                + New Baseline
            </button>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($baselines && count($baselines) > 0): ?>
            <div class="divide-y">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $baselines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $baseline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-semibold <?php echo e($baseline->status_badge); ?> rounded px-2 py-1">
                                        <?php echo e(ucfirst($baseline->status)); ?>

                                    </span>
                                    <span class="text-sm text-gray-600">
                                        <?php echo e($baseline->baseline_assessment_date?->format('M d, Y')); ?>

                                    </span>
                                </div>
                                <p class="text-sm font-semibold text-gray-800">
                                    <?php echo e($baseline->community->name ?? 'Community not specified'); ?>

                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    Target Beneficiaries: <?php echo e($baseline->target_beneficiaries_count ?? 'N/A'); ?> | 
                                    Target Income: ₱<?php echo e($baseline->target_average_income ? number_format($baseline->target_average_income, 2) : 'N/A'); ?>

                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="edit(<?php echo e($baseline->id); ?>)" type="button"
                                    class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Edit</button>
                                <button wire:click="delete(<?php echo e($baseline->id); ?>)" type="button" onclick="return confirm('Delete this baseline?')"
                                    class="text-red-600 hover:text-red-800 font-semibold text-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="p-6 text-center text-gray-500">
                <p class="text-sm">No baseline assessments yet. Create one to get started.</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- New/Edit Baseline Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white p-6 flex justify-between items-center sticky top-0">
                    <h3 class="text-lg font-bold"><?php echo e($editingId ? 'Edit Baseline' : 'New Baseline'); ?></h3>
                    <button 
                        wire:click="resetForm"
                        class="text-white hover:text-gray-200"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <!-- Community Selection -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Community *</label>
                        <select wire:model="community_id" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Community</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $communities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($community->id); ?>"><?php echo e($community->name); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['community_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Assessment Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Assessment Date *</label>
                        <input type="date" wire:model="baseline_assessment_date" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['baseline_assessment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Target Beneficiaries -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Beneficiaries</label>
                        <input type="number" wire:model="target_beneficiaries_count" placeholder="e.g., 100" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Target Literacy Level -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Literacy Level (1-5)</label>
                        <input type="number" wire:model="target_literacy_level" min="1" max="5" placeholder="1-5" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Target Average Income -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Avg Income (₱)</label>
                        <input type="number" wire:model="target_average_income" placeholder="e.g., 15000" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Target Skills -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Skills to Develop</label>
                        <div class="flex gap-2 mb-2">
                            <input type="text" wire:model="new_skill" placeholder="Add skill..." @keydown.enter="addSkill" class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="button" wire:click="addSkill" class="px-2 py-2 bg-blue-900 text-white rounded text-sm hover:bg-blue-800 hover:text-yellow-400">+</button>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($target_skills && count($target_skills) > 0): ?>
                            <div class="space-y-1 text-xs">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $target_skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div class="flex justify-between items-center bg-blue-50 p-2 rounded">
                                        <span><?php echo e($skill); ?></span>
                                        <button type="button" wire:click="removeSkill(<?php echo e($index); ?>)" class="text-red-600 text-xs font-bold">×</button>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                        <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="draft">Draft</option>
                            <option value="approved">Approved</option>
                            <option value="completed">Completed</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Notes</label>
                        <textarea wire:model="notes" rows="2" placeholder="Additional notes..." class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-4 border-t">
                        <button type="button" wire:click="resetForm" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 bg-blue-900 hover:bg-blue-900 text-white hover:text-yellow-400 font-semibold py-2 px-4 rounded-lg transition">
                            <?php echo e($editingId ? 'Update' : 'Create'); ?> Baseline
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\Nikko\Desktop\CAPSTONE\SmartCEMES_FINAL\resources\views/livewire/manage-program-baseline.blade.php ENDPATH**/ ?>