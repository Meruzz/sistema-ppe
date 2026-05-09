<div
    x-data="{ open: false, formAction: '', message: '' }"
    x-on:open-confirm.window="open = true; formAction = $event.detail.action; message = $event.detail.message ?? 'Esta acción no se puede deshacer.'"
    @keydown.escape.window="open = false"
    role="dialog"
    aria-modal="true"
    aria-labelledby="confirm-title"
    style="display: none"
    x-show="open"
>
    {{-- Backdrop --}}
    <div
        class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm"
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        aria-hidden="true"
    ></div>

    {{-- Dialog panel --}}
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <div class="cy-card w-full max-w-sm p-6 space-y-4">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 id="confirm-title" class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                        ¿Confirmar eliminación?
                    </h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400" x-text="message"></p>
                </div>
            </div>

            <form :action="formAction" method="POST" class="flex justify-end gap-2 pt-2">
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    @click="open = false"
                    class="cy-btn-ghost"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="cy-btn-danger"
                >
                    Sí, eliminar
                </button>
            </form>
        </div>
    </div>
</div>
