<script setup>
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import $ from 'jquery';
import { ref, watch, onMounted } from 'vue';

const fechaActual = new Date();
const fechaFiltro = ref(fechaActual);
const fechaHoy = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate()).toISOString().split('T')[0];
const cargandoDetalle = ref(false);
const cargando = ref(false);
const procesos = ref([]);
const detalleProceso = ref(null);

function fn_traerProcesos(fecha){
    $.ajax({
        url: '/fn_traerProcesos',
        method: 'GET',
        data: {
            fecha: fecha
        },
        success: function (response) {
            procesos.value = response;
            cargando.value = false;
        },
        error: function (error) {
            console.error('Error al obtener los datos', error);
        }
    });
}

function fn_traerDetalleProceso(proceso) {
    cargandoDetalle.value = true;
    let idProceso = proceso.idProceso;
    detalleProceso.value = proceso;
    const $container = $('#tablasDetalleProceso');
    $container.empty();
    $.ajax({
        url: '/fn_traerDetalleProceso',
        method: 'GET',
        data: { idProceso: idProceso },
        success: function (response) {
            let especies = {
                'TALLO SOLO': {},
                'TALLO CORAL': {},
                'MEDIA VALVA T/S': {},
                'MEDIA VALVA T/C': {},
                'OTROS': {}
            };

            response.forEach(item => {
                const especie = item.especie && especies[item.especie] ? item.especie : 'OTROS';

                if (!especies[especie][item.nombreGrupo]) {
                    especies[especie][item.nombreGrupo] = {};
                }

                if (item.idLote && item.totalPesoNeto) {
                    especies[especie][item.nombreGrupo][item.idLote] = item.totalPesoNeto;
                } else {
                    console.warn(`Datos faltantes para especie: ${especie}, grupo: ${item.nombreGrupo}, lote: ${item.idLote}`);
                }
            });

            renderTablas(especies);
        },
        error: function (error) {
            console.error('Error al obtener el detalle del proceso', error);
        }
    });
}

function renderTablas(especies) {
    const $container = $('#tablasDetalleProceso');
    $container.empty();

    $.each(especies, function (especie, grupos) {
        if (especie === 'OTROS' && Object.keys(grupos).length === 0) {
            return;
        }

        const $template = $(
            `<div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden border-black">
                        </div>
                    </div>
                </div>
            </div>`
        );

        const $table = $('<table class="min-w-full divide-y divide-gray-200"></table>');
        let colorBgText = "";
        if (especie == "TALLO SOLO") {
            colorBgText = "bg-[#FF0000] text-white";
        } else if (especie == "TALLO CORAL") {
            colorBgText = "bg-[#0060ff] text-white";
        } else if (especie == "MEDIA VALVA T/S") {
            especie = "MEDIA VALVA TALLO SOLO";
            colorBgText = "bg-[#00B050] text-white";
        } else if (especie == "MEDIA VALVA T/C") {
            especie = "MEDIA VALVA TALLO CORAL";
            colorBgText = "bg-[#000000] text-white";
        } else if (especie == "OTROS") {
            colorBgText = "bg-white text-black";
        }
        const $caption = $(`<caption class="${colorBgText} p-3 text-center text-base font-bold uppercase whitespace-nowrap"></caption>`).text(especie);
        $table.append($caption);

        let lotes = new Set();

        $.each(grupos, function (grupo, loteData) {
            $.each(loteData, function (lote) {
                lotes.add(lote);
            });
        });

        lotes = Array.from(lotes).sort((a, b) => a - b);

        const $thead = $(`<thead class="${colorBgText}"></thead>`);
        const $headerRow = $('<tr></tr>');
        $headerRow.append($('<th class="p-3 text-left text-xs font-semibold border-r uppercase"></th>').text('Servis'));

        $.each(lotes, function (_, lote) {
            $headerRow.append($(`<th class="p-3 text-left text-xs font-semibold border-r uppercase"></th>`).text(`Lote ${lote}`));
        });
        $headerRow.append($('<th class="p-3 text-left text-xs font-bold border-r uppercase"></th>').text('Total Servis'));
        $headerRow.append($('<th class="p-3 text-left text-xs font-bold uppercase"></th>').text('%'));
        $thead.append($headerRow);
        $table.append($thead);

        const $tbody = $('<tbody class="bg-white divide-y divide-gray-200 text-sm"></tbody>');

        let totalGeneral = 0;

        $.each(grupos, function (nombreGrupo, loteData) {
            let totalGrupo = 0;
            
            $.each(lotes, function (_, lote) {
                const peso = loteData[lote] || 0;
                totalGrupo += parseFloat(peso);
            });
            totalGeneral += totalGrupo;
        });

        $.each(grupos, function (nombreGrupo, loteData) {
            const $row = $('<tr></tr>');
            $row.append($('<td class="p-3 text-left border-r border-gray-300"></td>').text(nombreGrupo));

            let totalGrupo = 0;
            
            $.each(lotes, function (_, lote) {
                const peso = loteData[lote] || 0;
                totalGrupo += parseFloat(peso);
                const $td = $('<td class="p-3 text-left border-r border-gray-300"></td>').text(peso || '-');
                $row.append($td);
            });

            $row.append($('<td class="p-3 text-left font-bold border-r border-gray-300"></td>').text(totalGrupo.toFixed(3)));

            const porcentaje = totalGeneral ? ((totalGrupo / totalGeneral) * 100).toFixed(2) : '0.00';
            $row.append($('<td class="p-3 text-left font-bold border-r border-gray-300"></td>').text(`${porcentaje} %`));

            $tbody.append($row);
        });

        const $totalRow = $(`<tr class="${colorBgText}"></tr>`);
        $totalRow.append($('<td class="p-3 text-left font-bold border-r border-gray-300"></td>').text('Total Lote'));

        $.each(lotes, function (_, lote) {
            let totalLote = 0;

            $.each(grupos, function (_, loteData) {
                totalLote += parseFloat(loteData[lote]) || 0;
            });

            $totalRow.append($('<td class="p-3 text-left font-bold border-r border-gray-300"></td>').text(totalLote.toFixed(3)));
        });

        $totalRow.append($('<td class="p-3 text-left font-bold border-r border-gray-300"></td>').text(totalGeneral.toFixed(3)));

        const totalPorcentaje = totalGeneral ? '100.00%' : '0.00%';
        $totalRow.append($('<td class="p-3 text-left font-bold border-r border-gray-300"></td>').text(totalPorcentaje));
        $tbody.append($totalRow);

        $table.append($tbody);
        $template.find('.border').append($table);

        $container.append($template);
    });
    cargandoDetalle.value = false;
}

function fn_formatoFecha(fecha) {
    const [year, month, day] = fecha.split('-');
    return `${day}/${month}/${year}`;
}

watch(fechaFiltro, (newFechaFiltro) => {
    detalleProceso.value = null;
    const $container = $('#tablasDetalleProceso');
    $container.empty();
    cargando.value = true;
    let fechaString = null;
    if (newFechaFiltro){
        fechaString = new Date(newFechaFiltro.getFullYear(), newFechaFiltro.getMonth(), newFechaFiltro.getDate()).toISOString().split('T')[0];
    }
    fn_traerProcesos(fechaString);
});

onMounted(() => {
    fn_traerProcesos(fechaHoy);
});
</script>

<template>
    <div class="flex justify-center md:justify-between items-center flex-wrap gap-4 mb-4">
        <h2 class="font-bold text-xl w-full md:w-auto">Reporte por Proceso</h2>
    </div>
    <div class="flex flex-wrap gap-4 mb-4">
        <div class="w-full md:max-w-max">
            <DatePicker v-model="fechaFiltro" showIcon fluid iconDisplay="input" inputId="fechaFiltro" dateFormat="dd/mm/yy"/>
        </div>
    </div>
    <div class="flex flex-col relative">
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
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="border rounded-lg overflow-hidden border-black">
                    <table class="min-w-full divide-y divide-gray-200" id="tablaReportePorProceso">
                        <caption class="bg-blue-600 p-3 text-center text-base font-medium text-white uppercase">Reporte por Proceso</caption>
                        <thead class="bg-blue-600 text-white">
                            <tr class="text-xs font-semibold uppercase">
                                <th class="p-3 text-left border-r">N&uacute;mero de <br> Proceso</th>
                                <th class="p-3 text-left border-r">Fecha Inicio</th>
                                <th class="p-3 text-left border-r">Hora Inicio</th>
                                <th class="p-3 text-left border-r">Fecha Término</th>
                                <th class="p-3 text-left border-r">Hora Término</th>
                                <th class="p-3 text-left border-r">Acumulado por <br> Colaboradores</th>
                                <th class="p-3 text-left border-r">Extra de <br> Colaboradores</th>
                                <th class="p-3 text-left border-r">Total <br> Acumulado</th>
                                <th class="p-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            <tr v-if="!Object.keys(procesos).length">
                                <td colspan="9" class="text-center">No hay datos</td>
                            </tr>
                            <tr v-for="proceso in procesos" :key="proceso.idProceso">
                                <td class="p-3 text-center border-r border-gray-300">{{ proceso.idProceso }}</td>
                                <td class="p-3 text-left border-r border-gray-300">{{ fn_formatoFecha(proceso.fechaInicio) }}</td>
                                <td class="p-3 text-left border-r border-gray-300">{{ proceso.horainicio }}</td>
                                <td class="p-3 text-left border-r border-gray-300">{{ fn_formatoFecha(proceso.fechaFin) }}</td>
                                <td class="p-3 text-left border-r border-gray-300">{{ proceso.horaFin }}</td>
                                <td class="p-3 text-left border-r border-gray-300">{{ proceso.acumulado }}</td>
                                <td class="p-3 text-left border-r border-gray-300">{{ proceso.totalPesoExtra }}</td>
                                <td class="p-3 text-left border-r border-gray-300">{{ parseFloat(proceso.acumulado) + parseFloat(proceso.totalPesoExtra) }}</td>
                                <td class="p-3 text-left border-b border-gray-300 flex justify-center">
                                    <Button icon="pi pi-search" class="p-button-rounded p-button-info" @click="fn_traerDetalleProceso(proceso)"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>   
    <hr class="my-10">
    <div class="flex flex-col gap-12 relative">
        <!-- Simulación de Loading -->
        <div v-show="cargandoDetalle" class="absolute top-0 left-0 w-full h-full bg-white z-50 flex justify-center items-center">
            <div class="flex flex-col justify-center items-center">
                <p class="text-sm font-medium">Cargando...</p>
                <div class="flex">
                    <div class="loader"></div>
                    <div class="loader"></div>
                </div>
            </div>
        </div>
        <!-- ==================== -->
        <div v-if="detalleProceso" class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="border rounded-lg overflow-hidden border-black">
                        <table class="min-w-full divide-y divide-gray-200" id="tablaReportePorProceso">
                            <caption class="bg-yellow-400 text-black p-3 text-center text-base font-bold uppercase">Proceso N° {{ detalleProceso.idProceso }}</caption>
                            <thead class="bg-yellow-400 text-black">
                                <tr class="text-xs font-bold uppercase">
                                    <th class="p-3 text-left border-r">Fecha Inicio</th>
                                    <th class="p-3 text-left border-r">Fecha Término</th>
                                    <th class="p-3 text-left border-r">Acumulado por <br> Colaboradores</th>
                                    <th class="p-3 text-left border-r">Extra de <br> Colaboradores</th>
                                    <th class="p-3 text-left border-r">Total <br> Acumulado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                <tr>
                                    <td class="p-3 text-left border-r border-gray-300">{{ fn_formatoFecha(detalleProceso.fechaInicio) }} | {{ detalleProceso.horainicio }}</td>
                                    <td class="p-3 text-left border-r border-gray-300">{{ fn_formatoFecha(detalleProceso.fechaFin) }} | {{ detalleProceso.horaFin }}</td>
                                    <td class="p-3 text-left border-r border-gray-300">{{ detalleProceso.acumulado }}</td>
                                    <td class="p-3 text-left border-r border-gray-300">{{ detalleProceso.totalPesoExtra }}</td>
                                    <td class="p-3 text-left border-r border-gray-300">{{ parseFloat(detalleProceso.acumulado) + parseFloat(detalleProceso.totalPesoExtra) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="tablasDetalleProceso" class="flex flex-col gap-8">

        </div>
    </div> 
</template>