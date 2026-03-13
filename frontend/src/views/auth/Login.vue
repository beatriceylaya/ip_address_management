<script setup lang="ts">
import { reactive } from 'vue'

import Card from '@/components/common/Card.vue'
import Button from '@/components/common/Button.vue'
import Input from '@/components/common/Input.vue'
import FormGroup from '@/components/form/FormGroup.vue'
import PageContainer from '@/layouts/PageContainer.vue'
import { useAuth } from '@/composables/useAxios'
import { useRouter } from 'vue-router'

interface LoginForm {
  email: string
  password: string
}

const form = reactive<LoginForm>({
  email: '',
  password: ''
})

const { login } = useAuth()
const router = useRouter()

const submit = async () => {
  await login({
    email: form.email,
    password: form.password
  })
}
</script>

<template>
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

        <Button type="submit" class="w-full">
          Login
        </Button>

      </form>
    </Card>
  </PageContainer>
</template>