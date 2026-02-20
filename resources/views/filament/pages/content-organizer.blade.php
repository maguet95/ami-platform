<x-filament-panels::page>
    @push('styles')
    <style>
        .co-search { width:100%; padding:0.5rem 0.75rem; border:1px solid var(--co-border); border-radius:0.5rem; font-size:0.875rem; background:var(--co-bg); color:var(--co-text); outline:none; transition:border-color 0.15s; }
        .co-search:focus { border-color:#6366f1; box-shadow:0 0 0 2px rgba(99,102,241,0.15); }
        .co-search::placeholder { color:var(--co-muted); }
        .co-tree { display:flex; flex-direction:column; gap:0.5rem; }

        .co-course { border:1px solid var(--co-border); border-radius:0.625rem; background:var(--co-bg); overflow:hidden; transition:box-shadow 0.15s; }
        .co-course:hover { box-shadow:0 1px 3px rgba(0,0,0,0.06); }
        .co-course-header { display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.625rem; cursor:default; }
        .co-module-header { display:flex; align-items:center; gap:0.5rem; padding:0.375rem 0.625rem 0.375rem 1.75rem; }
        .co-lesson-row { display:flex; align-items:center; gap:0.5rem; padding:0.25rem 0.625rem 0.25rem 3.25rem; border-top:1px solid var(--co-border-light); }

        .co-grip { cursor:grab; color:var(--co-muted); font-size:0.875rem; user-select:none; opacity:0.4; transition:opacity 0.15s; line-height:1; }
        .co-grip:hover { opacity:0.8; }
        .co-grip:active { cursor:grabbing; }

        .co-chevron { background:none; border:none; cursor:pointer; color:var(--co-muted); font-size:0.5rem; padding:0.25rem; transition:transform 0.2s; display:inline-flex; }
        .co-chevron.open { transform:rotate(90deg); }

        .co-title { flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; background:none; border:none; cursor:pointer; text-align:left; color:var(--co-text); }
        .co-title-course { font-size:0.8125rem; font-weight:600; }
        .co-title-module { font-size:0.8125rem; font-weight:500; color:var(--co-text-sec); }
        .co-title-lesson { font-size:0.75rem; color:var(--co-text-sec); cursor:default; }

        .co-badge { font-size:0.5625rem; font-weight:600; padding:0.0625rem 0.375rem; border-radius:0.25rem; white-space:nowrap; letter-spacing:0.02em; text-transform:uppercase; }
        .co-badge-pub { background:#dcfce7; color:#15803d; }
        .co-badge-draft { background:#fef3c7; color:#a16207; }
        .co-badge-archived { background:#f1f5f9; color:#64748b; }
        .co-badge-free { background:#dbeafe; color:#1d4ed8; font-size:0.5rem; }
        .co-badge-type { font-size:0.625rem; }

        .co-meta { font-size:0.625rem; color:var(--co-muted); white-space:nowrap; }

        .co-actions { display:flex; align-items:center; gap:0.125rem; margin-left:auto; }
        .co-btn { background:none; border:none; cursor:pointer; padding:0.25rem; color:var(--co-muted); font-size:0.8125rem; line-height:1; border-radius:0.25rem; transition:all 0.15s; opacity:0.5; }
        .co-btn:hover { opacity:1; background:var(--co-hover); }
        .co-btn-danger:hover { color:#ef4444; }

        .co-modules { background:var(--co-bg-sub); }
        .co-module-item { border-bottom:1px solid var(--co-border-light); }
        .co-module-item:last-child { border-bottom:none; }

        .co-empty { padding:0.75rem 1.75rem; text-align:center; font-size:0.75rem; color:var(--co-muted); font-style:italic; }

        .co-icon { font-size:0.875rem; flex-shrink:0; line-height:1; }
        .co-icon-course { color:#f59e0b; }
        .co-icon-module { color:#6366f1; }
        .co-icon-video { color:#3b82f6; }
        .co-icon-text { color:#10b981; }
        .co-icon-quiz { color:#8b5cf6; }

        .sortable-ghost { opacity:0.35; }

        :root {
            --co-bg: #fff; --co-bg-sub: #f8fafc; --co-text: #1e293b; --co-text-sec: #475569;
            --co-muted: #94a3b8; --co-border: #e2e8f0; --co-border-light: #f1f5f9; --co-hover: #f1f5f9;
        }
        .dark {
            --co-bg: #1e293b; --co-bg-sub: #0f172a; --co-text: #f1f5f9; --co-text-sec: #cbd5e1;
            --co-muted: #64748b; --co-border: #334155; --co-border-light: #1e293b; --co-hover: #334155;
        }
    </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    @endpush

    <div x-data="contentOrganizer(@js($this->getCourses()))">
        {{-- Search --}}
        <div style="margin-bottom:0.75rem;">
            <input type="text" x-model="search" placeholder="Buscar por titulo..." class="co-search" />
        </div>

        {{-- Empty state --}}
        <div x-show="filteredCourses.length === 0" class="co-empty" style="padding:3rem;border:2px dashed var(--co-border);border-radius:0.625rem;">
            <span x-text="search ? 'No se encontraron resultados' : 'No hay cursos creados todavia'"></span>
        </div>

        {{-- Course list --}}
        <div x-ref="courseList" class="co-tree">
            <template x-for="course in filteredCourses" :key="'c-'+course.id">
                <div :data-id="course.id" class="co-course">
                    <div class="co-course-header">
                        <span class="co-grip drag-handle">&#x2630;</span>
                        <button @click="toggleCourse(course.id)" class="co-chevron" :class="isCourseExpanded(course.id) && 'open'">&#9654;</button>
                        <span class="co-icon co-icon-course">&#128218;</span>
                        <button @click="toggleCourse(course.id)" class="co-title co-title-course" x-text="course.title"></button>
                        <span class="co-badge" :class="course.status==='published'?'co-badge-pub':course.status==='draft'?'co-badge-draft':'co-badge-archived'" x-text="course.statusLabel"></span>
                        <span class="co-meta" x-text="course.modules_count+' mod, '+course.lessons_count+' lec'"></span>
                        <div class="co-actions">
                            <button @click="$wire.mountAction('createModule',{course_id:course.id})" class="co-btn" title="Agregar modulo">+</button>
                            <button @click="$wire.mountAction('editCourse',{id:course.id})" class="co-btn" title="Editar">&#9998;</button>
                            <button @click="$wire.mountAction('confirmDeleteCourse',{id:course.id})" class="co-btn co-btn-danger" title="Eliminar">&#128465;</button>
                        </div>
                    </div>

                    <div x-show="isCourseExpanded(course.id)" x-collapse>
                        <div :data-course-id="course.id" class="co-modules module-list">
                            <template x-for="mod in course.modules" :key="'m-'+mod.id">
                                <div :data-id="mod.id" class="co-module-item">
                                    <div class="co-module-header">
                                        <span class="co-grip drag-handle">&#x2630;</span>
                                        <button @click="toggleModule(mod.id)" class="co-chevron" :class="isModuleExpanded(mod.id) && 'open'">&#9654;</button>
                                        <span class="co-icon co-icon-module">&#128194;</span>
                                        <button @click="toggleModule(mod.id)" class="co-title co-title-module" x-text="mod.title"></button>
                                        <span class="co-badge" :class="mod.is_published?'co-badge-pub':'co-badge-draft'" x-text="mod.is_published?'Pub':'Borr'"></span>
                                        <span class="co-meta" x-text="mod.lessons_count+' lec'"></span>
                                        <div class="co-actions">
                                            <button @click="$wire.mountAction('createLesson',{module_id:mod.id})" class="co-btn" title="Agregar leccion">+</button>
                                            <button @click="$wire.mountAction('editModule',{id:mod.id})" class="co-btn" title="Editar">&#9998;</button>
                                            <button @click="$wire.mountAction('confirmDeleteModule',{id:mod.id})" class="co-btn co-btn-danger" title="Eliminar">&#128465;</button>
                                        </div>
                                    </div>

                                    <div x-show="isModuleExpanded(mod.id)" x-collapse>
                                        <div :data-module-id="mod.id" class="lesson-list">
                                            <template x-for="lesson in mod.lessons" :key="'l-'+lesson.id">
                                                <div :data-id="lesson.id" class="co-lesson-row">
                                                    <span class="co-grip drag-handle">&#x2630;</span>
                                                    <span class="co-icon" :class="lesson.type==='video'?'co-icon-video':lesson.type==='text'?'co-icon-text':'co-icon-quiz'"
                                                          x-text="lesson.type==='video'?'\u25B6':lesson.type==='text'?'\uD83D\uDCC4':'\u2753'"></span>
                                                    <span class="co-title co-title-lesson" x-text="lesson.title"></span>
                                                    <span class="co-badge" :class="lesson.is_published?'co-badge-pub':'co-badge-draft'" x-text="lesson.is_published?'Pub':'Borr'"></span>
                                                    <span x-show="lesson.is_free_preview" class="co-badge co-badge-free">Gratis</span>
                                                    <span x-show="lesson.duration_minutes>0" class="co-meta" x-text="lesson.duration_minutes+'min'"></span>
                                                    <div class="co-actions">
                                                        <button @click="$wire.mountAction('editLesson',{id:lesson.id})" class="co-btn" title="Editar">&#9998;</button>
                                                        <button @click="$wire.mountAction('confirmDeleteLesson',{id:lesson.id})" class="co-btn co-btn-danger" title="Eliminar">&#128465;</button>
                                                    </div>
                                                </div>
                                            </template>
                                            <div x-show="mod.lessons.length===0" class="co-empty">Sin lecciones</div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="course.modules.length===0" class="co-empty">Sin modulos</div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('contentOrganizer', (initialCourses) => ({
            courses: initialCourses,
            search: '',
            expandedCourses: new Set(),
            expandedModules: new Set(),
            sortableInstances: [],

            init() {
                this.$nextTick(() => this.initSortable());
                Livewire.on('tree-updated', (data) => {
                    this.courses = data.courses || data[0]?.courses || data[0] || [];
                    this.$nextTick(() => this.initSortable());
                });
            },

            get filteredCourses() {
                if (!this.search) return this.courses;
                const q = this.search.toLowerCase();
                return this.courses.filter(c =>
                    c.title.toLowerCase().includes(q) ||
                    c.modules.some(m =>
                        m.title.toLowerCase().includes(q) ||
                        m.lessons.some(l => l.title.toLowerCase().includes(q))
                    )
                );
            },

            toggleCourse(id) {
                if (this.expandedCourses.has(id)) this.expandedCourses.delete(id);
                else this.expandedCourses.add(id);
                this.expandedCourses = new Set(this.expandedCourses);
                this.$nextTick(() => this.initSortable());
            },

            toggleModule(id) {
                if (this.expandedModules.has(id)) this.expandedModules.delete(id);
                else this.expandedModules.add(id);
                this.expandedModules = new Set(this.expandedModules);
                this.$nextTick(() => this.initSortable());
            },

            isCourseExpanded(id) { return this.expandedCourses.has(id); },
            isModuleExpanded(id) { return this.expandedModules.has(id); },

            // Reorder Alpine array by ID list
            reorderArray(arr, ids) {
                const map = new Map(arr.map(item => [item.id, item]));
                return ids.map(id => map.get(id)).filter(Boolean);
            },

            initSortable() {
                this.sortableInstances.forEach(s => s.destroy());
                this.sortableInstances = [];
                if (typeof Sortable === 'undefined') return;

                const self = this;

                // Course-level
                const courseList = this.$refs.courseList;
                if (courseList) {
                    this.sortableInstances.push(new Sortable(courseList, {
                        handle: '.drag-handle',
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        onEnd(evt) {
                            // Capture order, revert DOM, update Alpine data, persist
                            const ids = [...courseList.querySelectorAll(':scope > [data-id]')]
                                .map(el => parseInt(el.dataset.id));
                            // Revert DOM move
                            if (evt.oldIndex !== evt.newIndex) {
                                const parent = evt.from;
                                parent.removeChild(evt.item);
                                parent.insertBefore(evt.item, parent.children[evt.oldIndex] || null);
                            }
                            // Update Alpine data directly
                            self.courses = self.reorderArray(self.courses, ids);
                            // Persist
                            self.$wire.reorderCourses(ids);
                        }
                    }));
                }

                // Module-level
                document.querySelectorAll('.module-list[data-course-id]').forEach(el => {
                    this.sortableInstances.push(new Sortable(el, {
                        handle: '.drag-handle',
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        group: 'modules',
                        onEnd(evt) {
                            const toCourseId = parseInt(evt.to.dataset.courseId);
                            const moduleId = parseInt(evt.item.dataset.id);
                            const newIds = [...evt.to.querySelectorAll(':scope > [data-id]')]
                                .map(el => parseInt(el.dataset.id));

                            // Revert DOM
                            evt.to.removeChild(evt.item);
                            if (evt.from === evt.to) {
                                evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex] || null);
                            } else {
                                evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex] || null);
                            }

                            if (evt.from === evt.to) {
                                // Reorder within same course
                                const course = self.courses.find(c => c.id === toCourseId);
                                if (course) course.modules = self.reorderArray(course.modules, newIds);
                                self.$wire.reorderModules(toCourseId, newIds);
                            } else {
                                self.$wire.moveModule(moduleId, toCourseId, evt.newIndex);
                            }
                        }
                    }));
                });

                // Lesson-level
                document.querySelectorAll('.lesson-list[data-module-id]').forEach(el => {
                    this.sortableInstances.push(new Sortable(el, {
                        handle: '.drag-handle',
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        group: 'lessons',
                        onEnd(evt) {
                            const toModuleId = parseInt(evt.to.dataset.moduleId);
                            const lessonId = parseInt(evt.item.dataset.id);
                            const newIds = [...evt.to.querySelectorAll(':scope > [data-id]')]
                                .map(el => parseInt(el.dataset.id));

                            // Revert DOM
                            evt.to.removeChild(evt.item);
                            if (evt.from === evt.to) {
                                evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex] || null);
                            } else {
                                evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex] || null);
                            }

                            if (evt.from === evt.to) {
                                const course = self.courses.find(c => c.modules.some(m => m.id === toModuleId));
                                if (course) {
                                    const mod = course.modules.find(m => m.id === toModuleId);
                                    if (mod) mod.lessons = self.reorderArray(mod.lessons, newIds);
                                }
                                self.$wire.reorderLessons(toModuleId, newIds);
                            } else {
                                self.$wire.moveLesson(lessonId, toModuleId, evt.newIndex);
                            }
                        }
                    }));
                });
            }
        }));
    });
    </script>
    @endpush
</x-filament-panels::page>
