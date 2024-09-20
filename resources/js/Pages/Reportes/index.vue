<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ReporteDiario from '@/Pages/Reportes/reporteDiario.vue';
import ReporteSemanal from '@/Pages/Reportes/reporteSemanal.vue';
import Button from 'primevue/button';
import { ref } from 'vue';

const mostrarReporteDiario = ref(false);
const mostrarReporteSemanal = ref(false);
const cargando = ref(false);

const mostrarDiario = () => {
    cargando.value = true;
    mostrarReporteDiario.value = true;
    mostrarReporteSemanal.value = false;
    setTimeout(() => {
        cargando.value = false;
    }, 500);
};

const mostrarSemanal = () => {
    cargando.value = true;
    mostrarReporteDiario.value = false;
    mostrarReporteSemanal.value = true;
    setTimeout(() => {
        cargando.value = false;
    }, 500);
};

const ocultarReportes = () => {
    cargando.value = true;
    mostrarReporteDiario.value = false;
    mostrarReporteSemanal.value = false;
    setTimeout(() => {
        cargando.value = false;
    }, 500);
};
</script>

<template>
    <AppLayout title="Reportes">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reportes
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto md:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-8 relative min-h-[calc(100vh-252px)]">
                        
                        <!-- Simulación de Loading -->
                        <div v-show="cargando" class="absolute top-0 left-0 w-full h-full bg-white z-50 flex justify-center items-center">
                            <div class="flex flex-col justify-center items-center">
                                <p class="text-sm font-medium">Cargando...</p>
                                <div class="flex">
                                    <div class="loader"></div>
                                    <div class="loader"></div>
                                </div>
                            </div>
                        </div>
                        <!-- ==================== -->

                        <!-- Botones para elegir el reporte -->
                        <div v-if="!mostrarReporteDiario && !mostrarReporteSemanal && !cargando" class="flex justify-center gap-4">
                            <button @click="mostrarDiario" class="px-4 py-2 bg-blue-500 text-white rounded">Reporte Diario</button>
                            <button @click="mostrarSemanal" class="px-4 py-2 bg-green-500 text-white rounded">Reporte Semanal</button>
                        </div>

                        <!-- Mostrar Reporte Diario -->
                        <div v-if="mostrarReporteDiario">
                            <Button @click="ocultarReportes" icon="fa-solid fa-arrow-left" class="w-full md:w-auto mb-4" label="Regresar"/>
                            <ReporteDiario />
                        </div>

                        <!-- Mostrar Reporte Semanal -->
                        <div v-if="mostrarReporteSemanal">
                            <Button @click="ocultarReportes" icon="fa-solid fa-arrow-left" class="w-full md:w-auto mb-4" label="Regresar"/>
                            <ReporteSemanal />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.loader {
  width: 80px;
  height: 40px;
  background:
    radial-gradient(circle 11px at top,#0000 94%,#b0e5f3) 0 20px,
    radial-gradient(circle 11px at top,#0000 94%,#5dc1e4) 0 10px,
    radial-gradient(circle 11px at top,#0000 94%,#008cd9) 0 0   ;
  background-size: 20px 100%;
  background-repeat: repeat-x;
  animation: l7 1s infinite linear;
}
@keyframes l7 {
  50%  {background-position: 10px 15px,-10px 12px,15px 8px}
  100% {background-position: 20px 20px,-20px 10px,20px 0px}
}
</style>