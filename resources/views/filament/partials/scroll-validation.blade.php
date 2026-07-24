<!-- Global Filament Validation Error Auto-Scroll -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let shouldScrollOnNextUpdate = false;
        let scrollTimeout = null;

        function setScrollTrigger() {
            shouldScrollOnNextUpdate = true;
            if (scrollTimeout) clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                shouldScrollOnNextUpdate = false;
            }, 4000);
        }

        document.addEventListener('submit', () => {
            setScrollTrigger();
        });

        document.addEventListener('click', (e) => {
            const button = e.target.closest('button, input[type="submit"], input[type="button"]');
            if (button) {
                const isSubmitType = button.getAttribute('type') === 'submit';
                const hasWireClick = button.hasAttribute('wire:click');
                const isFilamentButton = button.classList.contains('fi-btn') || 
                                         button.closest('.fi-form-actions') || 
                                         button.closest('.fi-modal-footer') || 
                                         button.closest('.fi-ac-action');
                
                if (isSubmitType || hasWireClick || isFilamentButton) {
                    setScrollTrigger();
                }
            }
        });

        document.addEventListener('livewire:init', () => {
            Livewire.hook('commit', ({ succeed }) => {
                succeed(() => {
                    setTimeout(() => {
                        if (shouldScrollOnNextUpdate) {
                            const selectors = [
                                '.fi-fo-field-wrp-error-message',
                                '[aria-invalid="true"]',
                                '.text-danger-600',
                                '.text-red-600',
                                '.text-red-500',
                                '.invalid-feedback'
                            ];

                            let firstErrorElement = null;
                            for (const selector of selectors) {
                                firstErrorElement = document.querySelector(selector);
                                if (firstErrorElement) break;
                            }

                            if (firstErrorElement) {
                                firstErrorElement.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });

                                shouldScrollOnNextUpdate = false;

                                let input = null;
                                if (['INPUT', 'SELECT', 'TEXTAREA'].includes(firstErrorElement.tagName)) {
                                    input = firstErrorElement;
                                } else {
                                    const container = firstErrorElement.closest('.fi-fo-field-wrp, .mb-4, div');
                                    if (container) {
                                        input = container.querySelector('input, select, textarea');
                                    }
                                }
                                if (input) {
                                    input.focus();
                                }
                            }
                        }
                    }, 100);
                });
            });
        });
    });
</script>
