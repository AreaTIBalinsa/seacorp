<script setup>
import Button from 'primevue/button';
import Select from 'primevue/select';
import Checkbox from 'primevue/checkbox';

import $ from 'jquery';
import { ref, onMounted, watch } from 'vue';
import { DatosServis } from '@/Consultas/Personal/DatosServis';

const cargando = ref(false);
const selectServis = ref(null);
const servis = ref([]);
const muestraSabado = ref(true);

const precioTalloSolo = ref(0);
const precioTalloCoral = ref(0);
const precioMediaValvaTalloSolo = ref(0);
const precioMediaValvaTalloCoral = ref(0);
const precioOtros = ref(0);

const resultadoAgrupadoReactivo = ref([]);

const fechaLunes = ref(null);
const fechaMartes = ref(null);
const fechaMiercoles = ref(null);
const fechaJueves = ref(null);
const fechaViernes = ref(null);
const fechaSabado = ref(null);

const lunes = ref(null);
const martes = ref(null);
const miercoles = ref(null);
const jueves = ref(null);
const viernes = ref(null);
const sabado = ref(null);

// Establecer semana Actual
const fechaActual = new Date();
const firstDayOfYear = new Date(fechaActual.getFullYear(), 0, 1);
const pastDaysOfYear = (fechaActual - firstDayOfYear) / 86400000;

const currentWeekNumber = Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
const currentYear = fechaActual.getFullYear();

const semanaFiltro = ref(`${currentYear}-W${String(currentWeekNumber).padStart(2, '0')}`);

const totalLunesTALLOSOLO = ref(0);
const totalLunesTALLOCORAL = ref(0);
const totalLunesMEDIAVALVATALLOSOLO = ref(0);
const totalLunesMEDIAVALVATALLOCORAL = ref(0);
const totalLunesOTROS = ref(0);

const totalMartesTALLOSOLO = ref(0);
const totalMartesTALLOCORAL = ref(0);
const totalMartesMEDIAVALVATALLOSOLO = ref(0);
const totalMartesMEDIAVALVATALLOCORAL = ref(0);
const totalMartesOTROS = ref(0);

const totalMiercolesTALLOSOLO = ref(0);
const totalMiercolesTALLOCORAL = ref(0);
const totalMiercolesMEDIAVALVATALLOSOLO = ref(0);
const totalMiercolesMEDIAVALVATALLOCORAL = ref(0);
const totalMiercolesOTROS = ref(0);

const totalJuevesTALLOSOLO = ref(0);
const totalJuevesTALLOCORAL = ref(0);
const totalJuevesMEDIAVALVATALLOSOLO = ref(0);
const totalJuevesMEDIAVALVATALLOCORAL = ref(0);
const totalJuevesOTROS = ref(0);

const totalViernesTALLOSOLO = ref(0);
const totalViernesTALLOCORAL = ref(0);
const totalViernesMEDIAVALVATALLOSOLO = ref(0);
const totalViernesMEDIAVALVATALLOCORAL = ref(0);
const totalViernesOTROS = ref(0);

const totalSabadoTALLOSOLO = ref(0);
const totalSabadoTALLOCORAL = ref(0);
const totalSabadoMEDIAVALVATALLOSOLO = ref(0);
const totalSabadoMEDIAVALVATALLOCORAL = ref(0);
const totalSabadoOTROS = ref(0);

const totalDescuentoTALLOSOLO = ref(0);
const totalDescuentoTALLOCORAL = ref(0);
const totalDescuentoMEDIAVALVATALLOSOLO = ref(0);
const totalDescuentoMEDIAVALVATALLOCORAL = ref(0);
const totalDescuentoOTROS = ref(0);

const totalTALLOSOLO = ref(0);
const totalTALLOCORAL = ref(0);
const totalMEDIAVALVATALLOSOLO = ref(0);
const totalMEDIAVALVATALLOCORAL = ref(0);
const totalOTROS = ref(0);

const totalAPagar = ref(0);

function fn_resetTotales(){
    totalLunesTALLOSOLO.value = 0;
    totalLunesTALLOCORAL.value = 0;
    totalLunesMEDIAVALVATALLOSOLO.value = 0;
    totalLunesMEDIAVALVATALLOCORAL.value = 0;
    totalLunesOTROS.value = 0;

    totalMartesTALLOSOLO.value = 0;
    totalMartesTALLOCORAL.value = 0;
    totalMartesMEDIAVALVATALLOSOLO.value = 0;
    totalMartesMEDIAVALVATALLOCORAL.value = 0;
    totalMartesOTROS.value = 0;

    totalMiercolesTALLOSOLO.value = 0;
    totalMiercolesTALLOCORAL.value = 0;
    totalMiercolesMEDIAVALVATALLOSOLO.value = 0;
    totalMiercolesMEDIAVALVATALLOCORAL.value = 0;
    totalMiercolesOTROS.value = 0;

    totalJuevesTALLOSOLO.value = 0;
    totalJuevesTALLOCORAL.value = 0;
    totalJuevesMEDIAVALVATALLOSOLO.value = 0;
    totalJuevesMEDIAVALVATALLOCORAL.value = 0;
    totalJuevesOTROS.value = 0;

    totalViernesTALLOSOLO.value = 0;
    totalViernesTALLOCORAL.value = 0;
    totalViernesMEDIAVALVATALLOSOLO.value = 0;
    totalViernesMEDIAVALVATALLOCORAL.value = 0;
    totalViernesOTROS.value = 0;

    totalSabadoTALLOSOLO.value = 0;
    totalSabadoTALLOCORAL.value = 0;
    totalSabadoMEDIAVALVATALLOSOLO.value = 0;
    totalSabadoMEDIAVALVATALLOCORAL.value = 0;
    totalSabadoOTROS.value = 0;

    totalDescuentoTALLOSOLO.value = 0;
    totalDescuentoTALLOCORAL.value = 0;
    totalDescuentoMEDIAVALVATALLOSOLO.value = 0;
    totalDescuentoMEDIAVALVATALLOCORAL.value = 0;
    totalDescuentoOTROS.value = 0;
    
    totalTALLOSOLO.value = 0;
    totalTALLOCORAL.value = 0;
    totalMEDIAVALVATALLOSOLO.value = 0;
    totalMEDIAVALVATALLOCORAL.value = 0;
    totalOTROS.value = 0;

    totalAPagar.value = 0;
}

onMounted(() => {
    DatosServis.obtenerDatosServis().then((respuesta) => {
        servis.value = respuesta;
    }).catch((error) => {
        console.error('Error al obtener los datos', error);
    });
});

const fn_formatearDia = (fecha) => {
    const fechaOriginal = String(fecha)
    const meses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
    const [anio, mes, dia] = fechaOriginal.split("-");
    return `${dia} - ${meses[parseInt(mes, 10) - 1]}`;
}

const fn_asignaFechasEnTabla = () => {

    const [year, week] = semanaFiltro.value.split('-W');
    const firstDayOfWeek = new Date(year, 0, (week - 1) * 7 + 1);

    const dayOfWeek = firstDayOfWeek.getDay();
    const dayOffset = (dayOfWeek <= 1 ? 1 : 8) - dayOfWeek;
    const monday = new Date(firstDayOfWeek.setDate(firstDayOfWeek.getDate() + dayOffset));

    const days = [];
    for (let i = 0; i < 7; i++) {
        const day = new Date(monday);
        day.setDate(monday.getDate() + i);

        const formattedDay = day.toISOString().split('T')[0];
        days.push(formattedDay);
    }

    lunes.value = days[0];
    martes.value = days[1];
    miercoles.value = days[2];
    jueves.value = days[3];
    viernes.value = days[4];
    sabado.value = days[5];

    fechaLunes.value = fn_formatearDia(lunes.value);
    fechaMartes.value = fn_formatearDia(martes.value);
    fechaMiercoles.value = fn_formatearDia(miercoles.value);
    fechaJueves.value = fn_formatearDia(jueves.value);
    fechaViernes.value = fn_formatearDia(viernes.value);
    fechaSabado.value = fn_formatearDia(sabado.value);
}

fn_asignaFechasEnTabla();

function fn_redondearHaciaAbajo(numero, decimales) {
    const factor = Math.pow(10, decimales);
    return Math.floor(numero * factor) / factor;
}

async function fn_traerDatosReporteSemanal(grupo) {
    precioTalloSolo.value = 0;
    precioTalloCoral.value = 0;
    precioMediaValvaTalloSolo.value = 0;
    precioMediaValvaTalloCoral.value = 0;
    precioOtros.value = 0;
    resultadoAgrupadoReactivo.value = [];

    cargando.value = true;

    try {
        const pricingResponse = await fetch(`/fn_traerPreciosServisGrupo?grupo=${grupo}`);
        const pricingData = await pricingResponse.json();

        precioTalloSolo.value = pricingData[0]["preTalloSolo"];
        precioTalloCoral.value = pricingData[0]["preTalloCoral"];
        precioMediaValvaTalloSolo.value = pricingData[0]["preMediaValvaTS"];
        precioMediaValvaTalloCoral.value = pricingData[0]["preMediaValvaTC"];
        precioOtros.value = pricingData[0]["preOtros"];

        const reportResponse = await fetch(`/fn_traerDatosReporteSemanal?fechaDesde=${lunes.value}&fechaHasta=${sabado.value}&grupo=${grupo}`);
        const reportData = await reportResponse.json();

        const especies = ["TALLO SOLO", "TALLO CORAL", "MEDIA VALVA T/S", "MEDIA VALVA T/C", "OTROS"];

        const inicializarUsuario = (nombreCompleto) => {
            let data = { nombreCompleto };

            especies.forEach(especie => {
                ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'].forEach(dia => {
                    data[`totalPesoNeto${dia}${especie.replace(/\s+/g, '')}`] = "0.00";
                    data[`totalTara${dia}${especie.replace(/\s+/g, '')}`] = "0.00";
                });

                data[`totalPesoNeto${especie.replace(/\s+/g, '')}`] = "0.00";
                data[`totalTara${especie.replace(/\s+/g, '')}`] = "0.00";
                data[`pesoDescuento${especie.replace(/\s+/g, '')}`] = "0.00";
            });
            return data;
        };

        const procesarDatos = (response) => {
            return response.reduce((acc, curr) => {
                const { codigoUsuario, nombreCompleto, fech_InicioProc, especie, totalPesoNeto, totalTara, pesoDescuento } = curr;

                if (!acc[codigoUsuario]) {
                    acc[codigoUsuario] = inicializarUsuario(nombreCompleto);
                }

                let diaSemana = '';
                if (fech_InicioProc === lunes.value) diaSemana = 'Lunes';
                else if (fech_InicioProc === martes.value) diaSemana = 'Martes';
                else if (fech_InicioProc === miercoles.value) diaSemana = 'Miercoles';
                else if (fech_InicioProc === jueves.value) diaSemana = 'Jueves';
                else if (fech_InicioProc === viernes.value) diaSemana = 'Viernes';
                else if (fech_InicioProc === sabado.value) diaSemana = 'Sabado';

                if (diaSemana) {
                    acc[codigoUsuario][`totalPesoNeto${diaSemana}${especie.replace(/\s+/g, '')}`] = fn_redondearHaciaAbajo(totalPesoNeto, 2).toFixed(2);
                    acc[codigoUsuario][`totalTara${diaSemana}${especie.replace(/\s+/g, '')}`] = fn_redondearHaciaAbajo(totalTara, 2).toFixed(2);
                }

                acc[codigoUsuario][`pesoDescuento${especie.replace(/\s+/g, '')}`] = parseFloat(pesoDescuento || 0).toFixed(2);

                acc[codigoUsuario][`totalPesoNeto${especie.replace(/\s+/g, '')}`] = 
                    (parseFloat(acc[codigoUsuario][`totalPesoNeto${especie.replace(/\s+/g, '')}`]) + fn_redondearHaciaAbajo(totalPesoNeto, 2)).toFixed(2);

                acc[codigoUsuario][`totalTara${especie.replace(/\s+/g, '')}`] = 
                    (parseFloat(acc[codigoUsuario][`totalTara${especie.replace(/\s+/g, '')}`]) + fn_redondearHaciaAbajo(totalTara, 2)).toFixed(2);

                return acc;
            }, {});
        };

        const resultadoAgrupado = procesarDatos(reportData);
        resultadoAgrupadoReactivo.value = resultadoAgrupado;
        fn_sumarTotales();
        
    } catch (error) {
        console.error('Error al obtener los datos', error);
    } finally {
        cargando.value = false;
    }
}

watch([semanaFiltro, selectServis], ([newSemanaFiltro, newSelectServis]) => {
    fn_asignaFechasEnTabla();
    if(newSemanaFiltro && newSelectServis){
        fn_traerDatosReporteSemanal(newSelectServis);
    }
});

function fn_sumarTotales(){
    fn_resetTotales();
    Object.keys(resultadoAgrupadoReactivo.value).forEach(function(codigoUsuario) { 
        let item = resultadoAgrupadoReactivo.value[codigoUsuario]

        totalLunesTALLOSOLO.value += parseFloat(item.totalPesoNetoLunesTALLOSOLO);
        totalMartesTALLOSOLO.value += parseFloat(item.totalPesoNetoMartesTALLOSOLO);
        totalMiercolesTALLOSOLO.value += parseFloat(item.totalPesoNetoMiercolesTALLOSOLO);
        totalJuevesTALLOSOLO.value += parseFloat(item.totalPesoNetoJuevesTALLOSOLO);
        totalViernesTALLOSOLO.value += parseFloat(item.totalPesoNetoViernesTALLOSOLO);
        totalSabadoTALLOSOLO.value += parseFloat(item.totalPesoNetoSabadoTALLOSOLO);

        totalLunesTALLOCORAL.value += parseFloat(item.totalPesoNetoLunesTALLOCORAL);
        totalMartesTALLOCORAL.value += parseFloat(item.totalPesoNetoMartesTALLOCORAL);
        totalMiercolesTALLOCORAL.value += parseFloat(item.totalPesoNetoMiercolesTALLOCORAL);
        totalJuevesTALLOCORAL.value += parseFloat(item.totalPesoNetoJuevesTALLOCORAL);
        totalViernesTALLOCORAL.value += parseFloat(item.totalPesoNetoViernesTALLOCORAL);
        totalSabadoTALLOCORAL.value += parseFloat(item.totalPesoNetoSabadoTALLOCORAL);

        totalLunesMEDIAVALVATALLOSOLO.value += parseFloat(item["totalPesoNetoLunesMEDIAVALVAT/S"]);
        totalMartesMEDIAVALVATALLOSOLO.value += parseFloat(item["totalPesoNetoMartesMEDIAVALVAT/S"]);
        totalMiercolesMEDIAVALVATALLOSOLO.value += parseFloat(item["totalPesoNetoMiercolesMEDIAVALVAT/S"]);
        totalJuevesMEDIAVALVATALLOSOLO.value += parseFloat(item["totalPesoNetoJuevesMEDIAVALVAT/S"]);
        totalViernesMEDIAVALVATALLOSOLO.value += parseFloat(item["totalPesoNetoViernesMEDIAVALVAT/S"]);
        totalSabadoMEDIAVALVATALLOSOLO.value += parseFloat(item["totalPesoNetoSabadoMEDIAVALVAT/S"]);

        totalLunesMEDIAVALVATALLOCORAL.value += parseFloat(item["totalPesoNetoLunesMEDIAVALVAT/C"]);
        totalMartesMEDIAVALVATALLOCORAL.value += parseFloat(item["totalPesoNetoMartesMEDIAVALVAT/C"]);
        totalMiercolesMEDIAVALVATALLOCORAL.value += parseFloat(item["totalPesoNetoMiercolesMEDIAVALVAT/C"]);
        totalJuevesMEDIAVALVATALLOCORAL.value += parseFloat(item["totalPesoNetoJuevesMEDIAVALVAT/C"]);
        totalViernesMEDIAVALVATALLOCORAL.value += parseFloat(item["totalPesoNetoViernesMEDIAVALVAT/C"]);
        totalSabadoMEDIAVALVATALLOCORAL.value += parseFloat(item["totalPesoNetoSabadoMEDIAVALVAT/C"]);

        totalLunesOTROS.value += parseFloat(item.totalPesoNetoLunesOTROS);
        totalMartesOTROS.value += parseFloat(item.totalPesoNetoMartesOTROS);
        totalMiercolesOTROS.value += parseFloat(item.totalPesoNetoMiercolesOTROS);
        totalJuevesOTROS.value += parseFloat(item.totalPesoNetoJuevesOTROS);
        totalViernesOTROS.value += parseFloat(item.totalPesoNetoViernesOTROS);
        totalSabadoOTROS.value += parseFloat(item.totalPesoNetoSabadoOTROS);

        totalDescuentoTALLOSOLO.value += parseFloat(item.pesoDescuentoTALLOSOLO);
        totalDescuentoTALLOCORAL.value += parseFloat(item.pesoDescuentoTALLOCORAL);
        totalDescuentoMEDIAVALVATALLOSOLO.value += parseFloat(item["pesoDescuentoMEDIAVALVAT/S"]);
        totalDescuentoMEDIAVALVATALLOCORAL.value += parseFloat(item["pesoDescuentoMEDIAVALVAT/C"]);
        totalDescuentoOTROS.value += parseFloat(item.pesoDescuentoOTROS);
        
        totalTALLOSOLO.value += parseFloat(item.totalPesoNetoTALLOSOLO) - parseFloat(item.pesoDescuentoTALLOSOLO);
        totalTALLOCORAL.value += parseFloat(item.totalPesoNetoTALLOCORAL) - parseFloat(item.pesoDescuentoTALLOCORAL);
        totalMEDIAVALVATALLOSOLO.value += parseFloat(item["totalPesoNetoMEDIAVALVAT/S"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/S"]);
        totalMEDIAVALVATALLOCORAL.value += parseFloat(item["totalPesoNetoMEDIAVALVAT/C"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/C"]);
        totalOTROS.value += parseFloat(item.totalPesoNetoOTROS) - parseFloat(item.pesoDescuentoOTROS);

        totalAPagar.value += ((parseFloat(item.totalPesoNetoTALLOSOLO) - parseFloat(item.pesoDescuentoTALLOSOLO)) * precioTalloSolo.value) + 
                            ((parseFloat(item.totalPesoNetoTALLOCORAL) - parseFloat(item.pesoDescuentoTALLOCORAL)) * precioTalloCoral.value) +
                            ((parseFloat(item["totalPesoNetoMEDIAVALVAT/S"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/S"])) * precioMediaValvaTalloSolo.value) +
                            ((parseFloat(item["totalPesoNetoMEDIAVALVAT/C"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/C"])) * precioMediaValvaTalloCoral.value) +
                            ((parseFloat(item.totalPesoNetoOTROS) - parseFloat(item.pesoDescuentoOTROS)) * precioOtros.value);
    });
};

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
    let selectString = (servis.value.filter(servi => servi.idGrupo === selectServis.value))[0].nombreGrupo;
    tableToExcel("tablaReporteSemanalExcel", `REPORTE SEMANAL ${selectString} DESDE ${lunes.value} HASTA ${sabado.value}`);
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
        <h2 class="font-bold text-xl w-full md:w-auto">Reporte Semanal</h2>
    </div>
    <div class="flex flex-wrap gap-4 mb-4">
        <div class="w-full md:max-w-56">
            <input type="week" v-model="semanaFiltro" class="outline-none h-full w-full bg-gray-50 border border-gray-300 text-gray-500 text-base rounded-lg focus:ring-primary-600 focus:border-primary-600">
        </div>
        <Select v-model="selectServis" :options="servis" optionValue="idGrupo" optionLabel="nombreGrupo" placeholder="Selecciona Servis" checkmark :highlightOnSelect="false" class="w-full md:w-56 text-black" />
    </div>
    <div class="flex justify-end items-center gap-8 mb-4">
        <div class="flex items-center">
            <Checkbox v-model="muestraSabado" inputId="ocultarSabado" :binary="true"/>
            <label for="ocultarSabado" class="ml-2">Visualizar Sabado</label>
        </div>
        <Button @click="fn_crearExcel" icon="fa-regular fa-file-excel" class="w-full md:w-auto" severity="info" label="Exportar a Excel"/>
    </div>
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="border rounded-lg overflow-hidden border-black">
                    <table class="min-w-full divide-y divide-gray-200" id="tablaReporteSemanal">
                        <thead class="bg-gray-50">
                            <tr>
                                <th rowspan="5" class="p-2 text-center text-base font-bold bg-[#FFFF85] text-black border-r border-b border-black">Codigo</th>
                                <th rowspan="5" class="p-2 text-center text-base font-bold bg-[#FFFF85] min-w-[200px] text-black border-r border-b border-black">Nombre</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#FF0000] min-w-[100px] text-white border-r border-b border-black">T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#FF0000] min-w-[100px] text-white border-r border-b border-black">{{ precioTalloSolo }}</th>
                                
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>

                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>

                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>

                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>

                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                
                                <th v-if="muestraSabado" class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th v-if="muestraSabado" class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th v-if="muestraSabado" class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th v-if="muestraSabado" class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th v-if="muestraSabado" class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>

                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                <th class="p-2 text-center min-w-[100px] text-sm font-bold bg-[#ffffff] text-black">&nbsp;</th>
                                
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
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                
                                <th v-if="muestraSabado" class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                <th v-if="muestraSabado" class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                <th v-if="muestraSabado" class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                <th v-if="muestraSabado" class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>
                                <th v-if="muestraSabado" class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-b border-black">&nbsp;</th>

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
                                <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">{{ precioMediaValvaTalloSolo }}</th>
                                <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">LUNES</th>
                                <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">MARTES</th>
                                <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">MIERCOLES</th>
                                <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">JUEVES</th>
                                <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">VIERNES</th>
                                <th v-if="muestraSabado" colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">SABADO</th>
                                <th rowspan="2" colspan="5" class="p-2 text-center text-sm font-bold bg-[#F14747] text-white border-r border-b border-black">Descuentos Kg</th>
                                <th rowspan="2" colspan="5" class="p-2 text-center text-sm font-bold bg-[#2681e9] text-white border-r border-b border-black">Totales</th>
                                <th rowspan="2" class="p-2 text-center text-sm font-bold bg-[#FFFF00] text-black border-b border-black whitespace-nowrap">Neto a Pagar</th>
                            </tr>
                            <tr>
                                <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">{{ precioMediaValvaTalloCoral }}</th>
                                <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">{{fechaLunes}}</th>
                                <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">{{fechaMartes}}</th>
                                <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">{{fechaMiercoles}}</th>
                                <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">{{fechaJueves}}</th>
                                <th colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">{{fechaViernes}}</th>
                                <th v-if="muestraSabado" colspan="5" class="p-2 text-center text-sm font-bold bg-[#FFFF85] text-black border-b border-r border-black">{{fechaSabado}}</th>
                            </tr>
                            <tr>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">{{ precioOtros }}</th>

                                <th class="p-2 text-center text-sm font-bold bg-[#FF0000] text-white border-r border-b border-black">T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>

                                <th class="p-2 text-center text-sm font-bold bg-[#FF0000] text-white border-r border-b border-black">T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>

                                <th class="p-2 text-center text-sm font-bold bg-[#FF0000] text-white border-r border-b border-black">T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>

                                <th class="p-2 text-center text-sm font-bold bg-[#FF0000] text-white border-r border-b border-black">T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>

                                <th class="p-2 text-center text-sm font-bold bg-[#FF0000] text-white border-r border-b border-black">T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>

                                <th v-if="muestraSabado" class="p-2 text-center text-sm font-bold bg-[#FF0000] text-white border-r border-b border-black">T/S</th>
                                <th v-if="muestraSabado" class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">T/C</th>
                                <th v-if="muestraSabado" class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                <th v-if="muestraSabado" class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                <th v-if="muestraSabado" class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>
                                
                                <th class="p-2 text-center text-sm font-bold bg-[#FF0000] text-white border-r border-b border-black">T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>

                                <th class="p-2 text-center text-sm font-bold bg-[#FF0000] text-white border-r border-b border-black">T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#0060ff] text-white border-r border-b border-black">T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#00B050] text-white border-r border-b border-black">MV T/S</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#000000] text-white border-r border-b border-black">MV T/C</th>
                                <th class="p-2 text-center text-sm font-bold bg-[#ffffff] text-black border-r border-b border-black">OTROS</th>

                                <th class="p-2 text-center text-sm font-bold bg-[#FFFF00] text-black border-b border-black">S/</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black" id="bodyReporteSemanal">
                            <tr v-if="!Object.keys(resultadoAgrupadoReactivo).length">
                                <td :colspan="muestraSabado ? 40 : 35" class="text-center">No hay datos</td>
                            </tr>
                            <tr v-for="(item, codigoUsuario) in resultadoAgrupadoReactivo" :key="codigoUsuario">
                                <td class="p-2 text-center whitespace-nowrap text-sm font-bold text-[#000000] border-r border-black">{{codigoUsuario}}</td>
                                <td colspan="3" class="p-2 font-bold text-left whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item.nombreCompleto}}</td>

                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{item.totalPesoNetoLunesTALLOSOLO}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{item.totalPesoNetoLunesTALLOCORAL}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{item["totalPesoNetoLunesMEDIAVALVAT/S"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item["totalPesoNetoLunesMEDIAVALVAT/C"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item.totalPesoNetoLunesOTROS}}</td>

                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{item.totalPesoNetoMartesTALLOSOLO}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{item.totalPesoNetoMartesTALLOCORAL}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{item["totalPesoNetoMartesMEDIAVALVAT/S"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item["totalPesoNetoMartesMEDIAVALVAT/C"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item.totalPesoNetoMartesOTROS}}</td>

                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{item.totalPesoNetoMiercolesTALLOSOLO}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{item.totalPesoNetoMiercolesTALLOCORAL}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{item["totalPesoNetoMiercolesMEDIAVALVAT/S"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item["totalPesoNetoMiercolesMEDIAVALVAT/C"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item.totalPesoNetoMiercolesOTROS}}</td>

                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{item.totalPesoNetoJuevesTALLOSOLO}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{item.totalPesoNetoJuevesTALLOCORAL}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{item["totalPesoNetoJuevesMEDIAVALVAT/S"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item["totalPesoNetoJuevesMEDIAVALVAT/C"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item.totalPesoNetoJuevesOTROS}}</td>

                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{item.totalPesoNetoViernesTALLOSOLO}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{item.totalPesoNetoViernesTALLOCORAL}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{item["totalPesoNetoViernesMEDIAVALVAT/S"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item["totalPesoNetoViernesMEDIAVALVAT/C"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item.totalPesoNetoViernesOTROS}}</td>

                                <td v-if="muestraSabado" class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{item.totalPesoNetoSabadoTALLOSOLO}}</td>
                                <td v-if="muestraSabado" class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{item.totalPesoNetoSabadoTALLOCORAL}}</td>
                                <td v-if="muestraSabado" class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{item["totalPesoNetoSabadoMEDIAVALVAT/S"]}}</td>
                                <td v-if="muestraSabado" class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item["totalPesoNetoSabadoMEDIAVALVAT/C"]}}</td>
                                <td v-if="muestraSabado" class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item.totalPesoNetoSabadoOTROS}}</td>

                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{item.pesoDescuentoTALLOSOLO}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{item.pesoDescuentoTALLOCORAL}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{item["pesoDescuentoMEDIAVALVAT/S"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item["pesoDescuentoMEDIAVALVAT/C"]}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{item.pesoDescuentoOTROS}}</td>

                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{(parseFloat(item.totalPesoNetoTALLOSOLO) - parseFloat(item.pesoDescuentoTALLOSOLO)).toFixed(2)}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{(parseFloat(item.totalPesoNetoTALLOCORAL) - parseFloat(item.pesoDescuentoTALLOCORAL)).toFixed(2)}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{(parseFloat(item["totalPesoNetoMEDIAVALVAT/S"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/S"])).toFixed(2)}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{(parseFloat(item["totalPesoNetoMEDIAVALVAT/C"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/C"])).toFixed(2)}}</td>
                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{(parseFloat(item.totalPesoNetoOTROS) - parseFloat(item.pesoDescuentoOTROS)).toFixed(2)}}</td>

                                <td class="p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000]">
                                    {{
                                        (
                                            ((parseFloat(item.totalPesoNetoTALLOSOLO) - parseFloat(item.pesoDescuentoTALLOSOLO)) * precioTalloSolo) + 
                                            ((parseFloat(item.totalPesoNetoTALLOCORAL) - parseFloat(item.pesoDescuentoTALLOCORAL)) * precioTalloCoral) +
                                            ((parseFloat(item["totalPesoNetoMEDIAVALVAT/S"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/S"])) * precioMediaValvaTalloSolo) +
                                            ((parseFloat(item["totalPesoNetoMEDIAVALVAT/C"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/C"])) * precioMediaValvaTalloCoral) +
                                            ((parseFloat(item.totalPesoNetoOTROS) - parseFloat(item.pesoDescuentoOTROS)) * precioOtros)
                                        ).toFixed(2)
                                    }}
                                </td>
                            </tr>
                            <tr v-if="resultadoAgrupadoReactivo && Object.keys(resultadoAgrupadoReactivo).length">
                                <td colspan="4" class="bg-[#93c5fd] p-2 text-left whitespace-nowrap text-sm font-bold text-[#000000] border-r border-black">Total</td>

                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{totalLunesTALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{totalLunesTALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{totalLunesMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalLunesMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalLunesOTROS.toFixed(2)}}</td>

                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{totalMartesTALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{totalMartesTALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{totalMartesMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalMartesMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalMartesOTROS.toFixed(2)}}</td>

                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{totalMiercolesTALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{totalMiercolesTALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{totalMiercolesMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalMiercolesMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalMiercolesOTROS.toFixed(2)}}</td>

                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{totalJuevesTALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{totalJuevesTALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{totalJuevesMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalJuevesMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalJuevesOTROS.toFixed(2)}}</td>

                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{totalViernesTALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{totalViernesTALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{totalViernesMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalViernesMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalViernesOTROS.toFixed(2)}}</td>

                                <td v-if="muestraSabado" class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{totalSabadoTALLOSOLO.toFixed(2)}}</td>
                                <td v-if="muestraSabado" class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{totalSabadoTALLOCORAL.toFixed(2)}}</td>
                                <td v-if="muestraSabado" class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{totalSabadoMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td v-if="muestraSabado" class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalSabadoMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td v-if="muestraSabado" class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalSabadoOTROS.toFixed(2)}}</td>

                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{totalDescuentoTALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{totalDescuentoTALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{totalDescuentoMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalDescuentoMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalDescuentoOTROS.toFixed(2)}}</td>

                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#FF0000] border-r border-black">{{totalTALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#0060ff] border-r border-black">{{totalTALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#00B050] border-r border-black">{{totalMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000] border-r border-black">{{totalOTROS.toFixed(2)}}</td>

                                <td class="bg-[#93c5fd] p-2 text-center font-bold whitespace-nowrap text-sm text-[#000000]">{{totalAPagar.toFixed(2)}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="min-w-full divide-y divide-gray-200 hidden" id="tablaReporteSemanalExcel">
                        <thead class="bg-gray-50">
                            <tr>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" rowspan="5" class="p-2 text-base">Codigo</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; width: 200px; color: black" rowspan="5" class="p-2 text-base">Nombre</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; width: 100px; color: white" class="p-2 text-sm">T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; width: 100px; color: white" class="p-2 text-sm">{{ precioTalloSolo }}</th>
                                
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                
                                <th v-if="muestraSabado" style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th v-if="muestraSabado" style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th v-if="muestraSabado" style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th v-if="muestraSabado" style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th v-if="muestraSabado" style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                
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

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                
                                <th v-if="muestraSabado" style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th v-if="muestraSabado" style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th v-if="muestraSabado" style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th v-if="muestraSabado" style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th v-if="muestraSabado" style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>
                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 100px; color: black" class="p-2 text-sm">&nbsp;</th>

                                <th style="font-weight: bold; background-color: #FFFFFF; text-align: center; width: 200px; color: black" class="p-2 text-sm">&nbsp;</th>
                            </tr>
                            <tr>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">{{ precioMediaValvaTalloSolo }}</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">LUNES</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">MARTES</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">MIERCOLES</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">JUEVES</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">VIERNES</th>
                                <th v-if="muestraSabado" style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">SABADO</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #F14747; text-align: center; color: white" colspan="5" rowspan="2" class="p-2 text-sm">Descuentos Kg</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #2681e9; text-align: center; color: white" colspan="5" rowspan="2" class="p-2 text-sm">Descuentos Kg</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF00; text-align: center; color: black" rowspan="2" class="p-2 text-sm">Neto a Pagar</th>
                            </tr>
                            <tr>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">{{ precioMediaValvaTalloCoral }}</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">{{fechaLunes}}</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">{{fechaMartes}}</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">{{fechaMiercoles}}</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">{{fechaJueves}}</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">{{fechaViernes}}</th>
                                <th v-if="muestraSabado" style="border:1px solid #000000; font-weight: bold; background-color: #FFFF85; text-align: center; color: black" colspan="5" class="p-2 text-sm">{{fechaSabado}}</th>
                            </tr>
                            <tr>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">{{ precioOtros }}</th>

                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; color: white" class="p-2 text-sm">T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>

                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; color: white" class="p-2 text-sm">T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>

                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; color: white" class="p-2 text-sm">T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>

                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; color: white" class="p-2 text-sm">T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>

                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; color: white" class="p-2 text-sm">T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>

                                <th v-if="muestraSabado" style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; color: white" class="p-2 text-sm">T/S</th>
                                <th v-if="muestraSabado" style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">T/C</th>
                                <th v-if="muestraSabado" style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                <th v-if="muestraSabado" style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                <th v-if="muestraSabado" style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>
                                
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; color: white" class="p-2 text-sm">T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>

                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FF0000; text-align: center; color: white" class="p-2 text-sm">T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #0060ff; text-align: center; color: white" class="p-2 text-sm">T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #00B050; text-align: center; color: white" class="p-2 text-sm">MV T/S</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #000000; text-align: center; color: white" class="p-2 text-sm">MV T/C</th>
                                <th style="border:1px solid #000000; font-weight: bold; background-color: #ffffff; text-align: center; color: black" class="p-2 text-sm">OTROS</th>

                                <th style="border:1px solid #000000; font-weight: bold; background-color: #FFFF00; text-align: center; color: black" class="p-2 text-sm">S/</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black" id="bodyReporteSemanalExcel">
                            <tr v-if="!Object.keys(resultadoAgrupadoReactivo).length">
                                <td :colspan="muestraSabado ? 40 : 35" class="text-center">No hay datos</td>
                            </tr>
                            <tr v-for="(item, codigoUsuario) in resultadoAgrupadoReactivo" :key="codigoUsuario">
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{codigoUsuario}}</td>
                                <td style="border: 1px solid #000000; text-align: left; color: #000000; font-weight: bold; white-space: nowrap;" colspan="3" class="p-2 text-sm">{{item.nombreCompleto}}</td>

                                <td style="border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoLunesTALLOSOLO}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoLunesTALLOCORAL}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoLunesMEDIAVALVAT/S"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoLunesMEDIAVALVAT/C"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoLunesOTROS}}</td>

                                <td style="border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoMartesTALLOSOLO}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoMartesTALLOCORAL}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoMartesMEDIAVALVAT/S"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoMartesMEDIAVALVAT/C"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoMartesOTROS}}</td>

                                <td style="border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoMiercolesTALLOSOLO}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoMiercolesTALLOCORAL}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoMiercolesMEDIAVALVAT/S"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoMiercolesMEDIAVALVAT/C"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoMiercolesOTROS}}</td>

                                <td style="border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoJuevesTALLOSOLO}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoJuevesTALLOCORAL}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoJuevesMEDIAVALVAT/S"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoJuevesMEDIAVALVAT/C"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoJuevesOTROS}}</td>

                                <td style="border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoViernesTALLOSOLO}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoViernesTALLOCORAL}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoViernesMEDIAVALVAT/S"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoViernesMEDIAVALVAT/C"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoViernesOTROS}}</td>

                                <td v-if="muestraSabado" style="border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoSabadoTALLOSOLO}}</td>
                                <td v-if="muestraSabado" style="border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoSabadoTALLOCORAL}}</td>
                                <td v-if="muestraSabado" style="border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoSabadoMEDIAVALVAT/S"]}}</td>
                                <td v-if="muestraSabado" style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["totalPesoNetoSabadoMEDIAVALVAT/C"]}}</td>
                                <td v-if="muestraSabado" style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.totalPesoNetoSabadoOTROS}}</td>

                                <td style="border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.pesoDescuentoTALLOSOLO}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.pesoDescuentoTALLOCORAL}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["pesoDescuentoMEDIAVALVAT/S"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item["pesoDescuentoMEDIAVALVAT/C"]}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{item.pesoDescuentoOTROS}}</td>

                                <td style="border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{(parseFloat(item.totalPesoNetoTALLOSOLO) - parseFloat(item.pesoDescuentoTALLOSOLO)).toFixed(2)}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{(parseFloat(item.totalPesoNetoTALLOCORAL) - parseFloat(item.pesoDescuentoTALLOCORAL)).toFixed(2)}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{(parseFloat(item["totalPesoNetoMEDIAVALVAT/S"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/S"])).toFixed(2)}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{(parseFloat(item["totalPesoNetoMEDIAVALVAT/C"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/C"])).toFixed(2)}}</td>
                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{(parseFloat(item.totalPesoNetoOTROS) - parseFloat(item.pesoDescuentoOTROS)).toFixed(2)}}</td>

                                <td style="border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">
                                    {{
                                        (
                                            ((parseFloat(item.totalPesoNetoTALLOSOLO) - parseFloat(item.pesoDescuentoTALLOSOLO)) * precioTalloSolo) + 
                                            ((parseFloat(item.totalPesoNetoTALLOCORAL) - parseFloat(item.pesoDescuentoTALLOCORAL)) * precioTalloCoral) +
                                            ((parseFloat(item["totalPesoNetoMEDIAVALVAT/S"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/S"])) * precioMediaValvaTalloSolo) +
                                            ((parseFloat(item["totalPesoNetoMEDIAVALVAT/C"]) - parseFloat(item["pesoDescuentoMEDIAVALVAT/C"])) * precioMediaValvaTalloCoral) +
                                            ((parseFloat(item.totalPesoNetoOTROS) - parseFloat(item.pesoDescuentoOTROS)) * precioOtros)
                                        ).toFixed(2)
                                    }}
                                </td>
                            </tr>
                            <tr v-if="resultadoAgrupadoReactivo && Object.keys(resultadoAgrupadoReactivo).length">
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: left; color: #000000; font-weight: bold; white-space: nowrap;" colspan="4" class="p-2 text-sm">Total</td>

                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalLunesTALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalLunesTALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalLunesMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalLunesMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalLunesOTROS.toFixed(2)}}</td>

                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMartesTALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMartesTALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMartesMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMartesMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMartesOTROS.toFixed(2)}}</td>

                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMiercolesTALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMiercolesTALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMiercolesMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMiercolesMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMiercolesOTROS.toFixed(2)}}</td>

                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalJuevesTALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalJuevesTALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalJuevesMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalJuevesMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalJuevesOTROS.toFixed(2)}}</td>

                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalViernesTALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalViernesTALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalViernesMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalViernesMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalViernesOTROS.toFixed(2)}}</td>

                                <td v-if="muestraSabado" style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalSabadoTALLOSOLO.toFixed(2)}}</td>
                                <td v-if="muestraSabado" style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalSabadoTALLOCORAL.toFixed(2)}}</td>
                                <td v-if="muestraSabado" style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalSabadoMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td v-if="muestraSabado" style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalSabadoMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td v-if="muestraSabado" style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalSabadoOTROS.toFixed(2)}}</td>

                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalDescuentoTALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalDescuentoTALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalDescuentoMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalDescuentoMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalDescuentoOTROS.toFixed(2)}}</td>

                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #FF0000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalTALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #0060ff; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalTALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #00B050; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMEDIAVALVATALLOSOLO.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalMEDIAVALVATALLOCORAL.toFixed(2)}}</td>
                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalOTROS.toFixed(2)}}</td>

                                <td style="background-color: #93c5fd; border: 1px solid #000000; text-align: center; color: #000000; font-weight: bold; white-space: nowrap;" class="p-2 text-sm">{{totalAPagar.toFixed(2)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>