<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Head, Link, router } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import NavLink from '@/Components/NavLink.vue';
import { show_alerta } from '@/utils/alertasSwal';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    rol: ''
});

const submitForm = () => {
    form.errors.password_confirmation = '';
    if (form.password !== form.password_confirmation) {
        form.errors.password_confirmation = 'Las contraseñas no coinciden.';
        return;
    }
    form.post('/fn_registrarDatosdeUsuario', {
        onError: (errors) => {
            show_alerta('Rellene todos los campos del formulario.', 'error')
            console.error('Error al crear el registro:', errors);
        },
        onSuccess: () => {
            show_alerta('El Usuario se ha registrado correctamente.', 'success')
            router.get(route('listarusuarios.index'));
        }
    });
};


const btnCancelar = () => {
    router.get(route('listarusuarios.index'));
};

</script>

<template>
    <AppLayout title="Usuarios">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registrar Usuario 
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto md:px-8">
                <div class="bg-white text-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-8">
                        <form @submit.prevent="submitForm">
                            <div class="card text-sm mb-4 grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-2">
                                <div>
                                    <label for="nombre" class="font-bold block mb-2">Nombre</label>
                                    <InputText id="nombre" autocomplete="off" class="w-full" v-model="form.name" type="text" placeholder="Ingrese Nombre" />
                                    <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
                                </div>
                                <div>
                                    <label for="email" class="font-bold block mb-2">Email</label>
                                    <InputText id="email" autocomplete="off" class="w-full" v-model="form.email" type="email" placeholder="example@balinsa.net" />
                                    <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
                                </div>
                                <div>
                                    <label for="password" class="font-bold block mb-2">Contraseña</label>
                                    <InputText id="password" autocomplete="off" class="w-full" v-model="form.password" type="password" placeholder="Ingrese Contraseña" />
                                    <span v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
                                </div>
                                <div>
                                    <label for="password_confirmation" class="font-bold block mb-2">Confirmar Contraseña</label>
                                    <InputText id="password_confirmation" autocomplete="off" class="w-full" v-model="form.password_confirmation" type="password" placeholder="Ingrese Nuevamente" />
                                    <span v-if="form.errors.password_confirmation" class="text-red-500 text-sm">{{ form.errors.password_confirmation }}</span>
                                </div>
                                <div>
                                    <label for="rol" class="font-bold block mb-2">Rol</label>
                                    <select id="role" class="w-full border-[#cbd5e1] rounded-lg text-[#334155]" v-model="form.rol">
                                        <option value="" disabled selected>Seleccione un rol</option>
                                        <option value="Administradores">Administradores</option>
                                        <option value="Recursos Humanos">Recursos Humanos</option>
                                        <option value="Pesadores">Pesadores</option>
                                    </select>
                                    <span v-if="form.errors.rol" class="text-red-500 text-sm">{{ form.errors.rol }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row w-full justify-end items-end gap-x-4 gap-y-4">
                                <Button @click="btnCancelar" class="md:w-auto w-full" label="Cancelar" size="medium" severity="danger" />
                                <Button type="submit" icon="fa-solid fa-save" class="w-full md:w-auto" label="Guardar"/>
                            </div>
                        </form>
                        <div v-if="form.rol" class="border mt-4 p-4 rounded-lg pointer-events-none">
                            <h2 class="font-bold mb-2 text-base">Con el rol {{ form.rol }} tienes acceso a:</h2>
                            <hr>
                            <div class="flex justify-between h-16">
                                <div class="flex">
                                    <!-- Logo -->
                                    <div class="shrink-0 flex items-center">
                                        <Link>
                                            <img src="/img/logo.png" alt="" width="48" height="48">
                                        </Link>
                                    </div>

                                    <!-- Navigation Links -->
                                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                        <NavLink v-if="(form.rol == 'Administradores') || (form.rol == 'Recursos Humanos') || (form.rol == 'Pesadores')" :active="route().current('listarusuarios.*')" class="text-base">
                                            Inicio
                                        </NavLink>
                                        <NavLink v-if="(form.rol == 'Administradores') || (form.rol == 'Recursos Humanos') || (form.rol == 'Pesadores')" class="text-base">
                                            Personal
                                        </NavLink>
                                        <NavLink v-if="(form.rol == 'Administradores') || (form.rol == 'Recursos Humanos')" class="text-base">
                                            Reportes
                                        </NavLink>
                                        <NavLink v-if="(form.rol == 'Administradores') || (form.rol == 'Recursos Humanos')" class="text-base">
                                            Precios
                                        </NavLink>
                                        <NavLink v-if="(form.rol == 'Administradores')" class="text-base">
                                            Usuarios
                                        </NavLink>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 