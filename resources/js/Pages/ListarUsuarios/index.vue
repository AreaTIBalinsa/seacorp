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
import Tag from 'primevue/tag';
import Select from 'primevue/select';
import Button from 'primevue/button';
import { router } from '@inertiajs/vue3';
import Dialog from 'primevue/dialog';
import { DatosUsuarios } from '@/Consultas/Usuarios/DatosUsuarios';

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
    DatosUsuarios.obtenerDatosUsuarios().then((respuesta) => {
        customers.value = getCustomers(respuesta);
        filteredCustomers.value = customers.value;
        loading.value = false;
    }).catch((error) => {
        console.error('Error al obtener los datos', error);
    });
});

</script>

<template>
    <AppLayout title="Usuarios">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuarios
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto md:px-8">
                <div class="bg-white text-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-8">
                        <div class="flex justify-center md:justify-between items-center flex-wrap gap-4">
                            <h2 class="font-bold text-xl w-full md:w-auto">Lista de Usuarios</h2>
                        </div>
                        <DataTable id="tablaUsuarios" v-model:filters="filters" stateStorage="session" stateKey="dt-state-demo-session" :value="filteredCustomers" paginator paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
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
                            <template #empty> No se encontraron usuarios.</template>
                            <template #loading>Cargando datos de usuarios. Por favor espere. </template>
                            <Column header="Nombre Usuario" field="name" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    <div class="p-3 w-full">
                                        {{ data.name }}
                                    </div>
                                </template>
                            </Column>
                            <Column header="Email" field="email" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    {{ data.email }}
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
#tablaUsuarios .p-datatable-header{
    padding: 16px 0 !important;
}
#tablaUsuarios .p-datatable-sort-icon{
    color: white !important;
}
</style>