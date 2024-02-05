document.addEventListener('alpine:init', () => {
    Alpine.store('toast', {
        toasts: [],
        toggle(state, message) {
            this.toasts.push({ state, message })
            this.setActiveDuration()
        },
        async setActiveDuration() {
            await new Promise((res) => setTimeout(() => res(), 7500))
            this.toasts.shift();
        }
    })
})