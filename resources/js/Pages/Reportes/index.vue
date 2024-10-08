<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ReportePorProceso from '@/Pages/Reportes/reporteProceso.vue';
import ReporteDiario from '@/Pages/Reportes/reporteDiario.vue';
import ReporteSemanal from '@/Pages/Reportes/reporteSemanal.vue';
import Button from 'primevue/button';
import Card from 'primevue/card';
import { ref } from 'vue';
import ReporteProceso from '@/Pages/Reportes/reporteProceso.vue';

const mostrarReporteDiario = ref(false);
const mostrarReporteSemanal = ref(false);
const mostrarReportePorProceso = ref(false);
const cargando = ref(false);

const mostrarProceso = () => {
    cargando.value = true;
    mostrarReportePorProceso.value = true;
    mostrarReporteDiario.value = false;
    mostrarReporteSemanal.value = false;
    setTimeout(() => {
        cargando.value = false;
    }, 500);
};

const mostrarDiario = () => {
    cargando.value = true;
    mostrarReporteDiario.value = true;
    mostrarReporteSemanal.value = false;
    mostrarReportePorProceso.value = false;
    setTimeout(() => {
        cargando.value = false;
    }, 500);
};

const mostrarSemanal = () => {
    cargando.value = true;
    mostrarReporteSemanal.value = true;
    mostrarReporteDiario.value = false;
    mostrarReportePorProceso.value = false;
    setTimeout(() => {
        cargando.value = false;
    }, 500);
};

const ocultarReportes = () => {
    cargando.value = true;
    mostrarReporteDiario.value = false;
    mostrarReporteSemanal.value = false;
    mostrarReportePorProceso.value = false;
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

                        <div v-if="!mostrarReporteDiario && !mostrarReporteSemanal && !cargando && !mostrarReportePorProceso">
                            <div class="flex flex-col gap-8">
                                <h1 class="text-center text-2xl font-bold">REPORTES PARA LA EMPRESA</h1>
                                <div class="flex justify-evenly gap-4 flex-wrap">
                                    <Card style="width: 25rem; overflow: hidden">
                                        <template #header>
                                            <img alt="user header" src="/img/abanico2.jpeg" style="height: 220px !important; width: 400px !important; object-fit: cover;"/>
                                        </template>
                                        <template #title>Reporte por Proceso</template>
                                        <template #content>
                                            <p class="m-0">
                                                Este informe abarca las operaciones por proceso, que incluye peso total de cada especie por lotes y el total del producto procesado.
                                            </p>
                                        </template>
                                        <template #footer>
                                            <div class="flex gap-4 mt-1">
                                                <Button label="Ir a Reporte por Proceso" class="w-full" @click="mostrarProceso" severity="danger"/>
                                            </div>
                                        </template>
                                    </Card>
                                </div>
                            </div>
                            <hr class="my-10">
                            <div class="flex flex-col gap-8">
                                <h1 class="text-center text-2xl font-bold">REPORTES PARA COLABORADORES</h1>
                                <div class="flex justify-evenly gap-4 flex-wrap">
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
                            </div>
                        </div>

                        <!-- Mostrar Reporte Diario -->
                        <div v-if="mostrarReportePorProceso">
                            <Button @click="ocultarReportes" icon="fa-solid fa-arrow-left" class="w-full md:w-auto mb-4" label="Regresar"/>
                            <ReporteProceso />
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
.p-card-body{
    height: 100%;
    justify-content: space-between;
}
</style>