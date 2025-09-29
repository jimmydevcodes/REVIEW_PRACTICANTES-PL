// resources/js/tasks.js
// Sistema de notificaciones y mejoras UX para tareas

class TasksManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupModalEvents();
        this.setupFormValidations();
        this.setupFilePreview();
        this.setupProgressTracking();
        this.setupNotifications();
        this.setupKeyboardShortcuts();
    }

    // Configurar eventos de modales
    setupModalEvents() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });

        // Animaciones suaves para modales
        const modals = document.querySelectorAll('[id$="Modal"]');
        modals.forEach(modal => {
            modal.addEventListener('show', () => {
                modal.style.opacity = '0';
                modal.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    modal.style.transition = 'all 0.3s ease';
                    modal.style.opacity = '1';
                    modal.style.transform = 'scale(1)';
                }, 10);
            });
        });
    }

    // Validaciones de formularios en tiempo real
    setupFormValidations() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                this.validateFile(e.target);
            });
        });

        // Validación para textareas
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', (e) => {
                this.validateTextarea(e.target);
            });
        });
    }
    setupFilePreview() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                this.showFilePreview(e.target);
            });
        });
    }

    // Seguimiento de progreso
    setupProgressTracking() {
        this.trackUploadProgress();
        
        // Auto-save para formularios largos
        this.setupAutoSave();
    }

    // Sistema de notificaciones
    setupNotifications() {
        // Verificar soporte para notificaciones
        if ('Notification' in window) {
            Notification.requestPermission();
        }
        this.startRealTimeUpdates();
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl + U: Subir evidencia
            if (e.ctrlKey && e.key === 'u') {
                e.preventDefault();
                this.openFirstUploadModal();
            }

            // Ctrl + P: Solicitar pausa
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                this.openFirstPauseModal();
            }

            // Ctrl + S: Guardar borrador
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                this.saveDraft();
            }
        });
    }

    // Validar archivo
    validateFile(input) {
        const file = input.files[0];
        const feedback = input.parentNode.querySelector('.file-feedback') || this.createFeedbackElement(input);

        if (!file) return;

        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'];

        // Validar tamaño
        if (file.size > maxSize) {
            this.showFileError(feedback, 'El archivo es demasiado grande. Máximo 10MB.');
            input.value = '';
            return false;
        }
        if (!allowedTypes.includes(file.type)) {
            this.showFileError(feedback, 'Tipo de archivo no permitido. Solo PDF y WORD.');
            input.value = '';
            return false;
        }

        this.showFileSuccess(feedback, `Archivo válido: ${file.name}`);
        return true;
    }
    validateTextarea(textarea) {
        const minLength = textarea.hasAttribute('required') ? 10 : 0;
        const maxLength = parseInt(textarea.getAttribute('maxlength')) || 500;
        const feedback = textarea.parentNode.querySelector('.textarea-feedback') || this.createFeedbackElement(textarea);

        if (textarea.value.length < minLength && textarea.hasAttribute('required')) {
            this.showTextError(feedback, `Mínimo ${minLength} caracteres requeridos.`);
            return false;
        }

        if (textarea.value.length > maxLength) {
            this.showTextError(feedback, `Máximo ${maxLength} caracteres permitidos.`);
            return false;
        }

        this.showTextSuccess(feedback, `${textarea.value.length}/${maxLength} caracteres`);
        return true;
    }

    // Mostrar vista previa del archivo
    showFilePreview(input) {
        const file = input.files[0];
        if (!file) return;

        const previewContainer = input.parentNode.querySelector('.file-preview') || this.createPreviewContainer(input);

        const fileInfo = `
            <div class="flex items-center space-x-3 p-3 bg-orange-50 rounded-lg border border-orange-200">
                <div class="flex-shrink-0">
                    ${file.type.includes('pdf') ? 
                        '<i class="fas fa-file-pdf text-red-500 text-xl"></i>' : 
                        '<i class="fas fa-file-word text-blue-500 text-xl"></i>'
                    }
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                    <p class="text-xs text-gray-500">${this.formatFileSize(file.size)}</p>
                </div>
                <button type="button" onclick="this.closest('.file-preview').remove(); document.getElementById('${input.id}').value = ''" 
                        class="flex-shrink-0 text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        previewContainer.innerHTML = fileInfo;
    }
    trackUploadProgress() {
        const forms = document.querySelectorAll('form[id$="Form"]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                this.showProgressBar(form);
            });
        });
    }
    setupAutoSave() {
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            let timeout;
            textarea.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.saveDraft(textarea);
                }, 2000); // Auto-save después de 2 segundos
            });
        });
    }

    // Actualizaciones en tiempo real simuladas
    startRealTimeUpdates() {
        setInterval(() => {
            this.checkForUpdates();
        }, 30000); // Cada 30 segundos
    }

    // Funciones auxiliares
    createFeedbackElement(input) {
        const feedback = document.createElement('div');
        feedback.className = input.type === 'file' ? 'file-feedback mt-1' : 'textarea-feedback mt-1';
        input.parentNode.appendChild(feedback);
        return feedback;
    }

    createPreviewContainer(input) {
        const preview = document.createElement('div');
        preview.className = 'file-preview mt-2';
        input.parentNode.appendChild(preview);
        return preview;
    }

    showFileError(element, message) {
        element.innerHTML = `<span class="text-red-600 text-xs"><i class="fas fa-exclamation-circle mr-1"></i>${message}</span>`;
    }

    showFileSuccess(element, message) {
        element.innerHTML = `<span class="text-green-600 text-xs"><i class="fas fa-check-circle mr-1"></i>${message}</span>`;
    }

    showTextError(element, message) {
        element.innerHTML = `<span class="text-red-600 text-xs">${message}</span>`;
    }

    showTextSuccess(element, message) {
        element.innerHTML = `<span class="text-gray-500 text-xs">${message}</span>`;
    }

    formatFileSize(bytes) {
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        if (bytes === 0) return '0 Bytes';
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
    }

    showProgressBar(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Subiendo...
        `;

        // Restaurar botón después de la carga
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }, 3000);
    }

    saveDraft(textarea) {
        if (!textarea.value.trim()) return;

        const draftKey = `draft_${textarea.id || 'textarea'}_${Date.now()}`;
        localStorage.setItem(draftKey, textarea.value);
        
        // Mostrar indicador de guardado
        this.showSaveIndicator(textarea);
    }

    showSaveIndicator(element) {
        let indicator = element.parentNode.querySelector('.save-indicator');
        if (!indicator) {
            indicator = document.createElement('span');
            indicator.className = 'save-indicator text-xs text-green-600 opacity-0 transition-opacity';
            element.parentNode.appendChild(indicator);
        }

        indicator.innerHTML = '<i class="fas fa-check mr-1"></i>Guardado automáticamente';
        indicator.style.opacity = '1';
        
        setTimeout(() => {
            indicator.style.opacity = '0';
        }, 2000);
    }

    checkForUpdates() {
        // Aquí se conectaría con WebSockets o polling para updates reales
        console.log('Verificando actualizaciones...', new Date().toLocaleTimeString());
    }

    closeAllModals() {
        const modals = document.querySelectorAll('[id$="Modal"]');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    }

    openFirstUploadModal() {
        const uploadButtons = document.querySelectorAll('[onclick^="openUploadModal"]');
        if (uploadButtons.length > 0) {
            uploadButtons[0].click();
        }
    }

    openFirstPauseModal() {
        const pauseButtons = document.querySelectorAll('[onclick^="openPauseModal"]');
        if (pauseButtons.length > 0) {
            pauseButtons[0].click();
        }
    }
    showNotification(title, body, options = {}) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(title, {
                body: body,
                icon: '/favicon.ico',
                badge: '/favicon.ico',
                ...options
            });
        }
    }
    showToast(message, type = 'info') {
        const toastContainer = this.getOrCreateToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `
            transform transition-all duration-300 ease-in-out translate-y-2 opacity-0
            max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden
        `;

        const bgColor = {
            success: 'bg-green-50 border-green-200',
            error: 'bg-red-50 border-red-200', 
            warning: 'bg-yellow-50 border-yellow-200',
            info: 'bg-blue-50 border-blue-200'
        }[type] || 'bg-gray-50 border-gray-200';

        const iconColor = {
            success: 'text-green-600',
            error: 'text-red-600',
            warning: 'text-yellow-600', 
            info: 'text-blue-600'
        }[type] || 'text-gray-600';

        const icon = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        }[type] || 'fas fa-info-circle';

        toast.innerHTML = `
            <div class="p-4 ${bgColor} border-l-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="${icon} ${iconColor}"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.closest('.toast').remove()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        toast.classList.add('toast');
        toastContainer.appendChild(toast);
        setTimeout(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        }, 100);

        // Auto-remove después de 5 segundos
        setTimeout(() => {
            toast.classList.add('translate-y-2', 'opacity-0');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }, 5000);
    }

    getOrCreateToastContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-4';
            document.body.appendChild(container);
        }
        return container;
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.tasksManager = new TasksManager();
});

window.showToast = (message, type = 'info') => {
    if (window.tasksManager) {
        window.tasksManager.showToast(message, type);
    }
};