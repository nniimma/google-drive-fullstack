<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <Form :resolver @submit="onFormSubmit" class="flex flex-col gap-4 w-full sm:w-56">
            <FormField v-slot="$field" name="email" initialValue="" class="flex flex-col gap-1">
                <FloatLabel variant="on">
                    <label for="email">{{ $field.name }}</label>
                    <InputText v-model="form.email" class="w-[170%]" id="email" type="text" />
                </FloatLabel>
                <div v-if="$field?.invalid" class="w-[170%]">
                    <Message 
                        v-for="(error, index) in $field.errors" 
                        :key="index" 
                        severity="error" 
                        size="small" 
                        variant="simple">
                        - {{ error.message }}
                    </Message>
                </div>
                <InputError class="mt-2 w-[170%]" :message="form.errors.email" v-model="form.email" autofocus/>
            </FormField>
            <FormField v-slot="$field" name="password" initialValue="" class="flex flex-col gap-1">
                <FloatLabel variant="on">
                    <label for="password">{{ $field.name }}</label>
                    <InputText v-model="form.password" class="w-[170%]" id="password" type="text" />
                </FloatLabel>
                <div v-if="$field?.invalid" class="w-[170%]">
                    <Message 
                        v-for="(error, index) in $field.errors" 
                        :key="index" 
                        severity="error" 
                        size="small" 
                        variant="simple">
                        <i class="pi pi-key" style="font-size: 12px;"></i> {{ error.message }}
                    </Message>
                </div>
                <InputError class="mt-2 w-[170%]" :message="form.errors.password" />
            </FormField>
            <div class="flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </Link>
                <Link
                    :href="route('register')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800 ms-2"
                >
                    Register
                </Link>

            </div>
            <Button class="w-[170%]" type="submit" severity="secondary" label="Submit" />
        </Form>
    </GuestLayout>
</template>


<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Form } from '@primevue/forms';
import { FormField } from '@primevue/forms';
import InputText from 'primevue/inputtext';
import FloatLabel from 'primevue/floatlabel';
import Message from 'primevue/message';
import Button from 'primevue/button';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { z } from 'zod';


defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const resolver = zodResolver(
    z.object({
    email: z.string().min(1, { message: 'Email is required.' }).email({ message: 'Invalid email format.' }),
    password: z.string()
      .min(6, { message: 'Password must be at least 6 characters long.' })
      .regex(/[A-Z]/, { message: 'Password must contain at least one uppercase letter.' })
      .regex(/[a-z]/, { message: 'Password must contain at least one lowercase letter.' })
      .regex(/[0-9]/, { message: 'Password must contain at least one number.' })
  })
);

const onFormSubmit = ({ valid }) => {
  if (valid) {
        form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
  }
};

</script>
