import $ from 'jquery';

export const DatosPersonal = {
    obtenerDatosPersonal() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/fn_traerDatosTrabajadores',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    resolve(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Error en la consulta:', textStatus, errorThrown);
                    reject(errorThrown);
                }
            });
        });
    }
};
