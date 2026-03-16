<script setup lang="ts">
import { reactive } from 'vue'

import Card from '@/components/common/Card.vue'
import Button from '@/components/common/Button.vue'
import Input from '@/components/common/Input.vue'
import FormGroup from '@/components/form/FormGroup.vue'
import PageContainer from '@/layouts/PageContainer.vue'
import { useAuth } from '@/composables/useAuth'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { RouterLink } from 'vue-router'

interface LoginForm {
  email: string
  password: string
}

const form = reactive<LoginForm>({
  email: '',
  password: ''
})

const { login, loading, error } = useAuth()


async function submit() {
  await login(form)
}
</script>

<template>
  <AuthLayout>
    <PageContainer>
      <Card class="max-w-md mx-auto">
          <h2 class="text-xl font-semibold text-center">
            Login
          </h2>

        <form class="space-y-4" @submit.prevent="submit">

          <FormGroup label="Email">
            <Input v-model="form.email" type="email" />
          </FormGroup>

          <FormGroup label="Password">
            <Input v-model="form.password" type="password" />
          </FormGroup>

          <p v-if="error" class="error">{{ error }}</p>
          <Button type="submit" class="w-full">
            {{ loading ? 'Logging in...' : 'Login' }}
          </Button>
          <p>
            No account yet?
            <RouterLink to="/register" class="text-blue-500 hover:underline">
              Register
            </RouterLink>
          </p>
        </form>
      </Card>
    </PageContainer>
  </AuthLayout>
</template>