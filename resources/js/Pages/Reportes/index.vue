<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ReporteDiario from '@/Pages/Reportes/reporteDiario.vue';
import ReporteSemanal from '@/Pages/Reportes/reporteSemanal.vue';
import Button from 'primevue/button';
import Card from 'primevue/card';
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

                        <div v-if="!mostrarReporteDiario && !mostrarReporteSemanal && !cargando" class="flex justify-evenly gap-4 flex-wrap">
                            <Card style="width: 25rem; overflow: hidden">
                                <template #header>
                                    <img alt="user header" src="/img/abanico1.jpeg" style="height: 220px !important; width: 400px !important; object-fit: cover;"/>
                                </template>
                                <template #title>Reporte Diario</template>
                                <template #content>
                                    <p class="m-0">
                                        Este informe abarca las operaciones diarias, que incluye peso total, precios de cada servicio, los totales procesados y el total a pagar por el producto procesado a cada colaborador.
                                    </p>
                                </template>
                                <template #footer>
                                    <div class="flex gap-4 mt-1">
                                        <Button label="Ir a Reporte Diario" class="w-full" @click="mostrarDiario" severity="info"/>
                                    </div>
                                </template>
                            </Card>
                            <Card style="width: 25rem; overflow: hidden">
                                <template #header>
                                    <img alt="user header" src="/img/abanico3.jpeg" style="height: 220px !important; width: 400px !important; object-fit: cover;" />
                                </template>
                                <template #title>Reporte Semanal</template>
                                <template #content>
                                    <p class="m-0">
                                        Este informe abarca las operaciones realizadas de lunes a viernes, incluyendo el peso total procesado, los precios por servicio, el volumen acumulado de producción y el total a pagar a cada colaborador por la semana.
                                    </p>
                                </template>
                                <template #footer>
                                    <div class="flex gap-4 mt-1">
                                        <Button label="Ir a Reporte Semanal" class="w-full" @click="mostrarSemanal" severity="success"/>
                                    </div>
                                </template>
                            </Card>
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
.p-card-body{
    height: 100%;
    justify-content: space-between;
}
</style>