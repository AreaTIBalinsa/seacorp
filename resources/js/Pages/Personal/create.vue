<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import InputMask from 'primevue/inputmask';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Button from 'primevue/button';
import { ref, onMounted, watch } from 'vue';
import { DatosServis } from '@/Consultas/Personal/DatosServis';
import { useForm, usePage } from '@inertiajs/vue3';
import $ from 'jquery';
const page = usePage();

const form = useForm({
    nombresEmpleado: "",
    apellidoPaternoEmple: "",
    apellidoMaternoEmple: "",
    tipoDocumento: "DNI",
    numDocEmple: "",
    celularEmple: "",
    direccionEmple: "",
    estado: "Activo",
    grupo: "",
    codigo: "",
    fecha_registro: "",
    hora_registro: "",
    user_registro: page.props.auth.user.id,
})

const servis = ref([]);

const documentos = ref([
    { tipoDocumento: 'DNI', valor: 'DNI' },
    { tipoDocumento: 'RUC', valor: 'RUC' },
]);

const estados = ref([
    { estados: 'Activo', valor: 'Activo' },
    { estados: 'Suspendido', valor: 'Suspendido' },
    { estados: 'Inhabilitado', valor: 'Inhabilitado' },
]);

onMounted(() => {
    DatosServis.obtenerDatosServis().then((respuesta) => {
        servis.value = respuesta;
    }).catch((error) => {
        console.error('Error al obtener los datos', error);
    });
});

const agregarPersonal = () => {
    const ahoraEnNY = new Date();
    const fechaActual = ahoraEnNY.toISOString().split('T')[0];
    const horaActual = ahoraEnNY.toTimeString().split(' ')[0];

    form.fecha_registro = fechaActual;
    form.hora_registro = horaActual;

    form.post(route('personal.store'), {
        onSuccess: () => {
            console.log('Registro creado exitosamente');
        },
        onError: (errors) => {
            console.error('Error al crear el registro:', errors);
        },
    });
}

watch(() => form.grupo, (newValue) => {
    $.ajax({
        url: '/fn_traerCodigoPersonal',
        method: 'GET',
        data: {
            grupo: newValue
        },
        success: function (response) {
            const codigo = response[0]["cant_cod"];
            form.codigo = codigo;
        },
        error: function (error) {
            console.error('Error al obtener los datos', error);
        }
    });
});

</script>

<template>
    <AppLayout title="Agregar Personal">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Agregar Personal
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto md:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-8">
                        <form @submit.prevent="agregarPersonal">
                            <div class="flex justify-between items-center mb-5">
                                <div class="flex ">
                                    <div class="flex flex-col gap-y-2">
                                        <label class="text-gray-500 text-xs">Servis</label>
                                        <Select v-model="form.grupo" id="grupo" :options="servis" optionValue="idGrupo" optionLabel="nombreGrupo" placeholder="Selecciona Servis" checkmark :highlightOnSelect="false" class="w-full md:w-56 text-black" />
                                        <span v-if="form.errors.grupo" class="text-red-500 text-xs">{{ form.errors.grupo }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-y-2">
                                    <label for="codigo" class="text-gray-500 text-xs">Codigo</label>
                                    <InputText id="codigo" v-model="form.codigo" class="w-full md:w-56" disabled="disabled"/>
                                    <span v-if="form.errors.codigo" class="text-red-500 text-xs">{{ form.errors.codigo }}</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div class="flex flex-col gap-y-2">
                                    <label for="nombresEmpleado" class="text-gray-500 text-xs">Nombre</label>
                                    <InputText id="nombresEmpleado" v-model="form.nombresEmpleado" class="w-full"/>
                                    <span v-if="form.errors.nombresEmpleado" class="text-red-500 text-xs">{{ form.errors.nombresEmpleado }}</span>
                                </div>
                                <div class="flex flex-col gap-y-2">
                                    <label for="apellidoPaternoEmple" class="text-gray-500 text-xs">Apellido Paterno</label>
                                    <InputText id="apellidoPaternoEmple" v-model="form.apellidoPaternoEmple" class="w-full"/>
                                    <span v-if="form.errors.apellidoPaternoEmple" class="text-red-500 text-xs">{{ form.errors.apellidoPaternoEmple }}</span>
                                </div>
                                <div class="flex flex-col gap-y-2">
                                    <label for="apellidoMaternoEmple" class="text-gray-500 text-xs">Apellido Materno</label>
                                    <InputText id="apellidoMaternoEmple" v-model="form.apellidoMaternoEmple" class="w-full"/>
                                    <span v-if="form.errors.apellidoMaternoEmple" class="text-red-500 text-xs">{{ form.errors.apellidoMaternoEmple }}</span>
                                </div>
                                <div class="flex flex-col gap-y-2">
                                    <label class="text-gray-500 text-xs">Tipo de Documento</label>
                                    <Select v-model="form.tipoDocumento" id="tipoDocumento" :options="documentos" optionValue="valor" optionLabel="tipoDocumento" placeholder="Selecciona Tipo Documento" checkmark :highlightOnSelect="false" class="w-full text-black" />
                                    <span v-if="form.errors.tipoDocumento" class="text-red-500 text-xs">{{ form.errors.tipoDocumento }}</span>
                                </div>
                                <div class="flex flex-col gap-y-2">
                                    <label for="numDocEmple" class="text-gray-500 text-xs">Numero de Documento</label>
                                    <InputText id="numDocEmple" v-model="form.numDocEmple" class="w-full"/>
                                    <span v-if="form.errors.numDocEmple" class="text-red-500 text-xs">{{ form.errors.numDocEmple }}</span>
                                </div>
                                <div class="flex flex-col gap-y-2">
                                    <label for="celularEmple" class="text-gray-500 text-xs">Celular</label>
                                    <InputMask id="celularEmple" v-model="form.celularEmple" mask="999 999 999" placeholder="999 999 999" class="w-full"/>
                                    <span v-if="form.errors.celularEmple" class="text-red-500 text-xs">{{ form.errors.celularEmple }}</span>
                                </div>
                                <div class="flex flex-col gap-y-2 md:col-span-2">
                                    <label for="direccionEmple" class="text-gray-500 text-xs">Dirección</label>
                                    <InputText id="direccionEmple" v-model="form.direccionEmple" class="w-full"/>
                                    <span v-if="form.errors.direccionEmple" class="text-red-500 text-xs">{{ form.errors.direccionEmple }}</span>
                                </div>
                            </div>
                            <div class="flex justify-end items-center">
                                <Button type="submit" icon="fa-solid fa-save" class="w-full md:w-auto" label="Guardar"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
