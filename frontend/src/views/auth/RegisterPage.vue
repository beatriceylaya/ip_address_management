<script setup lang="ts">
import { reactive } from 'vue'
import Card from '@/components/common/BaseCard.vue'
import Input from '@/components/common/BaseInput.vue'
import FormGroup from '@/components/form/FormGroup.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'
import PageContainer from '@/layouts/PageContainer.vue'
import Button from '@/components/common/BaseButton.vue'
import { RouterLink } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

interface RegisterForm {
  name: string
  email: string
  password: string
}

const form = reactive<RegisterForm>({
  name: '',
  email: '',
  password: ''
})

const { register, error, loading } = useAuth()

async function submit () {
  await register(form)
}
</script>

<template>
  <AuthLayout>
    <PageContainer>
      <Card class="max-w-md mx-auto">
        <h2 class="text-xl font-semibold text-center">
          Register
        </h2>

        <form class="space-y-4" @submit.prevent="submit">
          <FormGroup label="Name">
            <Input v-model="form.name" type="text" />
          </FormGroup>

          <FormGroup label="Email">
            <Input v-model="form.email" type="email" />
          </FormGroup>

          <FormGroup label="Password">
            <Input v-model="form.password" type="password" />
          </FormGroup>

          <p v-if="error" class="error">{{ error }}</p>
          <Button type="submit" class="w-full">
            {{ loading ? 'Registering...' : 'Register' }}
          </Button>

          <p>
            Already have an account?
            <RouterLink to="/login" class="text-blue-500 hover:underline">
              Login
            </RouterLink>
          </p>
        </form>

      </Card>
    </PageContainer>
  </AuthLayout>
</template> 
