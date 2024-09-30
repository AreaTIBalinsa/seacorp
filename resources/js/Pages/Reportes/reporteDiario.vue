<script setup>
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import $ from 'jquery';
import { ref, watch, onMounted } from 'vue';
import { DatosServis } from '@/Consultas/Personal/DatosServis';

const fechaActual = new Date();
const fechaFiltro = ref(fechaActual);
const selectServis = ref(null);
const servis = ref([]);
const cargando = ref(false);

const precioTalloSolo = ref(0);
const precioTalloCoral = ref(0);
const precioMediaValvaTalloSolo = ref(0);
const precioMediaValvaTalloCoral = ref(0);
const precioOtros = ref(0);

onMounted(() => {
    DatosServis.obtenerDatosServis().then((respuesta) => {
        servis.value = respuesta;
    }).catch((error) => {
        console.error('Error al obtener los datos', error);
    });
});

function fn_traerDatosReporteDiario(fecha, grupo){
    precioTalloSolo.value = 0;
    precioTalloCoral.value = 0;
    precioMediaValvaTalloSolo.value = 0;
    precioMediaValvaTalloCoral.value = 0;
    precioOtros.value = 0;

    cargando.value = true;

    $.ajax({
        url: '/fn_traerPreciosServisGrupo',
        method: 'GET',
        data: {
            grupo: grupo
        },
        success: function (response) {
            precioTalloSolo.value = response[0]["preTalloSolo"];
            precioTalloCoral.value = response[0]["preTalloCoral"];
            precioMediaValvaTalloSolo.value = response[0]["preMediaValvaTS"];
            precioMediaValvaTalloCoral.value = response[0]["preMediaValvaTC"];
            precioOtros.value = response[0]["preOtros"];

            $.ajax({
                url: '/fn_traerDatosReporteDiario',
                method: 'GET',
                data: {
                    fecha: fecha,
                    grupo: grupo
                },
                success: function (response) {
                    const bodyReporteDiario = $('#bodyReporteDiario');
                    bodyReporteDiario.empty();
                    const bodyReporteDiarioExcel = $('#bodyReporteDiarioExcel');
                    bodyReporteDiarioExcel.empty();

                    if(response.length > 0){

                        const result = {};

                        response.forEach(item => {
                            const { codigoUsuario, nombreCompleto, especie, totalPesoNeto, totalTara, pesoDescuento } = item;

                            // Si no existe el código de usuario en el resultado, inicializar sus valores
                            if (!result[codigoUsuario]) {
                                result[codigoUsuario] = {
                                    codigoUsuario,
                                    nombreCompleto,
                                    totalPesoNetoMEDIAVALVATC: 0,
                                    totalTaraMEDIAVALVATC: 0,
                                    totalPesoNetoMEDIAVALVATS: 0,
                                    totalTaraMEDIAVALVATS: 0,
                                    totalPesoNetoTALLOSOLO: 0,
                                    totalTaraTALLOSOLO: 0,
                                    totalPesoNetoTALLOCORAL: 0,
                                    totalTaraTALLOCORAL: 0,
                                    totalPesoNetoOTROS: 0,
                                    totalTaraOTROS: 0,
                                    descuentoMEDIAVALVATC: 0,
                                    descuentoMEDIAVALVATS: 0,
                                    descuentoTALLOSOLO: 0,
                                    descuentoTALLOCORAL: 0,
                                    descuentoOTROS: 0
                                };
                            }

                            // Identificar la especie y sumar los valores correspondientes
                            switch (especie) {
                                case "MEDIA VALVA T/C":
                                    result[codigoUsuario].totalPesoNetoMEDIAVALVATC += parseFloat(totalPesoNeto);
                                    result[codigoUsuario].totalTaraMEDIAVALVATC += parseFloat(totalTara);
                                    result[codigoUsuario].descuentoMEDIAVALVATC += parseFloat(pesoDescuento);
                                    break;
                                case "MEDIA VALVA T/S":
                                    result[codigoUsuario].totalPesoNetoMEDIAVALVATS += parseFloat(totalPesoNeto);
                                    result[codigoUsuario].totalTaraMEDIAVALVATS += parseFloat(totalTara);
                                    result[codigoUsuario].descuentoMEDIAVALVATS += parseFloat(pesoDescuento);
                                    break;
                                case "TALLO SOLO":
                                    result[codigoUsuario].totalPesoNetoTALLOSOLO += parseFloat(totalPesoNeto);
                                    result[codigoUsuario].totalTaraTALLOSOLO += parseFloat(totalTara);
                                    result[codigoUsuario].descuentoTALLOSOLO += parseFloat(pesoDescuento);
                                    break;
                                case "TALLO CORAL":
                                    result[codigoUsuario].totalPesoNetoTALLOCORAL += parseFloat(totalPesoNeto);
                                    result[codigoUsuario].totalTaraTALLOCORAL += parseFloat(totalTara);
                                    result[codigoUsuario].descuentoTALLOCORAL += parseFloat(pesoDescuento);
                                    break;
                                case "OTROS":
                                    result[codigoUsuario].totalPesoNetoOTROS += parseFloat(totalPesoNeto);
                                    result[codigoUsuario].totalTaraOTROS += parseFloat(totalTara);
                                    result[codigoUsuario].descuentoOTROS += parseFloat(pesoDescuento);
                                    break;
                                default:
                                    break;
                            }
                        });

                        const resultadoFinal = Object.values(result);

                        let contadorExcelInicio = 6;
                        let contadorExcel = 6;

                        let totalKgTalloSolo = 0;
                        let totalKgTalloCoral = 0;
                        let totalKgMediaValvaTalloSolo = 0;
                        let totalKgMediaValvaTalloCoral = 0;
                        let totalKgOtros = 0;
                        
                        let descuentoTotalKgTalloSolo = 0;
                        let descuentoTotalKgTalloCoral = 0;
                        let descuentoTotalKgMediaValvaTalloSolo = 0;
                        let descuentoTotalKgMediaValvaTalloCoral = 0;
                        let descuentoTotalKgOtros = 0;

                        let totalAPagarGlobal = 0;

                        resultadoFinal.forEach(function(item) {

                            let pesoNetoTalloSolo = item.totalPesoNetoTALLOSOLO;
                            let pesoNetoTalloCoral = item.totalPesoNetoTALLOCORAL;
                            let pesoNetoMediaValvaTalloSolo = item.totalPesoNetoMEDIAVALVATS;
                            let pesoNetoMediaValvaTalloCoral = item.totalPesoNetoMEDIAVALVATC;
                            let pesoNetoOtros = item.totalPesoNetoOTROS;

                            totalKgTalloSolo += pesoNetoTalloSolo;
                            totalKgTalloCoral += pesoNetoTalloCoral;
                            totalKgMediaValvaTalloSolo += pesoNetoMediaValvaTalloSolo;
                            totalKgMediaValvaTalloCoral += pesoNetoMediaValvaTalloCoral;
                            totalKgOtros += pesoNetoOtros;

                            let descuentoTalloSolo = item.descuentoTALLOSOLO;
                            let descuentoTalloCoral = item.descuentoTALLOCORAL;
                            let descuentoMediaValvaTalloSolo = item.descuentoMEDIAVALVATS;
                            let descuentoMediaValvaTalloCoral = item.descuentoMEDIAVALVATC;
                            let descuentoOtros = item.descuentoOTROS;

                            descuentoTotalKgTalloSolo += descuentoTalloSolo;
                            descuentoTotalKgTalloCoral += descuentoTalloCoral;
                            descuentoTotalKgMediaValvaTalloSolo += descuentoMediaValvaTalloSolo;
                            descuentoTotalKgMediaValvaTalloCoral += descuentoMediaValvaTalloCoral;
                            descuentoTotalKgOtros += descuentoOtros;

                            let totalAPagar = ((pesoNetoTalloSolo - descuentoTalloSolo) * precioTalloSolo.value) + ((pesoNetoTalloCoral - descuentoTalloCoral) * precioTalloCoral.value) + ((pesoNetoMediaValvaTalloSolo - descuentoMediaValvaTalloSolo) * precioMediaValvaTalloSolo.value) + ((pesoNetoMediaValvaTalloCoral - descuentoMediaValvaTalloCoral) * precioMediaValvaTalloCoral.value) + ((pesoNetoOtros - descuentoOtros) * precioOtros.value);
                            totalAPagarGlobal += totalAPagar;

                            const nuevaFila = `
                                <tr>
                                    <td class="p-2 text-center whitespace-nowrap text-sm font-bold text-[#000000] border-r border-black">${item.codigoUsuario}</td>
                                    <td colspan="3" class="p-2 font-bold text-left whitespace-nowrap text-sm text-[#000000] border-r border-black">${item.nombreCompleto}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">${(pesoNetoTalloSolo).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">${(pesoNetoTalloCoral).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">${(pesoNetoMediaValvaTalloSolo).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">${(pesoNetoMediaValvaTalloCoral).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">${(pesoNetoOtros).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">${(descuentoTalloSolo).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">${(descuentoTalloCoral).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">${(descuentoMediaValvaTalloSolo).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">${(descuentoMediaValvaTalloCoral).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">${(descuentoOtros).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000]">${totalAPagar.toFixed(2)}</td>
                                </tr>
                            `;

                            bodyReporteDiario.append(nuevaFila);

                            const nuevaFilaExcel = `
                                <tr>
                                    <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${item.codigoUsuario}</td>
                                    <td style="border: 1px solid #000000; text-align: left; color: #000000; font-weight: bold; white-space: nowrap;" colspan="3" class="p-2 text-sm">${item.nombreCompleto}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${(pesoNetoTalloSolo).toFixed(2)}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${(pesoNetoTalloCoral).toFixed(2)}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${(pesoNetoMediaValvaTalloSolo).toFixed(2)}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${(pesoNetoMediaValvaTalloCoral).toFixed(2)}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${(pesoNetoOtros).toFixed(2)}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${(descuentoTalloSolo).toFixed(2)}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${(descuentoTalloCoral).toFixed(2)}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${(descuentoMediaValvaTalloSolo).toFixed(2)}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${(descuentoMediaValvaTalloCoral).toFixed(2)}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">${(descuentoOtros).toFixed(2)}</td>
                                    <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =($D$1 * (E${contadorExcel} - J${contadorExcel})) + ($D$2 * (F${contadorExcel} - K${contadorExcel})) + ($D$3 * (G${contadorExcel} - L${contadorExcel})) + ($D$4 * (H${contadorExcel} - M${contadorExcel})) + ($D$5 * (I${contadorExcel} - N${contadorExcel}))</td>
                                </tr>
                            `;

                            contadorExcel++;

                            bodyReporteDiarioExcel.append(nuevaFilaExcel);
                        });

                        const nuevaFila = `
                                <tr class="bg-blue-300">
                                    <td colspan="4" class="p-2 font-bold text-left whitespace-nowrap text-sm text-[#000000] border-r border-black">Total</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">${(totalKgTalloSolo).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">${(totalKgTalloCoral).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">${(totalKgMediaValvaTalloSolo).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">${(totalKgMediaValvaTalloCoral).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">${(totalKgOtros).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">${(descuentoTotalKgTalloSolo).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">${(descuentoTotalKgTalloCoral).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">${(descuentoTotalKgMediaValvaTalloSolo).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">${(descuentoTotalKgMediaValvaTalloCoral).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">${(descuentoTotalKgOtros).toFixed(2)}</td>
                                    <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000]">${totalAPagarGlobal.toFixed(2)}</td>
                                </tr>
                            `;

                        bodyReporteDiario.append(nuevaFila);

                        const nuevaFilaExcel = `
                                <tr>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: left; color: #000000; font-weight: bold; white-space: nowrap;" colspan="4" class="p-2 text-sm">Total</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(E${contadorExcelInicio}:E${contadorExcel - 1})</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(F${contadorExcelInicio}:F${contadorExcel - 1})</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(G${contadorExcelInicio}:G${contadorExcel - 1})</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(H${contadorExcelInicio}:H${contadorExcel - 1})</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(I${contadorExcelInicio}:I${contadorExcel - 1})</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(J${contadorExcelInicio}:J${contadorExcel - 1})</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(K${contadorExcelInicio}:K${contadorExcel - 1})</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(L${contadorExcelInicio}:L${contadorExcel - 1})</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(M${contadorExcelInicio}:M${contadorExcel - 1})</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(N${contadorExcelInicio}:N${contadorExcel - 1})</td>
                                    <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm"> =SUMA(O${contadorExcelInicio}:O${contadorExcel - 1})</td>
                                </tr>
                            `;

                        bodyReporteDiarioExcel.append(nuevaFilaExcel);

                        cargando.value = false;
                    }else{
                        bodyReporteDiario.append(`<tr><td colspan="15" class="text-center">No hay datos</td></tr>`);
                        cargando.value = false;
                    }
                },
                error: function (error) {
                    console.error('Error al obtener los datos', error);
                }
            });
        },
        error: function (error) {
            console.error('Error al obtener los datos', error);
        }
    });
}

watch([fechaFiltro, selectServis], ([newFechaFiltro, newSelectServis]) => {
    let fechaString = null;
    if (newFechaFiltro){
        fechaString = new Date(newFechaFiltro).toISOString().split('T')[0]
    }
    if (fechaString && newSelectServis){
        fn_traerDatosReporteDiario(fechaString, newSelectServis);
    }
});

const tableToExcel = (function () {
    const uri = "data:application/vnd.ms-excel;charset=utf-8;base64,",
        template =
            '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        base64 = function (s) {
            return window.btoa(unescape(encodeURIComponent(s)));
        },
        format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            });
        };
    return function (table, name) {
        if (!table.nodeType) table = document.getElementById(table);
        const ctx = { worksheet: name || "Worksheet", table: table.innerHTML };
        const link = document.createElement("a");
        link.download = name + ".xls";
        link.href = uri + base64(format(template, ctx));
        link.click();
    };
})();

const fn_crearExcel = () => {
    let fechaString = new Date(fechaFiltro.value).toISOString().split('T')[0];
    let selectString = (servis.value.filter(servi => servi.idGrupo === selectServis.value))[0].nombreGrupo;
    tableToExcel("tablaReporteDiarioExcel", `REPORTE DIARIO ${selectString} ${fechaString}`);
};

</script>

<template>
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
        <div class="flex justify-center md:justify-between items-center flex-wrap gap-4 mb-4">
            <h2 class="font-bold text-xl w-full md:w-auto">Reporte Diario</h2>
        </div>
        <div class="flex flex-wrap gap-4 mb-4">
            <div class="w-full md:max-w-max">
                <DatePicker v-model="fechaFiltro" showIcon fluid iconDisplay="input" inputId="fechaFiltro" />
            </div>
            <Select v-model="selectServis" :options="servis" optionValue="idGrupo" optionLabel="nombreGrupo" placeholder="Selecciona Servis" checkmark :highlightOnSelect="false" class="w-full md:w-56 text-black" />
        </div>
        <div class="flex justify-end items-center mb-4">
            <Button @click="fn_crearExcel" icon="fa-regular fa-file-excel" class="w-full md:w-auto" severity="info" label="Exportar a Excel"/>
        </div>
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="border rounded-lg overflow-hidden border-black">
                        <table class="min-w-full divide-y divide-gray-200" id="tablaReporteDiario">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th rowspan="5" class="p-2 text-center text-base font-bold bg-[#FFFF85] text-black border-r border-b border-black">Codigo</th>
                                    <th rowspan="5" class="p-2 text-center text-base font-bold bg-[#FFFF85] min-w-[200px] text-black border-r border-b border-black">Nombre</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FF0000] min-w-[100px] text-white border-r border-b border-black">T/S</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FF0000] min-w-[100px] text-white border-r border-b border-black">{{ precioTalloSolo }}</th>
                                    <th class="p-2 text-center min-w-[150px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center min-w-[150px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center min-w-[150px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center min-w-[150px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center min-w-[150px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center min-w-[200px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">T/C</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">{{ precioTalloCoral }}</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">{{ precioMediaValvaTalloSolo }}</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">{{ precioMediaValvaTalloCoral }}</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FF0000] text-white border-r border-b border-black">Tallo Solo</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">Tallo Coral</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">Media Valva T/S</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">Media Valva T/C</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">Otros</th>
                                    <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#F14747] text-white border-r border-b border-black">Descuentos Kg</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FFFF00] text-black border-b border-black">Neto a Pagar</th>
                                </tr>
                                <tr>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">{{ precioOtros }}</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-r border-b border-black">Kg</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-r border-b border-black">Kg</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-r border-b border-black">Kg</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-r border-b border-black">Kg</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-r border-b border-black">Kg</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FF0000] text-white border-r border-b border-black">T/S</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">T/C</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>
                                    <th class="p-2 text-center text-sm font-bold bg-[#FFFF00] text-black border-b border-black">S/</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black" id="bodyReporteDiario">
                                <tr><td colspan="15" class="text-center">No hay datos</td></tr>
                            </tbody>
                        </table>
                        <table class="min-w-full hidden" id="tablaReporteDiarioExcel">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" rowspan="5" class="p-2 text-base">Codigo</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; width: 200px; color: black" rowspan="5" class="p-2 text-base">Nombre</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; width: 100px; color: white" class="p-2 text-sm">T/S</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; width: 100px; color: white" class="p-2 text-sm">{{ precioTalloSolo }}</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 150px; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 150px; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 150px; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 150px; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 150px; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 200px; color: black" class="p-2 text-sm">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">T/C</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">{{ precioTalloCoral }}</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">{{ precioMediaValvaTalloSolo }}</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                    <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; color: black" class="p-2 text-sm">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">{{ precioMediaValvaTalloCoral }}</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; color: white" class="p-2 text-sm">Tallo Solo</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">Tallo Coral</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">Media Valva T/S</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">Media Valva T/C</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">Otros</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #F14747; text-align: center; color: white" colspan="5" class="p-2 text-sm">Descuentos Kg</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF00; text-align: center; color: black" class="p-2 text-sm">Neto a Pagar</th>
                                </tr>
                                <tr>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">{{ precioOtros }}</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" class="p-2 text-sm">Kg</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" class="p-2 text-sm">Kg</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" class="p-2 text-sm">Kg</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" class="p-2 text-sm">Kg</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" class="p-2 text-sm">Kg</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; color: white" class="p-2 text-sm">T/S</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">T/C</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>
                                    <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF00; text-align: center; color: black" class="p-2 text-sm">S/</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black" id="bodyReporteDiarioExcel">
                                <tr><td colspan="15" class="text-center">No hay datos</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</template>