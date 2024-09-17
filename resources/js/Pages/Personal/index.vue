<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { ref, onMounted } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { CustomerService } from '@/service/CustomerService';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import Tag from 'primevue/tag';

const customers = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});

const loading = ref(true);

onMounted(() => {
    CustomerService.getCustomersMedium().then((data) => {
            customers.value = getCustomers(data);
            loading.value = false;
        });
});

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
</script>

<template>
    <AppLayout title="Reportes">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reportes
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white text-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-8">
                        <DataTable v-model:filters="filters" stateStorage="session" stateKey="dt-state-demo-session" :value="customers" paginator :rows="10" :loading="loading" removableSort class="text-sm">
                            <template #header>
                                <div class="flex justify-end">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="filters['global'].value" placeholder="Buscar" class="text-sm"/>
                                    </IconField>
                                </div>
                            </template>
                            <template #empty> No se encontraron trabajadores.</template>
                            <template #loading>Cargando datos de trabajadores. Por favor espere. </template>
                            <Column header="#" class="hidden">
                                <template #body="{ data }">
                                    {{ data.idEmpleado }}
                                </template>
                            </Column>
                            <Column header="Codigo" field="codigo" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    {{ data.codigo }}
                                </template>
                            </Column>
                            <Column header="Apellidos y Nombres" field="nombreCompleto" sortable headerStyle="background-color: #0060ff; color: white;">
                                <template #body="{ data }">
                                    {{ data.nombreCompleto }}
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
                            <Column header="Grupo" class="hidden">
                                <template #body="{ data }">
                                    {{ data.grupo }}
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>