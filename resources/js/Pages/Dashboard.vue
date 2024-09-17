<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted, ref, defineProps, watchEffect } from 'vue';

const mensaje = ref("")

function fn_mensajeBienvenida() {
    const hora = new Date().getHours();

    if (hora >= 0 && hora < 6) {
        mensaje.value = "¡Haz madrugado mucho hoy!";
    } else if (hora >= 6 && hora < 12) {
        mensaje.value = "¡Buenos días!";
    } else if (hora >= 12 && hora < 18) {
        mensaje.value = "¡Buenas tardes!";
    } else {
        mensaje.value = "¡Buenas noches!";
    }
}

onMounted(() => {
    fn_mensajeBienvenida();
});
</script>

<template>
    <AppLayout title="Inicio">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Inicio
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-8">
                        <img src="/img/InicioAbanico.png" alt="" class="m-auto w-56 h-56 rounded-full mb-4 object-cover">
                        <h1 class="text-center text-3xl font-bold text-gray-800">
                            {{ $page.props.auth.user.name }}
                        </h1>
                        <h3 class="text-center text-1xl mb-4 text-gray-800">{{ mensaje }}</h3>
                        <p class="text-center text-[20px] font-medium leading-[20px] text-gray-800">Bienvenido al Sistema de Gestión de Concha de Abanico</p>
                        <div class="flex justify-center items-center gap-4 mt-4">
                            <img src="/img/abanico1.jpeg" alt="" class="w-20 h-20 rounded-full object-cover">
                            <img src="/img/abanico2.jpeg" alt="" class="w-20 h-20 rounded-full object-cover">
                            <img src="/img/abanico3.jpeg" alt="" class="w-20 h-20 rounded-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
