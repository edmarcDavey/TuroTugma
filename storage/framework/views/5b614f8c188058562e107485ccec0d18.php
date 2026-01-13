

<?php $__env->startSection('title','Sections & Subjects'); ?>
<?php $__env->startSection('heading','Sections & Subjects'); ?>

<?php $__env->startSection('content'); ?>
<div class="pb-16 pt-0">
    <div class="space-y-8">

        <!-- Tabs -->
        <div class="flex items-center gap-3 mb-2">
            <div class="inline-flex ss-tabs">
                <button type="button" id="tab-sections" class="px-4 py-2 text-sm font-medium ss-tab" aria-pressed="true" role="tab" aria-controls="panel-sections">Sections</button>
                <button type="button" id="tab-subjects" class="px-4 py-2 text-sm font-medium ss-tab" aria-pressed="false" role="tab" aria-controls="panel-subjects">Subjects</button>
            </div>
        </div>

        <div id="ss-grid" class="grid grid-cols-1 gap-8 mt-0">
        <!-- Sections panel -->
        <div id="panel-sections" class="block" role="tabpanel" aria-labelledby="tab-sections">
                <?php
                    // themes config
                    $allThemes = config('section_themes.themes');
                    // $hasSections should be provided by the route; fallback to DB check if not set
                    $hasSections = $hasSections ?? \App\Models\Section::exists();
                ?>

                <?php if(!$hasSections): ?>
                    <div class="space-y-6">
                        <!-- Junior High builder -->
                        <div class="border rounded p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="font-medium">Junior High School</div>
                                <div class="text-sm text-slate-500">Grades 7 &ndash; 10</div>
                            </div>
                            <div class="space-y-3">
                                <?php $__currentLoopData = range(7,10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $grade = $gradeLevels->firstWhere('year', $yr);
                                        $name = $grade->name ?? 'Grade '.$yr;
                                        $gid = $grade->id ?? '';
                                        $theme = $grade->section_naming ?? '';
                                        $planned = $grade->section_naming_options['planned_sections'] ?? 0;
                                        // if theme not set, pick a random theme key so each grade may get a different theme
                                        $themeKeys = array_keys($allThemes ?: []);
                                        $selectedThemeKey = $theme ?: (count($themeKeys) ? $themeKeys[array_rand($themeKeys)] : '');
                                    ?>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-center py-2 border-b">
                                        <div class="md:col-span-1 font-medium"><?php echo e($name); ?></div>
                                        <div>
                                            <select name="theme" data-year="<?php echo e($yr); ?>" data-grade-id="<?php echo e($gid); ?>" class="w-full border rounded px-2 py-1">
                                                <?php $__currentLoopData = $allThemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tkey => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($tkey); ?>" data-label="<?php echo e($t['label']); ?>" <?php echo e(($tkey === $selectedThemeKey) ? 'selected' : ''); ?>><?php echo e($t['label']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div>
                                            <input name="count" data-year="<?php echo e($yr); ?>" data-grade-id="<?php echo e($gid); ?>" type="number" min="0" class="w-full border rounded px-2 py-1" value="<?php echo e($planned); ?>">
                                        </div>
                                        <div class="md:col-span-4 mt-2">
                                            <div class="preview-area" data-year="<?php echo e($yr); ?>" data-grade-id="<?php echo e($gid); ?>"></div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Senior High builder - Coming Soon -->
                        <div class="border rounded p-4 bg-amber-50">
                            <div class="flex items-center justify-between mb-3">
                                <div class="font-medium">Senior High School</div>
                                <div class="text-sm text-slate-500">Grades 11 &ndash; 12</div>
                            </div>
                            <div class="py-4 text-center text-amber-800">
                                <p class="font-semibold">⏳ Coming Soon</p>
                                <p class="text-sm mt-2">Senior High section management will be available in the future.</p>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button id="save-all" type="button" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 w-36 text-center">Create Sections</button>
                        </div>
                    </div>
                <?php else: ?>
                    <div id="server-created-sections" class="space-y-6">
                        <div class="border rounded p-4 bg-white">
                            <div class="flex items-center justify-between mb-3">
                                <div class="font-medium">Junior High Sections</div>
                                <div class="text-sm text-slate-500">Grades 7 &ndash; 10</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <?php $__currentLoopData = range(7,10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $grade = $gradeLevels->firstWhere('year',$yr); 
                                        $secs = collect();
                                        if($grade && $grade->sections){
                                            // For Junior High, put special sections first then order by ordinal
                                            $secs = $grade->sections->sortBy(function($s){
                                                $key = ($s->is_special ? 0 : 1) * 100000 + (($s->ordinal !== null) ? $s->ordinal : 99999);
                                                return $key;
                                            });
                                        }
                                    ?>
                                    <div class="border rounded p-3 bg-gray-50">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="font-medium">Grade <?php echo e($yr); ?></div>
                                            <div class="flex items-center gap-3">
                                                <div class="text-xs text-slate-500"><?php echo e($secs->count()); ?> item(s)</div>
                                                <?php if($grade && $grade->id): ?>
                                                    <button type="button" class="text-sm text-indigo-600 grade-edit-btn" data-grade-id="<?php echo e($grade->id); ?>" data-year="<?php echo e($yr); ?>">Edit</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if($secs->count()): ?>
                                            <ul class="space-y-1">
                                                <?php $__currentLoopData = $secs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="py-1" data-section-id="<?php echo e($s->id); ?>">
                                                        <div class="flex items-center justify-between">
                                                            <div class="font-medium section-name"><?php echo e($s->name); ?></div>
                                                            <div class="text-xs text-slate-500 section-meta"><?php echo e($s->track ?? ($s->is_special ? 'Special' : '')); ?></div>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php else: ?>
                                            <div class="text-sm text-slate-500">No sections</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="border rounded p-4 bg-white">
                            <div class="flex items-center justify-between mb-3">
                                <div class="font-medium">Senior High Sections</div>
                                <div class="text-sm text-slate-500">Grades 11 &ndash; 12</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php $__currentLoopData = range(11,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $grade = $gradeLevels->firstWhere('year',$yr); 
                                        $secs = collect();
                                        if($grade && $grade->sections){
                                            // For Senior High we keep ordinal ordering; SHS tracks will be shown in meta
                                            $secs = $grade->sections->sortBy('ordinal');
                                        }
                                    ?>
                                    <div class="border rounded p-3 bg-gray-50">
                                        <div class="flex items-center justify-between mb-2"><div class="font-medium">Grade <?php echo e($yr); ?></div><div class="text-xs text-slate-500"><?php echo e($secs->count()); ?> item(s)</div></div>
                                        <?php if($secs->count()): ?>
                                            <ul class="space-y-1">
                                                <?php $__currentLoopData = $secs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="py-1">
                                                        <div class="flex items-center justify-between">
                                                            <div class="font-medium"><?php echo e($s->name); ?></div>
                                                            <div class="text-xs text-slate-500"><?php echo e($s->track ?? ($s->is_special ? 'Special' : '')); ?></div>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php else: ?>
                                            <div class="text-sm text-slate-500">No sections</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
        </div>
        <!-- End Sections panel -->

        <!-- Subjects panel -->
        <div id="panel-subjects" class="hidden bg-white border rounded p-4" role="tabpanel" aria-labelledby="tab-subjects">
            <div class="mb-4 p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-900">
                <p class="font-semibold">⏳ Senior High Coming Soon</p>
                <p class="text-sm mt-1">Senior High (Grade 11-12) subject management will be available in the future. Currently, only Junior High (Grade 7-10) subjects can be managed.</p>
            </div>

            <!-- Filter & Action Bar -->
            <div class="mb-4 flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-3 flex-wrap">
                    <select id="filter-grade" class="px-3 py-2 border rounded text-sm">
                        <option value="">All Levels</option>
                        <option value="junior-high">Junior High (Grade 7-10)</option>
                        <option value="senior-high" disabled>Senior High (Coming Soon)</option>
                        <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(in_array($gl->name, ['Grade 11', 'Grade 12'])): ?>
                                <option value="<?php echo e($gl->id); ?>" disabled><?php echo e($gl->name); ?> (Coming Soon)</option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select id="filter-strand" class="px-3 py-2 border rounded text-sm opacity-50 cursor-not-allowed" disabled>
                        <option value="">All Strands</option>
                        <?php $__currentLoopData = $strands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($st->id); ?>"><?php echo e($st->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select id="filter-type" class="px-3 py-2 border rounded text-sm">
                        <option value="">All Types</option>
                        <option value="core">Core</option>
                        <option value="specialized">Specialized</option>
                        <option value="applied">Applied</option>
                        <option value="elective">Elective</option>
                    </select>
                </div>
                <button id="btn-add-subject" class="px-4 py-2 bg-[#3b4197] text-white rounded font-semibold hover:bg-[#2d3273]">
                    + Add Subject
                </button>
            </div>

            <!-- Subjects Table -->
            <div class="bg-white border rounded-lg shadow overflow-x-auto">
                <table id="subjects-table" class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Subject Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Strand</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Hours/Week</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50" data-subject-id="<?php echo e($subj->id); ?>" data-grade-ids="<?php echo e($subj->gradeLevels->pluck('id')->join(',')); ?>" data-strand-id="<?php echo e($subj->strand_id ?? ''); ?>" data-type="<?php echo e($subj->type); ?>">
                                <td class="px-4 py-3 font-medium text-slate-900"><?php echo e($subj->name); ?></td>
                                <td class="px-4 py-3 text-sm text-slate-600"><?php echo e($subj->code ?? '—'); ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <?php if($subj->strand): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-purple-100 text-purple-800"><?php echo e($subj->strand->name); ?></span>
                                    <?php else: ?>
                                        <span class="text-slate-500">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if($subj->type): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"><?php echo e(ucfirst($subj->type)); ?></span>
                                    <?php else: ?>
                                        <span class="text-slate-500">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600"><?php echo e($subj->hours_per_week ?? '—'); ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <button class="text-blue-600 hover:text-blue-800 mr-2 btn-edit-subject" data-id="<?php echo e($subj->id); ?>">Edit</button>
                                    <button class="text-red-600 hover:text-red-800 btn-delete-subject" data-id="<?php echo e($subj->id); ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr id="empty-row">
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500 italic text-sm">
                                    No subjects yet. Click "+ Add Subject" to create one.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Subject Modal -->
            <div id="subject-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50" style="align-items: center; justify-content: center;">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 border-b flex items-center justify-between">
                        <h3 id="modal-title" class="text-lg font-semibold">Add Subject</h3>
                        <button id="close-modal" class="text-slate-400 hover:text-slate-600">&times;</button>
                    </div>
                    <form id="subject-form" class="px-6 py-4">
                        <input type="hidden" id="subject-id" name="id">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Subject Name *</label>
                                <input type="text" id="subject-name-input" name="name" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[#3b4197]" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Subject Code</label>
                                <input type="text" id="subject-code" name="code" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[#3b4197]">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Grade Levels *</label>
                                <div class="flex flex-col gap-2">
                                    <?php
                                        $juniorHighGrades = $gradeLevels->filter(function($gl) {
                                            return in_array($gl->name, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10']);
                                        });
                                        $juniorHighIds = $juniorHighGrades->pluck('id')->toArray();
                                    ?>
                                    
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" id="junior-high-checkbox" data-grade-ids="<?php echo e(json_encode($juniorHighIds)); ?>" class="mr-2">
                                        <span class="text-sm">Junior High (Grade 7-10)</span>
                                    </label>
                                    
                                    <?php $__currentLoopData = $gradeLevels->whereIn('name', ['Grade 11', 'Grade 12']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="inline-flex items-center opacity-50 cursor-not-allowed">
                                            <input type="checkbox" name="grade_levels[]" value="<?php echo e($gl->id); ?>" class="mr-2" disabled data-shs="1">
                                            <span class="text-sm"><?php echo e($gl->name); ?></span>
                                            <span class="text-xs text-amber-600 ml-1">(Coming Soon)</span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                
                                <!-- Hidden inputs for Junior High grades -->
                                <div id="junior-high-inputs"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Strand (Senior High only)</label>
                                <select id="subject-strand" name="strand_id" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[#3b4197] opacity-50 cursor-not-allowed" disabled>
                                    <option value="">None</option>
                                    <?php $__currentLoopData = $strands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($st->id); ?>"><?php echo e($st->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Type *</label>
                                <select id="subject-type" name="type" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[#3b4197]" required>
                                    <option value="">Select Type</option>
                                    <option value="core">Core</option>
                                    <option value="specialized">Specialized</option>
                                    <option value="applied">Applied</option>
                                    <option value="elective">Elective</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Hours per Week *</label>
                                <input type="number" id="subject-hours" name="hours_per_week" min="1" max="20" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[#3b4197]" required>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex gap-2 justify-end">
                            <button type="button" id="cancel-modal" class="px-4 py-2 bg-slate-200 text-slate-700 rounded hover:bg-slate-300">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-[#3b4197] text-white rounded hover:bg-[#2d3273]">Save Subject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Subjects panel -->
        </div>
        <!-- End ss-grid -->
    </div>
</div>

<!-- Grade Editor Modal -->
    <div id="grade-modal" class="hidden fixed inset-0 z-50 items-center justify-center">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <div class="relative bg-white rounded shadow-lg w-full max-w-3xl mx-4 p-4" role="dialog" aria-modal="true" aria-labelledby="grade-modal-title">
            <div class="flex items-center justify-between mb-3">
                <h3 id="grade-modal-title" class="text-lg font-medium">Edit Grade Sections</h3>
                <button type="button" id="grade-modal-close" class="text-gray-600 hover:text-gray-800">&times;</button>
            </div>
            <div id="grade-modal-body">Loading…</div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Toggle pill styles for Regular / Special -->
<style>
    /* Tabs: underline indicator and hover state to match the provided design */
    .ss-tabs{ padding-bottom:8px; background:transparent; border:0; }
    .ss-tabs .ss-tab{ position:relative; background:transparent; border:0; margin:0; color:#374151; cursor:pointer; padding:8px 16px; }
    .ss-tabs .ss-tab:hover{ color:#3B4197; }
    .ss-tabs .ss-tab:focus{ outline:0; }
    /* underline indicator — centered and thin */
    .ss-tabs .ss-tab::after{ content:''; position:absolute; left:22%; right:22%; bottom:0; height:3px; background:transparent; border-radius:2px; transition:background-color .12s ease, transform .12s ease; }
    .ss-tabs .ss-tab[aria-pressed="true"]{ color:#3B4197; font-weight:600; }
    .ss-tabs .ss-tab[aria-pressed="true"]::after{ background:#3B4197; }

    /* Tab panels - show/hide based on state */
    [role="tabpanel"]{ display: block; }
    [role="tabpanel"].hidden{ display: none; }

    /* container for previous pill kept for backwards compat */
    .toggle-pill{ display:inline-flex; align-items:center; gap:1rem; border-radius:9999px; padding:6px 12px; cursor:pointer; user-select:none; border:1px solid #e6eef6; background:#f8fafc; }

    /* Segmented two-button control (Regular / Special) */
    .seg-toggle{ display:flex; position:relative; align-items:stretch; border-radius:9999px; overflow:visible; border:1px solid #d7e1e6; }
    /* center divider drawn as pseudo-element so there's no layout gap between buttons */
    /* make it thinner and sit above the button background but under the focus ring */
    .seg-toggle::before{ content:''; position:absolute; left:50%; top:8px; bottom:8px; width:2px; background:rgba(59,65,151,0.08); transform:translateX(-50%); border-radius:2px; z-index:3; pointer-events:none; }
    /* focus visuals intentionally removed per user preference */
    .seg-toggle .seg-btn{ flex:1 1 0; padding:10px 16px; font-size:0.95rem; background:transparent; border:0; cursor:pointer; color:#334155; margin:0; line-height:1; display:inline-flex; align-items:center; justify-content:center; font-weight:600; position:relative; z-index:2; }
    /* active uses brand blue and white text; keep weight consistent */
    .seg-toggle .seg-btn.active{ background:#3B4197; color:#ffffff; }
    .seg-toggle .seg-btn:not(.active){ opacity:0.95; }
    /* remove per-button box-shadow focus which produced rectangular artifact; rely on container focus ring instead */
    .seg-toggle .seg-btn:focus{ outline:0; }
    /* rounded halves so active background respects the capsule ends */
    .seg-toggle .seg-left{ border-radius:9999px 0 0 9999px; }
    .seg-toggle .seg-right{ border-radius:0 9999px 9999px 0; }
    .seg-toggle.pill-small .seg-btn{ padding:6px 10px; font-size:0.82rem; }

    /* keep old pill styles for any remaining references */
    .toggle-pill .pill-label{ font-size:0.85rem; padding:4px 8px; color:#475569; display:inline-block; position:relative; z-index:3; transition:color .12s ease, opacity .12s ease; }
</style>
<div id="ss-hooks" style="display:none"
    data-preview-url="<?php echo e(route('admin.grade-levels.preview')); ?>"
    data-subject-store-url="<?php echo e(route('admin.subjects.store')); ?>"
></div>
<script>
    (function(){
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const hooks = document.getElementById('ss-hooks');
        console.log('subjects-sections script loaded');
        const previewUrl = hooks.dataset.previewUrl;
        const subjectStoreUrl = hooks.dataset.subjectStoreUrl;
        const toggleBase = '/admin/subjects';

        const SHS_TRACK_OPTIONS = ['STEM','ABM','HUMSS','TVL','GAS'];

        // Restore active tab from sessionStorage
        window.addEventListener('load', function() {
            const savedTab = sessionStorage.getItem('activeTab');
            if (savedTab) {
                const tabBtn = document.getElementById(savedTab);
                if (tabBtn) {
                    document.querySelectorAll('.ss-tab').forEach(t => t.setAttribute('aria-pressed', 'false'));
                    tabBtn.setAttribute('aria-pressed', 'true');
                    document.querySelectorAll('[role="tabpanel"]').forEach(p => p.classList.add('hidden'));
                    const panelId = tabBtn.getAttribute('aria-controls');
                    const panel = document.getElementById(panelId);
                    if (panel) panel.classList.remove('hidden');
                    sessionStorage.removeItem('activeTab');
                }
            }
        });

        function getInputsForGrade(gradeId, container=null){
            container = container || document;
            const themeEl = container.querySelector('select[name="theme"][data-grade-id="'+gradeId+'"]');
            const theme = themeEl ? (themeEl.value || '').trim() : '';
            const count = parseInt(container.querySelector('input[name="count"][data-grade-id="'+gradeId+'"]').value || '0', 10);
            return { theme, count };
        }

        async function postJSON(url, body){
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify(body)
            });
            return res.json();
        }

        // helper to set a select[name="theme"] based on server response (tries key first, then label)
        // returns true if it successfully selected an option
        function setThemeSelect(selectEl, resp){
            if(!selectEl || !resp) return false;
            // server may return a theme key
            if(resp.theme){
                const opt = Array.from(selectEl.options).find(o => o.value === resp.theme);
                if(opt){ selectEl.value = resp.theme; return true; }
            }
            const label = resp.theme_label || resp.theme || null;
            if(label){
                const opt2 = Array.from(selectEl.options).find(o => (o.text === label) || (o.getAttribute('data-label') === label));
                if(opt2){ selectEl.value = opt2.value; return true; }
            }
            return false;
        }

        // Ensure toggles default to Regular when not explicitly set by server
        function ensureToggleDefaults(container){
            if(!container) container = document;
            const toggles = Array.from(container.querySelectorAll('.btn-toggle-special'));
            toggles.forEach(t=>{
                if(typeof t.dataset.special === 'undefined' || t.dataset.special === null || t.dataset.special === ''){
                    t.dataset.special = '0';
                }
                const isSpecial = t.dataset.special === '1';
                t.setAttribute('aria-pressed', isSpecial ? 'true' : 'false');
                const left = t.querySelector('.seg-left');
                const right = t.querySelector('.seg-right');
                if(left) left.classList.toggle('active', !isSpecial);
                if(right) right.classList.toggle('active', isSpecial);
            });
        }

        // Delegated handler: segmented two-button Regular / Special control
        document.addEventListener('click', function(e){
            const segBtn = e.target.closest ? e.target.closest('.seg-btn') : null;
            if(!segBtn) return;
            const container = segBtn.closest('.btn-toggle-special');
            if(!container) return;
            const val = segBtn.getAttribute('data-value') === '1' ? '1' : '0';
            container.dataset.special = val;
            container.setAttribute('aria-pressed', val === '1' ? 'true' : 'false');
            const children = Array.from(container.querySelectorAll('.seg-btn'));
            children.forEach(c => c.classList.toggle('active', c === segBtn));
        });

        // (Builder-related preview/generate/save JS removed to slim page; subject handlers remain below)
        document.querySelectorAll('.subject-toggle-open').forEach(btn => {
            btn.addEventListener('click', function(){
                const sid = this.dataset.subjectId;
                const list = document.querySelector('.subject-grade-list[data-subject-id="'+sid+'"]');
                if(list) list.classList.toggle('hidden');
            });
        });

        document.querySelectorAll('.subject-grade-checkbox').forEach(cb => {
            cb.addEventListener('change', async function(){
                const subjectId = this.dataset.subjectId;
                const gradeId = this.dataset.gradeId;
                const checked = this.checked;
                const url = `${toggleBase}/${subjectId}/toggle-grade/${gradeId}`;
                try {
                    const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept':'application/json' }, body: JSON.stringify({ attach: checked }) });
                    const json = await res.json();
                    if(!(json && json.success)){
                        if(typeof showToast === 'function') showToast('Toggle failed','error'); else alert('Toggle failed');
                        this.checked = !checked; // revert UI
                    }
                } catch(err){
                    console.error(err);
                    if(typeof showToast === 'function') showToast('Network error','error'); else alert('Network error');
                    this.checked = !checked;
                }
            });
        });

        // Tabs handlers (switch panels)
        const tabSections = document.getElementById('tab-sections');
        const tabSubjects = document.getElementById('tab-subjects');
        const panelSections = document.getElementById('panel-sections');
        const panelSubjects = document.getElementById('panel-subjects');

        function activateTab(tab){
            if(!tab) return;
            if(tab === 'sections'){
                panelSections.classList.remove('hidden');
                panelSubjects.classList.add('hidden');
                tabSections.setAttribute('aria-pressed','true');
                tabSubjects.setAttribute('aria-pressed','false');
                tabSections.focus();
            } else {
                panelSections.classList.add('hidden');
                panelSubjects.classList.remove('hidden');
                tabSections.setAttribute('aria-pressed','false');
                tabSubjects.setAttribute('aria-pressed','true');
                tabSubjects.focus();
            }
        }

        function showSections(){ activateTab('sections'); }
        function showSubjects(){ activateTab('subjects'); }

        tabSections.addEventListener('click', showSections);
        tabSubjects.addEventListener('click', showSubjects);

        // initialize with Sections as default
        activateTab('sections');

        // keyboard navigation: left/right arrows switch tabs
        const tabButtons = [tabSections, tabSubjects];
        tabButtons.forEach((btn, idx) => {
            btn.addEventListener('keydown', (ev) => {
                if(ev.key === 'ArrowRight' || ev.key === 'ArrowLeft'){
                    ev.preventDefault();
                    const nextIdx = ev.key === 'ArrowRight' ? (idx + 1) % tabButtons.length : (idx - 1 + tabButtons.length) % tabButtons.length;
                    tabButtons[nextIdx].focus();
                    // also activate on arrow navigation
                    const id = tabButtons[nextIdx].id === 'tab-sections' ? 'sections' : 'subjects';
                    activateTab(id);
                }
                if(ev.key === 'Enter' || ev.key === ' '){ ev.preventDefault(); btn.click(); }
            });
        });

        // Global generate button: request preview for each grade-year that has a count > 0
        const globalGenerate = document.getElementById('global-generate');
        if(globalGenerate){
            globalGenerate.addEventListener('click', async function(){
                const counts = Array.from(document.querySelectorAll('input[name="count"]'));
                for (const inp of counts) {
                    const cnt = parseInt(inp.value || '0', 10);
                    const year = inp.getAttribute('data-year');
                    if (!year) continue;
                    const themeInput = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        const theme = themeInput ? themeInput.value.trim() : '';
                            if (cnt > 0) {
                        try {
                            // if theme is empty, pick a random theme from the select options for this year
                            let chosenTheme = theme;
                            if(!chosenTheme){
                                if (themeInput && themeInput.options && themeInput.options.length){
                                    // collect non-empty option values
                                    const opts = Array.from(themeInput.options).map(o=>o.value).filter(v=>v);
                                    if(opts.length) chosenTheme = opts[Math.floor(Math.random()*opts.length)];
                                }
                            }
                            const payload = { theme: chosenTheme, count: cnt, name: 'Grade ' + year };
                            const resp = await postJSON(previewUrl, payload);
                            const preview = resp.preview ?? resp.names ?? resp;
                            // if server returned the resolved theme, populate the theme input so UI reflects it
                            if (themeInput) setThemeSelect(themeInput, resp);
                            const area = document.querySelector('.preview-area[data-year="'+year+'"]');
                            if(area){
                                area.innerHTML = '';
                                if(Array.isArray(preview) && preview.length){
                                    const list = document.createElement('div');
                                    list.className = 'space-y-1';
                                    preview.forEach((item, idx) => {
                            const payload = (typeof item === 'string') ? { name: item } : item || {};
                            const name = payload.name ?? '';
                            const isSpecial = payload.is_special == 1 || payload.is_special === true ? true : false;
                            const trackVal = payload.track || '';
                            const row = document.createElement('div');
                                row.className = 'flex items-center gap-2 py-1';
                                const year = (area && area.getAttribute) ? area.getAttribute('data-year') : null;
                                let html = `<input data-idx="${idx}" class="flex-1 border rounded px-2 py-1 section-name-input" value="${name}"/>`;
                                // show Regular/Special only for Junior High (years < 11)
                                if(!year || parseInt(year,10) < 11){
                                    html += `<div class="btn-toggle-special seg-toggle ml-2 pill-small" data-idx="${idx}" data-special="${isSpecial ? '1' : '0'}" role="tablist" aria-pressed="${isSpecial ? 'true' : 'false'}">` +
                                                `<button type="button" class="seg-btn seg-left ${isSpecial ? '' : 'active'}" data-value="0">Regular</button>` +
                                                `<button type="button" class="seg-btn seg-right ${isSpecial ? 'active' : ''}" data-value="1">Special</button>` +
                                            `</div>`;
                                }
                                // show Track selector only for Senior High (years >= 11)
                                if(year && parseInt(year,10) >= 11){
                                    let sel = `<select class="section-track-select border rounded px-2 py-1 ml-2" data-idx="${idx}"><option value="">— Track —</option>`;
                                    SHS_TRACK_OPTIONS.forEach(t => { sel += `<option value="${t}" ${trackVal === t ? 'selected' : ''}>${t}</option>`; });
                                    sel += `</select>`;
                                    html += sel;
                                }
                                row.innerHTML = html;
                            list.appendChild(row);
                        });
                                    area.appendChild(list);
                                    ensureToggleDefaults(area);
                                    // (removed per-list mark-all controls — use per-row toggle buttons)
                                }
                            }
                        } catch(err){
                            console.error('preview error', err);
                            // continue to next
                        }
                    }
                }
            });
        }

        // Debounce helper to avoid excessive requests while typing
        function debounce(fn, wait){
            let t;
            return function(...args){
                clearTimeout(t);
                t = setTimeout(()=>fn.apply(this, args), wait);
            };
        }

        // Live preview: when number of sections input changes, auto-generate preview for that year
        const countInputs = Array.from(document.querySelectorAll('input[name="count"]'));
        countInputs.forEach(inp => {
            const year = inp.getAttribute('data-year');
            const handler = debounce(async function(e){
                const cnt = parseInt(inp.value || '0', 10);
                const themeInput = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                const theme = themeInput ? themeInput.value.trim() : '';
                const area = document.querySelector('.preview-area[data-year="'+year+'"]');
                if(!area) return;
                if(!cnt){
                    // clear preview if count is zero or empty
                    area.innerHTML = '';
                    return;
                }
                try{
                    // if theme is empty, pick a random theme from the select options for this year
                    let chosenTheme = theme;
                    if(!chosenTheme){
                        const themeInputLocal = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        if(themeInputLocal && themeInputLocal.options && themeInputLocal.options.length){
                            const opts = Array.from(themeInputLocal.options).map(o=>o.value).filter(v=>v);
                            if(opts.length) chosenTheme = opts[Math.floor(Math.random()*opts.length)];
                        }
                    }
                    const payload = { theme: chosenTheme, count: cnt, name: 'Grade ' + year };
                    const resp = await postJSON(previewUrl, payload);
                    const preview = resp.preview ?? resp.names ?? resp;
                    // populate theme input if server tells us which theme was used
                    if (resp) {
                        const themeInputLocal = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        if (themeInputLocal) setThemeSelect(themeInputLocal, resp);
                    }
                    area.innerHTML = '';
                    if(Array.isArray(preview) && preview.length){
                        const list = document.createElement('div');
                        list.className = 'space-y-1';
                        preview.forEach((item, idx) => {
                            const payload = (typeof item === 'string') ? { name: item } : item || {};
                            const name = payload.name ?? '';
                            const isSpecial = payload.is_special == 1 || payload.is_special === true ? true : false;
                            const trackVal = payload.track || '';
                            const row = document.createElement('div');
                                row.className = 'flex items-center gap-2 py-1';
                                const year = (area && area.getAttribute) ? area.getAttribute('data-year') : null;
                                let html = `<input data-idx="${idx}" class="flex-1 border rounded px-2 py-1 section-name-input" value="${name}"/>`;
                                // show Regular/Special only for Junior High (years < 11)
                                if(!year || parseInt(year,10) < 11){
                                    html += `<div class="btn-toggle-special seg-toggle ml-2 pill-small" data-idx="${idx}" data-special="${isSpecial ? '1' : '0'}" role="tablist" aria-pressed="${isSpecial ? 'true' : 'false'}">` +
                                                `<button type="button" class="seg-btn seg-left ${isSpecial ? '' : 'active'}" data-value="0">Regular</button>` +
                                                `<button type="button" class="seg-btn seg-right ${isSpecial ? 'active' : ''}" data-value="1">Special</button>` +
                                            `</div>`;
                                }
                                // show Track selector only for Senior High (years >= 11)
                                if(year && parseInt(year,10) >= 11){
                                    let sel = `<select class="section-track-select border rounded px-2 py-1 ml-2" data-idx="${idx}"><option value="">— Track —</option>`;
                                    SHS_TRACK_OPTIONS.forEach(t => { sel += `<option value="${t}" ${trackVal === t ? 'selected' : ''}>${t}</option>`; });
                                    sel += `</select>`;
                                    html += sel;
                                }
                                row.innerHTML = html;
                            list.appendChild(row);
                        });
                        area.appendChild(list);
                        ensureToggleDefaults(area);
                        // (removed per-list mark-all controls — use per-row toggle buttons)
                    }
                } catch(err){
                    console.error('live preview error', err);
                }
            }, 450);

            inp.addEventListener('input', handler);

            // also regenerate when theme changes for the same year
            const themeInput = document.querySelector('select[name="theme"][data-year="'+year+'"]');
            if(themeInput){
                themeInput.addEventListener('change', debounce(function(){
                    // trigger the same handler as if count changed
                    handler();
                }, 450));
            }
        });

        // Save All: create grade if missing, then bulk-create sections for each previewed grade
        const saveAllBtn = document.getElementById('save-all');
        // Helper to render a view-only horizontal grid panel showing created sections grouped into Junior (7-10) and Senior (11-12)
        function renderCreatedSectionsView(createdMap, failedYears){
            // createdMap: { year: [ { name, is_special, id? } ] }
            // failedYears: Set or array of years that failed
            let container = document.getElementById('created-sections-view');
            if(!container){
                container = document.createElement('div');
                container.id = 'created-sections-view';
                container.className = 'mt-6';
                const toolbar = document.querySelector('.mt-4.flex.justify-end');
                if(toolbar && toolbar.parentNode){
                    toolbar.parentNode.insertBefore(container, toolbar);
                } else {
                    const panel = document.getElementById('panel-sections');
                    panel.insertBefore(container, panel.firstChild);
                }
            }
            container.innerHTML = '';

            // Create a horizontal grid for a set of years
            function makeHorizontalGrid(title, subtitle, years, cols){
                const wrapper = document.createElement('div');
                wrapper.className = 'border rounded p-4 mb-4 bg-white';
                const header = document.createElement('div');
                header.className = 'flex items-center justify-between mb-3';
                header.innerHTML = `<div class="font-medium">${title}</div><div class="text-sm text-slate-500">${subtitle}</div>`;
                wrapper.appendChild(header);

                // Use Tailwind grid cols via utility - if unavailable, fallback to flex
                const grid = document.createElement('div');
                grid.className = 'grid gap-4';
                grid.style.gridTemplateColumns = `repeat(${cols}, minmax(0, 1fr))`;

                years.forEach(y => {
                    const secList = createdMap[y] || [];
                    const col = document.createElement('div');
                    col.className = 'border rounded p-3 bg-gray-50 flex flex-col h-full';
                    const head = document.createElement('div');
                    head.className = 'flex items-center justify-between mb-2';
                    head.innerHTML = `<div class="font-medium">Grade ${y}</div><div class="text-xs text-slate-500">${secList.length} item(s)</div>`;
                    col.appendChild(head);

                    const body = document.createElement('div');
                    body.className = 'overflow-auto';
                    // default: expanded, show list
                    if(secList.length){
                        const ul = document.createElement('ul');
                        ul.className = 'space-y-1';
                        secList.forEach(s => {
                            const li = document.createElement('li');
                            li.className = 'py-1';
                            const label = document.createElement('div'); label.className = 'flex items-center justify-between';
                            const nm = document.createElement('div'); nm.className = 'font-medium'; nm.textContent = s.name;
                            const tag = document.createElement('div'); tag.className = 'text-xs text-slate-500'; tag.textContent = s.track ? s.track : (s.is_special ? 'Special' : '');
                            label.appendChild(nm); label.appendChild(tag);
                            li.appendChild(label);
                            ul.appendChild(li);
                        });
                        body.appendChild(ul);
                    } else {
                        const p = document.createElement('div'); p.className = 'text-sm text-slate-500'; p.textContent = failedYears && failedYears.indexOf(String(y)) !== -1 ? 'Failed to create sections for this grade' : 'No new sections';
                        body.appendChild(p);
                    }
                    col.appendChild(body);
                    grid.appendChild(col);
                });

                wrapper.appendChild(grid);
                return wrapper;
            }

            // Junior: 4 columns for grades 7-10
            const junior = makeHorizontalGrid('Junior High Sections', 'Grades 7 – 10', [7,8,9,10], 4);
            const senior = makeHorizontalGrid('Senior High Sections', 'Grades 11 – 12', [11,12], 2);
            container.appendChild(junior);
            container.appendChild(senior);

            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Hide builder inputs/preview for grades that have been created (do NOT render per-grade static lists here)
        // The canonical created sections view is the bottom horizontal grid; avoid duplicating lists in the grade rows.
        function updateBuilderForCreated(createdMap){
            try{
                Object.keys(createdMap || {}).forEach(year => {
                    const preview = document.querySelector('.preview-area[data-year="'+year+'"]');
                    // clear preview area (do not insert static list to avoid duplication)
                    if(preview){ preview.innerHTML = ''; }

                    // hide theme select and count input for the created grade
                    const themeSel = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                    const countInp = document.querySelector('input[name="count"][data-year="'+year+'"]');
                    if(themeSel) themeSel.style.display = 'none';
                    if(countInp) countInp.style.display = 'none';

                    // hide any generate/preview/save buttons associated with this year
                    const btns = Array.from(document.querySelectorAll('button[data-year="'+year+'"], button[data-grade-id="'+year+'"]'));
                    btns.forEach(b => { try{ b.style.display = 'none'; }catch(e){} });
                });
            }catch(err){ console.error('updateBuilderForCreated error', err); }
        }

        if (saveAllBtn) {
            saveAllBtn.addEventListener('click', async function(){
                if(!(typeof confirmDialog === 'function' ? await confirmDialog('Create all generated sections for all grades? This will persist sections to the database.') : window.confirm('Create all generated sections for all grades? This will persist sections to the database.'))) return;
                const previewAreas = Array.from(document.querySelectorAll('.preview-area'));
                const createdMap = {}; // year -> [{name, is_special, id?}]
                const failedYears = [];

                // disable inputs while processing
                saveAllBtn.disabled = true; saveAllBtn.textContent = 'Creating…';
                const inputsToDisable = Array.from(document.querySelectorAll('select[name="theme"], input[name="count"], button.btn-generate, button.btn-preview, #save-all, #global-generate'));
                inputsToDisable.forEach(i => i.disabled = true);

                for (const area of previewAreas){
                    const year = area.getAttribute('data-year');
                    const gidAttr = area.getAttribute('data-grade-id');
                    let items = Array.from(area.querySelectorAll('.section-name-input')).map(i=>{
                        const idx = i.getAttribute('data-idx');
                        const name = i.value.trim();
                        const yearLocal = area.getAttribute('data-year');
                        const isJHS = !yearLocal || parseInt(yearLocal,10) < 11;
                        const btn = area.querySelector('.btn-toggle-special[data-idx="'+idx+'"]');
                        const trackSel = area.querySelector('.section-track-select[data-idx="'+idx+'"]');
                        const track = trackSel ? (trackSel.value || null) : null;
                        const is_special = isJHS ? (btn && btn.dataset && btn.dataset.special === '1' ? 1 : 0) : 0;
                        return { name, is_special, track };
                    }).filter(it=>it.name);

                    // If no preview items are present but a count input exists and >0, request a preview from the server
                    if(!items.length){
                        const countInputLocal = document.querySelector('input[name="count"][data-year="'+year+'"]');
                        const themeInputLocal = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        const cntLocal = countInputLocal ? parseInt(countInputLocal.value||'0',10) : 0;
                        const themeLocal = themeInputLocal ? (themeInputLocal.value || '') : '';
                        if(cntLocal > 0){
                            try{
                                const resp = await postJSON(previewUrl, { theme: themeLocal, count: cntLocal, name: 'Grade ' + year });
                                const preview = resp.preview ?? resp.names ?? resp;
                                    if(Array.isArray(preview) && preview.length){
                                    items = preview.map(it => (typeof it === 'string') ? { name: it, is_special: 0, track: null } : { name: it.name || '', is_special: it.is_special ? 1 : 0, track: it.track || null }).filter(it => it.name);
                                }
                            } catch(err){
                                console.error('preview before save error', err);
                            }
                        }
                    }
                    if(!items.length) continue;

                    let gradeId = gidAttr || null;
                    // if grade not persisted yet, create it
                    if(!gradeId){
                        const themeInput = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        const countInput = document.querySelector('input[name="count"][data-year="'+year+'"]');
                        const theme = themeInput ? themeInput.value.trim() : null;
                        const planned = countInput ? parseInt(countInput.value||'0',10) : 0;
                        try{
                            // if theme empty, choose random from options
                            let gradeTheme = theme;
                            if(!gradeTheme && themeInput && themeInput.options && themeInput.options.length){
                                const opts = Array.from(themeInput.options).map(o=>o.value).filter(v=>v);
                                if(opts.length) gradeTheme = opts[Math.floor(Math.random()*opts.length)];
                            }
                            const createResp = await postJSON('/admin/grade-levels', { name: 'Grade ' + year, section_naming: gradeTheme, section_naming_options: { planned_sections: planned } });
                            if(createResp && createResp.grade && createResp.grade.id){
                                gradeId = createResp.grade.id;
                                // annotate DOM so future saves know id
                                area.setAttribute('data-grade-id', gradeId);
                            } else {
                                console.warn('failed to create grade for year', year);
                                failedYears.push(year);
                                continue;
                            }
                        } catch(err){
                            console.error('create grade error', err);
                            failedYears.push(year);
                            continue;
                        }
                    }

                    // now bulk-create sections
                    try{
                        const url = `/admin/grade-levels/${gradeId}/sections/bulk-create`;
                        const saveResp = await postJSON(url, { items: items });
                        // prefer server-returned sections (with ids) else use submitted items
                        const sections = Array.isArray(saveResp.sections) && saveResp.sections.length ? saveResp.sections.map(s => ({ name: s.name || s, is_special: s.is_special || 0, track: s.track || null, id: s.id || null })) : items.map(i => ({ name: i.name, is_special: i.is_special || 0, track: i.track || null }));
                        createdMap[year] = sections;
                    } catch(err){
                        console.error('bulk create error', err);
                        failedYears.push(year);
                    }
                }

                // re-enable inputs
                saveAllBtn.disabled = false; saveAllBtn.textContent = 'Create Sections';
                inputsToDisable.forEach(i => i.disabled = false);

                // Show results inline and update builder areas
                renderCreatedSectionsView(createdMap, failedYears);
                try{ updateBuilderForCreated(createdMap); }catch(err){ console.error('updateBuilderForCreated error', err); }

                if(failedYears.length){
                    if(typeof showToast === 'function') showToast('Some grades failed to create. See the created sections panel for details.','error'); else alert('Some grades failed to create. See the created sections panel for details.');
                } else {
                    if(typeof showToast === 'function') showToast('Sections created','success'); else alert('Sections created');
                    // reload so server-side $hasSections becomes true and BUILDER is hidden
                    location.reload();
                }
            });
        }

        // On page load: if the grade already has saved sections rendered server-side, hide the builder for that grade
        function hideBuilderIfSavedExists(){
            try{
                const previewAreas = Array.from(document.querySelectorAll('.preview-area'));
                previewAreas.forEach(area => {
                    const year = area.getAttribute('data-year');
                    // look for a sibling saved sections container (the server renders a flex.wrap container with saved section pills)
                    const parent = area.parentElement || area.parentNode;
                    if(!parent) return;
                    const saved = parent.querySelector('.flex.flex-wrap');
                    if(saved && saved.children && saved.children.length){
                        // hide inputs and preview and buttons for this year (do NOT copy saved pills into preview area)
                        const themeSel = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        const countInp = document.querySelector('input[name="count"][data-year="'+year+'"]');
                        if(themeSel) themeSel.style.display = 'none';
                        if(countInp) countInp.style.display = 'none';
                        area.innerHTML = '';
                        // hide any buttons associated with this year
                        const btns = Array.from(document.querySelectorAll('button[data-year="'+year+'"], button[data-grade-id="'+year+'"]'));
                        btns.forEach(b => { try{ b.style.display = 'none'; }catch(e){} });
                    }
                });
            }catch(err){ console.error('hideBuilderIfSavedExists error', err); }
        }

        // run initial hide pass
        hideBuilderIfSavedExists();

        // Unified Add Subject form (handles both Junior and Senior submissions)
        const addSubjectForm = document.getElementById('add-subject');
        if(addSubjectForm){
            // tag-style subject entry
            const subjectInput = document.getElementById('subject-name');
            const tagsContainer = document.getElementById('subject-tags');
            let subjectNames = [];

            function renderTags(){
                tagsContainer.innerHTML = '';
                subjectNames.forEach((nm, idx) => {
                    const tag = document.createElement('span');
                    tag.className = 'inline-flex items-center bg-gray-100 text-gray-800 px-2 py-0.5 rounded-full text-sm';
                    tag.innerHTML = `<span class="mr-2">${escapeHtml(nm)}</span><button type="button" data-idx="${idx}" class="ml-1 text-gray-500 hover:text-gray-700">&times;</button>`;
                    tagsContainer.appendChild(tag);
                });
                // attach remove handlers
                Array.from(tagsContainer.querySelectorAll('button[data-idx]')).forEach(b => {
                    b.addEventListener('click', function(){
                        const i = Number(this.getAttribute('data-idx'));
                        if(!isNaN(i)){
                            subjectNames.splice(i,1);
                            renderTags();
                        }
                    });
                });
            }

            function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]; }); }

            // add on Enter
            subjectInput.addEventListener('keydown', function(ev){
                if(ev.key === 'Enter'){
                    ev.preventDefault();
                    const val = (subjectInput.value || '').trim();
                    if(!val) return;
                    subjectNames.push(val);
                    subjectInput.value = '';
                    renderTags();
                }
            });

            // also allow pasting newline-separated list
            subjectInput.addEventListener('paste', function(ev){
                const text = (ev.clipboardData || window.clipboardData).getData('text') || '';
                if(text.indexOf('\n') !== -1){
                    ev.preventDefault();
                    const parts = text.split(/\r?\n+/).map(s=>s.trim()).filter(Boolean);
                    subjectNames = subjectNames.concat(parts);
                    subjectInput.value = '';
                    renderTags();
                }
            });

            addSubjectForm.addEventListener('submit', async function(e){
                e.preventDefault();
                const classification = document.getElementById('subject-classification').value;
                const grades = Array.from(addSubjectForm.querySelectorAll('input[name="grades[]"]:checked')).map(i=>i.value);

                // if no names from tags, fallback to single input value
                if(subjectNames.length === 0){
                    const v = (subjectInput.value || '').trim();
                    if(v) subjectNames.push(v);
                }

                if(!subjectNames.length){ if(typeof showToast === 'function') showToast('Provide at least one subject name','error'); else alert('Provide at least one subject name'); return; }
                if(!classification){ if(typeof showToast === 'function') showToast('Select a classification','error'); else alert('Select a classification'); return; }
                if(!grades.length){ if(typeof showToast === 'function') showToast('Select at least one grade','error'); else alert('Select at least one grade'); return; }

                const results = { created: 0, existing: 0, failed: 0 };
                for(const nm of subjectNames){
                    try{
                        const payload = { name: nm, type: classification, grade_levels: grades };
                        const resp = await postJSON(subjectStoreUrl, payload);
                        if(resp && resp.success){
                            if(resp.created === true) results.created++;
                            else results.existing++;
                        } else {
                            results.failed++;
                        }
                    } catch(err){
                        console.error('add subject error', err);
                        results.failed++;
                    }
                }

                // Show summary and reload
                let msg = [];
                if(results.created) msg.push(`${results.created} added`);
                if(results.existing) msg.push(`${results.existing} already existed`);
                if(results.failed) msg.push(`${results.failed} failed`);
                if(msg.length) {
                    if(typeof showToast === 'function') showToast(msg.join(', '),'success'); else alert(msg.join(', '));
                    window.location.reload();
                } else {
                    if(typeof showToast === 'function') showToast('No subjects were added','info'); else alert('No subjects were added');
                }
            });
        }

    // Grade group selectors: toggle ranges (Junior / Senior)
    const selectJunior = document.getElementById('select-junior');
    const selectSenior = document.getElementById('select-senior');
    const gradeCheckboxes = Array.from(document.querySelectorAll('.grade-checkbox'));

        function setGroupChecked(yearsCsv, checked){
            const years = String(yearsCsv).split(',').map(s=>s.trim());
            gradeCheckboxes.forEach(cb => {
                const y = cb.getAttribute('data-year');
                if(years.indexOf(String(y)) !== -1){ cb.checked = checked; }
            });
        }

        if(selectJunior){
            selectJunior.addEventListener('change', function(){ setGroupChecked(this.dataset.years, this.checked); updateGroupStates(); });
        }
        if(selectSenior){
            selectSenior.addEventListener('change', function(){ setGroupChecked(this.dataset.years, this.checked); updateGroupStates(); });
        }

        function updateGroupStates(){
            const juniorYears = (selectJunior && selectJunior.dataset.years) ? selectJunior.dataset.years.split(',') : [];
            const seniorYears = (selectSenior && selectSenior.dataset.years) ? selectSenior.dataset.years.split(',') : [];

            if(selectJunior){
                selectJunior.checked = gradeCheckboxes.filter(cb => juniorYears.indexOf(cb.getAttribute('data-year'))!==-1).every(cb=>cb.checked);
            }
            if(selectSenior){
                selectSenior.checked = gradeCheckboxes.filter(cb => seniorYears.indexOf(cb.getAttribute('data-year'))!==-1).every(cb=>cb.checked);
            }
        }

        gradeCheckboxes.forEach(cb => cb.addEventListener('change', updateGroupStates));

        // Classification visibility rules:
        // Core: always available
        // Special: only when any JHS grade (7-10) selected
        // Strand options (abm, humss, stem, tvl, gas): only when any SHS grade (11-12) selected
        const classificationSelect = document.getElementById('subject-classification');
        const addSubjectBtn = document.querySelector('#add-subject button[type="submit"]');

        function hasJuniorSelected(){
            return Array.from(document.querySelectorAll('input[name="grades[]"]:checked')).some(cb => {
                const y = cb.getAttribute('data-year');
                return y && Number(y) >= 7 && Number(y) <= 10;
            });
        }
        function hasSeniorSelected(){
            return Array.from(document.querySelectorAll('input[name="grades[]"]:checked')).some(cb => {
                const y = cb.getAttribute('data-year');
                return y && Number(y) >= 11 && Number(y) <= 12;
            });
        }

        function updateClassificationOptions(){
            if(!classificationSelect) return;
            const junior = hasJuniorSelected();
            const senior = hasSeniorSelected();

            // iterate options and enable/disable accordingly
            Array.from(classificationSelect.options).forEach(opt => {
                const v = (opt.value || '').toLowerCase();
                if(v === 'core'){ opt.disabled = false; }
                else if(v === 'special'){ opt.disabled = !junior; }
                else if(['abm','humss','stem','tvl','gas'].indexOf(v) !== -1){ opt.disabled = !senior; }
                else { /* other custom types remain enabled */ opt.disabled = false; }
            });

            // if current selection is now disabled, fallback to 'core'
            const cur = (classificationSelect.value || '').toLowerCase();
            const curOpt = Array.from(classificationSelect.options).find(o => (o.value||'').toLowerCase() === cur);
            if(curOpt && curOpt.disabled){
                classificationSelect.value = 'core';
                // brief toast to user
                try{ if(typeof showToast === 'function') showToast('Classification was reset to Core because selected grades changed.','info'); else alert('Classification was reset to Core because selected grades changed.'); }catch(e){}
            }

            // enable/disable submit based on at least one grade selected
            const anyGrade = Array.from(document.querySelectorAll('input[name="grades[]"]:checked')).length > 0;
            if(addSubjectBtn) addSubjectBtn.disabled = !anyGrade;
        }

        // hook into grade checkbox changes
        document.querySelectorAll('input[name="grades[]"]').forEach(cb => cb.addEventListener('change', function(){ updateGroupStates(); updateClassificationOptions(); }));
        if(selectJunior) selectJunior.addEventListener('change', updateClassificationOptions);
        if(selectSenior) selectSenior.addEventListener('change', updateClassificationOptions);

        // initial run
        updateClassificationOptions();

        // Collapsible groups for subject classifications
        document.querySelectorAll('.group-toggle').forEach(btn => {
            const target = btn.dataset.target;
            const body = document.getElementById(target);
            if(!body) return;
            // default: expanded
            body.style.display = 'block';
            btn.addEventListener('click', () => {
                const isHidden = body.style.display === 'none';
                body.style.display = isHidden ? 'block' : 'none';
            });
        });

        // No UI for creating default grade levels per user's preference.

        // ========== OLD SUBJECT MODAL CODE (DISABLED - REPLACED BY NEW MODAL BELOW) ==========
        /* const oldSubjectModal = document.getElementById('subject-modal');
        const oldSubjectModalBody = document.getElementById('subject-modal-body');
        const oldSubjectModalClose = document.getElementById('subject-modal-close');

        function showModal(){
            if(!subjectModal) return;
            subjectModal.classList.remove('hidden');
            subjectModal.classList.add('flex');
        }
        function hideModal(){
            if(!subjectModal) return;
            subjectModal.classList.remove('flex');
            subjectModal.classList.add('hidden');
            subjectModalBody.innerHTML = 'Loading…';
        }

        if(subjectModalClose){ subjectModalClose.addEventListener('click', hideModal); }
        // click outside modal to close
        if(subjectModal){ subjectModal.addEventListener('click', function(e){ if(e.target === subjectModal) hideModal(); }); }

        // Open edit fragment in modal
        async function openSubjectEdit(id){
            try{
                showModal();
                const url = `/admin/subjects/${id}/fragment`;
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                subjectModalBody.innerHTML = html;

                // Find form inside fragment and hijack submit
                const form = subjectModalBody.querySelector('form');
                if(form){
                    form.addEventListener('submit', async function(ev){
                        ev.preventDefault();
                        const fd = new FormData(form);
                        try{
                            const resp = await fetch(form.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': token }, body: fd });
                            if(resp.ok){
                                // update DOM for the subject's name (best-effort) and close modal
                                const nameInput = form.querySelector('input[name="name"]');
                                const newName = nameInput ? nameInput.value.trim() : null;
                                const li = document.querySelector('li[data-subject-id="' + id + '"]');
                                if(li && newName){
                                    const nmEl = li.querySelector('.subject-name'); if(nmEl) nmEl.textContent = newName;
                                }
                                hideModal();
                            } else {
                                // replace modal body with server-returned HTML (validation errors)
                                const text = await resp.text();
                                subjectModalBody.innerHTML = text;
                            }
                        } catch(err){ console.error('update subject error', err); if(typeof showToast === 'function') showToast('Network error while updating subject','error'); else alert('Network error while updating subject'); }
                    });
                }
            } catch(err){ console.error('open fragment error', err); subjectModalBody.innerHTML = '<div class="text-red-600">Failed to load form</div>'; }
        }

        // Delegated clicks for edit/delete
        document.addEventListener('click', async function(e){
            const editBtn = e.target.closest ? e.target.closest('.subject-edit-btn') : null;
            if(editBtn){
                const id = editBtn.getAttribute('data-id');
                if(!id) return;
                // Inline rename: replace the subject name with an input + Save/Cancel
                const li = document.querySelector('li[data-subject-id="' + id + '"]');
                if(!li) return;
                // avoid creating multiple editors
                if(li.classList.contains('editing')) return;
                li.classList.add('editing');
                const nameEl = li.querySelector('.subject-name');
                const original = nameEl ? nameEl.textContent.trim() : '';
                // create input and controls
                const input = document.createElement('input');
                input.type = 'text'; input.value = original; input.className = 'border rounded px-2 py-1 w-72';
                const saveBtn = document.createElement('button'); saveBtn.type = 'button'; saveBtn.className = 'ml-2 px-2 py-1 bg-[#3b4197] text-white rounded text-sm'; saveBtn.textContent = 'Save';
                const cancelBtn = document.createElement('button'); cancelBtn.type = 'button'; cancelBtn.className = 'ml-2 px-2 py-1 border rounded text-sm'; cancelBtn.textContent = 'Cancel';
                // replace contents
                const left = li.querySelector('.subject-name');
                const right = li.querySelector('div.flex.items-center') || li.querySelector('div.flex.items-center.gap-2');
                if(left) left.style.display = 'none';
                if(right) right.style.display = 'none';
                const editContainer = document.createElement('div'); editContainer.className = 'flex items-center gap-2';
                editContainer.appendChild(input); editContainer.appendChild(saveBtn); editContainer.appendChild(cancelBtn);
                li.appendChild(editContainer);
                input.focus(); input.select();

                cancelBtn.addEventListener('click', function(){
                    editContainer.remove();
                    if(left) left.style.display = '';
                    if(right) right.style.display = '';
                    li.classList.remove('editing');
                });

                saveBtn.addEventListener('click', async function(){
                    const newName = (input.value || '').trim();
                    if(!newName){ if(typeof showToast === 'function') showToast('Name cannot be empty','error'); else alert('Name cannot be empty'); return; }
                    saveBtn.disabled = true; saveBtn.textContent = 'Saving…';
                    try{
                        const url = `/admin/subjects/${id}`;
                        const res = await fetch(url, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: JSON.stringify({ name: newName }) });
                        if(res.ok){
                            // update DOM
                            const nmEl = li.querySelector('.subject-name'); if(nmEl) nmEl.textContent = newName;
                            editContainer.remove();
                            if(left) left.style.display = '';
                            if(right) right.style.display = '';
                            li.classList.remove('editing');
                        } else {
                            const json = await res.json().catch(()=>null);
                            if(typeof showToast === 'function') showToast((json && json.message) ? json.message : 'Failed to update subject','error'); else alert((json && json.message) ? json.message : 'Failed to update subject');
                            saveBtn.disabled = false; saveBtn.textContent = 'Save';
                        }
                    } catch(err){ console.error('inline update error', err); if(typeof showToast === 'function') showToast('Network error while saving','error'); else alert('Network error while saving'); saveBtn.disabled = false; saveBtn.textContent = 'Save'; }
                });

                return;
            }
            const delBtn = e.target.closest ? e.target.closest('.subject-delete-btn') : null;
            if(delBtn){
                const id = delBtn.getAttribute('data-id');
                if(!id) return;
                if(!(typeof confirmDialog === 'function' ? await confirmDialog('Delete this subject? This cannot be undone.') : window.confirm('Delete this subject? This cannot be undone.'))) return;
                try{
                        const url = `/admin/subjects/${id}`;
                    const resp = await fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } });
                    if(resp.ok){
                        // remove list item
                        const li = document.querySelector('li[data-subject-id="' + id + '"]');
                        if(li) li.parentNode.removeChild(li);
                    } else {
                        const json = await resp.json().catch(()=>null);
                        if(typeof showToast === 'function') showToast((json && json.message) ? json.message : 'Failed to delete subject','error'); else alert((json && json.message) ? json.message : 'Failed to delete subject');
                    }
                } catch(err){ console.error('delete error', err); if(typeof showToast === 'function') showToast('Network error while deleting','error'); else alert('Network error while deleting'); }
                return;
            }
        });

        // Grade editor: open modal, list/create/update/delete sections
        const gradeModal = document.getElementById('grade-modal');
        const gradeModalBody = document.getElementById('grade-modal-body');
        const gradeModalClose = document.getElementById('grade-modal-close');

        function showGradeModal(){ if(!gradeModal) return; gradeModal.classList.remove('hidden'); gradeModal.classList.add('flex'); }
        function hideGradeModal(){ if(!gradeModal) return; gradeModal.classList.remove('flex'); gradeModal.classList.add('hidden'); gradeModalBody.innerHTML = 'Loading…'; }
        if(gradeModalClose) gradeModalClose.addEventListener('click', hideGradeModal);
        if(gradeModal) gradeModal.addEventListener('click', function(e){ if(e.target === gradeModal) hideGradeModal(); });

        async function openGradeEditor(gradeId, year){
            showGradeModal();
            try{
                const url = `/admin/grade-levels/${gradeId}/sections`;
                const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const json = await res.json();
                const sections = Array.isArray(json.sections) ? json.sections : [];
                renderGradeEditor(gradeId, year, sections);
            } catch(err){ console.error('openGradeEditor error', err); gradeModalBody.innerHTML = '<div class="text-red-600">Failed to load sections</div>'; }
        }

        function renderGradeEditor(gradeId, year, sections){
            gradeModalBody.innerHTML = '';
            const container = document.createElement('div');
            const header = document.createElement('div'); header.className = 'mb-3 flex items-center justify-between';
            header.innerHTML = `<div class="font-medium">Grade ${year} — Manage sections</div>`;
            container.appendChild(header);

            const list = document.createElement('div'); list.className = 'space-y-2';
            sections.forEach(s => list.appendChild(makeSectionRow(gradeId, year, s)));

            const addBtn = document.createElement('button'); addBtn.type='button'; addBtn.className='mt-3 px-3 py-1 bg-green-600 text-white rounded'; addBtn.textContent='Add Section';
            addBtn.addEventListener('click', function(){ list.appendChild(makeSectionRow(gradeId, year, null)); });

            container.appendChild(list);
            container.appendChild(addBtn);

            // Controls: single Save All and Close
            const controls = document.createElement('div'); controls.className = 'mt-3 flex items-center gap-2 justify-end';
            const saveAllBtn = document.createElement('button'); saveAllBtn.type = 'button'; saveAllBtn.className = 'px-3 py-1 bg-[#3b4197] text-white rounded'; saveAllBtn.textContent = 'Save All';
            const closeBtn = document.createElement('button'); closeBtn.type = 'button'; closeBtn.className = 'ml-2 px-3 py-1 border rounded text-sm'; closeBtn.textContent = 'Close';
            controls.appendChild(saveAllBtn); controls.appendChild(closeBtn);
            container.appendChild(controls);

            // Close handler
            closeBtn.addEventListener('click', function(){ hideGradeModal(); });

            // Batch save handler
            saveAllBtn.addEventListener('click', async function(){
                if(!(typeof confirmDialog === 'function' ? await confirmDialog('Apply all changes for this grade?') : window.confirm('Apply all changes for this grade?'))) return;
                saveAllBtn.disabled = true; saveAllBtn.textContent = 'Saving…';
                const rows = Array.from(list.children);
                const promises = rows.map(row => (async () => {
                    try{
                        const nameInput = row.querySelector('input[type="text"]');
                        const name = nameInput ? (nameInput.value||'').trim() : '';
                        if(!name) return { success: false, message: 'Empty name', row };
                        const isJHS = Number(year) < 11;
                        let payload = { name };
                        if(isJHS){ const chk = row.querySelector('input[type="checkbox"]'); payload.is_special = chk && chk.checked ? 1 : 0; }
                        else { const sel = row.querySelector('select'); payload.track = sel ? (sel.value || null) : null; }

                        const sid = row.getAttribute('data-section-id');
                        if(sid){
                            const resp = await fetch(`/admin/sections/${sid}`, { method: 'PUT', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify(payload) });
                            if(!resp.ok){ const j = await resp.json().catch(()=>null); return { success:false, message: (j && j.message) ? j.message : 'Update failed', row }; }
                            const j = await resp.json(); return { success:true, section: j.section, row };
                        } else {
                            const resp = await fetch(`/admin/grade-levels/${gradeId}/sections`, { method: 'POST', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify(payload) });
                            if(!resp.ok){ const j = await resp.json().catch(()=>null); return { success:false, message: (j && j.message) ? j.message : 'Create failed', row }; }
                            const j = await resp.json(); return { success:true, section: j.section, row };
                        }
                    }catch(err){ return { success:false, message: err.message || 'Network error', row }; }
                })());
                const results = await Promise.all(promises);
                const failed = results.filter(r => !r.success);
                // On success: if created sections, add to main list; if updated, update main list text/meta and reposition JHS specials
                results.filter(r => r.success && r.section).forEach(r => {
                    const sec = r.section;
                    const row = r.row;
                    // ensure row has data-section-id
                    if(sec.id) row.setAttribute('data-section-id', sec.id);
                    // update main list: find list container for grade
                    try{
                        const gradePanel = document.querySelector('[data-grade-id="'+gradeId+'"]');
                        if(gradePanel){ const listUl = gradePanel.closest('.border').querySelector('ul');
                            if(listUl){
                                let mainLi = document.querySelector('[data-section-id="'+sec.id+'"]');
                                if(!mainLi){
                                    mainLi = document.createElement('li'); mainLi.className='py-1'; mainLi.setAttribute('data-section-id', sec.id);
                                    mainLi.innerHTML = `<div class="flex items-center justify-between"><div class="font-medium section-name">${esc(sec.name)}</div><div class="text-xs text-slate-500 section-meta">${sec.track ? esc(sec.track) : (sec.is_special ? 'Special' : '')}</div></div>`;
                                    if(Number(year) < 11 && sec.is_special){ listUl.insertBefore(mainLi, listUl.firstChild); } else { listUl.appendChild(mainLi); }
                                } else {
                                    const nEl = mainLi.querySelector('.section-name'); if(nEl) nEl.textContent = sec.name;
                                    const mEl = mainLi.querySelector('.section-meta'); if(mEl) mEl.textContent = sec.track ? sec.track : (sec.is_special ? 'Special' : '');
                                    // reposition for JHS
                                    if(Number(year) < 11){ const parentUl = mainLi.closest('ul'); if(parentUl){ if(sec.is_special){ const firstNonSpecial = Array.from(parentUl.querySelectorAll('li')).find(li => { const meta = li.querySelector('.section-meta'); return !(meta && meta.textContent.trim() === 'Special'); }); if(firstNonSpecial) parentUl.insertBefore(mainLi, firstNonSpecial); else parentUl.insertBefore(mainLi, parentUl.firstChild); } else { // move after last special
                                                    const lis = Array.from(parentUl.querySelectorAll('li'));
                                                    let insertAfter = null; for(let i=0;i<lis.length;i++){ const li = lis[i]; const meta = li.querySelector('.section-meta'); if(meta && meta.textContent.trim() === 'Special'){ insertAfter = li; } }
                                                    if(insertAfter && insertAfter !== mainLi) insertAfter.parentNode.insertBefore(mainLi, insertAfter.nextSibling);
                                                } } }
                                }
                            }
                        }
                    }catch(e){ console.error('update main list error', e); }
                });

                saveAllBtn.disabled = false; saveAllBtn.textContent = 'Save All';
                if(failed.length){
                    if(typeof showToast === 'function') showToast('Some rows failed to save. Please check and try again.','error'); else alert('Some rows failed to save. Please check and try again.');
                } else {
                    // everything saved — refresh to ensure server-side ordering and counts are consistent
                    location.reload();
                }
            });

            gradeModalBody.appendChild(container);
        }

        function makeSectionRow(gradeId, year, s){
            const row = document.createElement('div'); row.className = 'flex items-center gap-2';
            const nameInput = document.createElement('input'); nameInput.type='text'; nameInput.className='border rounded px-2 py-1 flex-1'; nameInput.value = s ? (s.name || '') : '';
            row.appendChild(nameInput);

            if(Number(year) < 11){
                const chk = document.createElement('input'); chk.type='checkbox'; chk.className='ml-2'; chk.checked = s ? !!s.is_special : false;
                const lbl = document.createElement('label'); lbl.className='text-sm ml-2'; lbl.appendChild(chk); lbl.insertAdjacentText('beforeend',' Special');
                row.appendChild(lbl);
            } else {
                const sel = document.createElement('select'); sel.className='border rounded px-2 py-1'; sel.innerHTML = `<option value="">— Track —</option>`;
                SHS_TRACK_OPTIONS.forEach(t => { const o = document.createElement('option'); o.value = t; o.textContent = t; if(s && s.track === t) o.selected = true; sel.appendChild(o); });
                row.appendChild(sel);
            }

            // Add a delete/remove button: Delete for persisted sections, Remove for new rows
            const delBtn = document.createElement('button'); delBtn.type='button'; delBtn.className='ml-2 px-2 py-1 border rounded text-sm text-red-600'; delBtn.textContent = s && s.id ? 'Delete' : 'Remove';
            row.appendChild(delBtn);

            if(s && s.id){
                // mark row with section id so batch save can detect persisted rows
                row.setAttribute('data-section-id', s.id);
                delBtn.addEventListener('click', async function(){
                    const ok = (typeof confirmDialog === 'function') ? await confirmDialog('Delete section?') : window.confirm('Delete section?');
                    if(!ok) return;
                    try{
                        const resp = await fetch(`/admin/sections/${s.id}`, { method:'DELETE', headers: {'X-CSRF-TOKEN': token, 'Accept':'application/json'} });
                        if(resp.ok){ row.remove(); const mainLi = document.querySelector('[data-section-id="'+s.id+'"]'); if(mainLi) mainLi.remove(); }
                        else { const j = await resp.json().catch(()=>null); if(typeof showToast === 'function') showToast((j && j.message) ? j.message : 'Failed','error'); else alert((j && j.message) ? j.message : 'Failed'); }
                    }catch(err){ console.error(err); if(typeof showToast === 'function') showToast('Network error','error'); else alert('Network error'); }
                });
            } else {
                delBtn.addEventListener('click', function(){ row.remove(); });
            }

            return row;
        }

        function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
        */ 
        // ========== END OLD SUBJECT MODAL CODE ==========

        // Delegated grade-edit button handler
        document.addEventListener('click', function(e){
            const gbtn = e.target.closest ? e.target.closest('.grade-edit-btn') : null;
            if(gbtn){ const gid = gbtn.getAttribute('data-grade-id'); const yr = gbtn.getAttribute('data-year'); if(gid) openGradeEditor(gid, yr); }
        });

        // ========== SUBJECTS MANAGEMENT ==========
        const subjectsTable = document.getElementById('subjects-table');
        const subjectModal = document.getElementById('subject-modal');
        const subjectForm = document.getElementById('subject-form');
        const modalTitle = document.getElementById('modal-title');
        const btnAddSubject = document.getElementById('btn-add-subject');
        const btnCloseModal = document.getElementById('close-modal');
        const btnCancelModal = document.getElementById('cancel-modal');
        const subjectStrand = document.getElementById('subject-strand');

        // Filter functionality
        const filterGrade = document.getElementById('filter-grade');
        const filterStrand = document.getElementById('filter-strand');
        const filterType = document.getElementById('filter-type');

        // Get all Junior High grade IDs for filtering
        const juniorHighGradeIds = Array.from(document.querySelectorAll('input[id="junior-high-checkbox"]')).length > 0 
            ? JSON.parse(document.querySelector('input[id="junior-high-checkbox"]')?.getAttribute('data-grade-ids') || '[]')
            : [];

        function applyFilters() {
            const gradeFilter = filterGrade.value;
            const strandId = filterStrand.value;
            const type = filterType.value;

            const rows = subjectsTable.querySelectorAll('tbody tr[data-subject-id]');
            rows.forEach(row => {
                let show = true;
                
                if (gradeFilter) {
                    const gradeIds = row.dataset.gradeIds.split(',').filter(id => id);
                    if (gradeFilter === 'junior-high') {
                        // Show if row has ANY Junior High grade
                        show = show && gradeIds.length > 0;
                    } else {
                        // Show if row has the specific grade ID
                        show = show && gradeIds.includes(gradeFilter);
                    }
                }
                
                if (strandId) {
                    show = show && row.dataset.strandId === strandId;
                }
                
                if (type) {
                    show = show && row.dataset.type === type;
                }
                
                row.style.display = show ? '' : 'none';
            });

            // Show/hide empty row
            const emptyRow = document.getElementById('empty-row');
            const visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');
            if (emptyRow) {
                emptyRow.style.display = visibleRows.length === 0 ? '' : 'none';
            }
        }

        // Update strand filter visibility based on grade selection
        function updateStrandFilterState() {
            if (!filterStrand) return;
            const gradeFilter = filterGrade.value;
            const isSH = gradeFilter === 'senior-high' || (gradeFilter && parseInt(gradeFilter) > 10);
            if (isSH) {
                filterStrand.disabled = false;
                filterStrand.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                filterStrand.value = '';
                filterStrand.disabled = true;
                filterStrand.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        if (filterGrade) {
            filterGrade.addEventListener('change', () => {
                updateStrandFilterState();
                applyFilters();
            });
        }
        if (filterStrand) filterStrand.addEventListener('change', applyFilters);
        if (filterType) filterType.addEventListener('change', applyFilters);

        // Open modal for adding
        if (btnAddSubject) {
            btnAddSubject.addEventListener('click', () => {
                modalTitle.textContent = 'Add Subject';
                subjectForm.reset();
                document.getElementById('subject-id').value = '';
                if (juniorHighCheckbox) juniorHighCheckbox.checked = false;
                if (juniorHighInputsContainer) juniorHighInputsContainer.innerHTML = '';
                updateStrandLock();
                openModal();
            });
        }

        // Close modal
        function closeModal() {
            subjectModal.classList.add('hidden');
            subjectModal.style.display = 'none';
            subjectForm.reset();
        }

        function openModal() {
            subjectModal.classList.remove('hidden');
            subjectModal.style.display = 'flex';
        }

        if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
        if (btnCancelModal) btnCancelModal.addEventListener('click', closeModal);

        // Handle Junior High checkbox and strand lock (SHS only)
        const juniorHighCheckbox = document.getElementById('junior-high-checkbox');
        const juniorHighInputsContainer = document.getElementById('junior-high-inputs');

        function updateStrandLock() {
            if (!subjectStrand) return;
            const shChecks = document.querySelectorAll('input[name="grade_levels[]"][data-shs="1"]');
            const anySH = Array.from(shChecks).some(cb => cb.checked && !cb.disabled);
            if (anySH) {
                subjectStrand.disabled = false;
                subjectStrand.classList.remove('opacity-50','cursor-not-allowed');
            } else {
                subjectStrand.value = '';
                subjectStrand.disabled = true;
                subjectStrand.classList.add('opacity-50','cursor-not-allowed');
            }
        }

        if (juniorHighCheckbox) {
            juniorHighCheckbox.addEventListener('change', function() {
                const gradeIds = JSON.parse(this.getAttribute('data-grade-ids'));
                juniorHighInputsContainer.innerHTML = '';
                
                if (this.checked) {
                    gradeIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'grade_levels[]';
                        input.value = id;
                        juniorHighInputsContainer.appendChild(input);
                    });
                }
                updateStrandLock();
            });
        }

        // Senior High checkboxes (future use) toggle strand state
        document.querySelectorAll('input[name="grade_levels[]"][data-shs="1"]').forEach(cb => {
            cb.addEventListener('change', updateStrandLock);
        });

        // Edit subject
        document.addEventListener('click', async function(e) {
            const editBtn = e.target.closest('.btn-edit-subject');
            if (!editBtn) return;

            const id = editBtn.dataset.id;
            try {
                const resp = await fetch(`/admin/subjects/${id}`);
                const data = await resp.json();
                
                if (data.subject) {
                    const s = data.subject;
                    modalTitle.textContent = 'Edit Subject';
                    document.getElementById('subject-id').value = s.id;
                    document.getElementById('subject-name-input').value = s.name || '';
                    document.getElementById('subject-code').value = s.code || '';
                    if (subjectStrand) subjectStrand.value = s.strand_id || '';
                    document.getElementById('subject-type').value = s.type || '';
                    document.getElementById('subject-hours').value = s.hours_per_week || '';

                    // Check Junior High checkbox if any Junior High grades are selected
                    const juniorHighCheckbox = document.getElementById('junior-high-checkbox');
                    if (juniorHighCheckbox) {
                        if (juniorHighInputsContainer) juniorHighInputsContainer.innerHTML = '';
                        const juniorHighIds = JSON.parse(juniorHighCheckbox.getAttribute('data-grade-ids'));
                        const hasJuniorHigh = s.grade_levels && juniorHighIds.some(id => s.grade_levels.includes(id));
                        juniorHighCheckbox.checked = hasJuniorHigh;
                        
                        // Trigger change to create hidden inputs
                        if (hasJuniorHigh) juniorHighCheckbox.dispatchEvent(new Event('change'));
                        else if (juniorHighInputsContainer) juniorHighInputsContainer.innerHTML = '';
                    }

                    // Update strand lock based on SHS selection (or absence)
                    updateStrandLock();

                    // Check individual grade levels (for Grade 11 and 12 when enabled)
                    document.querySelectorAll('input[name="grade_levels[]"]:not([disabled])').forEach(cb => {
                        if (!cb.id || cb.id !== 'junior-high-checkbox') {
                            cb.checked = s.grade_levels && s.grade_levels.includes(parseInt(cb.value));
                        }
                    });

                    openModal();
                }
            } catch (err) {
                console.error('Failed to load subject:', err);
                alert('Failed to load subject data');
            }
        });

        // Delete subject
        document.addEventListener('click', async function(e) {
            const delBtn = e.target.closest('.btn-delete-subject');
            if (!delBtn) return;

            const id = delBtn.dataset.id;
            const ok = window.confirm('Delete this subject? This action cannot be undone.');
            if (!ok) return;

            try {
                const resp = await fetch(`/admin/subjects/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });

                if (resp.ok) {
                    const row = subjectsTable.querySelector(`tr[data-subject-id="${id}"]`);
                    if (row) row.remove();
                    
                    // Show empty row if no subjects left
                    const remainingRows = subjectsTable.querySelectorAll('tbody tr[data-subject-id]');
                    if (remainingRows.length === 0) {
                        const emptyRow = document.getElementById('empty-row');
                        if (emptyRow) emptyRow.style.display = '';
                    }
                } else {
                    const data = await resp.json();
                    alert(data.message || 'Failed to delete subject');
                }
            } catch (err) {
                console.error('Delete failed:', err);
                alert('Network error');
            }
        });

        // Save subject (add/edit)
        if (subjectForm) {
            subjectForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const id = document.getElementById('subject-id').value;
                const formData = new FormData(subjectForm);
                
                const data = {
                    name: formData.get('name'),
                    code: formData.get('code'),
                    strand_id: subjectStrand && !subjectStrand.disabled ? (formData.get('strand_id') || null) : null,
                    type: formData.get('type'),
                    hours_per_week: formData.get('hours_per_week'),
                    grade_levels: formData.getAll('grade_levels[]')
                };

                try {
                    const url = id ? `/admin/subjects/${id}` : '/admin/subjects';
                    const method = id ? 'PUT' : 'POST';

                    const resp = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await resp.json();

                    if (resp.ok) {
                        closeModal();
                        // Store active tab before reload
                        const activeTab = document.querySelector('.ss-tab[aria-pressed="true"]')?.id || 'tab-sections';
                        sessionStorage.setItem('activeTab', activeTab);
                        // Reload page to refresh the table
                        window.location.reload();
                    } else {
                        alert(result.message || 'Failed to save subject');
                    }
                } catch (err) {
                    console.error('Save failed:', err);
                    alert('Network error');
                }
            });
        }

    })();
</script>
<?php $__env->stopSection(); ?>


    
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/admin/sections/subjects-sections.blade.php ENDPATH**/ ?>