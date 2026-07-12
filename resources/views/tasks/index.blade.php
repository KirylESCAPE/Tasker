@extends('layouts.app')

@section('title', 'My tasks')

@section('content')
    <div class="row mb-4">

        <div class="col-md-8">
            <h2 class="text-dark-blue">My tasks</h2>
            @guest
                <p class="text-light-blue">
                    <i class="bi bi-info-circle me-1"></i>
                    <a href="{{ route('register') }}" class="text-primary fw-semibold">Sign up</a>
                    to keep the tasks
                </p>
            @endguest
        </div>

        <div class="col-md-4 text-end">
            <form action="{{ route('tasks.index') }}" method="GET" class="d-inline">
                <div class="input-group search-box shadow-sm">
                    <input type="text"
                           name="search"
                           id="searchInput"
                           class="form-control border-primary-subtle"
                           placeholder="Search..."
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary-gradient">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <div class="mt-4">
        {{ $tasks->links('pagination::bootstrap-5') }}
    </div>

    <div class="card border-primary-subtle shadow-sm mb-4 bg-light-gradient">
        <div class="card-body">
            <h5 class="card-title text-dark-blue mb-3 fw-bold">
                <i class="bi bi-plus-circle me-2"></i>New task
            </h5>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label text-muted small fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="title"
                                   id="taskTitle"
                                   class="form-control border-primary-subtle"
                                   placeholder="Title"
                                   value="{{ old('title') }}"
                                   required
                                   maxlength="255"
                                   oninput="updateCharCounter(this, 'titleCounter')">
                            <div class="form-text d-flex justify-content-between mt-1">
                                <span class="text-muted">Max length 255</span>
                                <span class="char-counter text-end" id="titleCounter">255</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label text-muted small fw-semibold">Description</label>
                            <textarea name="description"
                                      id="taskDescription"
                                      class="form-control border-primary-subtle"
                                      placeholder="Description"
                                      rows="6"
                                      maxlength="3000"
                                      oninput="updateCharCounter(this, 'descCounter')"
                                      style="resize: vertical; min-height: 120px;">{{ old('description') }}</textarea>
                            <div class="form-text d-flex justify-content-between mt-1">
                                <span class="text-muted">Max length 3000 </span>
                                <span class="char-counter text-end" id="descCounter">3000</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-start">
                        <button type="submit" class="btn btn-primary-gradient w-100 py-3">
                            <i class="bi bi-plus-lg me-1"></i>Add
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('tasks.index', ['status' => 'all', 'search' => request('search')]) }}"
               class="btn btn-filter {{ request('status', 'all') == 'all' ? 'active btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-list-ul me-2"></i>All
                <span class="badge bg-light text-primary ms-2">{{ $stats['total'] ?? 0 }}</span>
            </a>
            <a href="{{ route('tasks.index', ['status' => 'active', 'search' => request('search')]) }}"
               class="btn btn-filter {{ request('status') == 'active' ? 'active btn-info' : 'btn-outline-info' }}">
                <i class="bi bi-circle me-2"></i>Active
                <span class="badge bg-light text-info ms-2">{{ $stats['active'] ?? 0 }}</span>
            </a>
            <a href="{{ route('tasks.index', ['status' => 'completed', 'search' => request('search')]) }}"
               class="btn btn-filter {{ request('status') == 'completed' ? 'active btn-success' : 'btn-outline-success' }}">
                <i class="bi bi-check-circle me-2"></i>Done
                <span class="badge bg-light text-success ms-2">{{ $stats['completed'] ?? 0 }}</span>
            </a>
        </div>
    </div>

    @if($tasks->count() > 0)
        <div class="tasks-container">
            @foreach($tasks as $task)
                <div class="task-card card shadow-sm border-start-4 {{ $task->is_completed ? 'border-success bg-success-subtle' : 'border-primary' }} mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="task-checkbox">
                                        <input type="checkbox"
                                               id="task-{{ $task->id }}"
                                               {{ $task->is_completed ? 'checked' : '' }}
                                               onchange="this.closest('form').submit()">
                                        <label for="task-{{ $task->id }}" class="checkbox-label">
                                            @if($task->is_completed)
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            @else
                                                <i class="bi bi-circle text-primary"></i>
                                            @endif
                                        </label>
                                    </div>
                                </form>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        @if($task->title)
                                            <h6 class="task-title mb-1 {{ $task->is_completed ? 'text-muted text-decoration-line-through' : 'text-dark' }}">
                                                {{ $task->title }}
                                            </h6>
                                        @endif
                                        <p class="task-description mb-2 {{ $task->is_completed ? 'text-muted' : 'text-secondary' }}">
                                            {{ $task->description }}
                                        </p>
                                        <div class="task-meta">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $task->created_at->format('d.m.Y H:i') }}
                                                @if($task->is_completed && $task->completed_at)
                                                    <span class="ms-3">
                                                        <i class="bi bi-check2-circle me-1"></i>
                                                        Done: {{ $task->completed_at->format('d.m.Y H:i') }}
                                                    </span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>

                                    <div class="task-actions ms-3">
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $task->id }}"
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('tasks.delete', $task) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete this task?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editModal{{ $task->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content border-primary">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-pencil-square me-2"></i>Edit
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('tasks.update', $task) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label text-dark-blue">Title <span class="text-danger">*</span></label>
                                        <input type="text"
                                               name="title"
                                               id="editTitle{{ $task->id }}"
                                               class="form-control border-primary-subtle edit-title"
                                               value="{{ $task->title }}"
                                               placeholder=""
                                               required
                                               maxlength="255"
                                               data-task-id="{{ $task->id }}"
                                               oninput="updateCharCounter(this, 'editTitleCounter{{ $task->id }}')">
                                        <div class="form-text d-flex justify-content-between mt-1">
                                            <span class="text-muted">Max length 255</span>
                                            <span class="char-counter" id="editTitleCounter{{ $task->id }}">
                                                {{ 255 - strlen($task->title ?? '') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-dark-blue">Description</label>
                                        <textarea name="description"
                                                  id="editDescription{{ $task->id }}"
                                                  class="form-control border-primary-subtle edit-description"
                                                  rows="5"
                                                  maxlength="3000"
                                                  data-task-id="{{ $task->id }}"
                                                  oninput="updateCharCounter(this, 'editDescCounter{{ $task->id }}')">{{ $task->description }}</textarea>
                                        <div class="form-text d-flex justify-content-between mt-1">
                                            <span class="text-muted">Max length 3000</span>
                                            <span class="char-counter" id="editDescCounter{{ $task->id }}">
                                                {{ 3000 - strlen($task->description ?? '') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary-gradient">
                                        <i class="bi bi-save me-1"></i>Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state text-center py-5">
            <div class="empty-state-icon mb-3">
                <i class="bi bi-clipboard2-x text-primary" style="font-size: 4rem;"></i>
            </div>
            @if(request('search'))
                <h4 class="text-dark-blue mb-2">Nothing found</h4>
                <p class="text-muted mb-3">
                    Nothing found by "{{ request('search') }}"
                </p>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>Back to the tasks
                </a>
            @else
                <h4 class="text-dark-blue mb-2">No tasks</h4>
            @endif
        </div>
    @endif
@endsection

@push('styles')
    <style>
        :root {
            --primary-blue: #0d6efd;
            --primary-blue-light: #e3f2fd;
            --primary-blue-dark: #0a58ca;
            --secondary-blue: #6c757d;
            --success-green: #198754;
            --success-green-light: #d1e7dd;
            --info-blue: #0dcaf0;
            --info-blue-light: #d1ecf1;
            --light-bg: #f8f9fa;
            --border-light: #dee2e6;
        }

        .text-dark-blue { color: var(--primary-blue-dark); }
        .text-light-blue { color: var(--secondary-blue); }
        .bg-light-gradient { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); }
        .border-primary-subtle { border-color: var(--primary-blue-light) !important; }

        .btn-primary-gradient {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-primary-gradient:hover {
            background: linear-gradient(135deg, var(--primary-blue-dark) 0%, #084298 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .task-card {
            transition: all 0.3s ease;
            border-left-width: 4px !important;
        }
        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1) !important;
        }

        .task-checkbox input[type="checkbox"] {
            display: none;
        }
        .checkbox-label {
            cursor: pointer;
            font-size: 1.25rem;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .checkbox-label:hover {
            background-color: var(--primary-blue-light);
        }

        .btn-filter {
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-filter:hover {
            transform: translateY(-1px);
        }

        .badge {
            padding: 4px 8px;
            border-radius: 10px;
            font-weight: 600;
        }

        .empty-state-icon {
            opacity: 0.6;
        }

        .search-box input:focus {
            border-color: var(--primary-blue) !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }
        textarea#taskDescription {
            resize: vertical;
            min-height: 100px;
            transition: all 0.3s ease;
        }

        textarea#taskDescription:focus {
            min-height: 120px;
            border-color: var(--primary-blue) !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }

        textarea.form-control {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.5;
            font-size: 0.95rem;
        }
        .char-counter {
            font-size: 0.85rem;
            font-weight: 500;
            color: #6c757d;
            transition: color 0.2s ease;
        }

        .char-counter.warning {
            color: #ffc107;
        }

        .char-counter.danger {
            color: #dc3545;
            font-weight: bold;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .task-title {
            font-weight: 600 !important;
            line-height: 1.4;
            word-wrap: break-word;
            overflow-wrap: break-word;
            margin-bottom: 0.5rem !important;
        }

        .task-description {
            line-height: 1.5;
            word-wrap: break-word;
            overflow-wrap: break-word;

            max-height: 150px;
            overflow-y: auto;
            padding-right: 5px;
            margin-bottom: 0.75rem !important;
        }

        .align-btn-fix {
            display: flex;
            align-items: flex-end;
            height: 100%;
        }

        .card-body .row {
            align-items: stretch;
        }

        .modal-body .form-control {
            max-width: 100%;
            box-sizing: border-box;
        }

        .form-control {
            font-weight: 500;
        }

        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .char-counter {
            font-size: 0.8rem;
            font-weight: 500;
            min-width: 80px;
            text-align: right;
        }
        .task-card .card-body {
            overflow: hidden;
        }

        .task-title {
            font-weight: 600;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
            hyphens: auto;
            margin-bottom: 0.5rem;
            font-size: 1.05rem;
            line-height: 1.4;
        }

        .task-description {
            word-break: break-word !important;
            overflow-wrap: break-word !important;
            line-height: 1.5;
            max-height: 150px;
            overflow-y: auto;
            padding-right: 5px;
            margin-bottom: 0.75rem;
        }

        .task-description::-webkit-scrollbar {
            width: 6px;
        }

        .task-description::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .task-description::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .task-description::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .col-md-2.d-flex.align-items-start {
            display: flex;
            align-items: flex-start !important;
            justify-content: center;
            padding-top: 1.7rem;
        }

        .col-md-2.d-flex.align-items-start .btn {
            height: auto;
            min-height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .col-md-2.d-flex.align-items-start {
                padding-top: 0;
                margin-top: 1rem;
            }

            .col-md-2.d-flex.align-items-start .btn {
                width: 100%;
                height: 50px;
            }
        }

        .text-break {
            word-break: break-word !important;
            overflow-wrap: break-word !important;
        }

        .task-meta small {
            font-size: 0.8rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        .task-actions {
            display: flex;
            gap: 0.25rem;
            flex-shrink: 0;
        }

        @media (max-width: 576px) {
            .task-actions {
                flex-direction: column;
                gap: 0.5rem;
            }

            .task-description {
                max-height: 120px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function updateCharCounter(inputElement, counterId) {
            try {
                // Validate parameters
                if (!inputElement || !(inputElement instanceof HTMLElement)) {
                    console.warn('Invalid input element provided to updateCharCounter');
                    return;
                }

                if (!counterId || typeof counterId !== 'string') {
                    console.warn('Invalid counter ID provided to updateCharCounter');
                    return;
                }

                const maxLength = parseInt(inputElement.getAttribute('maxlength')) || 0;
                const currentLength = inputElement.value.length;
                const remaining = maxLength - currentLength;
                const counterElement = document.getElementById(counterId);

                if (!counterElement) {
                    console.warn(`Counter element with ID "${counterId}" not found`);
                    return;
                }

                // Update counter text
                counterElement.textContent = remaining;

                // Remove any existing status classes
                counterElement.classList.remove('counter-danger', 'counter-warning', 'counter-safe');
                inputElement.classList.remove('is-invalid');

                // Apply styling based on remaining characters
                if (remaining < 0) {
                    counterElement.classList.add('counter-danger');
                    counterElement.textContent = `-${Math.abs(remaining)}`;
                    inputElement.classList.add('is-invalid');
                } else if (remaining <= 10) {
                    counterElement.classList.add('counter-warning');
                } else {
                    counterElement.classList.add('counter-safe');
                }

                // Trigger custom event for other scripts to listen to
                const event = new CustomEvent('charCounterUpdated', {
                    detail: {
                        inputElement,
                        counterElement,
                        maxLength,
                        currentLength,
                        remaining
                    }
                });
                inputElement.dispatchEvent(event);

            } catch (error) {
                console.error('Error in updateCharCounter:', error);
            }
        }

        function initializeAllCounters() {
            console.log('Initializing character counters...');

            // Common counter configurations
            const counterConfigs = [
                { inputId: 'taskTitle', counterId: 'titleCounter' },
                { inputId: 'taskDescription', counterId: 'descCounter' },
                { inputId: 'searchInput', counterId: 'searchCounter' }
            ];

            // Initialize each counter
            counterConfigs.forEach(config => {
                const input = document.getElementById(config.inputId);
                if (input) {
                    updateCharCounter(input, config.counterId);

                    // Add real-time update on input
                    input.addEventListener('input', () => {
                        updateCharCounter(input, config.counterId);
                    });

                    // Add update on paste
                    input.addEventListener('paste', (e) => {
                        setTimeout(() => {
                            updateCharCounter(input, config.counterId);
                        }, 0);
                    });

                    console.log(`Counter initialized for: ${config.inputId}`);
                }
            });

            // Initialize edit form counters
            document.querySelectorAll('[id^="editTitle"]').forEach(input => {
                const taskId = input.id.replace('editTitle', '');
                updateCharCounter(input, `editTitleCounter${taskId}`);
            });

            document.querySelectorAll('[id^="editDescription"]').forEach(textarea => {
                const taskId = textarea.id.replace('editDescription', '');
                updateCharCounter(textarea, `editDescCounter${taskId}`);
            });
        }

        function setupKeyboardShortcuts() {
            // Ctrl+K / Cmd+K for search
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.getElementById('searchInput');
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                        console.log('Search focused via Ctrl+K');
                    }
                }

                // Escape to clear search
                if (e.key === 'Escape') {
                    const searchInput = document.getElementById('searchInput');
                    if (document.activeElement === searchInput && searchInput.value) {
                        searchInput.value = '';
                        searchInput.dispatchEvent(new Event('input'));
                        console.log('Search cleared via Escape');
                    }
                }
            });
        }

        function enhanceFilterButtons() {
            const activeFilter = document.querySelector('.btn-filter.active');
            if (activeFilter) {
                activeFilter.style.transform = 'translateY(-1px)';
                activeFilter.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';

                // Add pulse animation
                activeFilter.classList.add('animate__animated', 'animate__pulse');
                setTimeout(() => {
                    activeFilter.classList.remove('animate__pulse');
                }, 1000);
            }

            // Add click effects to all filter buttons
            document.querySelectorAll('.btn-filter').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.classList.add('animate__animated', 'animate__bounceIn');
                    setTimeout(() => {
                        this.classList.remove('animate__bounceIn');
                    }, 500);
                });
            });
        }

        function animateTaskCards() {
            const taskCards = document.querySelectorAll('.task-card');
            taskCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 50}ms`;
                card.classList.add('animate__animated', 'animate__fadeInUp');

                // Add hover effect
                card.addEventListener('mouseenter', function() {
                    this.classList.add('animate__pulse');
                });

                card.addEventListener('mouseleave', function() {
                    this.classList.remove('animate__pulse');
                });
            });

            console.log(`Animated ${taskCards.length} task cards`);
        }

        function setupModalReset() {
            // Listen for when modal is about to be shown
            document.querySelectorAll('[id^="editModal"]').forEach(modal => {
                // Store original values when modal is first shown
                modal.addEventListener('show.bs.modal', function() {
                    const taskId = this.id.replace('editModal', '');
                    const originalTitle = this.querySelector('.edit-title').dataset.originalValue || this.querySelector('.edit-title').value;
                    const originalDescription = this.querySelector('.edit-description').dataset.originalValue || this.querySelector('.edit-description').value;

                    // Store original values in data attributes
                    this.querySelector('.edit-title').dataset.originalValue = originalTitle;
                    this.querySelector('.edit-description').dataset.originalValue = originalDescription;
                });

                // Reset to original values when modal is hidden
                modal.addEventListener('hidden.bs.modal', function() {
                    const taskId = this.id.replace('editModal', '');
                    const titleInput = document.getElementById('editTitle' + taskId);
                    const descInput = document.getElementById('editDescription' + taskId);

                    // Reset to original values
                    if (titleInput && titleInput.dataset.originalValue) {
                        titleInput.value = titleInput.dataset.originalValue;
                        updateCharCounter(titleInput, 'editTitleCounter' + taskId);
                    }
                    if (descInput && descInput.dataset.originalValue) {
                        descInput.value = descInput.dataset.originalValue;
                        updateCharCounter(descInput, 'editDescCounter' + taskId);
                    }
                });
            });
        }

        // Initialize modal reset functionality
        document.addEventListener('DOMContentLoaded', function() {
            setupModalReset();
        });

        // Main initialization when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded and parsed');

            // Initialize all features
            initializeAllCounters();
            setupKeyboardShortcuts();
            enhanceFilterButtons();
            animateTaskCards();

            // Listen for modal openings to initialize counters in modals
            document.addEventListener('shown.bs.modal', function(event) {
                const modal = event.target;
                modal.querySelectorAll('[data-counter]').forEach(input => {
                    const counterId = input.dataset.counter;
                    updateCharCounter(input, counterId);
                });
            });

            // Make function globally available
            window.updateCharCounter = updateCharCounter;

            console.log('All features initialized successfully');
        });

        // For dynamic content (AJAX, etc.)
        document.addEventListener('ajaxComplete', function() {
            setTimeout(initializeAllCounters, 100);
        });
    </script>
@endpush
