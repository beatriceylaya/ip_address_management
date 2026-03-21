import type { DirectiveBinding, ObjectDirective } from 'vue'
import { useAuthStore } from '@/stores/auth'

export const vCan: ObjectDirective = {
  mounted(el: HTMLElement, binding: DirectiveBinding<string>) {
    const auth = useAuthStore()
    const allowed = auth.hasPermission(binding.value)

    if (!allowed) {
      if (binding.modifiers.hide) {
        el.style.display = 'none'
      } else {
        el.setAttribute('disabled', 'true')
        el.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none')
      }
    }
  },
}