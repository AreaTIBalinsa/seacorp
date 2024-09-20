<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import $ from 'jquery';

import { ref, onMounted, watch } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { DatosPersonal } from '@/Consultas/Personal/DatosPersonal';
import { DatosServis } from '@/Consultas/Personal/DatosServis';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import Tag from 'primevue/tag';
import Select from 'primevue/select';
import Button from 'primevue/button';
import { router } from '@inertiajs/vue3';

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

const getSeverity = (status) => {
    switch (status) {
        case 'Activo':
            return 'success';

        case 'Suspendido':
            return 'warn';

        case 'Inhabilitado':
            return 'danger';
    }
}

// Select

const selectServis = ref(0);
const servis = ref([]);

watch(selectServis, (newValue) => {
    if (newValue === 0) {
        filteredCustomers.value = customers.value;
    } else {
        filteredCustomers.value = customers.value.filter(customer => customer.grupo === newValue);
    }
});

onMounted(() => {
    DatosPersonal.obtenerDatosPersonal().then((respuesta) => {
        customers.value = getCustomers(respuesta);
        filteredCustomers.value = customers.value;
        loading.value = false;
    }).catch((error) => {
        console.error('Error al obtener los datos', error);
    });

    DatosServis.obtenerDatosServis().then((respuesta) => {
        servis.value = [{ nombreGrupo: "TODOS", idGrupo: 0 }, ...respuesta];
    }).catch((error) => {
        console.error('Error al obtener los datos', error);
    });
});

const fn_extraerDatosEditarPersonal = (data) => {
    router.get(route('personal.edit-personal'), { idEmpleado: data.idEmpleado });
};

</script>

<template>
    <AppLayout title="Personal">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Personal
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto md:px-8">
                <div class="bg-white text-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-8">
                        <div class="flex justify-center md:justify-between items-center flex-wrap gap-4">
                            <h2 class="font-bold text-xl w-full md:w-auto">Lista de Personal</h2>
                            <Button icon="fa-solid fa-user-plus" class="w-full md:w-auto" label="Agregar Personal" as="a" href="/personal/create"/>
                        </div>
                        <DataTable v-model:filters="filters" stateStorage="session" stateKey="dt-state-demo-session" :value="filteredCustomers" paginator paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                        currentPageReportTemplate="{first} a {last} de {totalRecords}" :rows="10" :rowsPerPageOptions="[10, 20, 50]" :loading="loading" removableSort class="text-sm">
                            <template #header>
                                <div class="flex justify-end gap-4 flex-wrap">
                                    <Select v-model="selectServis" :options="servis" optionValue="idGrupo" optionLabel="nombreGrupo" placeholder="Selecciona Servis" checkmark :highlightOnSelect="false" class="w-full md:w-56 text-black" />
                                    <IconField class="w-full md:w-56">
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="filters['global'].value" placeholder="Buscar" class="text-sm w-full md:w-56"/>
                                    </IconField>
                                </div>
                            </template>
                            <template #empty> No se encontraron trabajadores.</template>
                            <template #loading>Cargando datos de trabajadores. Por favor espere. </template>
                            <Column header="Codigo" field="codigo" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <div @dblclick="fn_extraerDatosEditarPersonal(data)" class="cursor-pointer">
                                        {{ data.codigo }}
                                    </div>
                                </template>
                            </Column>
                            <Column header="Apellidos y Nombres" field="nombreCompleto" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <div @dblclick="fn_extraerDatosEditarPersonal(data)" class="cursor-pointer">
                                        {{ data.nombreCompleto }}
                                    </div>
                                </template>
                            </Column>
                            <Column header="Tipo Doc" field="tipoDocumento" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    {{ data.tipoDocumento }}
                                </template>
                            </Column>
                            <Column header="Num Doc" field="numDocEmple" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    {{ data.numDocEmple }}
                                </template>
                            </Column>
                            <Column header="Telefono" field="celularEmple" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    {{ data.celularEmple }}
                                </template>
                            </Column>
                            <Column header="Estado" field="estado" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <Tag :value="data.estado" :severity="getSeverity(data.estado)" />
                                </template>
                            </Column>
                            <Column header="Acciones" headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <div @click="fn_extraerDatosEditarPersonal(data)" class="cursor-pointer flex justify-center items-center">
                                        <Button icon="fa-regular fa-pen-to-square" severity="success" aria-label=""/>
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.p-datatable-header{
    padding: 16px 0 !important;
}
.p-datatable-sort-icon{
    color: white !important;
}
</style>