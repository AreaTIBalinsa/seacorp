<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import $ from 'jquery';

import { ref, onMounted, watch } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import { PreciosServis } from '@/Consultas/Precios/PreciosServis';
import Dialog from 'primevue/dialog';

const idServis = ref(null);
const precioActualPresentacion = ref(null);
const presentacionSelect = ref(null);
const visible = ref(false);
const nombreServis = ref(null);
const presentacionServis = ref(null);
const nuevoPrecioPresentacion = ref(null);

// Table

const customers = ref();
const filteredCustomers = ref([]);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});

const loading = ref(true);

const getCustomers = (data) => {
    return [...(data || [])].map((d) => {
        return d;
    });
};

onMounted(() => {
    PreciosServis.obtenerPreciosServis().then((respuesta) => {
        customers.value = getCustomers(respuesta);
        filteredCustomers.value = customers.value;
        loading.value = false;
    }).catch((error) => {
        console.error('Error al obtener los datos', error);
    });
});

const fn_extraerDatosCambiarPrecio = (data, presentacion) => {
    idServis.value = data.idGrupo;
    nombreServis.value = data.nombreGrupo;
    precioActualPresentacion.value = 0;
    presentacionSelect.value = presentacion
    presentacionServis.value = null;
    nuevoPrecioPresentacion.value = null;

    if(presentacion == 1){
        precioActualPresentacion.value = data.preTalloSolo;
        presentacionServis.value = "Tallo Solo";
    }else if(presentacion == 2){
        precioActualPresentacion.value = data.preTalloCoral;
        presentacionServis.value = "Tallo Coral";
    }else if(presentacion == 3){
        precioActualPresentacion.value = data.preMediaValvaTS;
        presentacionServis.value = "Media Valva TS";
    }else if(presentacion == 4){
        precioActualPresentacion.value = data.preMediaValvaTC;
        presentacionServis.value = "Media Valva TC";
    }else if(presentacion == 5){
        precioActualPresentacion.value = data.preOtros;
        presentacionServis.value = "Otros";
    }

    nuevoPrecioPresentacion.value = precioActualPresentacion.value;
    visible.value = true;
};

const fn_guardarPrecio = () => {
    $.ajax({
        url: '/fn_guardarNuevoPrecioServis',
        method: 'GET',
        data: {
            idServis: idServis.value,
            presentacion: presentacionSelect.value,
            nuevoPrecio: nuevoPrecioPresentacion.value
        },
        success: function (response) {
            if (response.success) {
                visible.value = false;
                nuevoPrecioPresentacion.value = null;
                PreciosServis.obtenerPreciosServis().then((respuesta) => {
                    customers.value = getCustomers(respuesta);
                    filteredCustomers.value = customers.value;
                    loading.value = false;
                }).catch((error) => {
                    console.error('Error al obtener los datos', error);
                });
            }
        },
        error: function (error) {
            console.error('Error al obtener los datos', error);
        }
    });
};

</script>

<template>
    <AppLayout title="Precios">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Precios
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto md:px-8">
                <div class="bg-white text-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-8">
                        <div class="flex justify-center md:justify-between items-center flex-wrap gap-4">
                            <h2 class="font-bold text-xl w-full md:w-auto">Precios por Servis</h2>
                        </div>
                        <DataTable id="tablaPrecios" v-model:filters="filters" stateStorage="session" stateKey="dt-state-precios" :value="filteredCustomers" paginator paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                        currentPageReportTemplate="{first} a {last} de {totalRecords}" :rows="10" :rowsPerPageOptions="[10, 20, 50]" :loading="loading" removableSort class="text-sm">
                            <template #header>
                                <div class="flex justify-end gap-4 flex-wrap">
                                    <IconField class="w-full md:w-56">
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="filters['global'].value" placeholder="Buscar" class="text-sm w-full md:w-56"/>
                                    </IconField>
                                </div>
                            </template>
                            <template #empty> No se encontraron precios.</template>
                            <template #loading>
                                <!-- Simulación de Loading -->
                                <div class="absolute top-0 left-0 w-full h-full text-gray-800 bg-white z-50 flex justify-center items-center">
                                    <div class="flex flex-col justify-center items-center">
                                        <p class="text-sm font-medium">Cargando precios... Por favor espere.</p>
                                        <div class="flex">
                                            <div class="loader"></div>
                                            <div class="loader"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ==================== -->
                            </template>
                            <Column header="Nombre Servis" field="nombreGrupo" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <div class="p-3 w-full">
                                        {{ data.nombreGrupo }}
                                    </div>
                                </template>
                            </Column>
                            <Column header="Tallo Solo" field="preTalloSolo" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <div @dblclick="fn_extraerDatosCambiarPrecio(data, 1)" class="cursor-pointer p-3 w-full">
                                        {{ data.preTalloSolo }}
                                    </div>
                                </template>
                            </Column>
                            <Column header="Tallo Coral" field="preTalloCoral" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <div @dblclick="fn_extraerDatosCambiarPrecio(data, 2)" class="cursor-pointer p-3 w-full">
                                        {{ data.preTalloCoral }}
                                    </div>
                                </template>
                            </Column>
                            <Column header="Media Valva T/S" field="preMediaValvaTS" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <div @dblclick="fn_extraerDatosCambiarPrecio(data, 3)" class="cursor-pointer p-3 w-full">
                                        {{ data.preMediaValvaTS }}
                                    </div>
                                </template>
                            </Column>
                            <Column header="Media Valva T/C" field="preMediaValvaTC" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <div @dblclick="fn_extraerDatosCambiarPrecio(data, 4)" class="cursor-pointer p-3 w-full">
                                        {{ data.preMediaValvaTC }}
                                    </div>
                                </template>
                            </Column>
                            <Column header="Otros" field="preOtros" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <div @dblclick="fn_extraerDatosCambiarPrecio(data, 5)" class="cursor-pointer p-3 w-full">
                                        {{ data.preOtros }}
                                    </div>
                                </template>
                            </Column>
                        </DataTable>

                        <Dialog v-model:visible="visible" modal header="Actualizar Precio" :style="{ width: '25rem' }">
                            <span class="text-surface-500 dark:text-surface-400 block mb-5">Servis {{ nombreServis }}, presentación : {{ presentacionServis }}</span>
                            <div class="flex items-center gap-2 mb-4">
                                <label for="precioModal" class="font-semibold">Nuevo Precio</label>
                                <InputNumber  id="precioModal" inputId="locale-us" locale="en-US" :minFractionDigits="2" fluid class="flex-auto" autocomplete="off" v-model="nuevoPrecioPresentacion" autofocus/>
                            </div>
                            <div class="flex justify-end gap-2">
                                <Button type="button" label="Cancelar" severity="secondary" @click="visible = false"></Button>
                                <Button type="button" label="Guardar" @click="fn_guardarPrecio"></Button>
                            </div>
                        </Dialog>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
#tablaPrecios .p-datatable-header{
    padding: 16px 0 !important;
}
#tablaPrecios .p-datatable-sort-icon{
    color: white !important;
}
#tablaPrecios .p-datatable-tbody > tr > td{
    border: 1px solid #e2e8f0 !important;
    padding: 0 !important;
}
#tablaPrecios .p-datatable-thead > tr > th{
    border: 1px solid #e2e8f0 !important;
}
</style>